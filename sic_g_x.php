<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: csvsingleissuecategoryglobal.php

   Purpose: Export SI Category global data
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
// SI Category string processing
include('templates/sicategory_string.php');

/*------------------------------------------------------------------------------------------------------------*/
// Set report variables for includes
$tabletitle 	  = 'Single Issue Category Distribution'		;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title
$tablehead2 	  = 'Percentage,'								;	// Set second table header title
$tablehead3 	  = 'Total ROs'									;	// Set third table header title
$filename1		  = 'SingleIssueCategoryDistributionGlobal.csv'	;	// Set file name for header instruction		
 
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
	include ('templates/query_sic_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_sic_global.php');
	// Reset variables for use below
	$total_level1 	= $total_level12	;
	$total_wm	  	= $total_wm2		;
	$total_repair 	= $total_repair2  	;
	$percent_level1 = $percent_level12	;
	$percent_wm		= $percent_wm2		;
	$percent_repair = $percent_repair2  ;
}
	// Summary Query
	include ('templates/query_sic_summary.php');
/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/global_exportbody.php');
	
// Generate first line
$output .= " Level 1 Services ,";
$output .= '"' .$percent_level1. '%'. '",';
$output .= '"' .$total_level1. '",';
$output .="\n";
	
// Generate second line
$output .= " Wear Maintenance ,";
$output .= '"' .$percent_wm. '%'. '",';
$output .= '"' .$total_wm. '",';
$output .="\n";
	
// Generate third line
$output .= " Repair Services ,";
$output .= '"' .$percent_repair. '%'. '",';
$output .= '"' .$total_repair. '",';
$output .="\n";
	
// Download the file
include ('templates/exportfooter.php');
?>