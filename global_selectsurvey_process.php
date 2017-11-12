<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* -----------------------------------------------------------------------------*
   Program: global_selectsurvey_process.php

   Purpose: Process survey menu selections for global reports

	History:
    Date		Description											by
	10/27/2014	Initial design and coding							Matt Holland
	11/05/2014	Added $_SESSION['globalsurveyindexid_rows'] to
				be used for report heading titles					Matt Holland
	
 *-----------------------------------------------------------------------------*/
// DB connection
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);

if ($mysqli->connect_errno) {
	error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
	$_SESSION['error'][] = "System could not connect to database.  See administrator.";
	}

$dealerID   = $_SESSION['dealerID']		;  	// Initiate dealerID magic variable
$dealercode = $_SESSION['dealercode']	;	// Initiate dealercode magic variable
$userID		= $user->userID				;	// Initiate userID magic variable

if (isset($_POST['survey_selection']) && $_POST['survey_selection'] !="") {
	$globalsurveyindex_id = $mysqli->real_escape_string($_POST['survey_selection']);
	$query = "SELECT surveyindex_id, survey_description FROM survey_index WHERE surveyindex_id = $globalsurveyindex_id";
	$mainresult = $mysqli->query($query);
	if (!$mainresult) {
		$_SESSION['error'][] = "surveys SELECT query failed.  See administrator.";
		die (header("Location: ".$_SESSION['lastpageglobalreports']));
	}
	$value = $mainresult->fetch_assoc();
	// Save description for error message
	$globalsurvey_description_error = $value['survey_description'];
	
	// Query surveys to see if user selection does exist in repairorder
	$query = "SELECT * FROM repairorder WHERE surveyindex_id = $globalsurveyindex_id";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "surveys SELECT query failed.  See administrator.";
		die (header("Location: ".$_SESSION['lastpageglobalreports']));
	}
	$rows = $result->num_rows;
	// If no rows, set globalsurveyindex_id and globalsurvey_description to default and also issue message that no $globalsurveyindex_id exists
	if ($rows == 0) {
		// Get default values from survey_index table
		$query = "SELECT surveyindex_id, survey_description FROM survey_index";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "surveys SELECT query failed.  See administrator.";
			die (header("Location: ".$_SESSION['lastpageglobalreports']));
		}
		$value = $result->fetch_assoc();
		$globalsurveyindex_id 	  = $value['surveyindex_id'];
		$globalsurvey_description = $value['survey_description'];
		
		// Set global survey values if survey type does not exist in repairorder table
		$_SESSION['globalsurveyindex_id'] = $globalsurveyindex_id;
		$_SESSION['globalsurvey_description'] = $globalsurvey_description;
		$_SESSION['globalsurveyindexid_rows'] = 1;  // Set rows = 1 to display report headings correctly
		//echo 'None of those survey types were in repairorder table.  Global was just set to default: $_SESSION[globalsurveyindex_id]: 	  ' .$_SESSION['globalsurveyindex_id'].    '<br>';
		//echo 'None of those survey types were in repairorder table.  Global was just set to default: $_SESSION[globalsurvey_description]: ' .$_SESSION['globalsurvey_description'].'<br>';
		$_SESSION['error'][] = "There are no " .$globalsurvey_description_error. "s in the system.";
		//die();
		die (header("Location: ".$_SESSION['lastpageglobalreports']));
	} else {	
		// If survey type is in repairorder table, set survey globals = POST value
		$mainresult->data_seek(0);  // reset internal pointer for fetch_assoc()
		$survey_value = $mainresult->fetch_assoc();
		$globalsurveyindex_id	  = $survey_value['surveyindex_id'];
		$globalsurvey_description = $survey_value['survey_description'];
		
		// Save survey_description and surveyindex_id as magic variables
		$_SESSION['globalsurveyindex_id']	  = $globalsurveyindex_id	 ;
		$_SESSION['globalsurvey_description'] = $globalsurvey_description;
		$_SESSION['globalsurveyindexid_rows'] = 1;  // Set rows = 1 to display report headings correctly
		//echo 'Global was just set to POST value: $_SESSION[globalsurveyindex_id]: 	  ' .$_SESSION['globalsurveyindex_id'].    '<br>';
		//echo 'Global was just set to POST value: $_SESSION[globalsurvey_description]: ' .$_SESSION['globalsurvey_description'].'<br>';
		// Return to main page
		//die();
		die (header("Location: ".$_SESSION['lastpageglobalreports']));
	} 	
} else {
		$_SESSION['error'][] = "You did not select a survey type.  Please try again.";
		die (header("Location: ".$_SESSION['lastpageglobalreports']));
}

?>


