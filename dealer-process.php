<?php 
	require_once('functions.inc');
	include ('templates/login_check.php');
	
	// prevent access if they haven't submitted the form. 
	if (!isset($_POST['submit'])) { 
		die(header("Location: authenticated.php")); 
	} 

	$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DB);
	if ($mysqli->connect_errno) { 
		error_log("Cannot connect to MySQL: " . $mysqli->connect_error); 
			return false; 
		} 
	// first, lookup the dealercode to see if it exists. 
	$safedealercode = $mysqli->real_escape_string($_POST['dealercode']); 
	$query = "SELECT dealerID, dealercode FROM dealer WHERE dealercode = '{$safedealercode}'"; 
	if (!$result = $mysqli->query($query)) {
		$_SESSION['error'][] = "Unknown Error"; 
		die(header("Location: authenticated.php")); 
		return false; 
	}
	if ($result->num_rows == 0) { 
		$_SESSION['error'][] = "Dealer code not found"; 
		die(header("Location: authenticated.php")); 
		return false; 
		} 
	else {
		$row = $result->fetch_assoc();
		$_SESSION['dealerID'] = $row['dealerID'];
		$_SESSION['dealercode'] = $row['dealercode'];
		die(header("Location: enterro.php")); 
		return true;
	}