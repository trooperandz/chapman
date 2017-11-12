<?php
require_once("functions.inc");
include ('templates/login_check.php');

/* ----------------------------------------------------------------------*
   Program: expressimpactadmin_process.php

   Purpose: Entry of express and bay volume values in admin section

	History:
    Date		Description									by
	10/03/2014	Initial design and coding					Matt Holland
	10/10/2014	Revise express impact form with one 		Matt Holland
				submit button								
	
 *-----------------------------------------------------------------------*/
// DB connection
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);

if ($mysqli->connect_errno) {
	error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
		return false;
	}

$dealerID   = $_SESSION['dealerID']		;  	// Initiate dealerID magic variable
$dealercode = $_SESSION['dealercode']	;	// Initiate dealercode magic variable
$userID		= $user->userID				;	// Initiate userID magic variable

// Check for POST
/*
if (   isset($_POST['days_week']	)	&& !empty($_POST['days_week'] 	)
	&& isset($_POST['hrs_ebay']		)  	&& !empty($_POST['hrs_ebay']	)
	&& isset($_POST['hrs_sbay']		)  	&& !empty($_POST['hrs_sbay']	)
	&& isset($_POST['maxvol_ebay']	)	&& !empty($_POST['maxvol_ebay']	)
	&& isset($_POST['maxvol_sbay']	)	&& !empty($_POST['maxvol_sbay']	)
	&& isset($_POST['monthly_ros']	)	&& !empty($_POST['monthly_ros']	)
	&& isset($_POST['total_bays']	)	&& !empty($_POST['total_bays']	)
	&& isset($_POST['total_ebays']	)	&& !empty($_POST['total_ebays']	)) {	
*/	
if (   (isset($_POST['days_week']		)	&& $_POST['days_week'] 		!="" 	)
	&& (isset($_POST['hrs_ebay']		)  	&& $_POST['hrs_ebay']  		!=""	)
	&& (isset($_POST['hrs_sbay']		)  	&& $_POST['hrs_sbay']  		!=""	)
	&& (isset($_POST['maxvol_ebay']		)	&& $_POST['maxvol_ebay']	!=""	)	
	&& (isset($_POST['maxvol_sbay']		)	&& $_POST['maxvol_sbay']	!=""	)	
	&& (isset($_POST['bay_test_ratio']	)	&& $_POST['bay_test_ratio']	!=""	)
	&& (isset($_POST['monthly_ros']		)	&& $_POST['monthly_ros']	!=""	)	
	&& (isset($_POST['total_bays']		)	&& $_POST['total_bays']		!=""	)
	&& (isset($_POST['total_ebays']		)	&& $_POST['total_ebays']	!=""	)	) {
	
	// Set variables to POST values
	$days_week 		= $mysqli->real_escape_string($_POST['days_week'] 		);
	$hrs_ebay  		= $mysqli->real_escape_string($_POST['hrs_ebay']  		);
	$hrs_sbay  		= $mysqli->real_escape_string($_POST['hrs_sbay']  		);
	$maxvol_ebay   	= $mysqli->real_escape_string($_POST['maxvol_ebay']		);
	$maxvol_sbay   	= $mysqli->real_escape_string($_POST['maxvol_sbay']		);
	$bay_test_ratio = $mysqli->real_escape_string($_POST['bay_test_ratio']	);	
	$monthly_ros   	= $mysqli->real_escape_string($_POST['monthly_ros']		);
	$total_bays   	= $mysqli->real_escape_string($_POST['total_bays']		);
	$total_ebays   	= $mysqli->real_escape_string($_POST['total_ebays']		);

/*---------------------------------------------------------------express_effect operations------------------------------------------------------------*/	
	// Check to see if some values for current dealer are already in express_effect table
	$query  = "SELECT * FROM express_effect WHERE dealerID = $dealerID";
	$result = $mysqli->query($query);
	if (!$result) {
	$_SESSION['error'][] = "express_effect query failed.  Please see administrator.";
	die(header("Location: setadminvalues.php")); }
	$rows = $result->num_rows;
		
	// If values already exist, overwrite with new values
	if ($rows > 0) {
		$query = "UPDATE express_effect SET monthly_ros = '$monthly_ros', days_week = '$days_week', hrs_ebay = '$hrs_ebay', hrs_sbay = '$hrs_sbay', 
				   total_bays = '$total_bays', total_ebays = '$total_ebays', create_date = NOW(), userID = '$userID' WHERE dealerID = $dealerID";
		if (!$mysqli->query($query)) {	
			$_SESSION['error'][] = "Update express_effect table query failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		}
	} else {
	// If there no row exists for current dealer, insert new record into table	
		$query = "INSERT INTO express_effect(dealerID, monthly_ros, days_week, hrs_ebay, hrs_sbay, total_bays, total_ebays, create_date, userID)
				  VALUES ('$dealerID', '$monthly_ros', '$days_week', '$hrs_ebay', '$hrs_sbay', '$total_bays', '$total_ebays', NOW(), '$userID')";
		if (!$mysqli->query($query)) {
			$_SESSION['error'][] = "INSERT INTO express_effect instruction failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		}
	}
/*---------------------------------------------------------------bay_volume operations------------------------------------------------------------*/	
	// Query bay_volume table to update or set values
	$query = "SELECT * FROM bay_volume WHERE dealerID = $dealerID";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "bay_volume query failed.  See administrator.";
		die(header("Location: setadminvalues.php"));
	}
	$rows = $result->num_rows;
	// If $dealerID record exists, update existing record
	if ($rows > 0) {
		$query = "UPDATE bay_volume SET maxvol_ebay = '$maxvol_ebay', maxvol_sbay = '$maxvol_sbay', create_date = NOW(), userID = '$userID' WHERE dealerID = $dealerID";
		if (!$mysqli->query($query)) {
			$_SESSION['error'][] = "maxvol_ebay UPDATE query failed.  See administrator.";
			die(header("Location: setadminvalues.php'"));
		} 
	} else {
		// $dealerID record does not exist, so insert records into bay_volume table
		$query = "INSERT INTO bay_volume(dealerID, maxvol_ebay, maxvol_sbay, create_date, userID)
				  VALUES ('$dealerID', '$maxvol_ebay', '$maxvol_sbay', NOW(), '$userID')";
		if (!$mysqli->query($query)) {
			$_SESSION['error'][] = "bay_volume INSERT query failed.  See administrator.";
			die(header("Location: setadminvalues.php'"));
		}
	}
/*---------------------------------------------------------------bay_ratio operations------------------------------------------------------------*/	
	// Query bay_ratio table to update or set value
	$query = "SELECT bay_test_ratio FROM bay_ratio";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "bay_ratio query failed.  See administrator.";
	}
	$rows = $result->num_rows;
	// If bay_ratio record already exists, update record
	if ($rows > 0 )	{
		$query = "UPDATE bay_ratio SET bay_test_ratio = '$bay_test_ratio', create_date = NOW(), userID = '$userID'";
		if (!$mysqli->query($query)) {
			$_SESSION['error'][] = "bay_ratio UPDATE query failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		} else {
			$_SESSION['success'][] = "Express metrics updated successfully.";
			die(header("Location: setadminvalues.php"));
		}
	} else {
	// bay_ratio record does not exist, so insert new record
		$query = "INSERT INTO bay_ratio (bay_test_ratio, create_date, userID) VALUES ('$bay_test_ratio', NOW(), '$userID')";
		if (!$mysqli->query($query)) {
			$_SESSION['error'][] = "bay_ratio INSERT failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		} else {
			$_SESSION['success'][] = "Express metrics updated successfully.";
			die(header("Location: setadminvalues.php"));
		}
	}	
} else {
	$_SESSION['error'][] = "You left a form field empty";
	die(header("Location: setadminvalues.php"));
}
?>

