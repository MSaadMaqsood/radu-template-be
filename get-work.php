<?php
include "config.php";
ini_set('display_errors', 1);
if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "user_id required"]);
    exit;
}

$user_id = intval($_GET['user_id']);

$stmt = $conn->prepare("SELECT * FROM work_history WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>