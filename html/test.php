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

	<table style="width:100%">
  <tr>
    <th>Read</th>
    <th>Title</th>
    <th>Sender</th> 
    <th>Date</th>
	<th>Actions</th>
  </tr>

<?php
	
    try {
		
  	  // Connect DB
  	   $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
  	   // Set errormode to exceptions
  	   $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	   
		$username = $_SESSION['username'];
		$result =  $file_db->query("SELECT * FROM messages WHERE receiver = '$username';");
		
		foreach($result as $row) {
			echo "<tr>";
			echo "<td>";
			if ($row['read'])
				echo "<input type=\"checkbox\" checked disabled readonly />";
			else
				echo "<input type=\"checkbox\" disabled readonly />";
			echo "<td>" . $row['title'] . "</td>";
			echo "<td>" . $row['sender'] . "</td>";
			echo "<td>" . $row['datetime'] . "</td>";
			echo "<td><input type=\"button\" value=\"delete\" onclick=\"location.href='delete.php'\" /> <button onclick=\"location.href='/read.php'\" type=\"button\">Read</button></td>";
			echo "</tr>";
		}
		echo "</table>";
		
	   
     } catch(PDOException $e) {
       // Print PDOException message
       echo $e->getMessage();
     }  
?>
<button onclick="window.history.back();"> Back </button>

</body>
</html>