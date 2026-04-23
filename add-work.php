<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'])) {
    echo json_encode(["error" => "user_id required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO work_history 
(user_id, company, title, start_date, end_date, description) 
VALUES (?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "isssss",
    $data['user_id'],
    $data['company'],
    $data['title'],
    $data['start_date'],
    $data['end_date'],
    $data['description']
);

if ($stmt->execute()) {
    echo json_encode([
        "message" => "Work added",
        "id" => $stmt->insert_id
    ]);
} else {
    echo json_encode(["error" => "Insert failed"]);
}
?>