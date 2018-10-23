<?php
include_once 'header.php';
?>
<body>
<h1>
	Change password
</h1>
<ul>
  <li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>
	
<?php
$password1 = $password2 = "";
$passwordMismatch = "";
$applied = 2;
$username = $_SESSION['username'];
if (isset($_GET['username']) and $_SESSION['admin'] == 1) {
    $username = htmlspecialchars($_GET['username']);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["password1"]) and !empty($_POST["password1"])) {
        $password1 = htmlspecialchars($_POST["password1"]);
    }
    if (isset($_POST["password2"]) and !empty($_POST["password2"])) {
        $password2 = htmlspecialchars($_POST["password2"]);
    }
    if ($password1 == $password2) {
        try {
            // Connect DB
            $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
            // Set errormode to exceptions
            $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $file_db->prepare('UPDATE users SET password = :password WHERE username = :username');
            $stmt->execute(array(':password' => $password1, ':username' => $username));
            $applied = $stmt->rowCount();
            $stmt->close();
            $stmt = null;
            $file_db = null;
        }
        catch(PDOException $e) {
            // Print PDOException message
            echo $e->getMessage();
        }
    } else {
        $passwordMismatch = "Les mot de passe de correspondent pas";
    }
}
?>


<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
  Password: </br><input type="password" name="password1">
  <br><br>
  Re-Type: </br><input type="password" name="password2">
  <br><br>
  <?php
if (!empty($passwordMismatch)) {
    echo $passwordMismatch;
}
if ($applied == 0) {
    echo "Impossible de mettre à jour le mot de passe de cet utilisateur : $username, êtes-vous sûr qu'il existe ?";
}
?>
  <button type="submit">Submit</button>
</form>
<button onclick="window.history.back();">Return</button>
</body>
</html>