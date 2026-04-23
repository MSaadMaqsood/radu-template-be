<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'];
$email = $data['email'];
$subject = $data['subject'];
$message = $data['message'];

// ❌ SQL Injection vulnerable
$query = "INSERT INTO contacts (name, email, subject, message)
VALUES ('$name', '$email', '$subject', '$message')";

if ($conn->query($query)) {
    echo json_encode(["message" => "Saved"]);
} else {
    echo json_encode(["error" => $conn->error]);
}
?>