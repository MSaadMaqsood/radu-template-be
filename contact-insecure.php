<?php
include "config.php";
rate_limit(5, 60);

$data = json_decode(file_get_contents("php://input"), true);

if (!verify_recaptcha($data['recaptcha_token'] ?? '')) {
    echo json_encode(["error" => "reCAPTCHA verification failed"]);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$subject = $data['subject'];
$message = $data['message'];

// ❌ SQL Injection vulnerable
$query = "INSERT INTO contacts (name, email, subject, message)
VALUES ('$name', '$email', '$subject', '$message')";

if ($conn->query($query)) {
    echo json_encode(["message" => "Saved"]);
} else {
    echo json_encode(["error" => $conn->error]);
}
?>