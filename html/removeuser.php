<!DOCTYPE HTML>  
<html>
<head>
	<style>
		.error {color: #FF0000;}
		</style>
	</head>
	<body>  
		<h1>
			REMOVE USER
		</h1>
		<ul>
			<li>Logged as <?php session_start(); echo $_SESSION['username']?></li>
		</ul>
	
		<?php	
		
		session_start();

		if (!isset($_SESSION['username']))	 {
			header("Location: login.php");
		}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			$usernameErr = "";
			$username = "";
			$error = False;
	
			if (empty($_POST["username"])) {
				$usernameErr = "Username is required";
				$error = True;
			} else {
				$username = $_POST["username"];	
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
						$file_db->exec("DELETE FROM users WHERE username='$username';");
						echo 'USER REMOVED';
					} else {
						echo 'USER DOES NOT EXIST';
					}
	   
				} catch(PDOException $e) {
					// Print PDOException message
					echo $e->getMessage();
				}
   
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
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		<button onclick="window.history.back();"> Return </button>
	</body>
	</html>