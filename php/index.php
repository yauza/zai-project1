<?php

session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/db.php";
    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>HOME</title>
    <meta charset="utf-8">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"> -->
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <div class="navbar-container">

        <nav class="navbar">
            <div class="logo">TEST</div>
            <ul>
                <?php if (isset($user)): ?>
                    <li>Hello <?= htmlspecialchars($user["login"]) ?>.</li>
                    <li><a href="logout.php">Log out</a></li>
                <?php else: ?>
                    <li><a href="login.php">Log in</a> or <a href="../signup.html">sign up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <div>
            

    </div>

</body>
</html>