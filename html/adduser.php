<?php
include_once 'header.php';
if (!isset($_SESSION['admin']) or $_SESSION['admin'] !== 1) {

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
    isset($_POST["newusername"]) AND isset($_POST["password"]) OR
    isset($_POST["enable"]) AND isset($_POST["admin"]) OR
    !is_null($_POST["newusername"]) AND !is_null($_POST["password"])) {

    // Check field types
    if (filter_var($_POST["newusername"], PARAM_STR) !== false AND
        filter_var($_POST["password"], PARAM_STR) !== false AND
        filter_var($_POST["enable"], PARAM_INT) !== false AND
        filter_var($_POST["admin"], PARAM_INT) !== false) {

        $username = $_POST["username"];
        $password = $_POST["password"];
        $hash = \Sodium\crypto_pwhash_str($password,
            \Sodium\CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            \Sodium\CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE);

        $enable = $_POST["enable"];
        $admin = $_POST["admin"];
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
        $stmt = $file_db->prepare("SELECT username FROM users WHERE username=:username;");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $file_db->execute();
        $rowCount = $stmt->rowCount();

        if ($rowCount == 0) {
            $stmt = $file_db->prepare("INSERT INTO users (username, password, enable, admin) 
					VALUES (:username, :password, :enable, :admin);");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
            $stmt->bindParam(':enable', $enable, PDO::PARAM_INT);
            $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
            $file_db->execute();

            echo 'USER ADDED' . $admin . $enable;
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
        <p><?php echo $errorMsg; ?></p>
    </div>
<?php } ?>

<br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
	Username: </br><input type="text" name="newusername">
	<br><br>
	Password: </br><input type="text" name="password">
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