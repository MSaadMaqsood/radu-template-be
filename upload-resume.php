<?php
include "config.php";
ini_set('display_errors', 1);

$user_id = $_POST['user_id'];

validate_upload(
    $_FILES['file'],
    ['pdf'],
    ['application/pdf']
);

$targetDir  = "./uploads/";
$fileName   = uniqid() . '.pdf';
$targetFile = $targetDir . $fileName;

move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);

// Save file path in DB
$stmt = $conn->prepare("UPDATE users SET resume=? WHERE id=?");
$stmt->bind_param("si", $fileName, $user_id);
$stmt->execute();

echo json_encode([
    "message" => "Uploaded",
    "file" => $fileName
]);
?>