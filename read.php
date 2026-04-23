<?php
include "config.php";

$user_id = $_GET['id'];

// User
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Languages
$langs = [];
$res = $conn->query("SELECT * FROM languages WHERE user_id = $user_id");
while ($row = $res->fetch_assoc()) {
    $langs[] = $row;
}

// Skills
$skills = [];
$res = $conn->query("SELECT * FROM skills WHERE user_id = $user_id");
while ($row = $res->fetch_assoc()) {
    $skills[] = $row;
}

echo json_encode([
    "user" => $user,
    "languages" => $langs,
    "skills" => $skills
]);
?>