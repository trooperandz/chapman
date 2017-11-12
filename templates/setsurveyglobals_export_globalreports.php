<?php
/* ----------------------------------------------------------------------------------------*
   Program: setsurveyglobals_globalreports.php

   Purpose: Used as standard include for global reports => sets survey type magic variables

	History:
    Date		Description													by
	10/29/2014	Initial design & coding										Matt Holland
	11/05/2014	Added $_SESSION['globalsurveyindexid_rows'] to be used
				for report headings to display correct survey type			Matt Holland
	11/05/2014	Changed WHERE surveyindex_id = $globalsurveyindex_id
				to WHERE surveyindex_id IN($globalsurveyindex_id)			Matt Holland
 *------------------------------------------------------------------------------------------*/
// Check to see if survey globals are set for global reports.  If not, initialize default values.
if (isset($_SESSION['globalsurveyindex_id']) && isset($_SESSION['globalsurvey_description'])) {
	// Initialize survey globals
	$surveyindex_id 			= $_SESSION['globalsurveyindex_id'];
	$globalsurvey_description	= $_SESSION['globalsurvey_description'];
	//echo 'globals already set: $_SESSION[globalsurveyindex_id]: ' .$_SESSION['globalsurveyindex_id'];
	//echo 'globals already set: $_SESSION[globalsurvey_description]: ' .$_SESSION['globalsurvey_description'];
	$globalsurvey_test = TRUE;
} else {
	$globalsurvey_test = FALSE;
}
// Generate default survey globals from first row of survey_index if they are not set (there will always be level 1 surveys)
if ($globalsurvey_test == FALSE) {
	// Query survey_index table to get values for default globals
	$query = "SELECT surveyindex_id, survey_description FROM survey_index";
	$surveyresult = $mysqli->query($query);
	if (!$surveyresult) {
		$_SESSION['error'][] = "survey_index SELECT query failed.  See administrator.";
	}
	$vtest = $surveyresult->fetch_assoc();
	$surveyindex_id 	  	  = $vtest['surveyindex_id'];
	$globalsurvey_description = $vtest['survey_description'];
	$_SESSION['globalsurveyindex_id'] 	  = $surveyindex_id;
	$_SESSION['globalsurvey_description'] = $globalsurvey_description;
	$_SESSION['globalsurveyindexid_rows'] = 1; // Set rows to 1 to display correct survey type on report headings
	//echo 'globals were just set: $_SESSION[globalsurveyindex_id]: ' .$_SESSION['globalsurveyindex_id'];
	//echo 'globals were just set: $_SESSION[globalsurvey_description]: ' .$_SESSION['globalsurvey_description'];	
}
// Get total count of dealers in repairorder for survey type
$query = "SELECT DISTINCT dealerID FROM repairorder WHERE surveyindex_id IN($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Failed to retrieve total dealer count.  repairorder SELECT query failed.  See administrator.";
}
$totaldealers_persurvey = $result->num_rows;
// echo '$totaldealers_persurvey: ' .$totaldealers_persurvey. '<br>';

/* TAKE THIS OUT FOR EXPORTS TO PREVENT PAGE GOING BACK TO EXPORT */
/*  Set last page in globals  */
//$_SESSION['lastpageglobalreports'] = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);