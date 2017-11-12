<?php
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: csvexportallglobal.php

   Purpose: Export model year global data
   History:
    Date			Description												by
	07/01/2014		Initial design and coding								Matt Holland
	11/24/2014		Revised with template includes and query includes		Matt Holland
	12/01/2014		Added variable resets for global queries				Matt Holland
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
$userID		= $user->userID;			// Initialize user
$errorfile	= 'csvexportallglobalreworked.php:';

/*------------------------------------------------------------------------------------------------------------*/
// Set $surveyindex_id variable for queries
if (isset($_SESSION['globalsurveyindex_id'])) {
	$surveyindex_id = $_SESSION['globalsurveyindex_id'];
} else {
	$_SESSION['error'][] = $errorfile. " Line 29 $surveyindex_id is not set.  See administrator.";
	die(header("Location: enterrofoundation.php"));
}

// Set $globalsurvey_description variable for queries
if (isset($_SESSION['globalsurvey_description'])) {
	$globalsurvey_description = $_SESSION['globalsurvey_description'];
} else {
	$_SESSION['error'][] = $errorfile. "$globalsurvey_description variable is not set. See administrator.";
	die(header("Location: enterrofoundation.php"));
}

/*------------------------------------------------------------------------------------------------*/
// Service categories processing
include ('templates/set_reporttype_global.php');
include ('templates/st_string_processing.php');

// Start of multidealer queries	
if (isset($_SESSION['multidealer'])) {
	$multidealerIDs = $_SESSION['multidealer'];
}
/*------------------------------------------------------------------------------------------------*/
// Set service string for SI Category queries
include('templates/sicategory_string.php');	

/*------------------------------------------------------------------------------------------------*/
// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');

/*------------------------------------------------------------------------------------------------*/
// Multidealer queries

if (isset($_SESSION['multidealer']) OR isset($_SESSION['regiondealerIDs'])) {
	if (isset($_SESSION['multidealer'])) {
		// Query for total dealer rows
		$dealerIDs1 = $_SESSION['multidealer'];
	} elseif (isset($_SESSION['regiondealerIDs'])) {
		// Query for total dealer rows
		$dealerIDs1 = $_SESSION['regiondealerIDs'];
	}
	/*----------------------------------------------------*/
	// Total ROs (multidealer)
	include ('templates/query_totalros_md1.php');
	// Total SI ROs (multidealer)
	include ('templates/query_totalros_si_md1.php');
	/*----------------------------------------------------*/
	// YM	
	include ('templates/query_ym_md1.php');
	include ('templates/query_ym_si_md1.php');
	/*----------------------------------------------------*/	
	// MS
	include ('templates/query_ms_md1.php');
	include ('templates/query_ms_si_md1.php');
	/*----------------------------------------------------*/	
	// ST
	include ('templates/query_st_md1.php');
	include ('templates/query_st_si_md1.php');
	/*----------------------------------------------------*/	
	// LOF D
	include ('templates/query_lofd_md1.php');
	include ('templates/query_lofd_summary.php');
	/*----------------------------------------------------*/	
	// LOF B
	include ('templates/query_lofb_md1.php');
	/*----------------------------------------------------*/	
	// SI
	include ('templates/query_si_md1.php');
	include ('templates/query_si_summary.php');
	/*----------------------------------------------------*/	
	// SI C
	include ('templates/query_sic_md1.php');
	include ('templates/query_sic_summary.php');
	/*----------------------------------------------------*/	
	// SVC D
	include ('templates/query_svcd_md1.php');
	include ('templates/query_svcd_summary.php');
	/*----------------------------------------------------*/	
	// L1&L2 OpGap
	include ('templates/query_L1_md1.php');
	include ('templates/query_L2_md1.php');

/*------------------------------------------------------------------------------------------------------------*/	
// Global queries
} else {
	/*----------------------------------------------------*/
	// Total ROs (global)
	include ('templates/query_totalros_global.php');
	// Total SI ROs (global)
	include ('templates/query_totalros_si_global.php');
	/*----------------------------------------------------*/	
	// YM
	include ('templates/query_ym_global.php');
	include ('templates/query_ym_si_global.php');
	// Reset variable for use below:
	$resultmy 	 = $resultmy2;
	$resultmy_si = $resultmy2_si;
	/*----------------------------------------------------*/	
	// MS
	include ('templates/query_ms_global.php');
	include ('templates/query_ms_si_global.php');
	// Reset variable for use below:
	$resultms 	 = $resultms2;
	$resultms_si = $resultms2_si;
	/*----------------------------------------------------*/	
	// ST
	include ('templates/query_st_global.php');
	include ('templates/query_st_si_global.php');
	// Reset variable for use below:
	$resultst 	 = $resultst2;
	$resultst_si = $resultst2_si;
	/*----------------------------------------------------*/	
	// LOF D
	include ('templates/query_lofd_global.php') ;
	// Reset variables for use below:
	$totalros  				= $totalros2	    	;
	$totalros_si			= $totalros2_si			;
	$LOFrows 				= $LOFrows2				;
	$SILOFrows 				= $SILOFrows2			;
	$percent_LOF			= $percent_LOF2	  		;
	$percent_SILOF			= $percent_SILOF2	  	;
	include ('templates/query_lofd_summary.php');
	/*----------------------------------------------------*/	
	// LOF B
	include ('templates/query_lofb_global.php');
	// Reset variables for use below:
	$averagelabor 			= $averagelabor2			;
	$averageparts 			= $averageparts2			;
	$averagepartspluslabor 	= $averagepartspluslabor2	;
	/*----------------------------------------------------*/	
	// SI
	include ('templates/query_si_global.php');
	// Reset variables for use below:
	$totalsingle 			= $totalsingle2				;
	$totalmultiple			= $totalmultiple2			;
	$percentsingle			= $percentsingle2			;
	$percentmultiple		= $percentmultiple2 		;
	include ('templates/query_si_summary.php');
	/*----------------------------------------------------*/	
	// SI C
	include ('templates/query_sic_global.php');
	// Reset variables for use below:
	$total_level1 			= $total_level12			;
	$total_wm	  			= $total_wm2				;
	$total_repair 			= $total_repair2  			;
	$percent_level1 		= $percent_level12			;
	$percent_wm				= $percent_wm2				;
	$percent_repair 		= $percent_repair2  		;
	include ('templates/query_sic_summary.php');
	/*----------------------------------------------------*/	
	// SVC D
	include ('templates/query_svcd_global.php');
	// Reset variables for use below:
	$total_level1a 			= $total_level1a2			;
	$total_level1b 			= $total_level1b2			;
	$total_level2a 			= $total_level2a2			;
	$total_level2b 			= $total_level2b2			;
	$total_full_L1a			= $total_full_L1a2			;
	$total_full_L1b			= $total_full_L1b2			;
	$total_full_L3 			= $total_full_L32			;
	$total_full_sd 			= $total_full_sd2			;
	$totalros 	   			= $totalros2     			;
	include ('templates/query_svcd_summary.php');
	/*----------------------------------------------------*/
	// L1&L2 OpGap
	include ('templates/query_L1_global.php');
	include ('templates/query_L2_global.php');
}
/*------------------------------------------------------------------------------------------------------------*/
// Get total count of dealers in repairorder per survey type for global reports
$query = "SELECT DISTINCT dealerID FROM repairorder WHERE surveyindex_id IN($surveyindex_id)";
$total_dealers_result = $mysqli->query($query);
if (!$total_dealers_result) {
	$_SESSION['error'][] = $errorfile. "Failed to retrieve total dealer count.  repairorder SELECT query failed.  See administrator.";
}
$totaldealers_persurvey = $total_dealers_result->num_rows;

// Manage Survey Type name processing (required for 'All Survey Types' heading)
$globalsurvey_description = $_SESSION['globalsurvey_description'];
if ($_SESSION['globalsurveyindexid_rows'] > 1) {
	$globalsurvey_description = 'All Survey Type';
}
/*------------------------------------------------------------------------------------------------------------*/
// Set file name for header instruction
$filename1 = 'ROSurveyGlobalExportAll.csv';

/*------------------------------------------------------------------------------------------------------------*/
// MY
$tabletitle 	  = 'Model Age Distribution'				;	// Set table title
$tablehead1 	  = 'Model Age,'							;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Set 1st output body (rest are includes)
$output= "";
$output .= "Data Export: " .Date("l F d Y");

include ('templates/global_exportall_body.php');

//Get records from table
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
/*------------------------------------------------------------------------------------------------------------*/
// MY SI
$tabletitle 	  = 'Single Issue Model Age Distribution'	;	// Set table title
$tablehead1 	  = 'Model Age,'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs,'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

include ('templates/global_exportall_body.php');

//Get records from table
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
/*------------------------------------------------------------------------------------------------------------*/
// MS
$tabletitle 	  = 'Mileage Spread Distribution'			;	// Set table title
$tablehead1 	  = 'Mileage Spread,'						;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

include ('templates/global_exportall_body.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// MS SI
$tabletitle 	  = 'Single Issue Mileage Spread Distribution'	;	// Set table title
$tablehead1 	  = 'Mileage Spread,'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs,'								;	// Set second table header title
$tablehead3 	  = 'Percentage'								;	// Set third table header title

include ('templates/global_exportall_body.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// ST
$tabletitle 	  = 'Longhorn Distribution'					;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title

include ('templates/global_exportall_body.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// ST SI
$tabletitle 	  = 'Single Issue Longhorn Distribution'	;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs,'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title

include ('templates/global_exportall_body.php');

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
/*------------------------------------------------------------------------------------------------------------*/
// LOF D
$tabletitle 	  = 'LOF Demand Distribution'				;	// Set table title
$tablehead1 	  = 'Category,'								;	// Set first table header title
$tablehead2 	  = 'Percentage,'							;	// Set second table header title
$tablehead3 	  = 'Total ROs'								;	// Set third table header title

include ('templates/global_exportall_body.php');

// Generate first line
$output .= "ROs with LOF,";
$output .= '"'.$percent_LOF. '%' . '",';
$output .= '"'.$LOFrows.'"';
$output .="\n";

// Generate second line
$output .= "SI ROs with LOF,";
$output .= '"'.$percent_SILOF. '%' . '",';
$output .= '"'.$SILOFrows.'"';
$output .="\n";

/*------------------------------------------------------------------------------------------------------------*/
// LOF B
$tabletitle 	  = 'LOF Baseline Distribution'				;	// Set table title
$tablehead1 	  = 'Average Labor,'						;	// Set first table header title
$tablehead2 	  = 'Average Parts,'						;	// Set second table header title
$tablehead3 	  = 'Avg Labor & Parts'						;	// Set third table header title

include ('templates/global_exportall_body.php');

// Generate first line
$output .= '"' . '$' .$averagelabor. '",';
$output .= '"' . '$' .$averageparts. '",';
$output .= '"' . '$' .$averagepartspluslabor. '",';
$output .="\n";

/*------------------------------------------------------------------------------------------------------------*/
// SI
$tabletitle 	  = 'Single Issue Distribution'				;	// Set table title
$tablehead1 	  = 'Category,'								;	// Set first table header title
$tablehead2 	  = 'Percentage,'							;	// Set second table header title
$tablehead3 	  = 'Total ROs'								;	// Set third table header title

include ('templates/global_exportall_body.php');

// Generate first line
$output .= " Single Issue ROs ,";
$output .= '"' .$percentsingle. '%'. '",';
$output .= '"' .$totalsingle. '",';
$output .="\n";
	
// Generate second line
$output .= " Multiple Issue ROs ,";
$output .= '"' .$percentmultiple. '%'. '",';
$output .= '"' .$totalmultiple. '",';
$output .="\n";

/*------------------------------------------------------------------------------------------------------------*/
// SI C
$tabletitle 	  = 'Single Issue Category Distribution'		;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title
$tablehead2 	  = 'Percentage,'								;	// Set second table header title
$tablehead3 	  = 'Total ROs'									;	// Set third table header title

include ('templates/global_exportall_body.php');

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

/*------------------------------------------------------------------------------------------------------------*/
// SVC D
$tabletitle 	  = 'Service Demand Distribution'			;	// Set table title
$tablehead1 	  = 'Category,'								;	// Set first table header title
$tablehead2 	  = 'Percentage,'							;	// Set second table header title
$tablehead3 	  = 'Total ROs'								;	// Set third table header title

include ('templates/global_exportall_body.php');

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
// L1 OpGap
$tabletitle 	 = 'Level 1 Operating Gap'			;	// Set table title
$tablehead1 	 = 'L1 Service,'					;	// Set first table header title
$tablehead3 	 = 'L1 Metric,'						;	// Set third table header title
$tablehead4		 = 'Operating Gap'					;   // Set fourth table header title
//$tablehead2 is set in global_exportbody_opgap.php

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
	   $row2= $resultL1->fetch_array();
		$output .='"'.$row[0].'",';
		$output .='"'.$row[2].'%'.'",';
		$output .='"'.$L1value[$index].'%'.'",';
		$output .='"'.($row[2] - $L1value[$index]).'%'.'",';
		$output .="\n";
		$index += 1;
}
/*------------------------------------------------------------------------------------------------------------*/
// L2 OpGap
$tabletitle 	 = 'Level 2 Operating Gap'			;	// Set table title
$tablehead1 	 = 'L2 Service,'					;	// Set first table header title
$tablehead3 	 = 'L2 Metric,'						;	// Set third table header title
$tablehead4		 = 'Operating Gap'					;   // Set fourth table header title
//$tablehead2 is set in global_exportbody_opgap.php

// Build export
$output .="\n";
$output .="----------------------------------------------------------------------------";
include ('templates/global_exportbody_opgap.php');

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
	   $row2= $resultL2->fetch_array();
		$output .='"'.$row[0].'",';
		$output .='"'.$row[2].'%'.'",';
		$output .='"'.$L2value[$index].'%'.'",';
		$output .='"'.($row[2] - $L2value[$index]).'%'.'",';
		$output .="\n";
		$index += 1;
}
/*-----------------------------------------------------------------------*/
// Generate last export line
include ('templates/exportfooter.php');
?>