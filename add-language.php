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

$stmt = $conn->prepare("INSERT INTO languages (user_id, name, percentage) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $data['user_id'], $data['name'], $data['percentage']);

if ($stmt->execute()) {
    echo json_encode([
        "message" => "Language added",
        "id" => $stmt->insert_id
    ]);
} else {
    echo json_encode(["error" => "Insert failed"]);
}
?>