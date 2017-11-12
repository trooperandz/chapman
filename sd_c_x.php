<?php
require_once("functions.inc");
include ('templates/login_check.php');

/* ----------------------------------------------------------------------------------------*
   Program: csvdemand1and2exportcomparison.php

   Purpose: Export of Service Demand chart data - comparison report
		
   History:
    Date		Description														by
	07/10/2014	Initial design and coding										Matt Holland
	09/25/2014	Rewrote to include new titles and Linux compatibility (isset)	Matt Holland
	11/19/2014	Rewrote to include template includes and reduced query blocks	Matt Holland
	12/01/2014	Rewrote with standard query includes							Matt Holland
	01/14/2015	Updated service demand variables								Matt Holland
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
// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');

/*------------------------------------------------------------------------------------------------------------*/
// Set report variables for includes
$tabletitle 	  = 'Service Demand Distribution'				;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title (rest are defined in comparison_exportbody.php)
$filename1		  = 'ServiceDemandDistributionComparison.csv'	;	// Set file name for header instruction	
	
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
	// To compute the total # rows for denominator, set 1
	include ('templates/query_totalros_md1.php');
	// Queries set 1
	include ('templates/query_svcd_md1.php');
	
	// To compute the total # rows for denominator, set 2
	include ('templates/query_totalros_md2.php');
	// Queries set 1
	include ('templates/query_svcd_md2.php');

/*--------------------------------------------------------Single Dealer queries------------------------------------------------------*/	
// Check to see if single dealer variables are set
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// To compute the total # rows for denominator, set 1
	include ('templates/query_totalros_md1.php');
	// Queries set 1
	include ('templates/query_svcd_md1.php');
	
	// To compute the total # rows for denominator, set 2
	include ('templates/query_totalros_global.php');
	// Queries set 1
	include ('templates/query_svcd_global.php');

/*------------------------------------------------------------Default query set--------------------------------------------------------*/
// If no globals are set, run default queries
} else {
	// Swap $dealerID variable
	$dealerIDs1 = $dealerID;
	// To compute the total # rows for denominator, set 1
	include ('templates/query_totalros_md1.php');
	// Queries set 1
	include ('templates/query_svcd_md1.php');
	
	// To compute the total # rows for denominator, set 2
	include ('templates/query_totalros_global.php');
	// Queries set 1
	include ('templates/query_svcd_global.php');
	
} // End else statement

// Summary set 1
include ('templates/query_svcd_summary.php');
// Summary set 2
include ('templates/query_svcd_summary2.php');

/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/comparison_exportbody.php');

// Generate first line
$output .= "Level 1 Demand,";
$output .= '"'.$percent_level1_sd. '%' . '",';
$output .= '"'.$percent_level1_sd2. '%' . '"';
$output .="\n";

// Generate second line
$output .= "Level 2 Demand,";
$output .= '"'.$percent_level2_sd. '%' . '",';
$output .= '"'.$percent_level2_sd2. '%' . '"';
$output .="\n";

// Generate third line
$output .= "Full Service,";
$output .= '"'.$percent_full_sd. '%' . '",';
$output .= '"'.$percent_full_sd2. '%' . '"';
$output .="\n";

// Download the file
include ('templates/exportfooter.php');
?>