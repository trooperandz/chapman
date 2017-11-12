<?php
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

$dealerID 		= $_SESSION['dealerID']			;  	// Initiate dealerID magic variable
$dealercode 	= $_SESSION['dealercode']		;	// Initiate dealercode magic variable
$userID 		= $user->userID					;	// Initiate $userID for query INSERT/UPDATE 
$surveyindex_id = $_SESSION['surveyindex_id']	;	// Initiate surveyindex_id magic variable

if (isset($_POST['yearstartadmin'])) {
	// Set post variable (yearmodelID)
	$yearstartadmin = $_POST['yearstartadmin'];

	// Find associated modelyear in yearmodel table that is related to $yearstartadmin value and save as variable for INSERT query
	$query = "SELECT modelyear FROM yearmodel WHERE yearmodelID = $yearstartadmin";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "Line 143: survey_year_start SELECT query failed.  See administrator.";
		die(header("Location: setadminvalues.php"));
	}
	$rows = $result->num_rows;
	if ($rows == 0) {
		$_SESSION['error'][] = "Line 147: Year queried in yearmodel table does not exist.  See administrator";
		die(header("Location: setadminvalues.php"));
	}
	// If modelyear is found, save as variable for success reporting
	$value 		= $result->fetch_assoc();
	$modelyear  = $value['modelyear']   ;
	// echo '$modelyear: ' .$modelyear. '<br>';

	$query = "SELECT yearmodelID FROM survey_year_start WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "survey_year_start SELECT query failed.  See administrator.";
		die(header("Location: setadminvalues.php"));
	}
	$rows = $result->num_rows;
	// If row is returned, UPDATE survey_year_start table
	if ($rows > 0) {
		$query = "UPDATE survey_year_start 
				  SET dealerID = '$dealerID', yearmodelID = '$yearstartadmin', surveyindex_id = '$surveyindex_id', create_date = NOW(), userID = '$userID' 
				  WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
		if(!$mysqli->query($query)) {
			$_SESSION['error'][] = "survey_year_start UPDATE query failed.  See administrator.";
			die (header("Location: setadminvalues.php"));
		}
		$_SESSION['success'][] = "Survey start date for dealer " .$dealercode. " has been updated to " .$modelyear. ".";
		die (header("Location: setadminvalues.php"));
	} else {
	// If there is no row returned, insert new record into survey_year_start table
		$query = "INSERT INTO survey_year_start (dealerID, yearmodelID, surveyindex_id, create_date, userID)
				  VALUES ('$dealerID', '$yearstartadmin', '$surveyindex_id', NOW(), '$userID')";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "survey_year_start INSERT query failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		}
		$_SESSION['success'][] = "Survey start date for dealer " .$dealerID. " has been set to " .$modelyear. ".";
		die (header("Location: setadminvalues.php"));
	}
} else {
	$_SESSION['error'][] = "You did not select a year value.  Please try again.";
	die(header("Location: setadminvalues.php"));
}
?>	
	




