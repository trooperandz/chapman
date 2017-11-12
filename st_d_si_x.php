<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: st_d_si_x.php

   Purpose: Single issue Longhorn dealer export
   History:
    Date		Description												by
	01/22/2015	Created Single Issue report from original ST report		Matt Holland
/*------------------------------------------------------------------------------------------------------------*/

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
 
$tabletitle 	  = 'Single Issue Longhorn Distribution'	;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs,'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title
$filename1		  = 'SILonghornDistribution.csv'			;	// Set file name for header instruction		 
/*---------------------------------------------Dealer query---------------------------------------------------*/

// Query for total dealer rows
include ('templates/query_totalros_si_md1.php');

// Query for dealer services	
include ('templates/query_st_si_md1.php');

/*---------------------------------------------Build Export---------------------------------------------------*/
include ('templates/dealer_exportbody.php');

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
include ('templates/dealer_exportfooter.php');
?>