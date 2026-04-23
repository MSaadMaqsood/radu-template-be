<?php
include "config.php";
ini_set('display_errors', 1);
rate_limit();

$data = json_decode(file_get_contents("php://input"), true);

if (!verify_recaptcha($data['recaptcha_token'] ?? '')) {
    echo json_encode(["error" => "reCAPTCHA verification failed"]);
    exit;
}

if (!isset($data['id'])) {
    echo json_encode(["error" => "User ID required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET 
fullname=?, position=?, facebook=?, instagram=?, linkedin=?, x=?, age=?, cell=?, email=?, address=?, bio=? 
WHERE id=?");

$stmt->bind_param(
    "ssssssissssi",
    $data['fullname'],
    $data['position'],
    $data['facebook'],
    $data['instagram'],
    $data['linkedin'],
    $data['x'],
    $data['age'],
    $data['cell'],
    $data['email'],
    $data['address'],
    $data['bio'],

    $data['id']
);

if ($stmt->execute()) {
    echo json_encode(["message" => "Bio updated"]);
} else {
    echo json_encode(["error" => "Update failed"]);
}
?>