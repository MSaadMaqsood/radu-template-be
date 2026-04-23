<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["error" => "Language ID required"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM languages WHERE id = ?");
$stmt->bind_param("i", $data['id']);

if ($stmt->execute()) {
    echo json_encode(["message" => "Language deleted"]);
} else {
    echo json_encode(["error" => "Delete failed"]);
}
?>