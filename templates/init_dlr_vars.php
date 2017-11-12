<?php
if (isset($_SESSION['dealerID'])) {
	$dealerID = $_SESSION['dealerID'];
	$dealerIDs1 = $_SESSION['dealerID'];
}
if (isset($_SESSION['dealercode'])) {
	$dealercode = $_SESSION['dealercode'];
}
// Initialize user
$userID = $user->userID;
			
// Initialize survey globals
if (isset($_SESSION['surveyindex_id'])) {
	$surveyindex_id = $_SESSION['surveyindex_id'];
}
if (isset($_SESSION['survey_description'])) {
	$survey_description = $_SESSION['survey_description'];
}