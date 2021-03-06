<?php
include_once 'header.php';
?>
<body>
<ul>
	<li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<?php
$idErr = "";
$error = False;
$applied = 2;
if (isset($_GET['id'])) {
    if (empty($_GET["id"])) {
        $idErr = "message ID is required";
        $error = True;
    } else {
        $id = htmlspecialchars($_GET["id"]);
    }
    if (!$error) {
        try {
            // Connect DB
            $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
            // Set errormode to exceptions
            $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Select message with provided ID in DB
            $stmt = $file_db->prepare("SELECT receiver FROM messages WHERE id=:id;");
            $stmt->execute(array(':id' => $id));
            $messageResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $correctUser = False;
            foreach ($messageResult as $row) {
                if ($_SESSION['username'] == $row['receiver']) {
                    $correctUser = True;
                }
            }
            if ($correctUser) {
                $stmt = $file_db->prepare("DELETE FROM messages WHERE id=:id;");
                $stmt->execute(array(':id' => $id));
                $applied = $stmt->rowCount();
                $stmt = null;
                echo 'MESSAGE REMOVED';
                header("Location: index.php");
            } else {
                echo 'MESSAGE IS NOT FOR THIS USER';
            }
            $file_db = null;
        }
        catch(PDOException $e) {
            // Print PDOException message
            echo $e->getMessage();
        }
    }
} else {
    echo "Aucun message n'a été fourni en paramètre";
}
?>
<a href="index.php" class="button">Retour</a>
</body>
</html>