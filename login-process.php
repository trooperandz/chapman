<?php 
	require_once('functions.inc'); 
	
	// prevent access if they haven't submitted the form. 
	if (!isset($_POST['submit'])) { 
		die(header("Location: loginform.php")); 
	} 
	
	$_SESSION['formAttempt'] = true;
	
	if (isset($_SESSION['error'])) { 
		unset($_SESSION['error']);
	} 
	
	$_SESSION['error'] = array();
	$required = array("email","password"); 
	
	// Check required fields 
	foreach ($required as $requiredField) {
	if (!isset($_POST[$requiredField]) || $_POST[$requiredField] == "") { 
		$_SESSION['error'][] = $requiredField . " is required."; 
		} 
	}
	
	if (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) { 
		$_SESSION['error'][] = "Invalid e-mail address"; 
	} 
		
	if (count($_SESSION['error']) > 0) { 
		die(header("Location: loginform.php")); 
	} else {
		$user = new User;
		if ($user->authenticate($_POST['email'],$_POST['password'])) {
			unset($_SESSION['formAttempt']);
			/*  Set session global for dealercode, set to UNDEFINED if not entered  */
			if (isset($_POST['dealercode'])) {
				$_SESSION['dealercode'] = $_POST['dealercode'];
				}
			else {
				$_SESSION['dealercode'] = 'UNDEFINED';
			}
			die(header("Location: enterrofoundation.php")); 
		} else { 
			$_SESSION['error'][] = "There was a problem with your username or password."; 
			die(header("Location: loginform.php")); 
		} 
	} 
?>
