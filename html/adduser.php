<!DOCTYPE HTML>  
<html>
<head>
	<style>
		.error {color: #FF0000;}
		</style>
	</head>
	<body>  
		<h1>
			ADD USER
		</h1>
		<ul>
			<li>Logged as <?php session_start(); echo $_SESSION['username']?></li>
		</ul>
	
		<?php	
		session_start();

		$passwordErr = $usernameErr = "";
		$newusername = $password = "";
		$enable = $admin = 0;
		$error = False;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
			if (empty($_POST["newusername"])) {
				$usernameErr = "Username is required";
				$error = True;
			} else {
				$newusername = $_POST["newusername"];	
			}
  
			if (empty($_POST["password"])) {
				$passwordErr = "Password is required";
				$error = True;
			} else {
				$password = $_POST["password"];
			}
  
			if ($_POST["enable"] == 1) {
				$enable = $_POST["enable"];
			} else {
				$enable = 0;
			}
			if ($_POST["admin"] == 1) {
				$admin = $_POST["admin"];
			} else {
				$admin = 0;
			}
  
			// check if user doesn't exist
			if (!$error) {
				try {
					// Connect DB
					$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
					// Set errormode to exceptions
					$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	   
					// Select all users/password in DB
	   
					$file_db->exec("SELECT username FROM users;");
	   
					$exist = False;
	   
					foreach($result as $row) {
						if ($newusername == $row['username']){
							$exist = True;
						}
					}
	   
					if (!$exist) {
						$file_db->exec("INSERT INTO users (username, password, enable, admin) 
							VALUES ('$newusername' , '$password', '$enable' ,'$admin');");
						echo 'USER ADDED';
					}
	   
				} catch(PDOException $e) {
					// Print PDOException message
					echo $e->getMessage();
				}
   
			} else {
				echo 'Error USER NOT ADDED';
			}   
	

		}

		?>

		<br><br>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
			Usenrmae: </br><input type="text" name="newusername">
			<?php
			if (!empty($usernameErr)) {
				echo $usernameErr;
			}
			?>
			<br><br>
			Password: </br><input type="text" name="password">
			<?php
			if (!empty($passwordErr)) {
				echo $passwordErr;
			}
			?>
			<br><br>
			Enable: </br><input type="checkbox" name="enable" value="1">
			<br><br>
			Admin: </br><input type="checkbox" name="admin" value="1">
			<br><br>
  
  
    
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		<form method="post" action="<?php echo htmlspecialchars("inbox.php");?>">  
			<button type="submit" class="btn btn-primary">Return</button>
		</form>
	</body>
	</html>