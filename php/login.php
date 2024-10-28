<?php

$valid_login = true;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/db.php";
    $sql = sprintf("SELECT * FROM user WHERE login = '%s'", $mysqli->real_escape_string($_POST["login"]));
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    
    if ($user) {
        if (password_verify($_POST["password"], $user["password"])) {
            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit;
        }
    }

    $valid_login = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
    <h1>Login</h1>

    <?php if (!$valid_login): ?>
        <em>Invalid login</em>
    <?php endif; ?>

    <form method="post">
        <label for="login">login</label>
        <input type="text" name="login" id="login"
               value="<?= htmlspecialchars($_POST["login"] ?? "") ?>">

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <button>Log in</button>
    </form>
</body>
</html>