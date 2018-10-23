<?php
include_once 'header.php';
if (!isset($_SESSION['admin']) or $_SESSION['admin'] != 1) {
    header("Location: index.php");
}
?>
<body>  
<h1>
	REMOVE USER
</h1>
<ul>
	<li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<?php
$usernameErr = "";
$username = "";
$error = False;
$formFilled = False;
$applied = 2;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
        $error = True;
    } else {
        $username = $_POST["username"];
    }
    $formFilled = True;
} elseif (isset($_GET['username'])) {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
        $error = True;
    } else {
        $username = htmlspecialchars($_GET["username"]);
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
            $stmt = $file_db->prepare("DELETE FROM users WHERE username=:username;");
            $stmt->execute(array(':username' => $username));
            $applied = $stmt->rowCount();
            $stmt->close();
            $stmt = null;
            echo 'USER REMOVED';
        } else {
            echo 'USER DOES NOT EXIST';
        }
    }
    catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
    }
}
?>

<br><br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
	Usenrmae: </br><input type="text" name="username">
	<?php
if (!empty($usernameErr)) {
    echo $usernameErr;
}
?>
	<br><br>
	<button type="submit">Submit</button>
</form>
 <?php
if ($applied == 0) {
    echo "Probème lors de la suppression de l'utilisateur";
}
?>
<button onclick="window.history.back();"> Return </button>
</body>
</html>