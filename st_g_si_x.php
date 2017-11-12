<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: st_g_si_x.php

   Purpose: Export single issue Longhorn global data
   History:
    Date		Description												by
	01/22/2015	Created Single Issue report from original ST report		Matt Holland
/*------------------------------------------------------------------------------------------------------------*/
// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];	// Dealercode set at login
$dealerID 	= $_SESSION['dealerID'];  	// Dealer ID set at login
$userID		= $user->userID;			// Initialize user

// Set $surveyindex_id variable for queries
$surveyindex_id = $_SESSION['globalsurveyindex_id'];

/*------------------------------------------------------------------------------------------------*/
// Service categories processing
include ('templates/set_reporttype_global.php');
include ('templates/st_string_processing.php');

/*------------------------------------------------------------------------------------------------------------*/
// Set report variables for includes
$tabletitle 	  = 'Single Issue Longhorn Distribution'	;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs,'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title
$filename1		  = 'SILonghornDistributionGlobal.csv'		;	// Set file name for header instruction		
 
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
	// Multidealer queries
	include ('templates/query_totalros_si_md1.php');
	include ('templates/query_st_si_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_si_global.php');	
	include ('templates/query_st_si_global.php');
	// Reset $resultst2 for use below
	$resultst_si = $resultst2_si;
}
/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/global_exportbody.php');
	
//Get records from table
	while ($row = $resultst_si->fetch_array()) {
		for ($i=0; $i < $columns_total; $i++){
			if ($i == 2) {
			$output .='"'.$row[2].'%'.'",';
			} else {
			$output .='"'.$row["$i"].'",';
			}
		}
	$output .="\n";
	}
// Download the file
include ('templates/exportfooter.php');
?>