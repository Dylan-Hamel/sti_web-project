<?php
include_once 'header.php';
if (!isset($_SESSION['admin']) or $_SESSION['admin'] !== 1) {

    // Make sure you end script execution if not logged in
    exit(header("Location: index.php"));
}
?>
<body>
<h1>
	MANAGE USER
</h1>
<ul>
   <li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<?php
$passwordErr = $usernameErr = "";
$enable = $admin = 0;
$valid = false;

// Check if is a valid request (4 fileds expected)
if ($_SERVER["REQUEST_METHOD"] == "POST" AND
    isset($_POST["username"]) AND isset($_POST["enable"]) AND
    !is_null($_POST["username"]) AND !is_null($_POST["enable"])) {

    // Check field types
    if (filter_var($_POST["username"], PARAM_STR) !== false AND
        filter_var($_POST["enable"], PARAM_INT) !== false) {

        $username = $_POST["username"];
        $enable = $_POST["enable"];
        $valid = true;
    }
    else {
        $errorMsg = "Invalid value";
    }
} else {
    $errorMsg = "Bad request";
}

if ($valid) {
    try {
        // Connect DB
        $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');

        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Select all users/password in DB
        $stmt = $file_db->prepare("UPDATE users SET enable=:enable WHERE username=:username;");
        $stmt->bindParam(':enable', $enable, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $file_db->execute();
        $rowCount = $stmt->rowCount();

        if ($rowCount == 0) {
            echo 'GIVEN USER COULD NOT BE UPDATED';
        } elseif ($rowCount == 1) {
            echo "USER SUCCESSFULLY UPDATED";
        } else {
            echo "Oups. Something wrong happened";
        }

        // Proper DB connection close
        $stmt->closeCursor();
        $file_db = null;
    }
    catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
    }
}

echo $errorMsg;

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
  Username : </br><input type="text" name="username"><?php if (!empty($usernameErr)) { echo $usernameErr; } ?>
  <br>
  Enable: </br><input type="checkbox" <?php echo (($enabled) ? 'checked="checked"' : ''); ?> name="enable">
  <br>
  
  <button type="submit">Submit</button>
</form>

<a href="index.php" class="button">Retour</a>
</body>
</html>