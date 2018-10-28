<?php
include_once 'header.php';
if (!isset($_SESSION['admin']) or $_SESSION['admin'] != 1) {
    header("Location: index.php");
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
$error = False;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["newusername"])) {
        $usernameErr = "Username is required";
        $error = True;
    } else {
        $newusername = $_POST["newusername"];
    }
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $error = True;
    } else {
        $password = $_POST["password"];
    }
    if ($_POST["enable"] == 1) {
        $enable = $_POST["enable"];
    } else {
        $enable = 0;
    }
    if ($_POST["admin"] == 1) {
        $admin = $_POST["admin"];
    } else {
        $admin = 0;
    }
    // check if user doesn't exist
    if (!$error) {
        try {
            // Connect DB
            $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
            // Set errormode to exceptions
            $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Select all users/password in DB
            $file_db->exec("SELECT username FROM users;");
            $exist = False;
            foreach ($result as $row) {
                if ($newusername == $row['username']) {
                    $exist = True;
                }
            }
            if (!$exist) {
                $file_db->exec("INSERT INTO users (username, password, enable, admin) 
					VALUES ('$newusername' , '$password', '$enable' ,'$admin');");
                echo 'USER ADDED' . $admin . $enable;
            }
			else {
				echo 'USER already exists';
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
	Username: </br><input type="text" name="newusername">
	<?php
if (!empty($usernameErr)) {
    echo $usernameErr;
}
?>
	<br><br>
	Password: </br><input type="text" name="password">
	<?php
if (!empty($passwordErr)) {
    echo $passwordErr;
}
?>
	<br><br>
	Enable: </br><input type="checkbox" name="enable" value="1">
	<br><br>
	Admin: </br><input type="checkbox" name="admin" value="1">
	<br><br>

	<button type="submit">Submit</button>
</form>
<a href="index.php" class="button">Retour</a>
</body>
</html>