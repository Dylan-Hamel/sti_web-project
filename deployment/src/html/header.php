<?php
session_start();
if (!isset($_SESSION['username'])) {

    // Make sure you end script execution if not logged in
    exit(header("Location: login.php"));
}
?>

<!DOCTYPE HTML>  
<html>
<head>
<?php
if (isset($_SESSION['admin']) and $_SESSION['admin'] == 1) {
    echo "admin";
}
?>
<link rel="stylesheet" type="text/css" href="style/tablesort.css">
<style>
.error {color: #FF0000;}
a.button {
    -webkit-appearance: button;
    -moz-appearance: button;
    appearance: button;

    text-decoration: none;
    color: initial;
}
</style>
</head>