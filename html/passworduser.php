<!DOCTYPE HTML>  
<html>
<head>
	<style>
		.error {color: #FF0000;}
		</style>
	</head>
	<body>  
		<h1>
			CHANGE USER PASSWORD
		</h1>
		<ul>
			<li>Logged as <?php session_start(); echo $_SESSION['username']?></li>
		</ul>
	
		<?php	
		session_start();

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			$usernameErr = $passwordErr = "";
			$username = $password = "";
			$error = False;
	
			if (empty($_POST["username"])) {
				$usernameErr = "Username is required";
				$error = True;
			} else {
				$username = $_POST["username"];	
			}
			
			if (empty($_POST["password"])) {
				$passwordErr = "Password is required";
				$error = True;
			} else {
				$password = $_POST["password"];	
			}
			
			if (!$error) {
				try {
					// Connect DB
					$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
					// Set errormode to exceptions
					$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	   
					// Select all users/password in DB	   
					$result =  $file_db->query("SELECT username FROM users;");
	   
					$exist = False;
	   
					foreach($result as $row) {
						if ($username == $row['username']){
							$exist = True;
						}
					}
	   
					if ($exist) {
						$file_db->exec("UPDATE users SET password='$password' WHERE username='$username';");
						echo 'PASSWORD UPDATED';
					} else {
						echo 'USER DOES NOT EXIST';
					}
	   
				} catch(PDOException $e) {
					// Print PDOException message
					echo $e->getMessage();
				}
   
			} else {
				echo 'Error PASSWORD NOT UPDATED';
			}   
	

		}

		?>

		<br><br>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
			Usenrmae: </br><input type="text" name="username">
			<?php
			if (!empty($usernameErr)) {
				echo $usernameErr;
			}
			?>
			<br><br>
			New Password: </br><input type="text" name="password">
			<?php
			if (!empty($passwordErr)) {
				echo $passwordErr;
			}
			?>
			<br><br>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		<form method="post" action="<?php echo htmlspecialchars("inbox.php");?>">  
			<button type="submit" class="btn btn-primary">Return</button>
		</form>
	</body>
	</html>