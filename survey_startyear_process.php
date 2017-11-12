<?php 
/* ------------------------------------------------------------------------*
	Program: survey_startyear_process.php
	
	Purpose: Insert survey start year into surveys table
			 
	History:
    Date		Description									by
	03/06/2015	Initial design and coding					M.T.Holland

 *-------------------------------------------------------------------------*/
 
// Required system includes
require_once('functions.inc'); 
include ('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

// Prevent access if they haven't submitted the form.  Else set variable to POST value
if (!isset($_POST['survey_start_yearmodelID'])) { 
	$_SESSION['error'][] = 'POST not set.';
	die (header('Location: enterrofoundation.php'));
} else {
	// Escape string not necessary due to select input
	$survey_start_yearmodelID = $_POST['survey_start_yearmodelID'];
	// Check to see if POST was NULL.  If so, die back to main program.  Otherwise proceed with program
	if ($survey_start_yearmodelID == '') {
		$_SESSION['error'][] = 'You left the Select Year form blank.';
		die(header('Location: enterrofoundation.php'));
	}
}
// echo '$survey_start_yearmodelID: '.$survey_start_yearmodelID.'<br>';

// Check surveys table to see if row in table for $dealerID has a 0 or a yearmodelID in it.  If 0, update row. Else issue error message and return to main
$query = "SELECT survey_start_yearmodelID FROM surveys where dealerID = $dealerID and surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Query failed.  See administrator.';
	die(header('Location: enterrofoundation.php'));
}
$lookup = $result->fetch_assoc();
$survey_start_yearmodelID_check = $lookup['survey_start_yearmodelID'];
// Execute update statement if fetched result is 0
if ($survey_start_yearmodelID_check == 0) {
	$query = "UPDATE surveys SET survey_start_yearmodelID = $survey_start_yearmodelID WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
	$result = $mysqli->query($query);
	if (!$result) {
	$_SESSION['error'][] = 'Query failed.  See administrator.';
	die(header('Location: enterrofoundation.php'));
	}
	// Update successful.  Issue success statement showing set start year and return to main
	$query = "SELECT modelyear from yearmodel WHERE yearmodelID = $survey_start_yearmodelID";
	$result = $mysqli->query($query);
	if (!$result) {
	$_SESSION['error'][] = 'Query failed.  See administrator.';
	die(header('Location: enterrofoundation.php'));
	}
	$lookup = $result->fetch_assoc();
	$modelyear_start = $lookup['modelyear'];
	$_SESSION['success'][] = '*Success: The survey start year has been set to '.$modelyear_start.'.';
	die(header('Location: enterrofoundation.php'));
} else {
	// If the survey start year is not equal to 0 upon entering this program, a fatal error has occurred
	// (because the selection menu should not have been available to the user).  Return to main.
	$_SESSION['error'][] = 'A fatal system error has occurred.<br>Please see the administrator before proceeding.';
	die(header('Location: enterrofoundation.php'));
}

// Insert the survey_start_yearmodelID into the surveys table as UPDATE statement (row will always exist in surveys table because enterrofoundation.php was accessed)	
 ?>