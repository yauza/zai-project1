<?php

$mysqli = require __DIR__ . "/db.php";

print_r($_POST);

$sql = "INSERT INTO entry (user, start_date, end_date, description, image_url, category, title) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}
$stmt->bind_param("sssssss", $_POST["user"], $_POST["start_date"], $_POST["end_date"], $_POST["description"], $_POST["image_url"], $_POST["category"], $_POST["title"]);

if ($stmt->execute()) {
    header("Location: index.php");
    exit;
} else {
    die($mysqli->error . " " . $mysqli->errno);
}