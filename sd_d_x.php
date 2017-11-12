<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: demand1and2export.php

   Purpose: Export Service Demand data in csv format
   History:
    Date		Description									by
	06/01/2014	Initial design and coding					Matt Holland
	08/03/2014	Update to mysqli & one query				Matt Holland
	11/17/2014	Incorporate new template includes format	
				and yearmodel_string processing				Matt Holland
	12/03/2014	Updated with standard query includes		Matt Holland
	01/14/2015	Updated service demand variables			Matt Holland
 *--------------------------------------------------------------------------*/

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']; 	// Initialize $dealerID variable
$dealerIDs1 = $_SESSION['dealerID'];  	// Initialize dealer variable for query includes
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable
$userID		= $user->userID;			// Initialize user

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');

/*-----------------------------------Set report variables for includes----------------------------------------*/
 
$tabletitle 	  = 'Service Demand Distribution'	;	// Set table title
$tablehead1 	  = 'Category,'						;	// Set first table header title
$tablehead2 	  = 'Percentage,'					;	// Set second table header title
$tablehead3 	  = 'Total ROs'						;	// Set third table header title
$filename1		  = 'ServiceDemandDistribution.csv'	;	// Set file name for header instruction	
	 
/*---------------------------------------------Dealer queries---------------------------------------------------*/

// To compute the total # rows for denominator	
include ('templates/query_totalros_md1.php');

// SVC D queries
include ('templates/query_svcd_md1.php');

/*  Consolidate computations  */
include ('templates/query_svcd_summary.php');

/*---------------------------------------------Build Export---------------------------------------------------*/
include ('templates/dealer_exportbody.php');

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

include ('templates/dealer_exportfooter.php');
?>