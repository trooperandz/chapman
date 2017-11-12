<?php
require_once("functions.inc");
include ('templates/login_check.php');

/* ------------------------------------------------------------------------------*
   Program: expressimpact_process.php

   Purpose: Enter express values into capacity_effect table for 
			roimpact.php report.

   History:
    Date		Description											by
	10/03/2014	Initial design and coding.							Matt Holland
	10/09/2014	Add hrs_ebay, hrs_sbay, total_bays, total_ebays		Matt Holland
	03/17/2015	Added surveyindex_id functionality					Matt Holland
 --------------------------------------------------------------------------------*/
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);

if ($mysqli->connect_errno) {
	error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
		return false;
	}

/* Set dealer ID */	
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;

// Set dealercode for report heading
$dealercode = $_SESSION['dealercode'];

// Set survey type variables
$surveyindex_id = $_SESSION['surveyindex_id'];
$survey_description = $_SESSION['survey_description'];

// Check form post and ensure no empty values
if (   (isset($_POST['monthly_ros'] )	&& $_POST['monthly_ros']!=""	)	
	&& (isset($_POST['days_week']	)	&& $_POST['days_week'] 	!="" 	)
	&& (isset($_POST['hrs_ebay']	)  	&& $_POST['hrs_ebay']  	!=""	)
	&& (isset($_POST['hrs_sbay']	)  	&& $_POST['hrs_sbay']  	!=""	)
	&& (isset($_POST['total_bays']	)	&& $_POST['total_bays'] !=""	)	
	&& (isset($_POST['total_ebays']	)	&& $_POST['total_ebays']!=""	)	) {
	
    // Set form post values
	$monthly_ros	= $mysqli->real_escape_string($_POST['monthly_ros']	);
	$days_week		= $mysqli->real_escape_string($_POST['days_week']	);
	$hrs_ebay		= $mysqli->real_escape_string($_POST['hrs_ebay']	);
	$hrs_sbay		= $mysqli->real_escape_string($_POST['hrs_sbay']	);
	$total_bays		= $mysqli->real_escape_string($_POST['total_bays']	);
	$total_ebays	= $mysqli->real_escape_string($_POST['total_ebays']	);
	
	// Check to see if there are ROs for $dealerID and $surveyindex_id.  If not, do not allow table update/insert
	$query = "SELECT * FROM repairorder WHERE dealerID = $dealerID and surveyindex_id = $surveyindex_id";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "There was a system error. Please see the administrator.";
	}
	$rows = $result->num_rows;
	if ($rows == 0) {
		$_SESSION['error'][] = "Dealer ".$dealercode." has no survey completed for a ".$survey_description.". Entry denied.";
		die(header("Location: roimpact.php"));	
	}
	
	// Check to see if record already exists for current dealerID and surveyindex_id
	$query = "SELECT * FROM express_effect WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
	$dealerresult = $mysqli->query($query);
	if (!$dealerresult) { 
		$_SESSION['error'][] = "SELECT express_effect query failed.  See administrator.";
		die(header("Location: roimpact.php"));
	}
	$dealerrows = $dealerresult->num_rows;
	// If dealer record already exists update record / else insert new record
	if ($dealerrows > 0) {
			$query = "UPDATE express_effect 
			SET monthly_ros = '$monthly_ros', days_week = '$days_week', hrs_ebay = '$hrs_ebay', hrs_sbay = '$hrs_sbay', total_bays = '$total_bays', total_ebays = '$total_ebays', create_date = NOW(), userID = '$userID'
			WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
			// Check for query error
			if (!$mysqli->query($query)) {
				$_SESSION['error'][] = "UPDATE express_effect query failed.  See administrator.";
				die(header("Location: roimpact.php"));
			} else {
				// Issue success message and return to roimpact.php if update query is successful
				$_SESSION['success'][] = "Dealer " .$dealercode. " assessment values were updated";
				die(header("Location: roimpact.php")); 
			}
	} else {
	// Insert new post values if no record exists
	$query = "INSERT INTO express_effect(dealerID, monthly_ros, days_week, hrs_ebay, hrs_sbay, total_bays, total_ebays, surveyindex_id, create_date, userID)	
			  VALUES ('$dealerID', '$monthly_ros', '$days_week', '$hrs_ebay', '$hrs_sbay', '$total_bays', '$total_ebays', '$surveyindex_id', NOW(), '$userID')";	  
	$result = $mysqli->query($query);
	// Check for failed query
	if(!$result) {
		$_SESSION['error'][] = "Insert into express_effect table query failed: ".$mysqli->error;
		die(header("Location: roimpact.php"));
	} else {
		// If successful query, issue insert confirmation and return to main page
		$_SESSION['success'][] = "Assessment values for dealer " .$dealercode. " successfully updated";
		die(header("Location: roimpact.php"));
		}	
	}
// If POST not successful, value was left blank	
} else {
	$_SESSION['error'][] = "You left a form value empty. Please re-enter assessment values.";
	die(header("Location: roimpact.php"));
}
?>