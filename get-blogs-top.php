<?php
include "config.php";

$result = $conn->query("SELECT * FROM blogs ORDER BY id DESC LIMIT 4");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>