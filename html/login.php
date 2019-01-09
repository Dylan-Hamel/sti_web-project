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

    // Make sure you end script execution if not logged in
    exit(header("Location: index.php"));
}

// define variables
$usernameErr = $passwordErr = $disabledErr = "";
$isEnabled = true;
$valid = false;

// Make sure we have a valid request (expecting 2 parameters)
if ($_SERVER["REQUEST_METHOD"] == "POST" AND
    isset($_POST["username"]) AND !is_null($_POST["username"]) AND
    isset($_POST["password"]) AND !is_null($_POST["password"])) {

    // Check the types are correct
    if (filter_var($_POST["username"], PARAM_STR) !== false AND
        filter_var($_POST["password"], PARAM_STR) !== false) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $valid = true;
    } else {
        $errorMsg = "Invalid parameter";
    }
	
	if($valid) {
		try {
			// Connect DB
			$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');

			// Set errormode to exceptions
			$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Select all users/password in DB
			$stmt = $file_db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1;");
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->execute();
			$row = $stmt->fetch();

			// First authenticate, whatever enabled or not
            if (\Sodium\crypto_pwhash_str_verify($row['password'], $password) !== false) {

                // Give hint about dis/en-abled account only once authenticated
                if ($row['enable'] == 1) {

                    // Setup session and redirect
                    $_SESSION['username'] = $username;
                    $_SESSION['admin'] = $row['admin'];

                    // Logged in
                    exit(header("Location: index.php"));
                } else {
                    $isEnabled = False;
                    $disabledErr = "Account disabled";
                }
            } else {
                $usernameErr = "Username error";
                $passwordErr = "Password error";
            }
        }
		catch(PDOException $e) {
		    // Print PDOException message
			echo $e->getMessage();
		}
	}
} else {
    echo "Bad request";
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
<?php
if (!$isEnabled) {
    echo $disabledErr;
}
?>
</body>
</html>