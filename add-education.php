<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!verify_recaptcha($data['recaptcha_token'] ?? '')) {
    echo json_encode(["error" => "reCAPTCHA verification failed"]);
    exit;
}

if (!isset($data['user_id'])) {
    echo json_encode(["error" => "user_id required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO education 
(user_id, institute, degree, start_date, end_date, grade, description) 
VALUES (?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "issssss",
    $data['user_id'],
    $data['institute'],
    $data['degree'],
    $data['start_date'],
    $data['end_date'],
    $data['grade'],
    $data['description']
);

if ($stmt->execute()) {
    echo json_encode([
        "message" => "Education added",
        "id" => $stmt->insert_id
    ]);
} else {
    echo json_encode(["error" => "Insert failed"]);
}
?>