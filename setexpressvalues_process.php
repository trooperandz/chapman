<?php
require_once("functions.inc");
include ('templates/login_check.php');
// DB connection
include ('templates/db_cxn.php');

$dealerID   = $_SESSION['dealerID']		;  	// Initiate dealerID magic variable
$dealercode = $_SESSION['dealercode']	;	// Initiate dealercode magic variable
$userID		= $user->userID				;	// Initiate userID magic variable

// Check for POST
if (   isset($_POST['days_week'])	&& !empty($_POST['days_week'] )
	&& isset($_POST['hrs_day']	)  	&& !empty($_POST['hrs_day']	  )
	&& isset($_POST['bay_qty']	)	&& !empty($_POST['bay_qty']	  )) {	
	
	// Set variables to POST values
	$days_week = $mysqli->real_escape_string($_POST['days_week'] );
	$hrs_day   = $mysqli->real_escape_string($_POST['hrs_day']   );
	$bay_qty   = $mysqli->real_escape_string($_POST['bay_qty']	 );
	
	// Check to see if some values for current dealer are already in bay_volume table
	$query  = "SELECT * FROM express_effect WHERE dealerID = $dealerID";
	$result = $mysqli->query($query);
	if (!$result) {
	$_SESSION['expresserror'][] = "express_effect query failed.  Please see administrator.";
	die(header("Location: setadminvalues.php")); }
	$rows = $result->num_rows;
		
	// If values already exist, overwrite with new values
	if ($rows == 1) {
		$query = "UPDATE express_effect SET days_week = '$days_week', hrs_day = '$hrs_day', create_date = NOW(), userID = '$userID' WHERE dealerID = $dealerID";
		if (!$mysqli->query($query)) {	
			$_SESSION['expresserror'][] = "Update express_effect table query failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		}
		$_SESSION['expresssuccess'][] = "Assessment values for Dealer " .$dealercode. " updated";
		die(header("Location: setadminvalues.php"));
	} else {
	// If there no row exists for current dealer, insert new record into table	
		$query = "INSERT INTO express_effect(dealerID, days_week, hrs_day, bay_qty, create_date, userID)
				  VALUES ('$dealerID', '$days_week', '$hrs_day', '$bay_qty', NOW(), '$userID')";
		if (!$mysqli->query($query)) {
			$_SESSION['expresserror'][] = "INSERT INTO express_effect instruction failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		} else {
			$_SESSION['expresssuccess'][] = "Assessment values for Dealer " .$dealercode. " updated";
			die(header("Location: setadminvalues.php"));
		}
	}		
} else {
	$_SESSION['expresserror'][] = "You left a form field empty";
	die(header("Location: setadminvalues.php"));
}
?>

