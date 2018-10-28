<?php
include_once 'header.php';
if (!isset($_SESSION['admin']) or $_SESSION['admin'] != 1) {
    header("Location: index.php");
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
$usernameErr = "";
$username = "";
$formFilled = False;
$error = False;
$applied = 2;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
        $error = True;
    } else {
        $username = $_POST["username"];
        $enable = $_POST["enable"];
    }
    $formFilled = True;
} elseif (isset($_GET['username'])) {
    if (empty($_GET["username"])) {
        $usernameErr = "Username is required";
        $error = True;
    } else {
        $username = htmlspecialchars($_GET["username"]);
		if(isset($_GET['enable'])) {
			$enable = htmlspecialchars($_GET["enable"]);
		}
		else {
			$enable = 0;
		}
    }
    $formFilled = True;
}
if ($formFilled and !$error) {
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
            $stmt = $file_db->prepare("UPDATE users SET enable=:enable WHERE username=:username;");
            $stmt->execute(array(':enable' => $enable, ':username' => $username));
            $applied = $stmt->rowCount();
            $stmt = null;
			if($enable){
				echo 'USER ENABLE';
			}
			else {
				echo 'USER DISABLE';
			}
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
?>


<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
  Username : </br><input type="text" name="username"><?php if (!empty($usernameErr)) {
    echo $usernameErr;
} ?>
  <br>
  Enable: </br><input type="checkbox" name="enable" value="1">
  <br>
  
  <button type="submit">Submit</button>
</form>
 <?php
if ($applied == 0) {
    echo "Problème lors de l'activation/desactivation de l'utilisateur";
}
?>
<a href="index.php" class="button">Retour</a>
</body>
</html>