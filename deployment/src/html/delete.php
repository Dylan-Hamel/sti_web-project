<?php
include_once 'header.php';
?>
<body>
<ul>
	<li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<?php

// Make sure URL param is set & not empty
if (isset($_GET['id']) && !is_null($_GET['id'])) {

    // Once we got a value, make sure it is a valid ID
    if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false) {

        // Exit here
        exit(header("Location: index.php?msg=bad-parameter"));
    } else {

        // Assuming the param is clean
        $id = $_GET['id'];
    }

    try {
        // Connect DB
        $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');

        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepared statement with type enforcment
        $stmt = $file_db->prepare("DELETE FROM messages WHERE id=:id AND receiver =:user;");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user', $_SESSION['username'], FILTER_SANITIZE_STRING);
        $stmt->execute();

        // Validation
        $applied = $stmt->rowCount();
        $done = ($applied == 1) ? true : false;

        // Proper connection close
        $stmt->closeCursor();
        $file_db = null;
    }
    catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
    }
}

header("Location: index.php?success=" . intval($done) . "&msg=Message sucessfully deleted");

?>