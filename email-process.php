<?php 
	
require_once('functions.inc'); 

// prevent access if they haven't submitted the form. 

if (!isset($_POST['submit'])) { 
	die(header("Location: index.php")); 
} 

$_SESSION['formAttempt'] = true; 
if (isset($_SESSION['error'])) { 
	unset($_SESSION['error']); 
} 

$_SESSION['error'] = array();

// Check required fields 
if (!isset($_POST['email']) || $_POST['email'] == "") { 
	$_SESSION['error'][] = "* Email address is required!"; 
} 

if (count($_SESSION['error']) > 0) { 
	die( header("Location: emailpass.php")); 
} 
else { 
	$user = new User; 
	if ($user->emailPass($_POST['email'])) { 
		unset($_SESSION['formAttempt']); 
		die(header("Location: email-success.php")); 
	} 
	else { 
		$_SESSION['error'][] = "That email address does not exist";
		die(header("Location: emailpass.php")); 
	}
} 

?>