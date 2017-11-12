<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* -----------------------------------------------------------------------------*
   Program: comparison_selectsurvey_process.php

   Purpose: Process survey menu selections for global reports

	History:
    Date		Description											by
	10/27/2014	Initial design and coding							Matt Holland
	
 *-----------------------------------------------------------------------------*/
// DB connection
include('templates/db_cxn.php');

$dealerID   = $_SESSION['dealerID']		;  	// Initiate dealerID magic variable
$dealercode = $_SESSION['dealercode']	;	// Initiate dealercode magic variable
$userID		= $user->userID				;	// Initiate userID magic variable

if (isset($_POST['survey_selection']) && $_POST['survey_selection'] !="") {
	$comparisonsurveyindex_id = $mysqli->real_escape_string($_POST['survey_selection']);
	$query = "SELECT surveyindex_id, survey_description FROM survey_index WHERE surveyindex_id = $comparisonsurveyindex_id";
	$mainresult = $mysqli->query($query);
	if (!$mainresult) {
		$_SESSION['error'][] = "surveys SELECT query failed.  See administrator.";
		die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
	}
	$value = $mainresult->fetch_assoc();
	// Save description for error message
	$comparisonsurvey_description_error = $value['survey_description'];
	
	// Query surveys to see if user selection does exist in repairorder
	$query = "SELECT * FROM repairorder WHERE surveyindex_id = $comparisonsurveyindex_id";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "surveys SELECT query failed.  See administrator.";
		die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
	}
	$rows = $result->num_rows;
	// If no rows, set comparisonsurveyindex_id and comparisonsurvey_description to default and also issue message that no $comparisonsurveyindex_id exists
	if ($rows == 0) {
		// Get default values from survey_index table
		$query = "SELECT surveyindex_id, survey_description FROM survey_index";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "surveys SELECT query failed.  See administrator.";
			die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
		}
		$value = $result->fetch_assoc();
		$comparisonsurveyindex_id 	  = $value['surveyindex_id'];
		$comparisonsurvey_description = $value['survey_description'];
		// Set global survey values if survey type does not exist in repairorder table
		$_SESSION['comparisonsurveyindex_id'] = $comparisonsurveyindex_id;
		$_SESSION['comparisonsurvey_description'] = $comparisonsurvey_description;
		$_SESSION['comparisonsurveyindexid_rows'] = 1;
		//echo 'None of those survey types were in repairorder table.  Global was just set to default: $_SESSION[comparisonsurveyindex_id]: 	  ' .$_SESSION['comparisonsurveyindex_id'].    '<br>';
		//echo 'None of those survey types were in repairorder table.  Global was just set to default: $_SESSION[comparisonsurvey_description]: ' .$_SESSION['comparisonsurvey_description'].'<br>';
		$_SESSION['error'][] = "There are no " .$comparisonsurvey_description_error. "s in the system.";
		//die();
		die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
	} else {	
		// If survey type is in repairorder table, set survey globals = POST value
		$mainresult->data_seek(0);  // reset internal pointer for fetch_assoc()
		$survey_value = $mainresult->fetch_assoc();
		$comparisonsurveyindex_id	  = $survey_value['surveyindex_id'];
		$comparisonsurvey_description = $survey_value['survey_description'];
		// Save survey_description and surveyindex_id as magic variables
		$_SESSION['comparisonsurveyindex_id']	  = $comparisonsurveyindex_id	 ;
		$_SESSION['comparisonsurvey_description'] = $comparisonsurvey_description;
		$_SESSION['comparisonsurveyindexid_rows'] = 1;
		//echo 'Global was just set to POST value: $_SESSION[comparisonsurveyindex_id]: 	  ' .$_SESSION['comparisonsurveyindex_id'].    '<br>';
		//echo 'Global was just set to POST value: $_SESSION[comparisonsurvey_description]: ' .$_SESSION['comparisonsurvey_description'].'<br>';
		// Return to main page
		//die();
		die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
	} 	
} else {
		$_SESSION['error'][] = "You did not select a survey type.  Please try again.";
		die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
}

?>


