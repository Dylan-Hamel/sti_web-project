<?php
include_once 'header.php';
?>
<script src='script/tablesort.min.js'></script>
<script src='script/tablesort.date.min.js'></script>
<body>
<h1>
	WELCOME	
</h1>
<ul>
  <li>Logged as <?php echo $_SESSION['username']; ?></li>
</ul>

<table id="messages" style="width:100%">
  <tr>
    <!-- <th>Read</th> -->
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
    $stmt = $file_db->prepare('SELECT * FROM messages WHERE receiver = :username;');
    $stmt->execute(array(':username' => $username));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null;
    $file_db = null;
    foreach ($result as $row) {
        echo "<tr>";
		/*
        echo "<td>";
        if ($row['read']) echo "<input type=\"checkbox\" checked disabled readonly />";
        else echo "<input type=\"checkbox\" disabled readonly />";
        echo "</td>";
		*/
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['sender'] . "</td>";
        echo "<td>" . $row['datetime'] . "</td>";
        echo "<td><a href=\"delete.php?id=".$row['id']."\" class=\"button\">Delete</a> <a href=\"read.php?id=".$row['id']."\" class=\"button\">Read</a> <a href=\"messages.php?receiver=".$row['sender']."&title=Re:".$row['title']."'\" class=\"button\">Answer</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}
?>
 <br>
<a href="messages.php" class="button">Send Message</a> <br>
<a href="manageuser.php" class="button">Manage User</a> <br>
<a href="password.php" class="button">Change Password</a> <br>
<a href="adduser.php" class="button">Add User</a> <br>
<a href="removeuser.php" class="button">Remove user</a> <br>
<a href="passworduser.php" class="button">Change User Password</a> <br>
<a href="logout.php" class="button">Logout</a>
</body>
<script>
  new Tablesort(document.getElementById('messages'));
</script>
</html>