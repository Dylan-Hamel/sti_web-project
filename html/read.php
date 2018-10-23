<?php
include_once 'header.php';
?>
<body>
<h1>
	READ MESSAGE	
</h1>
<ul>
  <li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<?php
if (isset($_GET['id'])) {
    if (empty($_GET["id"])) {
        $idErr = "message ID is required";
        $error = True;
    } else {
        $id = htmlspecialchars($_GET["id"]);
    }
    try {
        // Connect DB
        $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $file_db->prepare('SELECT * FROM messages WHERE id = :id;');
        $stmt->execute(array(':id' => $id));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        $correctUser = False;
        foreach ($result as $rows => $row) {
            if ($_SESSION['username'] == $row['receiver']) {
                $correctUser = True;
            }
        }
        if ($correctUser) {
            foreach ($result as $row) {
                /*
				if ($row['read']) echo "<input type=\"checkbox\" checked disabled readonly />";
                else echo "<input type=\"checkbox\" disabled readonly />";
				*/
                echo "</td>";
                echo "Title : " . $row['title'];
                echo "Sender : " . $row['sender'];
                echo "Date : " . $row['datetime'];
                echo "Body : " . $row['body'];
                echo "<a href=\"delete.php?id=$row['id']\" class=\"button\">Delete</a> <a href=\"messages.php?receiver=$row['sender']&title=Re:$row['title']'\" class=\"button\">Answer</a>";
            }
        } else {
            echo "Ce message ne vous est pas destiné";
        }
        $file_db = null;
    }
    catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
    }
}
?>

<button onclick="window.history.back();"> Return </button>
</body>
</html>