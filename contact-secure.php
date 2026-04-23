<?php
include "config.php";
rate_limit(5, 60);

$data = json_decode(file_get_contents("php://input"), true);

if (!verify_recaptcha($data['recaptcha_token'] ?? '')) {
    echo json_encode(["error" => "reCAPTCHA verification failed"]);
    exit;
}

$name    = $data['name']    ?? '';
$email   = $data['email']   ?? '';
$subject = $data['subject'] ?? '';
$message = $data['message'] ?? '';

$stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    echo json_encode(["message" => "Saved"]);
} else {
    echo json_encode(["error" => $conn->error]);
}
?>
