<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: csvexportall.php

   Purpose: Export all dealer data
   History:
    Date			Description												by
	07/12/2014		Initial design and coding								Matt Holland
	08/14/2014		Convert to mysqli										Matt Holland
	11/19/2014		Rewrote to include template includes					Matt Holland
	12/04/2014		Updated with standard query includes					Matt Holland
	12/16/2014		Updated LOF Demand report data with						Matt Holland
					new categories & updated LOFD queries					Matt Holland
	12/19/2014		Changed Longhorn table titles and took
					out query_totalsvcs	for new %calculation				Matt Holland
	01/07/2015		Fixed OpGap exports for new table format				Matt Holland
	01/14/2015		Changed service demand variables to 
					adjust for revamped SD queries							Matt Holland
	01/22/2015		Added YM Single Issue export							Matt Holland
	01/22/2015		Added MS Single Issue report							Matt Holland
	01/23/2015		Added ST Single Issue report							Matt Holland
/*------------------------------------------------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];	// Dealercode set at login
$dealerID 	= $_SESSION['dealerID'];  	// Dealer ID set at login
$dealerIDs1 = $_SESSION['dealerID'];	// Set $dealerIDs1
$userID		= $user->userID;			// Initialize user

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

// Set $currentyear for yearmodel_string processing
$currentyear = date('Y');
$month = date('m');
if ($month > 8) {
	$currentyear = date('Y')+1;
}

/*------------------------------------------------------------------------------------------------*/
// Retrieve yearmodel_string for queries
include ('templates/ym_string.php');
include ('templates/ym_string2.php');

/*------------------------------------------------------------------------------------------------*/
// Service categories processing
include ('templates/set_reporttype_dealer.php');
include ('templates/st_string_processing.php');

/*------------------------------------------------------------------------------------------------*/
// SI Category string processing
include ('templates/sicategory_string.php');

/*------------------------------------------------------------------------------------------------*/
// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');
		 
/*------------------------------------------------------------------------------------------------*/

// State all dealer queries
/*-----------------------------------------------------*/
// Total ROs Queries
	// Query for total dealer rows
	include ('templates/query_totalros_md1.php');
	// Query for total single issue dealer rows
	include ('templates/query_totalros_si_md1.php');
/*-----------------------------------------------------*/
// Model Year Queries
	//  Query for first set dealer model year	
	include ('templates/query_ym_dlr.php');
	include ('templates/query_ym_dlr2.php');
	//  Query for first set dealer SI model year	
	include ('templates/query_ym_si_dlr.php');
	include ('templates/query_ym_si_dlr2.php');
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');
	include ('templates/query_ms_si_md1.php');
/*-----------------------------------------------------*/
// Longhorn Queries	
	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');
	include ('templates/query_st_si_md1.php');
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');
/*-----------------------------------------------------*/
// Op Gap Queries
	include ('templates/query_L1_md1.php');
	include ('templates/query_L2_md1.php');
/*----------------------------------------------------------------------------------------------------*/
// Consolidate computations

/*  Consolidate LOF D computations  */
include ('templates/query_lofd_summary.php');

/*  Consolidate SI computations  */
include ('templates/query_si_summary.php');

/*  Consolidate SI C computations */
include ('templates/query_sic_summary.php');

/*  Consolidate SVC D computations */
include ('templates/query_svcd_summary.php'); 
/*------------------------------------------------------------------------------------------------------------*/
// Set file name for header instruction
$filename1 = 'ROSurveyDealerExportAll.csv';

/*------------------------------------------------------------------------------------------------------------*/
// Generate MY export
$tabletitle 	= 'Model Year Distribution'				;	// Set table title
$tablehead1 	= 'Model Year,'							;	// Set first table header title
$tablehead2 	= 'Total ROs,'							;	// Set second table header title
$tablehead3 	= 'Percentage'							;	// Set third table header title

$output= "";
$table= "";
$output .= 'Data Export: ' .(Date("l F d Y"));
$output .= "\n";
$output .= "\n";
$output .= constant('MANUF')." - ".constant('ENTITY')." ". $_SESSION['dealercode']. ' (' .$_SESSION['survey_description']. ') ';
$output .= "\n";
$output .= $tabletitle; 
$output .= "\n";
$output .= "\n";
$output .= $tablehead1;
$output .= $tablehead2;
$output .= $tablehead3;
$output .="\n";

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
// Get records from second query
$output .='"'.$bucket_year.'",';
$output .='"'.$bucket[0].'",';
$output .='"'.$bucket[1].'%'.'",';
$output .="\n";

/*------------------------------------------------------------------------------------------------------------*/
// Generate MY SI export
$tabletitle 	= 'Single Issue Model Year Distribution';	// Set table title
$tablehead1 	= 'Model Year,'							;	// Set first table header title
$tablehead2 	= 'Total SI ROs,'						;	// Set second table header title
$tablehead3 	= 'Percentage'							;	// Set third table header title

include ('templates/dealer_exportall_body.php');

// Get records from first query
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
// Get records from second query
$output .='"'.$bucket_year.'",';
$output .='"'.$bucket_si[0].'",';
$output .='"'.$bucket_si[1].'%'.'",';
$output .="\n";
/*------------------------------------------------------------------------------------------------------------*/
// Generate MS export
$tabletitle 	  = 'Mileage Spread Distribution'			;	// Set table title
$tablehead1 	  = 'Mileage Spread,'						;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

include ('templates/dealer_exportall_body.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// Generate MS SI export
$tabletitle 	  = 'Single Issue Mileage Spread Distribution'	;	// Set table title
$tablehead1 	  = 'Mileage Spread,'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs,'								;	// Set second table header title
$tablehead3 	  = 'Percentage'								;	// Set third table header title

include ('templates/dealer_exportall_body.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// Generate ST export
$tabletitle 	  = 'Longhorn Distribution'					;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title

include ('templates/dealer_exportall_body.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// Generate ST SI export
$tabletitle 	  = 'Single Issue Longhorn Distribution'	;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs,'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title

include ('templates/dealer_exportall_body.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// Generate LOFD export
$tabletitle 	  = 'LOF Demand Distribution'		;	// Set table title
$tablehead1 	  = 'Category,'						;	// Set first table header title
$tablehead2 	  = 'Percentage,'					;	// Set second table header title
$tablehead3 	  = 'Total ROs'						;	// Set third table header title

include ('templates/dealer_exportall_body.php');

// Generate first line
$output .= " ROs with LOF,";
$output .= '"'.$percent_LOF. '%' . '",';
$output .= '"'.$LOFrows.'"';
$output .="\n";

// Generate second line
$output .= "SI ROs with LOF,";
$output .= '"'.$percent_SILOF. '%' . '",';
$output .= '"'.$SILOFrows.'"';
$output .="\n";

/*------------------------------------------------------------------------------------------------------------*/
// Generate LOFB export
$tabletitle 	  = 'LOF Baseline Distribution'		;	// Set table title
$tablehead1 	  = 'Average Labor,'				;	// Set first table header title
$tablehead2 	  = 'Average Parts,'				;	// Set second table header title
$tablehead3 	  = 'Avg Labor & Parts'				;	// Set third table header title

include ('templates/dealer_exportall_body.php');

$output .= '"'. '$'.$averagelabor. '",';
$output .= '"'. '$'.$averageparts. '",';
$output .= '"'. '$'.$averagepartspluslabor. '"';
$output .="\n";
/*------------------------------------------------------------------------------------------------------------*/
// Generate SI export
$tabletitle 	  = 'Single Issue Distribution'		;	// Set table title
$tablehead1 	  = 'Category,'						;	// Set first table header title
$tablehead2 	  = 'Percentage,'					;	// Set second table header title
$tablehead3 	  = 'Total ROs'						;	// Set third table header title

include ('templates/dealer_exportall_body.php');

// Generate first line
$output .= "Single Issue ROs,"; 
$output .= '"'.$percentsingle. '%' . '",';
$output .= '"'.$totalsingle.'"';
$output .="\n";

// Generate second line
$output .= "Multiple Issue ROs,";
$output .= '"'.$percentmultiple. '%' . '",';
$output .= '"'.$totalmultiple.'"';
$output .="\n";
/*------------------------------------------------------------------------------------------------------------*/
// Generate SIC export
$tabletitle 	  = 'Single Issue Category Distribution'	;	// Set table title
$tablehead1 	  = 'Category,'								;	// Set first table header title
$tablehead2 	  = 'Percentage,'							;	// Set second table header title
$tablehead3 	  = 'Total ROs'								;	// Set third table header title

include ('templates/dealer_exportall_body.php');

// Generate first line
$output .= "Level 1 Services,";
$output .= '"'.$percent_level1. '%' . '",';
$output .= '"'.$total_level1.'"';
$output .="\n";

// Generate second line
$output .= "Wear Maintenance,";
$output .= '"'.$percent_wm. '%' . '",';
$output .= '"'.$total_wm.'"';
$output .="\n";

// Generate third line
$output .= "Repair Services,";
$output .= '"'.$percent_repair. '%' . '",';
$output .= '"'.$total_repair.'"';
$output .="\n";
/*------------------------------------------------------------------------------------------------------------*/
// Generate SVC D export
$tabletitle 	  = 'Service Demand Distribution'	;	// Set table title
$tablehead1 	  = 'Category,'						;	// Set first table header title
$tablehead2 	  = 'Percentage,'					;	// Set second table header title
$tablehead3 	  = 'Total ROs'						;	// Set third table header title

include ('templates/dealer_exportall_body.php');

// Generate first line
$output .= "Level 1 Demand,";
$output .= '"'.$percent_level1_sd. '%' . '",';
$output .= '"'.$total_level1_sd.'"';
$output .="\n";

// Generate second line
$output .= "Level 2 Demand,";
$output .= '"'.$percent_level2_sd. '%' . '",';
$output .= '"'.$total_level2_sd.'"';
$output .="\n";

// Generate third line
$output .= "Full Service,";
$output .= '"'.$percent_full_sd. '%' . '",';
$output .= '"'.$total_full_sd.'"';
$output .="\n";

/*------------------------------------------------------------------------------------------------------------*/
// Generate L1 Opgap export
$tabletitle 	 = 'Level 1 Operating Gap'			;	// Set table title
$tablehead1 	 = 'L1 Service,'					;	// Set first table header title
$tablehead2 	 = 'Dealer ' .$dealercode.','		;	// Set second table header title
$tablehead3 	 = 'L1 Metric,'						;	// Set third table header title
$tablehead4		 = 'Operating Gap'					;   // Set fourth table header title

$output .="\n";
$output .="----------------------------------------------------";
include ('templates/dealer_exportbody_opgap.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// Generate L2 Opgap export
$tabletitle 	 = 'Level 2 Operating Gap'			;	// Set table title
$tablehead1 	 = 'L2 Service,'					;	// Set first table header title
$tablehead2 	 = 'Dealer ' .$dealercode.','		;	// Set second table header title
$tablehead3 	 = 'L2 Metric,'						;	// Set third table header title
$tablehead4		 = 'Operating Gap'					;   // Set fourth table header title		 

$output .="\n";
$output .="----------------------------------------------------";
include ('templates/dealer_exportbody_opgap.php');

// Setup variable results and reset $resultL1 pointer	
$resultL2->data_seek(0);

// Convert L2_value string into array
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
/*------------------------------------------------------------------------------------------------------------*/
// Generate last export line
include ('templates/dealer_exportfooter.php');
?>