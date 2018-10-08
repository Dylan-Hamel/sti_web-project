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

$password_1 = $password_2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (((!empty($_POST["$password_1"])) and (!empty($_POST["$password_2"]))) or $_POST["$password_1"] != $_POST["$password_2"])  {
		// Injection SQL
		$update = ("UPDATE users SET password = " . $password_1 .  " WHERE username = " . $_SESSION['username'] . ";");
	}		
}
	

?>


<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Password: </br><input type="text" name="password_1">
  <br><br>
  Re-Type: </br><input type="text" name="password_2">
  <br><br>
    
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("inbox.php");?>">  
  <button type="submit" class="btn btn-primary">Return</button>
</form>
</body>
</html>