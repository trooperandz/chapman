<?php
/* -----------------------------------------------------------------------------*
   Program: comparison_selectsurveys_process.php

   Purpose: Process survey menu selections for comparison reports

	History:
    Date		Description											by
	11/04/2014	Initial design and coding							Matt Holland
	
 *-----------------------------------------------------------------------------*/
 
require_once("functions.inc");
include ('templates/login_check.php');

// DB connection
include('templates/db_cxn.php');

$dealerID   = $_SESSION['dealerID']		;  	// Initiate dealerID magic variable
$dealercode = $_SESSION['dealercode']	;	// Initiate dealercode magic variable
$userID		= $user->userID				;	// Initiate userID magic variable

if (isset($_POST['submitallsurveys'])) {
	$query = "SELECT surveyindex_id FROM survey_index";
	$mainresult = $mysqli->query($query);
	if (!$mainresult) {
		$_SESSION['error'][] = "survey_index SELECT query failed.  See administrator.";
		die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
	}
	$comparisonsurveyindexid_rows = $mainresult->num_rows;
	$comparisonsurveyindex_value = array(array());
	$index = 0;
	while ($value = $mainresult->fetch_assoc()) {
		$comparisonsurveyindex_value[$index]['surveyindex_id'] = $value['surveyindex_id'];
		$index += 1;	
	}
	$comparisonsurveyindex_id = "";
	for ($i=0; $i<$comparisonsurveyindexid_rows; $i++) {
		if ($i == $comparisonsurveyindexid_rows-1) {
			$comparisonsurveyindex_id .= $comparisonsurveyindex_value[$i]['surveyindex_id'];
		} else {
			$comparisonsurveyindex_id .= $comparisonsurveyindex_value[$i]['surveyindex_id']. ', ';
		}
	}
	// Save as magic variables
	$_SESSION['comparisonsurveyindex_id'] = $comparisonsurveyindex_id;
	$_SESSION['comparisonsurveyindexid_rows'] = $comparisonsurveyindexid_rows;
	//echo $surveyindexid_rows;
	die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
	
} else {	
	$_SESSION['error'][] = "The View All Surveys selection failed.  Please see administrator.";
	die (header("Location: ".$_SESSION['lastpagecomparisonreports']));
}
?>