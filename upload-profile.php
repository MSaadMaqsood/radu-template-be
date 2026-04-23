<?php
include "config.php";
ini_set('display_errors', 1);

$user_id = $_POST['user_id'];

validate_upload(
    $_FILES['file'],
    ['jpg', 'jpeg', 'png', 'webp', 'gif'],
    ['image/jpeg', 'image/png', 'image/webp', 'image/gif']
);

$targetDir = "./uploads/";
$ext       = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
$fileName  = uniqid() . '.' . $ext;
$targetFile = $targetDir . $fileName;

move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);

// Save file path in DB
$stmt = $conn->prepare("UPDATE users SET profile_image=? WHERE id=?");
$stmt->bind_param("si", $fileName, $user_id);
$stmt->execute();

echo json_encode([
    "message" => "Uploaded",
    "file" => $fileName
]);
?>