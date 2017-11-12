<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: csvdemand1and2exportglobal.php

   Purpose: Export Service Demand global data
   History:
    Date		Description									by
	06/01/2014	Initial design and coding					Matt Holland
	08/26/2014	Incorporate global if statements			Matt Holland
	09/03/2014	Incorporate regional if statements			Matt Holland
	11/18/2014	Redesign with includes template files		Matt Holland
	11/26/2014	Changed queries to standard includes		Matt Holland
	01/14/2015	Updated service demand variables			Matt Holland
/*------------------------------------------------------------------------------------------------------------*/
// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];	// Dealercode set at login
$dealerID 	= $_SESSION['dealerID'];  	// Dealer ID set at login
$userID		= $user->userID;			// Initialize user

// Set $surveyindex_id variable for queries
$surveyindex_id = $_SESSION['globalsurveyindex_id']; 

// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');

/*------------------------------------------------------------------------------------------------------------*/
// Set report variables for includes
$tabletitle 	  = 'Service Demand Distribution'			;	// Set table title
$tablehead1 	  = 'Category,'								;	// Set first table header title
$tablehead2 	  = 'Percentage,'							;	// Set second table header title
$tablehead3 	  = 'Total ROs'								;	// Set third table header title
$filename1		  = 'ServiceDemandDistributionGlobal.csv'	;	// Set file name for header instruction		
 
/*------------------------------------------------------------------------------------------------------------*/
// Multidealer queries
if (isset($_SESSION['multidealer']) OR isset($_SESSION['regiondealerIDs'])) {
	if (isset($_SESSION['multidealer'])) {
		// Query for total dealer rows
		$dealerIDs1 = $_SESSION['multidealer'];
	} elseif (isset($_SESSION['regiondealerIDs'])) {
		// Query for total dealer rows
		$dealerIDs1 = $_SESSION['regiondealerIDs'];
	}
	/*------------------------------------------------*/
	// Multidealer queries
	include ('templates/query_totalros_md1.php');
	include ('templates/query_svcd_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_global.php');	
	include ('templates/query_svcd_global.php');
	// Reset variables for use below
	$total_level1a 			= $total_level1a2			;
	$total_level1b 			= $total_level1b2			;
	$total_level2a 			= $total_level2a2			;
	$total_level2b 			= $total_level2b2			;
	$total_full_L1a			= $total_full_L1a2			;
	$total_full_L1b			= $total_full_L1b2			;
	$total_full_L3 			= $total_full_L32			;
	$total_full_sd 			= $total_full_sd2			;
	$totalros 	   			= $totalros2     			;
}
	// Query Summary
	include ('templates/query_svcd_summary.php');
/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/global_exportbody.php');
	
	// Generate first line
$output .= "Level 1 Demand,";
$output .= '"'.$percent_level1_sd. '%' . '",';
$output .= '"'.$total_level1_sd.'"';
$output .="\n";

// Generate second line
$output .= "Level 2 Demand,";
$output .= '"'.$percent_level2_sd. '%' . '",';
$output .= '"'.$total_level2_sd.'"';
$output .="\n";

// Generate third line
$output .= "Full Service,";
$output .= '"'.$percent_full_sd. '%' . '",';
$output .= '"'.$total_full_sd.'"';
$output .="\n";
	
// Download the file
include ('templates/exportfooter.php');
?>