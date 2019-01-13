<?php
include_once 'header.php';
?>
<body>
    <h1>Change password</h1>
    <ul>
        <li>Logged as <?php echo $_SESSION['username']; ?></li>
    </ul>
    <?php

        // Retrieve & check username param
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

        } else {
            // Send back with error message
            exit(header("Location: index.php?msg=not-allowed"));
        }

        // Make sure we have a valid POST request
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["password1"]) AND !empty($_POST["password1"]) AND
                isset($_POST["password2"]) and !empty($_POST["password2"])) {
                $password1 = htmlspecialchars($_POST["password1"]);
                $password2 = htmlspecialchars($_POST["password2"]);
            }

            // Password should match its confirmation
            if ($password1 === $password2) {
                try {
                    // Connect DB
                    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');

                    // Set errormode to exceptions
                    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $hash = password_hash($password1, PASSWORD_DEFAULT);

                    $stmt = $file_db->prepare('UPDATE users SET password = :password WHERE username = :username');
                    $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
                    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmt->execute();
                    $applied = $stmt->rowCount();
                }
                catch(PDOException $e) {
                    // Print PDOException message
                    echo $e->getMessage();
                } finally {
                    // Proper DB closing
                    $stmt->closeCursor();
                    $file_db = null;
                }

                if ($applied == 1) {
                    $msg = "Sucessfully updated";
                }

            } else {
                $passwordMismatch = "Les mot de passe de correspondent pas";
            }
        }
    ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: </br><input <?php echo ((!isset($_SESSION['username']) && $_SESSION['admin'] !== 1) ? 'disabled="disabled"' : '' ); ?>  type="text" name="username" value="<?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?>">
        <br><br>
        Password: </br><input type="password" name="password1">
        <br><br>
        Re-Type: </br><input type="password" name="password2">
        <br><br>
        <?php

        if (!empty($passwordMismatch)) {
            echo '<p><span style="color:red">' . $passwordMismatch . '</span></p>';
        }

        if (!empty($msg)) {
            echo '<p><span style="color:green">' . $msg . '</span></p>';
        }

        ?>
        <button type="submit">Submit</button>
    </form>

    <a href="index.php" class="button">Retour</a>
</body>
</html>