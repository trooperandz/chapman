<?php
/*-----------------------------------------------------------------------------------*
   Program: survey_notes_process.php

   Purpose: Add survey comments to database

	History:
    Date			Description											by
	03/20/2014		Initial design and coding							Matt Holland
			
*--------------------------------------------------------------------- --------------*/

// Required system includes
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

// Prevent access if form has not been submitted
if(!isset($_POST['survey_comment'])) {
	die(header('Location: index.php'));
} else {
	// Form was submitted; proceed with processing
	$survey_comment = $mysqli->real_escape_string($_POST['survey_comment']);
	
	// If empty entry, issue error.  Do not submit empty entry to db
	if ($survey_comment == '') {
		$_SESSION['error'][] = 'You left the survey note field blank.  Try again.';
		die(header('Location: '.$_SESSION['lastpagedealerreports'])); 
	}
	
	// If not null, insert note into repairorder_comment table
	$query = "INSERT INTO repairorder_comments (comment, dealerID, surveyindex_id, create_date, userID)
			 VALUES ('$survey_comment', '$dealerID', '$surveyindex_id', NOW(), '$userID')";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = 'There was a system error.  Your comment was not posted.  See administrator.'.$mysqli->error;
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
	} else {
		// Success message.  Return back to main page and display message
		$_SESSION['success'][] = 'You posted the following comment: <br><span style="color: blue;">'.$survey_comment.'</span>';
		die(header('Location: '.$_SESSION['lastpagedealerreports']));
	}
}
?>