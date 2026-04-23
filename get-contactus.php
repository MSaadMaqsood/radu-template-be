<?php
include "config.php";




$stmt = $conn->prepare("SELECT * FROM contacts ORDER BY id DESC");

$stmt->execute();

$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>