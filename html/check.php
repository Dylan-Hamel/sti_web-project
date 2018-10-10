<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<h1>
	My MESSAGES
	
</h1>

<ul>
  <li>Logged as <?php session_start(); echo $_SESSION['username']?></li>
</ul>

<form method="post" action="<?php echo htmlspecialchars("inbox.php");?>">
	<button type="submit" class="btn btn-primary">Return</button>
</form>

<?php
	
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

</body>
</html>