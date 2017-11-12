<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: ym_g_si_x.php

   Purpose: Produce global Single Issue Model Year export

	History:
    Date		Description										by
	01/22/2015	Created SI report from original YM report		Matt Holland
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
$tabletitle 	  = 'Single Issue Model Year Distribution'	;	// Set table title
$tablehead1 	  = 'Model Age,'							;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title
$filename1		  = 'SIModelYearDistributionGlobal.csv'		;	// Set file name for header instruction		
 
/*------------------------------------------------------------------------------------------------------------*/

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
	include ('templates/query_totalros_si_md1.php');
	include ('templates/query_ym_si_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_si_global.php');	
	include ('templates/query_ym_si_global.php');
	// Reset $resultmy2_si for use below
	$resultmy_si = $resultmy2_si;
}
/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/global_exportbody.php');
	
while ($row = $resultmy_si->fetch_array()) {
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