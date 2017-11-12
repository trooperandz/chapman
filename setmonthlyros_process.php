<?php
require_once("functions.inc");
include ('templates/login_check.php');
// DB connection
include ('templates/db_cxn.php');

$dealerID   = $_SESSION['dealerID']		;  	// Initiate dealerID magic variable
$dealercode = $_SESSION['dealercode']	;	// Initiate dealercode magic variable
$userID		= $user->userID				;	// Initiate userID magic variable

// Check for POST
if (isset($_POST['monthly_ros']) && !empty($_POST['monthly_ros'])) {	
	
	// Set variable to POST values
	$monthly_ros = $mysqli->real_escape_string($_POST['monthly_ros']);	
	
	// Check to see if value for current dealer is already in monthlyro_total table
	$query  = "SELECT * FROM monthlyro_total WHERE dealerID = $dealerID";
	$result = $mysqli->query($query);
	if (!$result) {
	$_SESSION['monthlyerror'][] = "monthlyro_total query failed.  Please see administrator.";
	die(header("Location: setadminvalues.php")); }
	$rows = $result->num_rows;
		
	// If values already exist, overwrite with new values
	if ($rows == 1) {
		$query = "UPDATE monthlyro_total SET monthly_ros = '$monthly_ros', create_date = NOW(), userID = '$userID' WHERE dealerID = $dealerID";
		if (!$mysqli->query($query)) {	
			$_SESSION['monthlyerror'][] = "Update monthlyros_total table query failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		}
		$_SESSION['monthlysuccess'][] = "Monthly RO average for Dealer " .$dealercode. " updated";
		die(header("Location: setadminvalues.php"));
	} else {
	// If no row exists for current dealer, insert new record into table	
		$query = "INSERT INTO monthlyro_total(dealerID, monthly_ros, create_date, userID)
				  VALUES ('$dealerID', '$monthly_ros', NOW(), '$userID')";
		if (!$mysqli->query($query)) {
			$_SESSION['monthlyerror'][] = "INSERT INTO monthlyro_total instruction failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		} else {
			$_SESSION['monthlysuccess'][] = "Monthly RO average for Dealer " .$dealercode. " updated";
			die(header("Location: setadminvalues.php"));
		}
	}		
} else {
	$_SESSION['monthlyerror'][] = "You left the form field empty";
	die(header("Location: setadminvalues.php"));
}
?>

