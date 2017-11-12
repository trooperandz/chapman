<?php
/* -----------------------------------------------------------------------------*
   Program: global_selectallsurvey_process.php

   Purpose: Process survey menu selections for global reports to view all
			survey

	History:
    Date		Description											by
	10/29/2014	Initial design and coding							Matt Holland
	11/05/2014	Add $_SESSION['globalsurveyindexid_rows'] for
				report menu processing								Matt Holland
	
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
		die (header("Location: ".$_SESSION['lastpageglobalreports']));
	}
	$surveyindexid_rows = $mainresult->num_rows;
	$surveyindex_value = array(array());
	$index = 0;
	while ($value = $mainresult->fetch_assoc()) {
		$surveyindex_value[$index]['surveyindex_id'] = $value['surveyindex_id'];
		$index += 1;	
	}
	$surveyindex_id = "";
	for ($i=0; $i<$surveyindexid_rows; $i++) {
		if ($i == $surveyindexid_rows-1) {
			$surveyindex_id .= $surveyindex_value[$i]['surveyindex_id'];
		} else {
			$surveyindex_id .= $surveyindex_value[$i]['surveyindex_id']. ', ';
		}
	}
	// Save as magic variables
	$_SESSION['globalsurveyindex_id']  	  = $surveyindex_id	;
	$_SESSION['globalsurveyindexid_rows'] = $surveyindexid_rows;
	//echo $surveyindexid_rows;
	die (header("Location: ".$_SESSION['lastpageglobalreports']));
	
} else {	
	$_SESSION['error'][] = "The View All Surveys selection failed.  Please see administrator.";
	die (header("Location: ".$_SESSION['lastpageglobalreports']));
}
	

?>


