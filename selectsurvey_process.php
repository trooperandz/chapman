<?php
require_once("functions.inc");
include ('templates/login_check.php');
// DB connection
include ('templates/db_cxn.php');

$dealerID   = $_SESSION['dealerID']		;  	// Initiate dealerID magic variable
$dealercode = $_SESSION['dealercode']	;	// Initiate dealercode magic variable
$userID		= $user->userID				;	// Initiate userID magic variable

if (isset($_POST['survey_selection']) && $_POST['survey_selection'] !="") {
	$surveyindex_id = $mysqli->real_escape_string($_POST['survey_selection']);

	// Query surveys to see if user selection already exists for $dealerID
	$query = "SELECT surveys.surveyindex_id, survey_index.survey_description FROM survey_index
	          INNER JOIN surveys ON surveys.surveyindex_id = survey_index.surveyindex_id
			  WHERE surveys.dealerID = $dealerID AND surveys.surveyindex_id = $surveyindex_id";
	$mainresult = $mysqli->query($query);
	if (!$mainresult) {
		$_SESSION['error'][] = "surveys SELECT query failed.  See administrator.";
		die (header("Location: ".$_SESSION['lastpagedealerreports']));
	}
	$rows = $mainresult->num_rows;
	// If no rows, insert survey type record into database
	if ($rows == 0) {
		$query = "INSERT INTO surveys (dealerID, surveyindex_id, create_date, userID)
			      VALUES ('$dealerID', '$surveyindex_id', NOW(), '$userID')";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "surveys INSERT query failed.  See administrator.";
			die (header("Location: ".$_SESSION['lastpagedealerreports']));
		}
		// Get survey_description using POST value
		$query = "SELECT survey_description FROM survey_index WHERE surveyindex_id = $surveyindex_id";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "surveys SELECT query failed.  See administrator.";
			die (header("Location: ".$_SESSION['lastpagedealerreports']));
		}
		$survey_value = $result->fetch_assoc();
		$survey_description = $survey_value['survey_description'];
		// Save survey_description and surveyindex_id as magic variables
		$_SESSION['surveyindex_id']		= $surveyindex_id	 ;
		$_SESSION['survey_description'] = $survey_description;
		//echo '$_SESSION[surveyindex_id]: 	 ' .$_SESSION['surveyindex_id'].     '<br>';
		//echo '$_SESSION[survey_description]: ' .$_SESSION['survey_description']. '<br>';
		// Return to main page
		//die();
		die (header("Location: ".$_SESSION['lastpagedealerreports']));
	} else {
		// If survey type already exists for $dealerID, set magic variables
		$survey_value = $mainresult->fetch_assoc();
		$surveyindex_id 	= $survey_value['surveyindex_id'];
		$survey_description = $survey_value['survey_description'];
		// Save as magic variables
		$_SESSION['surveyindex_id'] 	= $surveyindex_id;
		$_SESSION['survey_description'] = $survey_description;
		//echo '$_SESSION[surveyindex_id]: 	 ' .$_SESSION['surveyindex_id'].     '<br>';
		//echo '$_SESSION[survey_description]: ' .$_SESSION['survey_description']. '<br>';
		//die();
		// Return to main page
		die (header("Location: ".$_SESSION['lastpagedealerreports']));
	}	
} else {
		$_SESSION['error'][] = "You did not select a survey type.  Please try again.";
		die (header("Location: ".$_SESSION['lastpagedealerreports']));
}
?>