<?php
// Load .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$key, $val] = explode('=', $line, 2);
        // Strip inline comments (e.g. value   # comment)
        $_ENV[trim($key)] = trim(explode(' #', $val, 2)[0]);
    }
}

$host = "localhost";
$user = "radu";
$password = "radu";
$db = "portfolio_db";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// $max requests per IP within $window seconds; call at the top of every POST handler
function rate_limit(int $max = 30, int $window = 60): void {
    $ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $dir = sys_get_temp_dir() . '/php_rl';
    if (!is_dir($dir)) mkdir($dir, 0700, true);

    $file       = $dir . '/' . md5($ip) . '.json';
    $now        = time();
    $timestamps = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
    $timestamps = array_values(array_filter($timestamps, fn($t) => $now - $t < $window));

    if (count($timestamps) >= $max) {
        http_response_code(429);
        echo json_encode(["error" => "Too many requests. Please try again later."]);
        exit;
    }

    $timestamps[] = $now;
    file_put_contents($file, json_encode($timestamps), LOCK_EX);
}

// Validates extension + actual MIME type; exits with 415 on failure
function validate_upload(array $file, array $allowed_ext, array $allowed_mime): void {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext, true)) {
        http_response_code(415);
        echo json_encode(["error" => "Extension not allowed. Accepted: " . implode(', ', $allowed_ext)]);
        exit;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowed_mime, true)) {
        http_response_code(415);
        echo json_encode(["error" => "File content does not match its extension."]);
        exit;
    }
}

// Plain text fields — strip all tags and encode entities
function sanitize_text(string $val): string {
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Rich-text fields — strip only dangerous tags, keep safe formatting
function sanitize_html(string $val): string {
    $allowed = '';
    return strip_tags(trim($val), $allowed);
}

function verify_recaptcha(string $token): bool {
    $secret = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';
    $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query(['secret' => $secret, 'response' => $token]),
    ]);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $result['success'] ?? false;
}
?>