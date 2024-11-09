<?php

$valid_credentials = true;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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

    $mysqli = require __DIR__ . "/db.php";
    $sql = sprintf("SELECT * FROM user WHERE login = '%s'", $mysqli->real_escape_string($_POST["login"]));
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($_POST["old-password"], $user["password"])) {
            $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

            // Prevent SQL injection
            $sql = "UPDATE user SET password = ? WHERE login = ?";
            $stmt = $mysqli->stmt_init();
            if (!$stmt->prepare($sql)) {
                die("SQL error: " . $mysqli->error);
            }
            $stmt->bind_param("ss", $hashed_password, $_POST["login"]);

            if ($stmt->execute()) {
                header("Location: ../password-change-finished.html");
                exit;
            } else {
                die($mysqli->error . " " . $mysqli->errno);
            }
        }
    }

    $valid_credentials = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Jan Stobnicki">
    <title>Change password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
 
<body>
    <h1>Change password</h1>

    <?php if (!$valid_credentials): ?>
        <em>Invalid credentials</em>
    <?php endif; ?>

    <form method="post">
        <label for="login">Login</label>
        <input type="text" name="login" id="login"
               value="<?= htmlspecialchars($_POST["login"] ?? "") ?>"> 

        <label for="old-password">Old password</label>
        <input type="password" id="old-password" name="old-password">

        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <label for="repeat-password">Repeat new password</label>
        <input type="password" id="repeat-password" name="repeat-password">

        <button>Change password</button> 
    </form>
</body>
</html>