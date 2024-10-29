<?php

session_start();

$mysqli = require __DIR__ . "/db.php";

if (isset($_SESSION["user_id"])) {
    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
}

$sql = "SELECT user.login, title, start_date, end_date, description, image_path, category.type, category.color
        FROM entry
        JOIN category on category.id = entry.category
        JOIN user on user.id = entry.user";
$entries_result = $mysqli->query($sql);

$array = array(
    "1" => "description 1",
    "2" => "description 2",
    "3" => "description 3",
    "4" => "description 4",
);

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
                    <li><?= htmlspecialchars($user["login"]) ?></li>
                    <li><a href="logout.php">Log out</a></li>
                <?php else: ?>
                    <li><a href="login.php">Log in</a> or <a href="../signup.html">sign up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <div class="timeline">
    <?php
        $i = 0;
        while($row = $entries_result->fetch_assoc()) {
            if($i % 2 == 0) echo "<div class=\"container right\">";
            else echo "<div class=\"container left\">";
            $i++;

            echo "<div class=\"content\" onclick=\"showModal(" . htmlspecialchars($i) . ")\">";
            echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
            echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
            echo "</div></div>";

            echo "<div id=" . htmlspecialchars($i) . " class=\"modal\">";
            echo "<div class=\"modal-content\">";
            echo "<span class=\"close-btn\" onclick=\"closeModal(" . htmlspecialchars($i) . ")\">&times;</span>";
            echo "<h2>Title: " . htmlspecialchars($row["title"]) . "</h2>";
            echo "<h3>User: " . htmlspecialchars($row["login"]) . "</h3>";
            echo "<h4>Date: " . htmlspecialchars($row["start_date"]) . " - " . htmlspecialchars($row["end_date"]) . "</h4>";
            echo "<p>Category: " . htmlspecialchars($row["type"]) . "</p>";
            echo "<p>Description: " . htmlspecialchars($row["description"]) . "</p>";
            echo "</div></div>";
        }
    ?>
    </div>

<script>
    function showModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>


</body>
</html>