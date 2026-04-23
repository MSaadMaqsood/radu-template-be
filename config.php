<?php
$host = "localhost";
$user = "radu";
$password = "radu";
$db = "portfolio_db";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
?>