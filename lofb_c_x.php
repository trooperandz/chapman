<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------------------------*
   Program: lofb_c_x.php

   Purpose: Export of LOF Baseline chart data - comparison report
		
   History:
    Date		Description														by
	07/10/2014	Initial design and coding										Matt Holland
	09/25/2014	Rewrote to include new titles and Linux compatibility (isset)	Matt Holland
	11/19/2014	Rewrote to include template includes and reduced query blocks	Matt Holland
	12/01/2014	Rewrote to include standard query includes						Matt Holland
	12/18/2014	Updated includes and body/chart variables						Matt Holland
*------------------------------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];	// Dealercode set at login
$dealerID 	= $_SESSION['dealerID'];  	// Dealer ID set at login
$userID		= $user->userID;			// Initialize user

/*------------------------------------------------------------------------------------------------------------*/
// Set $comparisonsurveyindex_id variable for queries
$surveyindex_id = $_SESSION['comparisonsurveyindex_id'];

/*------------------------------------------------------------------------------------------------------------*/
// Set report variables for includes
$tabletitle 	  = 'LOF Baseline Distribution'					;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title (rest are defined in comparison_exportbody.php)
$filename1		  = 'LOFBaselineDistributionComparison.csv'		;	// Set file name for header instruction	
	
/*------------------------------------------------------------------------------------------------------------*/
// Query set 1
if((isset($_SESSION['comparedealer1IDs']) 		&& isset($_SESSION['comparedealer2IDs'])) 		OR 
   (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR
   (isset($_SESSION['compareregionIDs1']) 		&& isset($_SESSION['compareregionIDs2']))) {
	if (isset($_SESSION['comparedealer1IDs']) && isset($_SESSION['comparedealer2IDs'])) {
		$dealerIDs1 = $_SESSION['comparedealer1IDs']; // Initialize all globals to be used in queries
		$dealerIDs2 = $_SESSION['comparedealer2IDs'];
	} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
		$dealerIDs1 = $_SESSION['comparedealerregion1IDs'];
		$dealerIDs2 = $_SESSION['compareregiondealerIDs1'];
	} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {
		$dealerIDs1 = $_SESSION['compareregionIDs1'];
		$dealerIDs2 = $_SESSION['compareregionIDs2'];
	}
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	//  Query for second LOF Baseline set
	include ('templates/query_lofb_md2.php');
/*------------------------------------------------------------------------------------------------------------*/
// Query set 2
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	// Query for first LOF Baseline set
	include ('templates/query_lofb_global.php');
/*------------------------------------------------------------------------------------------------------------*/
// Default queries
} else {
	// Swap $dealerID variable
	$dealerIDs1 = $dealerID;
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	// Query for first LOF Baseline set
	include ('templates/query_lofb_global.php');
} // End else statement

/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/comparison_exportbody.php');

// Generate first line
$output .= "Average Labor,";
$output .= '"'. '$'.$averagelabor. '",';
$output .= '"'. '$'.$averagelabor2. '"';
$output .="\n";

// Generate second line
$output .= "Average Parts,";
$output .= '"'. '$'.$averageparts. '",';
$output .= '"'. '$'.$averageparts2. '"';
$output .="\n";

// Generate third line
$output .= "Avg Labor & Parts,";
$output .= '"'. '$'.$averagepartspluslabor. '",';
$output .= '"'. '$'.$averagepartspluslabor2. '"';
$output .="\n";

// Download the file
include ('templates/exportfooter.php');
?>