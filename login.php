<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "No input received"]);
    exit;
}

$username = $data['username'] ?? "";
$password = $data['password'] ?? "";

if (!$username || !$password) {
    echo json_encode(["error" => "Missing username or password"]);
    exit;
}

// ⚠️ Vulnerable (for demo)
// $query = "SELECT * FROM auth_users WHERE username = '' OR 1=1 -- ' AND password = 'anything';";
$query = "SELECT * FROM auth_users WHERE username = '$username' AND password = '$password'";


$result = $conn->query($query);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$user = $result->fetch_assoc();

if ($user) {
    echo json_encode(["message" => "Login success"]);
} else {
    echo json_encode(["error" => "Invalid credentials"]);
}