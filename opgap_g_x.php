<?php 
/* -----------------------------------------------------------------------------*
   Program: opgap_g_x.php

   Purpose: Produce L1 & L2 dealer export

	History:
    Date				Description									by
	12/11/2014			Initial design & coding						Matt Holland
	01/07/2015			Fixed to work with new table design			Matt Holland
 *-----------------------------------------------------------------------------*/
 
// Require instructions for all pages
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']; 	// Initialize $dealerID variable
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable
$userID		= $user->userID;			// Initialize user

// Set $surveyindex_id variable for queries
if (isset($_SESSION['globalsurveyindex_id'])) {
	$surveyindex_id = $_SESSION['globalsurveyindex_id'];
} else {
	$_SESSION['error'][] = "surveyindex_id variable is not set.  Please see administrator.";
	die(header("Location: ".$_SESSION['lastpageglobalreports']));
}
/*------------------------------------------------------------------------------------------------*/
// Get total count of dealers per survey type
$query = "SELECT DISTINCT dealerID FROM repairorder WHERE surveyindex_id IN($surveyindex_id)";
$total_dealers_result = $mysqli->query($query);
if (!$total_dealers_result) {
	$_SESSION['error'][] = "Failed to retrieve total dealer count.  repairorder SELECT query failed.  See administrator.";
}
$totaldealers_persurvey = $total_dealers_result->num_rows;

// Manage Survey Type name processing (required for 'All Survey Types' heading)
$globalsurvey_description = $_SESSION['globalsurvey_description'];
if ($_SESSION['globalsurveyindexid_rows'] > 1) {
	$globalsurvey_description = 'All Survey Type';
}

/*------------------------------------------------------------------------------------------------*/
// State queries

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
	// Query servicerendered table for L1 ($result)
	include ('templates/query_totalros_md1.php');
	// Query level_one_analysis, services and servicerendered tables ($resultL1, $resultst1, $strows1)
	include ('templates/query_L1_md1.php');
	//Query level_two_analysis, services and servicerendered tables  ($resultL2, $resultst2, $strows2)
	include ('templates/query_L2_md1.php');
} else {
	/*------------------------------------------------*/
	// Global queries
	include ('templates/query_totalros_global.php');
	// Query level_one_analysis, services and servicerendered tables ($resultL1, $resultst1, $strows1)
	include ('templates/query_L1_global.php');
	//Query level_two_analysis, services and servicerendered tables  ($resultL2, $resultst2, $strows2)
	include ('templates/query_L2_global.php');
	// Reset $resultmy2 for use below

} // End else statement

/*-----------------------------------Set report variables for includes----------------------------------------*/
$tabletitle 	 = 'Level 1 Operating Gap'			;	// Set table title
$tablehead1 	 = 'L1 Service,'					;	// Set first table header title
$tablehead3 	 = 'L1 Metric,'						;	// Set third table header title
$tablehead4		 = 'Operating Gap'					;   // Set fourth table header title
$filename1		 = 'L1&L2OpgapGlobalExport.csv'		;	// Set file name for header instruction	
//$tablehead2 is set in global_exportbody_opgap.php	 
/*------------------------------------------------------------------------------------------------------------*/
// Build export
// Begin string definition
$output= "";
$table= "";
$output .= 'Data Export: ' .(Date("l F d Y"));
$output .="\n";
$output .="----------------------------------------------------------------------------";
include ('templates/global_exportbody_opgap.php');

// Setup variable results and reset $resultL1 pointer	
$resultL1->data_seek(0);

// Convert L1_value string into array
$L1vals  = $resultL1->fetch_assoc();
$L1val   = explode(',', $L1vals['L1_value']);
$L1value = array();
$index = 0;
foreach ($L1val as $num) {
	$L1value[$index] = (int)$num;
	$index += 1;
}

//Get records from table
$index = 0;
while ($row = $resultst1->fetch_array()) { 
		$output .='"'.$row[0].'",';
		$output .='"'.$row[2].'%'.'",';
		$output .='"'.$L1value[$index].'%'.'",';
		$output .='"'.($row[2] - $L1value[$index]).'%'.'",';
		$output .="\n";
		$index += 1;
}

/*-----------------------------------Set report variables for includes----------------------------------------*/
$tabletitle 	 = 'Level 2 Operating Gap'			;	// Set table title
$tablehead1 	 = 'L2 Service,'					;	// Set first table header title
$tablehead3 	 = 'L2 Metric,'						;	// Set third table header title
$tablehead4		 = 'Operating Gap'					;   // Set fourth table header title
//$tablehead2 is set in global_exportbody_opgap.php 
/*------------------------------------------------------------------------------------------------------------*/
// Build export
$output .="\n";
$output .="----------------------------------------------------------------------------";
include ('templates/global_exportbody_opgap.php');

// Setup variable results and reset $resultL1 pointer	
$resultL2->data_seek(0);

// Convert L1_value string into array
$L2vals  = $resultL2->fetch_assoc();
$L2val   = explode(',', $L2vals['L2_value']);
$L2value = array();
$index = 0;
foreach ($L2val as $num) {
	$L2value[$index] = (int)$num;
	$index += 1;
}

//Get records from table
$index = 0;
while ($row = $resultst2->fetch_array()) { 
		$output .='"'.$row[0].'",';
		$output .='"'.$row[2].'%'.'",';
		$output .='"'.$L2value[$index].'%'.'",';
		$output .='"'.($row[2] - $L2value[$index]).'%'.'",';
		$output .="\n";
		$index += 1;
}
include ('templates/dealer_exportfooter.php');
?>