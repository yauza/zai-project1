<?php

session_start();

$mysqli = require __DIR__ . "/db.php";

if (isset($_SESSION["user_id"])) {
    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
}

$entries_sql = "SELECT user.login, entry.id, title, start_date, end_date, description, image_url, category.type, category.color
        FROM entry
        JOIN category on category.id = entry.category
        JOIN user on user.id = entry.user
        ORDER BY start_date";

$entries_result = $mysqli->query($entries_sql);

$categories = array();
$categories_sql = "SELECT id, type, color FROM category";
$categories_result = $mysqli->query($categories_sql);
while($cat_row = $categories_result->fetch_assoc()) {
    $categories[$cat_row["id"]] = $cat_row["type"];
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Diary</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/styles.css">
 </head>
<body>

    <div class="navbar-container">

        <nav class="navbar">
            <div class="logo">Diary</div>
            <ul>
                <?php if (isset($user)): ?>
                    <li><button class="entry" onclick="showModal('add-entry')">Add entry</button></li>
                    <li><?= htmlspecialchars($user["login"]) ?></li>
                    <li><a href="password-change.php">Change password</a></li>
                    <li><a href="logout.php">Log out</a></li>
                <?php else: ?>
                    <li><a href="login.php">Log in</a> or <a href="../signup.html">Sign up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <div class="timeline">
    <?php
        // Add modal
        echo "<div id=\"add-entry\" class=\"modal add-modal\">";
            echo "<div class=\"modal-content\">";
            echo "<span class=\"close-btn\" onclick=\"closeModal('add-entry')\">&times;</span>";
            echo "<form action=\"add-entry.php\" method=\"post\">";
            echo "<h2>Title: <textarea class=\"title\" name=\"title\" id=\"title\" cols=\"50\" rows=\"1\"></textarea></h2>";
            echo "<h3>User: " . htmlspecialchars($user["login"]) . "</h3><input type=\"hidden\" name=\"user\" value=\"" . htmlspecialchars($user["id"]) . "\">";
            echo "<h4>Start date: <input type=\"date\" name=\"start_date\" id=\"start-date\"> </h4>";
            echo "<h4>End date: <input type=\"date\" name=\"end_date\" id=\"end-date\"> </h4>";
            echo "<h4>Category: </h4>";
            echo "<select id=\"category\" name=\"category\">";     
                foreach($categories as $cat_id => $cat_type) {
                    echo "<option value=\"" . htmlspecialchars($cat_id) . "\">" . htmlspecialchars($cat_type) . "</option>";
                }
            echo "</select>";
            echo "<h4>Description: <textarea class=\"description\" name=\"description\" id=\"description\" cols=\"50\" rows=\"3\"></textarea></h4>";
            echo "<h4>Image url: <textarea class=\"image\" name=\"image_url\" id=\"image_url\" cols=\"50\" rows=\"1\"></textarea></h4>";
            echo "<button class=\"entry\">Save&exit</button>";    
            echo "</form>";           
        echo "</div></div>";   

        $i = 0;
        while($row = $entries_result->fetch_assoc()) {
            if($i % 2 == 0) echo "<div class=\"container right\">";
            else echo "<div class=\"container left\">";
            $i++;

            echo "<div class=\"content\" onclick=\"showModal(" . htmlspecialchars($i) . ")\">";
            echo "<h2 style=\"color:" . htmlspecialchars($row["color"]) .  "\">" . htmlspecialchars($row["title"]) . "</h2>";
            echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
            echo "<img src=\"" . htmlspecialchars($row["image_url"]) . "\">";
            echo "</div></div>";

            // Edit modal
            echo "<div id=" . htmlspecialchars($i) . " class=\"modal\">";
            echo "<div class=\"modal-content\">";
            echo "<span class=\"close-btn\" onclick=\"closeModal(" . htmlspecialchars($i) . ")\">&times;</span>";
            if (isset($user)) {
                echo "<form action=\"edit-entry.php\" method=\"post\">";
                echo "<input type=\"hidden\" id=\"id-input\" name=\"id\" value=\"" . htmlspecialchars($row["id"]) . "\">";
                echo "<h2>Title: <textarea class=\"title\" id=\"title\" cols=\"50\" rows=\"1\" name=\"title\">" . htmlspecialchars($row["title"]) . "</textarea></h2>";
                echo "<h3>User: " . htmlspecialchars($row["login"]) . "</h3>";
                echo "<h4>Date: " . htmlspecialchars($row["start_date"]) . " - " . htmlspecialchars($row["end_date"]) . "</h4>";
                echo "<h4>Category: </h4>";
                echo "<select id=\"categories\" name=\"category\">";         
                    foreach($categories as $cat_id => $cat_type) {
                        if ($row["type"] === $cat_type) $selected = "selected";
                        else $selected = "";
                        echo "<option " . $selected . " value=\"" . htmlspecialchars($cat_id) . "\">" . htmlspecialchars($cat_type) . "</option>";
                    }
                echo "</select>";
                echo "<h4>Description: <textarea class=\"description\" name=\"description\" id=\"description\" cols=\"50\" rows=\"3\">" . htmlspecialchars($row["description"]) . "</textarea></h4>";
                echo "<h4>Image url: <textarea class=\"image\" name=\"image_url\" id=\"image_url\" cols=\"50\" rows=\"1\">" . htmlspecialchars($row["image_url"]) . "</textarea></h4>";    
                echo "<button class=\"entry\">Save&exit</button>";
                echo "</form>";
            } else {                
                echo "<h2>Title: " . htmlspecialchars($row["title"]) . "</h2>";
                echo "<h3>User: " . htmlspecialchars($row["login"]) . "</h3>";
                echo "<h4>Date: " . htmlspecialchars($row["start_date"]) . " - " . htmlspecialchars($row["end_date"]) . "</h4>";
                echo "<h4>Category: " . htmlspecialchars($row["type"]) . "</h4>";
                echo "<h4>Image url: <p>" . htmlspecialchars($row["image_url"]) . "</p></h4>";
                echo "<h4>Description:</h4><p> " . htmlspecialchars($row["description"]) . "</p>";
            }
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