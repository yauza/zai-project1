<?php

// Check for empty fields
if (empty($_POST["login"])) {
    die("Login is required");
}

if (empty($_POST["password"])) {
    die("Password is required");
}

if (empty($_POST["repeat-password"])) {
    die("Password confirmation is required");
}

// Check matching
if ($_POST["password"] !== $_POST["repeat-password"]) {
    die("Password and confirmation must match");
}

$hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/db.php";

// Prevent SQL injection
$sql = "INSERT INTO user (login, password) VALUES (?, ?)";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}
$stmt->bind_param("ss", $_POST["login"], $hashed_password);

if ($stmt->execute()) {
    header("Location: ../signup-finished.html");
    exit;
} else {
    if ($mysqli->errno === 1062) {
        die("Login already taken");
    } else{
        die($mysqli->error . " " . $mysqli->errno);
    }
}
