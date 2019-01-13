<?php
include_once 'header.php';
if (!isset($_SESSION['admin']) or $_SESSION['admin'] != 1) {

    // Make sure you end script execution if not logged in
    exit(header("Location: index.php"));
}
?>
<body>  
<h1>
	ADD USER
</h1>
<ul>
	<li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<?php
$passwordErr = $usernameErr = "";
$newusername = $password = "";
$enable = $admin = 0;
$valid = false;

// Check if is a valid request (4 fileds expected)
if ($_SERVER["REQUEST_METHOD"] == "POST" AND
    isset($_POST["newusername"]) AND isset($_POST["password"]) AND
    isset($_POST["enable"]) AND isset($_POST["admin"]) AND
    !is_null($_POST["newusername"]) AND !is_null($_POST["password"])) {

    // Check field types
    if (filter_var($_POST["newusername"], FILTER_SANITIZE_STRING) !== false AND
        filter_var($_POST["password"], FILTER_SANITIZE_STRING) !== false AND
        ($_POST["enable"] == 1 OR $_POST["enable"] == 0 OR strtolower($_POST["enable"]) == 'on') AND
        $_POST["admin"] == 1 OR $_POST["admin"] == 0 OR strtolower($_POST["admin"]) == 'on') {

        $username = $_POST["newusername"];
        $password = $_POST["password"];
        $hash = password_hash($password, PASSWORD_DEFAULT);

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
        $stmt = $file_db->prepare("SELECT username FROM users WHERE username = :username;");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if ($rowCount == 0) {

            $stmt = $file_db->prepare("INSERT INTO users (username, password, enable, admin) 
					VALUES (:username, :password, :enable, :admin);");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
            $stmt->bindParam(':enable', $enable, PDO::PARAM_INT);
            $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
            $stmt->execute();

            echo '<span style="color: green">User successfully added</span>';
        } else {
            $errorMsg = 'this user already exists';
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

?>

<br><br>

<?php if (!empty($errorMsg)) { ?>
    <div>
        <p><?php echo '<span style="color: red">'.$errorMsg.'</span>'; ?></p>
    </div>
<?php } ?>

<br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
	Username: </br><input type="text" name="newusername">
	<br><br>
	Password: </br><input type="password" name="password">
	<br><br>
	Enable: </br><input type="checkbox" name="enable" />
	<br><br>
	Admin: </br><input type="checkbox" name="admin" />
	<br><br>

	<button type="submit">Submit</button>
</form>
<a href="index.php" class="button">Retour</a>
</body>
</html>