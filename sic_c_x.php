<?php
require_once("functions.inc");
include ('templates/login_check.php');

/* ----------------------------------------------------------------------------------------*
   Program: csvsingleissuecategorycomparison.php

   Purpose: Export of Model Year chart data - comparison report
		
   History:
    Date		Description														by
	07/10/2014	Initial design and coding										Matt Holland
	09/25/2014	Rewrote to include new titles and Linux compatibility (isset)	Matt Holland
	11/19/2014	Rewrote to include template includes and reduced query blocks	Matt Holland
	12/01/2014	Rewrote to include standard query include						Matt Holland
*------------------------------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];	// Dealercode set at login
$dealerID 	= $_SESSION['dealerID'];  	// Dealer ID set at login
$userID		= $user->userID;			// Initialize user

/*------------------------------------------------------------------------------------------------------------*/
// Set $comparisonsurveyindex_id variable for queries
$surveyindex_id = $_SESSION['comparisonsurveyindex_id'];

/*------------------------------------------------------------------------------------------------*/
// SI Category string processing
include ('templates/sicategory_string.php');

/*------------------------------------------------------------------------------------------------------------*/
// Set report variables for includes
$tabletitle 	  = 'Single Issue Category Distribution'		;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title (rest are defined in comparison_exportbody.php)
$filename1		  = 'SICategoryDistributionComparison.csv'		;	// Set file name for header instruction	
	
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
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_md2.php');

/*--------------------------------------------------------Single Dealer queries------------------------------------------------------*/	
// Check to see if single dealer variables are set
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_global.php');

/*------------------------------------------------------------Default query set--------------------------------------------------------*/
// If no globals are set, run default queries
} else {
	// Swap $dealerID variable
	$dealerIDs1 = $dealerID;
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_global.php');
} // End else statement
	
// Summary set 1
include ('templates/query_sic_summary.php');
// Summary set 2
include ('templates/query_sic_summary2.php');

/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/comparison_exportbody.php');

// Generate first line
$output .= "Level 1 Services,";
$output .= '"'.$percent_level1. '%' . '",';
$output .= '"'.$percent_level12. '%' . '"';
$output .="\n";

// Generate second line
$output .= "Wear Maintenance,";
$output .= '"'.$percent_wm. '%' . '",';
$output .= '"'.$percent_wm2. '%' . '"';
$output .="\n";

// Generate third line
$output .= "Repair Services,";
$output .= '"'.$percent_repair. '%' . '",';
$output .= '"'.$percent_repair2. '%' . '"' ;
$output .="\n";

// Download the file
include ('templates/exportfooter.php');
?>