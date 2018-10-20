<!DOCTYPE HTML>  
<html>
<head>
</head>
<body>
<h1>
	LOGIN
</h1>
	
<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
}
// define variables
$usernameErr = $passwordErr = $disabledErr = "";
$username = $password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = htmlspecialchars($_POST["username"]);
    }
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = htmlspecialchars($_POST["password"]);
    }
    try {
        // Connect DB
        $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Select all users/password in DB
        $result = $file_db->query("SELECT * FROM users;");
        /*
        $file_db->exec("INSERT INTO users (username, password, enable, admin)
                    VALUES ('dylan','123123',1,1)");
        $file_db->exec("INSERT INTO users (username, password, enable, admin)
                    VALUES ('yannis','123123',0,0)");
        
        
        
        
        foreach($result as $row) {
         echo "Username: " . $row['username'] . "<br/>";
         echo "Password: " . $row['password'] . "<br/>";
         echo "Enable: " . $row['enable'] . "<br/>";
         echo "Admin: " . $row['admin'] . "<br/>";
         echo "<br/>";
        }
        */
        foreach ($result as $row) {
            if ($username == $row['username'] && $password == $row['password'] && $row['enable'] == 1) {
                $_SESSION['username'] = $username;
                $_SESSION['admin'] = $row['admin'];
                header("Location: index.php");
            }
        }
        $usernameErr = "Username error";
        $passwordErr = "Password error";
        $disabledErr = "Account disabled";
    }
    catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
    }
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
  Username: </br><input type="text" name="username" value="<?php echo $username; ?>">
  <?php
if (!empty($usernameErr)) {
    echo $usernameErr;
}
?>
  <br><br>
  Password: </br><input type="password" name="password" value="<?php echo $password; ?>">
  <?php
if (!empty($passwordErr)) {
    echo $passwordErr;
}
?>
  <br><br>
  <button type="submit">Submit</button>
</form>

</body>
</html>