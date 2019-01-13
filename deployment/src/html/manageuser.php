<?php
include_once 'header.php';
if (!isset($_SESSION['admin']) or $_SESSION['admin'] != 1) {

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
    isset($_POST["username"]) AND isset($_POST["enable"]) AND isset($_POST["admin"]) AND
    !is_null($_POST["username"]) AND !is_null($_POST["enable"]) AND !is_null($_POST["admin"])) {

    // Check field types
    if ((filter_var($_POST["username"], FILTER_SANITIZE_STRING) !== false) AND
        (strtolower($_POST["enable"]) == 'on' OR strtolower($_POST["enable"]) == 'off' OR
            $_POST["enable"] == 1 OR $_POST["enable"] == 0) AND
        (strtolower($_POST["admin"]) == 'on' OR strtolower($_POST["admin"]) == 'off' OR
            $_POST["admin"] == 1 OR $_POST["admin"] == 0)) {

        $username = $_POST["username"];
        $enable = ( ($_POST["enable"] == 1 OR strtolower($_POST["enable"]) == 'on') ? 1 : 0);
        $admin = ( ($_POST["admin"] == 1 OR strtolower($_POST["admin"]) == 'on') ? 1 : 0);
        $valid = true;
    }
    else {
        $errorMsg = "Invalid value";
    }
}

if ($valid) {
    try {
        // Connect DB
        $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');

        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Select all users/password in DB
        $stmt = $file_db->prepare("UPDATE users SET enable = :enable, admin = :admin WHERE username = :username;");
        $stmt->bindParam(':enable', $enable, PDO::PARAM_INT);
        $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
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

if (!empty($errorMsg)) {
    echo $errorMsg;
}

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
    Username : </br><input type="text" name="username"><?php if (!empty($usernameErr)) { echo $usernameErr; } ?>
    <br>
    Enable: </br><input type="checkbox" name="enable">
    <br>
    Admin: </br><input type="checkbox" name="admin">
    <br>
  
  <button type="submit">Submit</button>
</form>

<a href="index.php" class="button">Retour</a>
</body>
</html>