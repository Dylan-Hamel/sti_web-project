<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<h1>
	WELCOME	
</h1>
<ul>
  <li>Logged as <?php session_start(); echo $_SESSION['username']?></li>
</ul>


<?php

session_start();

if (!isset($_SESSION['username']))	 {
	header("Location: login.php");
}
	
    try {
		
  	  // Connect DB
  	   $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
  	   // Set errormode to exceptions
  	   $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	   
		$username = $_SESSION['username'];
		$result =  $file_db->query("SELECT * FROM messages WHERE receiver = '$username';");

		foreach($result as $row) {
			echo "======================================================";
			echo "<br/>";
			echo "Title: " . $row['title'] . "<br/>";
			echo "Sender: " . $row['sender'] . "<br/>";
			echo "Receiver: " . $row['receiver'] . "<br/>";
			echo "Date: " . $row['datetime'] . "<br/>";
			echo "Body: " . $row['body'] . "<br/>";
			echo "<br/>";
		}
	   
     } catch(PDOException $e) {
       // Print PDOException message
       echo $e->getMessage();
     }  
?>


<?php

session_start();
	
// print_r($_SESSION);

		
?>

<form method="post" action="<?php echo htmlspecialchars("messages.php");?>">
	<button type="submit" class="btn btn-primary">Send Message</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("manageuser.php");?>">
	<button type="submit" class="btn btn-primary">Manage User</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("password.php");?>">
	<button type="submit" class="btn btn-primary">Change Password</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("adduser.php");?>">
	<button type="submit" class="btn btn-primary">Add User</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("removeuser.php");?>">
	<button type="submit" class="btn btn-primary">Remove user</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("passworduser.php");?>">
	<button type="submit" class="btn btn-primary">Change User Password</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("logout.php");?>">
	<button type="submit" class="btn btn-primary">Logout</button>
</form>
</body>
</html>