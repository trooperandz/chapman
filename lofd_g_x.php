<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: csvlofdemandexportglobal.php

   Purpose: Export LOF Demand global data
   History:
    Date		Description									by
	06/01/2014	Initial design and coding					Matt Holland
	08/26/2014	Incorporate global if statements			Matt Holland
	09/03/2014	Incorporate regional if statements			Matt Holland
	11/19/2014	Redesign with includes template files		Matt Holland
	11/26/2014	Changed queries to standard includes		Matt Holland
/*------------------------------------------------------------------------------------------------------------*/
// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];	// Dealercode set at login
$dealerID 	= $_SESSION['dealerID'];  	// Dealer ID set at login
$userID		= $user->userID;			// Initialize user

// Set $surveyindex_id variable for queries
$surveyindex_id = $_SESSION['globalsurveyindex_id'];

/*------------------------------------------------------------------------------------------------------------*/
// Set report variables for includes
$tabletitle 	  = 'LOF Demand Distribution'				;	// Set table title
$tablehead1 	  = 'Category,'								;	// Set first table header title
$tablehead2 	  = 'Percentage,'							;	// Set second table header title
$tablehead3 	  = 'Total ROs'								;	// Set third table header title
$filename1		  = 'LOFDemandDistributionGlobal.csv'		;	// Set file name for header instruction		
 
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
	include ('templates/query_lofd_md1.php');
	// Summary query
	include ('templates/query_lofd_summary.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_global.php');	
	include ('templates/query_lofd_global.php');
	// Summary query #2
	include ('templates/query_lofd_summary2.php');
	// Reset variables for below
	$totalros  			= $totalros2		;
	$LOFrows 			= $LOFrows2		  	;
	$SILOFrows 			= $SILOFrows2		;
	$percent_LOF		= $percent_LOF2	  	;
	$percent_SILOF		= $percent_SILOF2	;
}
/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/global_exportbody.php');
	
// Generate first line
$output .= "ROs with LOF,";
$output .= '"'.$percent_LOF. '%' . '",';
$output .= '"'.$LOFrows.'"';
$output .="\n";

// Generate second line
$output .= "SI ROs with LOF,";
$output .= '"'.$percent_SILOF. '%' . '",';
$output .= '"'.$SILOFrows.'"';
$output .="\n";

// Download the file
include ('templates/exportfooter.php');
?>