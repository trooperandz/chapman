<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: csvyearmodelexportglobal.php

   Purpose: Export model year global data
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
$tabletitle 	  = 'Model Year Distribution'				;	// Set table title
$tablehead1 	  = 'Model Age,'							;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title
$filename1		  = 'ModelYearDistributionGlobal.csv'		;	// Set file name for header instruction		
 
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
	include ('templates/query_totalros_md1.php');
	include ('templates/query_ym_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_global.php');	
	include ('templates/query_ym_global.php');
	// Reset $resultmy2 for use below
	$resultmy = $resultmy2;
}
/*------------------------------------------------------------------------------------------------------------*/
// Build Export
include ('templates/global_exportbody.php');
	
while ($row = $resultmy->fetch_array()) {
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