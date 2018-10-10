<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<h1>
	MESSAGES
	
</h1>

<ul>
  <li>Logged as <?php session_start(); echo $_SESSION['username']?></li>
</ul>

<?php

session_start();
	
// define variables
$receiverErr = $titleErr = $bodyErr = "";
$receiver = $title = $body = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["receiver"])) {
    $receiverErr = "Receiver is required";
  } else {
	  $receiver = $_POST["receiver"];	
  }
  
  if (empty($_POST["title"])) {
    $titleErr = "Title is required";
  } else {
    $title = $_POST["title"];
  }
  
  if (empty($_POST["body"])) {
    $bodyErr = "Body is required";
  } else {
    $body = $_POST["body"];
  }
  
  $now = date("F j, Y, g:i a");
  $username = $_SESSION['username'];
  
  try {
	  // Connect DB
	   $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
	   // Set errormode to exceptions
	   $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	   
	   // Select all users/password in DB
	   
	   $file_db->exec("INSERT INTO messages (title, body, sender, receiver, datetime) 
		   VALUES ('$title' , '$body', '$username' ,'$receiver', '$now');");
	   
   } catch(PDOException $e) {
     // Print PDOException message
     echo $e->getMessage();
   }   
  
}
		
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
 <p>Receiver : <br/><input type="text" name="receiver" />
 <?php
 if (!empty($receiverErr)) {
  echo $receiverErr;
 }
 ?>
 </p>
 <p>Title	  : <br/><input type="text" name="title" />
 <?php
 if (!empty($titleErr)) {
  echo $titleErr;
 }
 ?>
 </p>
 <p>Body	  : <br/><textarea name="body" rows="5" cols="40"></textarea>
 <?php
 if (!empty($bodyErr)) {
  echo $bodyErr;
 }
 ?>
 </p> 
 <p><input type="submit" value="Send"></p>
</form>
<form method="post" action="<?php echo htmlspecialchars("inbox.php");?>">
	<button type="submit" class="btn btn-primary">Return</button>
</form>
</body>
</html>
