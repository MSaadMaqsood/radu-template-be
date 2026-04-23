<?php
include "config.php";
rate_limit();

if (!verify_recaptcha($_POST['recaptcha_token'] ?? '')) {
    echo json_encode(["error" => "reCAPTCHA verification failed"]);
    exit;
}

$title       = sanitize_text($_POST['title']       ?? "");
$summary     = sanitize_text($_POST['summary']     ?? "");
$description = sanitize_html($_POST['description'] ?? "");

// Handle image
$imageName = null;

if (isset($_FILES["image"])) {
    $targetDir = "./uploads/";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $imageName = uniqid() . "." . $ext;

    $targetFile = $targetDir . $imageName;

    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
}

// Insert into DB
$stmt = $conn->prepare("INSERT INTO blogs (title, summary, description, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $summary, $description, $imageName);

if ($stmt->execute()) {
    echo json_encode([
        "message" => "Blog added",
        "id" => $stmt->insert_id,
        "image" => $imageName
    ]);
} else {
    echo json_encode(["error" => "Insert failed"]);
}
?>