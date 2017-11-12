<?php
// Initialize default variables
if(isset($_SESSION['dealerID'])) {
	$dealerID   = $_SESSION['dealerID']; // Initialize $dealerID variable
	$dealerIDs1 = $_SESSION['dealerID']; // Initialize dealer variable for query includes
}

if(isset($_SESSION['dealercode'])) {
	$dealercode = $_SESSION['dealercode']; // Initialize $dealercode variable
}

if(isset($user->userID)) {
	$userID	= $user->userID; // Initialize user
}

// Initialize survey globals
if(isset($_SESSION['surveyindex_id'])) {
	$surveyindex_id = $_SESSION['surveyindex_id']; //Initialize survey type variable
}

if(isset($_SESSION['survey_description'])) {
	$survey_description	= $_SESSION['survey_description']; // Initialize survey description variable
}

// Set last page variable
$_SESSION['lastpagedealerreports'] = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);