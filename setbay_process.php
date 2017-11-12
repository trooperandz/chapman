<?php
require_once("functions.inc");
include ('templates/login_check.php');
// DB connection
include ('templates/db_cxn.php');

$dealerID   = $_SESSION['dealerID']		;  	// Initiate dealerID magic variable
$dealercode = $_SESSION['dealercode']	;	// Initiate dealercode magic variable
$userID		= $user->userID				;	// Initiate userID magic variable

// Check for POST
if (   isset($_POST['maxvol_ebay']) && !empty($_POST['maxvol_ebay'])
	&& isset($_POST['maxvol_sbay'])  && !empty($_POST['maxvol_sbay'])) {	
	
	// Set variables to POST values
	$maxvol_ebay = $mysqli->real_escape_string($_POST['maxvol_ebay']);
	$maxvol_sbay = $mysqli->real_escape_string($_POST['maxvol_sbay']);	
	
	// Check to see if some values for current dealer are already in bay_volume table
	$query  = "SELECT maxvol_ebay, maxvol_sbay FROM bay_volume WHERE dealerID = $dealerID";
	$result = $mysqli->query($query);
	if (!$result) {
	$_SESSION['bayerror'][] = "bay_volume query failed.  Please see administrator.";
	die(header("Location: setadminvalues.php")); }
	$bayrows = $result->num_rows;
		
	// If values already exist, overwrite with new values
	if ($bayrows == 1) {
		$query = "UPDATE bay_volume SET maxvol_ebay = '$maxvol_ebay', maxvol_sbay = '$maxvol_sbay', create_date = NOW(), userID = '$userID' WHERE dealerID = $dealerID";
		if (!$mysqli->query($query)) {	
			$_SESSION['bayerror'][] = "Update bay_volume table query failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		}
		$_SESSION['baysuccess'][] = "Bay volumes for Dealer " .$dealercode. " updated";
		die(header("Location: setadminvalues.php"));
	} else {
	// If there no row exists for current dealer, insert new record into table	
		$query = "INSERT INTO bay_volume(dealerID, maxvol_ebay, maxvol_sbay, create_date, userID)
				  VALUES ('$dealerID', '$maxvol_ebay', '$maxvol_sbay', NOW(), '$userID')";
		if (!$mysqli->query($query)) {
			$_SESSION['bayerror'][] = "INSERT INTO bay_volume instruction failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		} else {
			$_SESSION['baysuccess'][] = "Bay volumes for Dealer " .$dealercode. " updated";
			die(header("Location: setadminvalues.php"));
		}
	}		
} else {
	$_SESSION['bayerror'][] = "You left a form field empty";
	die(header("Location: setadminvalues.php"));
}
?>

