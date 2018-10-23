<?php
include_once 'header.php';
?>
<body>  
<h1>
	SEND MESSAGE
</h1>
<ul>
  <li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<?php
$receiverErr = $titleErr = $bodyErr = "";
$receiver = $title = $body = "";
$formFilled = False;
$error = False;
$applied = 2;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["receiver"])) {
        $receiverErr = "Receiver is required";
        $error = True;
    } else {
        $receiver = htmlspecialchars($_POST["receiver"]);
    }
    if (empty($_POST["title"])) {
        $titleErr = "Title is required";
        $error = True;
    } else {
        $title = htmlspecialchars($_POST["title"]);
    }
    if (empty($_POST["body"])) {
        $bodyErr = "Body is required";
        $error = True;
    } else {
        $body = htmlspecialchars($_POST["body"]);
    }
    $formFilled = True;
}
/*
elseif(isset($_GET['receiver']) and isset($_GET['title'])){
	if (empty($_GET["receiver"])) {
		$receiverErr = "Receiver is required";
	  $error = True;
    } else {
  	  $receiver = htmlspecialchars($_GET["receiver"]);
    }
	
	if (empty($_GET["title"])) {
		$titleErr = "Title is required";
		$error = True;
	} else {
		$title = htmlspecialchars($_GET["title"]);
	}
	
	$formFilled = True;
}
*/
if ($formFilled and !$error) {
    $now = date("F j, Y, g:i a");
    try {
        // Connect DB
        $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $file_db->prepare('INSERT INTO messages (title, body, sender, receiver, datetime) VALUES (:title, :body, :sender, :receiver, :now)');
        $stmt->execute(array(':title' => $title, ':body' => $body, ':sender' => $_SESSION['username'], ':receiver' => $receiver, ':now' => $now));
        $applied = $stmt->rowCount();
        $stmt = null;
        $file_db = null;
    }
    catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
    }
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 
 <p>Receiver : <br/><input type="text" name="receiver" <?php if (isset($_GET["receiver"]) and !empty($_GET["receiver"])) echo "value=\"" . htmlspecialchars($_GET["receiver"]) . "\""; ?> />
 <?php
if (!empty($receiverErr)) {
    echo $receiverErr;
}
?>
 </p>
 <p>Title	  : <br/><input type="text" name="title" <?php if (isset($_GET["title"]) and !empty($_GET["title"])) echo "value=\"" . htmlspecialchars($_GET["title"]) . "\""; ?> />
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
 <?php
if ($applied == 0) {
    echo "Probème lors de l'envoi du message";
}
?>
<button onclick="window.history.back();"> Return </button>
</body>
</html>
