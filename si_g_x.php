<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: csvsingleissuequeryglobal.php

   Purpose: Export Single Issue global data
   History:
    Date		Description									by
	06/01/2014	Initial design and coding					Matt Holland
	08/26/2014	Incorporate global if statements			Matt Holland
	09/03/2014	Incorporate regional if statements			Matt Holland
	11/18/2014	Redesign with includes template files		Matt Holland
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
$tabletitle 	  = 'Single Issue Distribution'				;	// Set table title
$tablehead1 	  = 'Category,'								;	// Set first table header title
$tablehead2 	  = 'Percentage,'							;	// Set second table header title
$tablehead3 	  = 'Total ROs'								;	// Set third table header title
$filename1		  = 'SingleIssueDistributionGlobal.csv'		;	// Set file name for header instruction		
 
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
	include ('templates/query_si_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_si_global.php');
	// Reset variables for use below
	$totalsingle 	= $totalsingle2		;
	$totalmultiple	= $totalmultiple2	;
	$percentsingle	= $percentsingle2	;
	$percentmultiple= $percentmultiple2 ;
}
// Summary Query
include ('templates/query_si_summary.php');
/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/global_exportbody.php');
	
// Generate first line
$output .= " Single Issue ROs ,";
$output .= '"' .number_format($percentsingle,2). '%'. '",';
$output .= '"' .number_format($totalsingle,2). '",';
$output .="\n";
	
// Generate second line
$output .= " Multiple Issue ROs ,";
$output .= '"' .number_format($percentmultiple,2). '%'. '",';
$output .= '"' .number_format($totalmultiple,2). '",';
$output .="\n";

// Download the file
include ('templates/exportfooter.php');
?>