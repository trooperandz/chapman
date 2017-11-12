<?php
// Require access to page
require_once("functions.inc");
include ('templates/login_check.php');

// Check to see if survey globals are set for comparison reports.  If not, initialize default values.
if (isset($_SESSION['comparisonsurveyindex_id']) && isset($_SESSION['comparisonsurvey_description'])) {
	// Initialize survey globals
	$comparisonsurveyindex_id 		= $_SESSION['comparisonsurveyindex_id'];
	$comparisonsurvey_description	= $_SESSION['comparisonsurvey_description'];
	//echo 'globals already set: $_SESSION[comparisonsurveyindex_id]: ' .$_SESSION['comparisonsurveyindex_id'];
	//echo 'globals already set: $_SESSION[comparisonsurvey_description]: ' .$_SESSION['comparisonsurvey_description'];
	$comparisonsurvey_test = TRUE;
} else {
	$comparisonsurvey_test = FALSE;
}
// Generate default survey globals from first row of survey_index if they are not set (there will always be level 1 surveys)
if ($comparisonsurvey_test == FALSE) {
	// Query survey_index table to get values for default globals
	$query = "SELECT surveyindex_id, survey_description FROM survey_index";
	$surveyresult = $mysqli->query($query);
	if (!$surveyresult) {
		$_SESSION['error'][] = "survey_index SELECT query failed.  See administrator.";
	}
	$vtest = $surveyresult->fetch_assoc();
	$comparisonsurveyindex_id 	  = $vtest['surveyindex_id'];
	$comparisonsurvey_description = $vtest['survey_description'];
	$_SESSION['comparisonsurveyindex_id'] 	  = $comparisonsurveyindex_id;
	$_SESSION['comparisonsurvey_description'] = $comparisonsurvey_description;
	//echo 'globals were just set: $_SESSION[comparisonsurveyindex_id]: ' .$_SESSION['comparisonsurveyindex_id'];
	//echo 'globals were just set: $_SESSION[comparisonsurvey_description]: ' .$_SESSION['comparisonsurvey_description'];	
}
// Get total count of dealers in repairorder for survey type
$query = "SELECT DISTINCT dealerID FROM repairorder WHERE surveyindex_id IN ($comparisonsurveyindex_id)";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Failed to retrieve total dealer count.  repairorder SELECT query failed.  See administrator.";
}
$totaldealers_persurvey = $result->num_rows;
// echo '$totaldealers_persurvey: ' .$totaldealers_persurvey. '<br>';

/*  Set last page in globals  */
$_SESSION['lastpagecomparisonreports'] = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>