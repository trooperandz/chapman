<?php 
require_once("functions.inc");
include ('templates/login_check.php');
/* -------------------------------------------------------------------------*
   Program: yearmodelmenu_process.php

   Purpose: To process user-selected model years from Model Year report
			menubar for UPDATE or INSERT query instructions

   History:
    Date		Description										by
	11/05/2014	Adapted Longhorn svcs processing to Model Year
				processing file									Matt Holland
	11/16/2014	Updated yearmodel_string processing to include
				correct reading of yearmodel_strings table		Matt Holland
	
 ---------------------------------------------------------------------------*/
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);
if ($mysqli->connect_errno) { 
	$_SESSION['error'][] = "Database connection failed.";
}

$dealerID 		= $_SESSION['dealerID']	 		;  	// Initiate dealerID magic variable
$userID 	 	= $user->userID			 		;	// Initiate $userID magic variable
$surveyindex_id = $_SESSION['surveyindex_id']	;	// Initiate survey type magic variable

$yearmodelbox = $_POST['yearmodelbox']	;  // Initiate $_POST variable
if (isset($yearmodelbox) && !empty($yearmodelbox)) {
	$yearmodelcount = count($yearmodelbox);
	//echo 'You selected ' .$yearmodelcount. ' checkboxes: <br>';
	$yearmodel_string = "";
	for ($i=0; $i<$yearmodelcount; $i++) {
		if ($i == ($yearmodelcount-1)) {
			$yearmodel_string .= $yearmodelbox[$i];
		} else {
			$yearmodel_string .= $yearmodelbox[$i] . ',';
		}
	}
	//echo $yearmodel_string, '<br>';
	// Check to see if record with $userID is already in table
	$query = "SELECT * FROM yearmodel_strings WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id AND userID = $userID";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "yearmodel_strings SELECT query failed.  See administrator.";
		die(header("Location: enterrofoundation.php"));
	}
	$rows = $result->num_rows;
	if ($rows > 0) {
		// Update yearmodel_strings table with years string if $userID record already exists
		$query = "UPDATE yearmodel_strings SET yearmodel_string = '$yearmodel_string', create_date = NOW()  WHERE dealerID = $dealerID AND userID = $userID AND surveyindex_id = $surveyindex_id";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "yearmodel_strings UPDATE query failed.  See administrator.";
			die(header("Location: " .$_SESSION['lastpagedealerreports']));
		}
	} else {
		// If there is no record with $userID, insert new one
		$query = "INSERT INTO yearmodel_strings (dealerID, yearmodel_string, surveyindex_id, create_date, userID) 
				  VALUES ('$dealerID', '$yearmodel_string', '$surveyindex_id', NOW(), '$userID')";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "yearmodel_strings INSERT query failed.  See administrator.";
			die(header("Location: " .$_SESSION['lastpagedealerreports']));
		}
	}
	// Echo names of services that were selected
	$query = "SELECT modelyear FROM yearmodel WHERE yearmodelID IN ($yearmodel_string)
			  ORDER BY yearmodelID DESC";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "yearmodel SELECT query failed.  See administrator.";
	}
	$ymrows = $result->num_rows;
	$ymarray = array(array());
	$index = 0;
	while ($ymvalue = $result->fetch_assoc()) {
		$ymarray[$index] = $ymvalue['modelyear'];
		$index += 1;
	}

	//echo 'You selected the following years: <br>';
	$yearmodel_string = "";
	for ($i=0; $i<$ymrows; $i++) {
		if ($i == $ymrows-1) {
			$yearmodel_string .= $ymarray[$i];
		} else {
			$yearmodel_string .= $ymarray[$i] . ', ';
		}
	}
	//echo $yearmodel_string. '<br>';
	//die();
	// Save $yearmodel_string as magic variable
	$_SESSION['yearmodel_string']	= $yearmodel_string;
	// $_SESSION['success'][] = "Model years have been inserted successfully.";
	die(header("Location: " .$_SESSION['lastpagedealerreports']));
} else {
	$_SESSION['error'][] = "You did not select any year options.";
	die(header("Location: " .$_SESSION['lastpagedealerreports']));
}
// echo '<br><br><br>';
?>