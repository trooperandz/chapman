<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* --------------------------------------------------------------------------------------------*
   Program: ms_d_si_x.php

   Purpose: Export of Single Issue mileage spread data
   History:
    Date			Description													by
	01/22/2015		Created Single Issue report from original MS report			Matt Holland
*-----------------------------------------------------------------------------------------------*/

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
 
$tabletitle 	  = 'Single Issue Mileage Spread Distribution'	;	// Set table title
$tablehead1 	  = 'Mileage Spread,'							;	// Set first table header title
$tablehead2 	  = 'Total ROs,'								;	// Set second table header title
$tablehead3 	  = 'Percentage'								;	// Set third table header title
$filename1		  = 'SIMileageSpreadDistribution.csv'			;	// Set file name for header instruction		 
/*---------------------------------------------Dealer query---------------------------------------------------*/

// Query for total rows
include ('templates/query_totalros_si_md1.php');

//  Query for mileage ranges 
include ('templates/query_ms_si_md1.php');	

/*---------------------------------------------Build Export---------------------------------------------------*/
include ('templates/dealer_exportbody.php');

//Get records from table
while ($row = $resultms_si->fetch_array()) {
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