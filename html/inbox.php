<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
	INBOX

<?php

session_start();
	
print_r($_SESSION);
	
	
?>

<form method="post" action="<?php echo htmlspecialchars("logout.php");?>">
	<button type="submit" class="btn btn-primary">Logout</button>
</form>
<form method="post" action="<?php echo htmlspecialchars("password.php");?>">
	<button type="submit" class="btn btn-primary">Change Password</button>
</form>
</body>
</html>