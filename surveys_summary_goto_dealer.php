<?php
/* -----------------------------------------------------------------------------*
   Program: surveys_summary_goto_dealer.php

   Purpose: Enable user to navigate from Surveys Summary page to enterrofoundation
			by clicking on the dealercode in the summary table

	History:
    Date				Description									by
	01/29/2015			Initial design and coding					Matt Holland
 *------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include('templates/db_cxn.php');

if (isset($_POST['summary_dealerID']) && $_POST['summary_dealerID'] !=''
  &&isset($_POST['summary_surveyindex_id']) && $_POST['summary_surveyindex_id'] !='') {
   
   $dlr_id      = $mysqli->real_escape_string($_POST['summary_dealerID'])	   ; // hidden input field value
   $survey_id   = $mysqli->real_escape_string($_POST['summary_surveyindex_id']); // hidden input field value
   
   // Ensure that dealercode and survey_description global variables are correctly matched with dealerID and surveyindex_id
   $query = "SELECT dealercode from dealer WHERE dealerID = $dlr_id";
   $result = $mysqli->query($query);
   if (!$result) {
		$_SESSION['error'][] = "Dealer query failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }
   $lookup = $result->fetch_assoc();
   $dealercode = $lookup['dealercode'];
   
   $query = "SELECT survey_description FROM survey_index WHERE surveyindex_id = $survey_id";
   $result = $mysqli->query($query);
   if (!$result) {
		$_SESSION['error'][] = "Surveys query failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }
   $lookup = $result->fetch_assoc();
   $survey_description = $lookup['survey_description'];
   
   // Reset global variables if queries are successful
   $_SESSION['dealerID'] 			= $dlr_id			 ;
   $_SESSION['surveyindex_id']  	= $survey_id		 ;
   $_SESSION['dealercode']			= $dealercode		 ;
   $_SESSION['survey_description']  = $survey_description;
   
   // Die back to enterrofoundation.php after successful querying
   die(header('Location: enterrofoundation.php'));
   
} else {
	// If there was a form processing error, return back to system_summary.php
	$_SESSION['error'][] = "Request failed.  Please see administrator.";
	die(header('Location: '.$_SESSION['lastpagedealerreports']));
}
?>