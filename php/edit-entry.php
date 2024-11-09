<?php

$mysqli = require __DIR__ . "/db.php";

print_r($_POST);

$sql = "UPDATE entry SET title = ?, description = ?, image_url = ?, category = ? WHERE id = ?";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}
$stmt->bind_param("sssss", $_POST["title"], $_POST["description"], $_POST["image_url"], $_POST["category"], $_POST["image_url"]);

if ($stmt->execute()) {
    header("Location: index.php");
    exit;
} else {
    die($mysqli->error . " " . $mysqli->errno);
}