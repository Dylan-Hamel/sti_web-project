<?php
include_once 'header.php';
if (!isset($_SESSION['admin']) or $_SESSION['admin'] !== 1) {

    // Make sure you end script execution if not logged in
    exit(header("Location: index.php"));
}
?>
<body>  
<h1>
	CHANGE USER PASSWORD
</h1>
<ul>
	<li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameErr = $passwordErr = "";
    $username = $password = "";
    $error = False;
    $applied = 2;
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
        $error = True;
    } else {
        $username = $_POST["username"];
    }
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $error = True;
    } else {
        $password = $_POST["password"];
    }
    if (!$error) {
        try {
            // Connect DB
            $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
            // Set errormode to exceptions
            $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Select all users/password in DB
            $result = $file_db->query("SELECT username FROM users;");
            $exist = False;
            foreach ($result as $row) {
                if ($username == $row['username']) {
                    $exist = True;
                }
            }
            if ($exist) {
                $stmt = $file_db->prepare('UPDATE users SET password = :password WHERE username = :username');
                $stmt->execute(array(':password' => $password, ':username' => $username));
                $applied = $stmt->rowCount();
                $stmt = null;
                echo 'PASSWORD UPDATED';
            } else {
                echo 'USER DOES NOT EXIST';
            }
            $file_db = null;
        }
        catch(PDOException $e) {
            // Print PDOException message
            echo $e->getMessage();
        }
    }
}
?>

<br><br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
	Username: </br><input type="text" name="username">
	<?php
if (!empty($usernameErr)) {
    echo $usernameErr;
}
?>
	<br><br>
	New Password: </br><input type="text" name="password">
	<?php
if (!empty($passwordErr)) {
    echo $passwordErr;
}
?>
	<br><br>
	<button type="submit">Submit</button>
</form>
<?php
if ($applied == 0) {
    echo "Error";
}
?>
<a href="index.php" class="button">Retour</a>
</body>
</html>