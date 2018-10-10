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
	
// print_r($_SESSION);

		
?>

<form method="post" action="<?php echo htmlspecialchars("check.php");?>">
	<button type="submit" class="btn btn-primary">Check Messages</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("messages.php");?>">
	<button type="submit" class="btn btn-primary">Send Message</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("logout.php");?>">
	<button type="submit" class="btn btn-primary">Logout</button>
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
</body>
</html>