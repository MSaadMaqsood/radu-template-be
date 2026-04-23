<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["error" => "id required"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM work_history WHERE id = ?");
$stmt->bind_param("i", $data['id']);

if ($stmt->execute()) {
    echo json_encode(["message" => "Deleted"]);
} else {
    echo json_encode(["error" => "Delete failed"]);
}
?>