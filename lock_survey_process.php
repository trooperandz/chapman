<?php
/* -----------------------------------------------------------------------------*
   Program: lock_survey_process.php

   Purpose: Enable admin to restrict user access to surveys

	History:
    Date				Description									by
	01/19/2015			Initial design and coding					Matt Holland
 *------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include('templates/db_cxn.php');

$dealerID 			= $_SESSION['dealerID']			 	;  	// Initiate dealerID magic variable
$userID 			= $user->userID			     		;	// Initiate $userID magic variable
$dealercode 		= $_SESSION['dealercode']	 		;	// Initiate dealercode magic variable 
$surveyindex_id 	= $_SESSION['surveyindex_id']		;  	// Initiate surveyindex_id magic variable
$survey_description = $_SESSION['survey_description']	; 	// Initiate survey_description variable

if (isset($_POST['dlr_id']) && $_POST['dlr_id'] !=''
   && isset($_POST['survey_id']) && $_POST['survey_id'] !=''
   && isset($_POST['survey_lock']) && $_POST['survey_lock'] !='') {
   
   $dlr_id      = $mysqli->real_escape_string($_POST['dlr_id'])		;
   $survey_id   = $mysqli->real_escape_string($_POST['survey_id'])	;
   $survey_lock = $mysqli->real_escape_string($_POST['survey_lock']);
   
   $_SESSION['dlr_id'] 		= $dlr_id;  	 // Save for sticky form input in case of error
   $_SESSION['survey_lock'] = $survey_lock;  // Save for sticky form input in case of error
   $_SESSION['survey_id'] 	= $survey_id;    // Save for sticky form input in case of error
   
   // Retrieve dealercode FROM dealer table to display in success message
   $query = "SELECT dealercode FROM dealer WHERE dealerID = $dlr_id";
   $result = $mysqli->query($query);
   if (!$result) {
		$_SESSION['error'][] = "Dealer query failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }
   $lookup = $result->fetch_assoc();
   $dlr_code = $lookup['dealercode'];
   $_SESSION['dlr_code'] = $dlr_code;  // Save for sticky form input in case of error
   
   // Retrieve survey_description from surveys table to display in success message
   $query = "SELECT survey_description FROM survey_index WHERE surveyindex_id = $survey_id";
   $result = $mysqli->query($query);
   if (!$result) {
		$_SESSION['error'][] = "Surveys query failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }
   $lookup = $result->fetch_assoc();
   $survey_desc = $lookup['survey_description'];
   $_SESSION['survey_desc'] = $survey_desc;  // Save for sticky form input in case of error
   
   // Check to ensure that ROs exist for user selection
   $query = "SELECT ronumber FROM repairorder WHERE dealerID = $dlr_id AND surveyindex_id = $survey_id";
   $result = $mysqli->query($query);
   if (!$result) {
		$_SESSION['error'][] = "RO query failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }
   $rows = $result->num_rows;
   if ($rows == 0) {
		$_SESSION['error'][] = "The dealer and survey type that you selected contains no ROs.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   } else {
		// if ROs do exist, update 'locked' field in repairorder table with user selection
		$query = "UPDATE repairorder SET locked = $survey_lock WHERE dealerID = $dlr_id AND surveyindex_id = $survey_id";
		$result = $mysqli->query($query);
		if (!$result) {
		$_SESSION['error'][] = "Update query failed.  See administrator.";
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
		}
		
		// If update query successful, issue success message to user and go back to main page
		if ($survey_lock == 1) {
		$_SESSION['success'][] = constant('ENTITY').' '.$dlr_code.' '.$survey_desc.' has been locked.';
		} else {
		$_SESSION['success'][] = constant('ENTITY').' '.$dlr_code.' '.$survey_desc.' has been unlocked.';
		}
		
		// Unset error message globals since update was successful
		unset ($_SESSION['dlr_id'])		;
		unset ($_SESSION['dlr_code'])	;
		unset ($_SESSION['survey_id'])	;
		unset ($_SESSION['survey_desc']);
		unset ($_SESSION['survey_lock']);
		
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
   }	
   
} else {
	$_SESSION['error'][] = "You left a Survey Access Control form field blank.  Please try again.";
	die(header('Location: '.$_SESSION['lastpagedealerreports']));
}
?>