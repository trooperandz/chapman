<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: csvservicesexport.php

   Purpose: Export Longhorn data in csv format
   History:
    Date		Description									by
	06/01/2014	Initial design and coding					Matt Holland
	08/03/2014	Update to mysqli & one query				Matt Holland
	11/17/2014	Incorporate new template includes format	
				and yearmodel_string processing				Matt Holland
	12/03/2014	Update with standard query includes			Matt Holland
*-----------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

// Initialize default variables
$dealerID 	= $_SESSION['dealerID']		;  // Initialize $dealerID variable
$dealerIDs1 = $_SESSION['dealerID']		;  // Initialize $dealerIDs1 for queries
$dealercode = $_SESSION['dealercode']	;  // Initialize $dealercode variable
$userID		= $user->userID				;  // Initialize user

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

/*------------------------------------------------------------------------------------------------*/
// Service categories processing
include ('templates/set_reporttype_comparison.php');
include ('templates/st_string_processing.php');

/*-----------------------------------Set report variables for includes----------------------------------------*/
 
$tabletitle 	  = 'Longhorn Distribution'					;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title
$filename1		  = 'LonghornDistribution.csv'				;	// Set file name for header instruction		 
/*---------------------------------------------Dealer query---------------------------------------------------*/

// Query for total dealer rows
include ('templates/query_totalros_md1.php');

// Query for dealer services	
include ('templates/query_st_md1.php');

/*---------------------------------------------Build Export---------------------------------------------------*/
include ('templates/dealer_exportbody.php');

//Get records from table
while ($row = $resultst->fetch_array()) {
	for ($i=0; $i < $columns_total; $i++){
		if ($i == 2) {
			$output .='"'.$row[2].'%'.'",';
		} else {
			$output .='"'.$row["$i"].'",';
		}	
	}
	$output .="\n";
}
include ('templates/dealer_exportfooter.php');
?>