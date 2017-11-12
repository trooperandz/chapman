<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: csvmileageexport.php

   Purpose: Export Mileage Spread data in csv format
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

/*-----------------------------------Set report variables for includes----------------------------------------*/
 
$tabletitle 	  = 'Mileage Spread Distribution'			;	// Set table title
$tablehead1 	  = 'Mileage Spread,'						;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title
$filename1		  = 'MileageSpreadDistribution.csv'			;	// Set file name for header instruction		 
/*---------------------------------------------Dealer query---------------------------------------------------*/

// Query for total rows
include ('templates/query_totalros_md1.php');

//  Query for mileage ranges 
include ('templates/query_ms_md1.php');	

/*---------------------------------------------Build Export---------------------------------------------------*/
include ('templates/dealer_exportbody.php');

//Get records from table
while ($row = $resultms->fetch_array()) {
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