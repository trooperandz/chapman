<?php
/* -----------------------------------------------------------------------------*
   Program: lock_survey_process_byuser.php

   Purpose: Enable user to lock current survey.  
			Suggested by Marc Wollard that capability be available to non-admin
			users on enterrofoundation.php

	History:
    Date				Description									by
	01/28/2015			Initial design and coding					Matt Holland
 *------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include('templates/db_cxn.php');

if (isset($_POST['lock_survey_dealerID']) && $_POST['lock_survey_dealerID'] !=''
  &&isset($_POST['lock_survey_surveyindex_id']) && $_POST['lock_survey_surveyindex_id'] !=''
  &&isset($_POST['survey_lock']) && $_POST['survey_lock'] !='') {
   
   $survey_description = $_SESSION['survey_description']; 							 // Initiate survey_description variable for echo in success message.  Already set in system.
   $dlr_id      = $mysqli->real_escape_string($_POST['lock_survey_dealerID']);  	 // hidden input field value
   $survey_id	= $mysqli->real_escape_string($_POST['lock_survey_surveyindex_id']); // hidden input field value
   $survey_lock	= $mysqli->real_escape_string($_POST['survey_lock']);  				 // hidden input value defined as '1' in form
   
   // Retrieve dealercode FROM dealer table to display in success message
   $query = "SELECT dealercode FROM dealer WHERE dealerID = $dlr_id";
   $result = $mysqli->query($query);
   if (!$result) {
		$_SESSION['error'][] = "Survey lock failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }
   $lookup = $result->fetch_assoc();
   $dlr_code = $lookup['dealercode'];
   
   // Check to ensure that ROs exist for user selection.  If there aren't any ROs, do not let the user lock the survey.
   $query = "SELECT ronumber FROM repairorder WHERE dealerID = $dlr_id AND surveyindex_id = $survey_id";
   $result = $mysqli->query($query);
   if (!$result) {
		$_SESSION['error'][] = "RO query failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }
   $rows = $result->num_rows;
   if ($rows == 0) {
		$_SESSION['error'][] = "Lock denied.  Only surveys with ROs may be locked.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   } else {
		// if ROs do exist, update 'locked' field in repairorder table with user selection
		$query = "UPDATE repairorder SET locked = $survey_lock WHERE dealerID = $dlr_id AND surveyindex_id = $survey_id";
		$result = $mysqli->query($query);
		if (!$result) {
		$_SESSION['error'][] = "Survey lock failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
		}
		
		// If update query successful, go back to main page.  No success message necessary, as main page already has a lock message
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }	
   
} else {
	$_SESSION['error'][] = "Survey lock failed.  Please see administrator.";
	die(header('Location: '.$_SESSION['lastpagedealerreports']));
}
?>