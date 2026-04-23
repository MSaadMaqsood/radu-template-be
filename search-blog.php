<?php
include "config.php";

// ❌ Get query directly
$q = $_GET['q'] ?? "";

// ❌ VULNERABLE QUERY (INTENTIONAL FOR DEMO)
$query = "SELECT * FROM blogs 
WHERE title LIKE '%$q%' 
OR summary LIKE '%$q%' 
OR description LIKE '%$q%'";

$result = $conn->query($query);

if (!$result) {
    echo json_encode([
        "error" => "SQL Error",
        "details" => $conn->error
    ]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>