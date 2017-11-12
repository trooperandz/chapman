<?php
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');
	
/* Set user email */	
$email = $_SESSION['email'];

$query = "SELECT adminID FROM adminuser WHERE email = '$email' ";
$result = $mysqli->query($query);			
if (!$result) {
	$_SESSION['error'][] = "Admin authentication failed.  See administrator.";
	die (header("Location: enterrofoundation.php")); }
$rows = $result->num_rows;

if ($rows > 0) { 
	die(header("Location: manageusers.php"));
} else {
	die(header("Location: adminerror.php"));
}
?>	