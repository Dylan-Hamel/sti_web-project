<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
	
<?php	

session_start();

if (isset($_SESSION['username']))	 {
	header("Location: inbox.php");
}

// define variables
$usernameErr = $passwordErr = "";
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["username"])) {
    $usernameErr = "Username is required";
  } else {
	  $username = $_POST["username"];	
  }
  
  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = $_POST["password"];
  }

  try {
	  // Connect DB
	   $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
	   // Set errormode to exceptions
	   $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	   
	   // Select all users/password in DB
	   $result =  $file_db->query("SELECT * FROM user WHERE username='$username' AND password='$password'");
	   
	   if ($result->count()) {
		   $_SESSION['username'] = $username;
		   $_SESSION['admin'] = $row['admin'];
		   header("Location: inbox.php");
	   }  
	   
   } catch(PDOException $e) {
     // Print PDOException message
     echo $e->getMessage();
   }   
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Username: </br><input type="text" name="username" value="<?php echo $username;?>">
  <?php
  if (!empty($usernameErr)) {
	  echo $usernameErr;
  }
  ?>
  <br><br>
  Password: </br><input type="text" name="password" value="<?php echo $password;?>">
  <?php
  if (!empty($passwordErr)) {
	  echo $passwordErr;
  }
  ?>
  <br><br>
  
  <button type="submit" class="btn btn-primary">Submit</button>
  
</form>
</body>
</html>