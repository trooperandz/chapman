<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* -----------------------------------------------------------------------------------------------*
   Program: csvexportallcomparison.php

   Purpose: Report of mileage spread bar chart and data chart comparison
			for one dealer vs all dealers.
   History:
    Date			Description														by
	08/01/2014		Combine comparison exports into one program						Matt Holland
	09/25/2014		Rewrote to include new titles and Linux compatibility (isset)	Matt Holland
	12/03/2014		Rewrote to include query and body standard includes				Matt Holland
	01/14/2015		Changed service demand variables to adjust for revamped
					SD queries														Matt Holland
	01/22/2015		Added YM Single Issue report									Matt Holland
	01/22/2015		Added MS Single Issue report									Matt Holland
	01/23/2015		Added ST Single Issue report									Matt Holland
*-------------------------------------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];	// Dealercode set at login
$dealerID 	= $_SESSION['dealerID'];  	// Dealer ID set at login
$userID		= $user->userID;			// Initialize user

/*------------------------------------------------------------------------------------------------------------*/
// Set $comparisonsurveyindex_id variable for queries
if (isset($_SESSION['comparisonsurveyindex_id'])) {
	$surveyindex_id = $_SESSION['comparisonsurveyindex_id'];
} else {
	$_SESSION['error'][] = "$surveyindex_id variable is not set. See administrator.";
	die(header("Location: enterrofoundation.php"));
}

// Set $comparisonsurvey_description variable for queries
if (isset($_SESSION['comparisonsurvey_description'])) {
	$comparisonsurvey_description = $_SESSION['comparisonsurvey_description'];
} else {
	$_SESSION['error'][] = "$comparisonsurvey_description variable is not set. See administrator.";
	die(header("Location: enterrofoundation.php"));
}

/*------------------------------------------------------------------------------------------------*/
// Service categories processing
include ('templates/set_reporttype_comparison.php');
include ('templates/st_string_processing.php');

/*------------------------------------------------------------------------------------------------*/
// SI Category string processing
include ('templates/sicategory_string.php');

/*------------------------------------------------------------------------------------------------*/
// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');

/*------------------------------------------------------------------------------------------------------------*/
// Query set 1
if((isset($_SESSION['comparedealer1IDs']) 		&& isset($_SESSION['comparedealer2IDs'])) 		OR 
   (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR
   (isset($_SESSION['compareregionIDs1']) 		&& isset($_SESSION['compareregionIDs2']))) {
	if (isset($_SESSION['comparedealer1IDs']) && isset($_SESSION['comparedealer2IDs'])) {
		$dealerIDs1 = $_SESSION['comparedealer1IDs']; // Initialize all globals to be used in queries
		$dealerIDs2 = $_SESSION['comparedealer2IDs'];
	} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
		$dealerIDs1 = $_SESSION['comparedealerregion1IDs'];
		$dealerIDs2 = $_SESSION['compareregiondealerIDs1'];
	} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {
		$dealerIDs1 = $_SESSION['compareregionIDs1'];
		$dealerIDs2 = $_SESSION['compareregionIDs2'];
	}
/*-----------------------------------------------------*/
// Model Age Queries
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	include ('templates/query_totalros_si_md1.php');

	//  Query for first set dealer Model Age	
	include ('templates/query_ym_md1.php');
	include ('templates/query_ym_si_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_md2.php');
	include ('templates/query_totalros_si_md2.php');

	//  Query for second set dealer Model Age	
	include ('templates/query_ym_md2.php');
	include ('templates/query_ym_si_md2.php');
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');
	include ('templates/query_ms_si_md1.php');

	//  Query for second set dealer mileage spread	
	include ('templates/query_ms_md2.php');
	include ('templates/query_ms_si_md2.php');
/*-----------------------------------------------------*/
// Longhorn Queries	
	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');	
	include ('templates/query_st_si_md1.php');	

	//  Query for second set dealer Longhorn	
	include ('templates/query_st_md2.php');	
	include ('templates/query_st_si_md2.php');
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set dealer LOF data
	include ('templates/query_lofd_md2.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	//  Query for second LOF Baseline set
	include ('templates/query_lofb_md2.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_md2.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_md2.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');

	// Queries set 2
	include ('templates/query_svcd_md2.php');
/*----------------------------------------------------------------------------------------------------------*/
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
/*-----------------------------------------------------*/
// Model Age Queries
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	include ('templates/query_totalros_si_md1.php');

	//  Query for first set dealer Model Age	
	include ('templates/query_ym_md1.php');
	include ('templates/query_ym_si_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_global.php');
	include ('templates/query_totalros_si_global.php');

	//  Query for second set dealer Model Age	
	include ('templates/query_ym_global.php');
	include ('templates/query_ym_si_global.php');
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');
	include ('templates/query_ms_si_md1.php');	

	//  Query for second set dealer mileage spread	
	include ('templates/query_ms_global.php');
	include ('templates/query_ms_si_global.php');
/*-----------------------------------------------------*/
// Longhorn Queries	
	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');	
	include ('templates/query_st_si_md1.php');	

	//  Query for second set dealer Longhorn	
	include ('templates/query_st_global.php');	
	include ('templates/query_st_si_global.php');	
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set dealer LOF data
	include ('templates/query_lofd_global.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	//  Query for second LOF Baseline set
	include ('templates/query_lofb_global.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_global.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_global.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');

	// Queries set 2
	include ('templates/query_svcd_global.php');
/*----------------------------------------------------------------------------------------------------------*/
} else {

// Swap $dealerID variable
$dealerIDs1 = $dealerID;
/*-----------------------------------------------------*/
// Model Age Queries
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	include ('templates/query_totalros_si_md1.php');

	//  Query for first set dealer Model Age	
	include ('templates/query_ym_md1.php');
	include ('templates/query_ym_si_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_global.php');
	include ('templates/query_totalros_si_global.php');

	//  Query for second set dealer Model Age	
	include ('templates/query_ym_global.php');
	include ('templates/query_ym_si_global.php');
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');	
	include ('templates/query_ms_si_md1.php');	

	//  Query for second set dealer mileage spread	
	include ('templates/query_ms_global.php');
	include ('templates/query_ms_si_global.php');
/*-----------------------------------------------------*/
// Longhorn Queries	
	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');	
	include ('templates/query_st_si_md1.php');	

	//  Query for second set dealer Longhorn	
	include ('templates/query_st_global.php');	
	include ('templates/query_st_si_global.php');	
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set dealer LOF data
	include ('templates/query_lofd_global.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	//  Query for second LOF Baseline set
	include ('templates/query_lofb_global.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_global.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_global.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');

	// Queries set 2
	include ('templates/query_svcd_global.php');
	
} // End default else statement
/*----------------------------------------------------------------------------------------------------------*/
// Consolidate computations

/*  Consolidate LOF D computations  */
include ('templates/query_lofd_summary.php');
include ('templates/query_lofd_summary2.php');

/*  Consolidate SI computations  */
include ('templates/query_si_summary.php');
include ('templates/query_si_summary2.php');

/*  Consolidate SI C computations */
include ('templates/query_sic_summary.php');
include ('templates/query_sic_summary2.php');

/*  Consolidate SVC D computations */
include ('templates/query_svcd_summary.php');
include ('templates/query_svcd_summary2.php');

/*------------------------------------------------------------------------------------------------------------*/
// Set file name for header instruction
$filename1 = 'ROSurveyComparisonExportAll.csv';

/*------------------------------------------------------------------------------------------------------------*/
// Get total count of dealers in repairorder per survey type for comparison reports
$query = "SELECT DISTINCT dealerID FROM repairorder WHERE surveyindex_id IN($surveyindex_id)";
$total_dealers_result = $mysqli->query($query);
if (!$total_dealers_result) {
	$_SESSION['error'][] = "Failed to retrieve total dealer count.  repairorder SELECT query failed.  See administrator.";
}
$rows = $total_dealers_result->num_rows;
$totaldealers_persurvey = $rows;

/*------------------------------------------------------------------------------------------------------------*/
// MY export
$tabletitle 	  = 'Model Age Distribution'				;	// Set table title
$tablehead1 	  = 'Model Age,'							;	// Set first table header title (rest are defined in comparison_exportbody.php)

// Set 1st output body (rest are includes)
$output= "";
$output .= "Data Export: " .Date("l F d Y");
include ('templates/comparison_exportall_body.php');

//Get records from table
while ($row1 = $resultmy->fetch_array()) {
	$row2 = $resultmy2->fetch_array();
	$output .= '"'.$row1[0].'",';
	$output .= '"'.$row1[2]. '%",';
	$output .= '"'.$row2[2]. '%"';
	$output .= "\n";
}
/*------------------------------------------------------------------------------------------------------------*/
// MY SI export
$tabletitle 	  = 'Single Issue Model Age Distribution'	;	// Set table title
$tablehead1 	  = 'Model Age,'							;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

//Get records from table
while ($row1 = $resultmy_si->fetch_array()) {
	$row2 = $resultmy2_si->fetch_array();
	$output .= '"'.$row1[0].'",';
	$output .= '"'.$row1[2]. '%",';
	$output .= '"'.$row2[2]. '%"';
	$output .= "\n";
}
/*------------------------------------------------------------------------------------------------------------*/
// MS export
$tabletitle 	  = 'Mileage Spread Distribution'				;	// Set table title
$tablehead1 	  = 'Mileage Spread,'							;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

//Get records from table
while ($row1 = $resultms->fetch_array()) {
	$row2 = $resultms2->fetch_array();
	$output .= '"'.$row1[0].'",';
	$output .= '"'.$row1[2]. '%",';
	$output .= '"'.$row2[2]. '%"';
	$output .= "\n";
}
/*------------------------------------------------------------------------------------------------------------*/
// MS SI export
$tabletitle 	  = 'Single Issue Mileage Spread Distribution'	;	// Set table title
$tablehead1 	  = 'Mileage Spread,'							;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

//Get records from table
while ($row1 = $resultms_si->fetch_array()) {
	$row2 = $resultms2_si->fetch_array();
	$output .= '"'.$row1[0].'",';
	$output .= '"'.$row1[2]. '%",';
	$output .= '"'.$row2[2]. '%"';
	$output .= "\n";
}
/*------------------------------------------------------------------------------------------------------------*/
// ST export
$tabletitle 	  = 'Longhorn Distribution'					;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

//Get records from table
while ($row1 = $resultst->fetch_array()) {
	$row2 = $resultst2->fetch_array();
	$output .= '"'.$row1[0].'",';
	$output .= '"'.$row1[2]. '%",';
	$output .= '"'.$row2[2]. '%"';
	$output .= "\n";
}
/*------------------------------------------------------------------------------------------------------------*/
// ST SI export
$tabletitle 	  = 'Single Issue Longhorn Distribution'	;	// Set table title
$tablehead1 	  = 'Service Type,'							;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

//Get records from table
while ($row1 = $resultst_si->fetch_array()) {
	$row2 = $resultst2_si->fetch_array();
	$output .= '"'.$row1[0].'",';
	$output .= '"'.$row1[2]. '%",';
	$output .= '"'.$row2[2]. '%"';
	$output .= "\n";
}
/*------------------------------------------------------------------------------------------------------------*/
// LOF D export
// Set report variables for includes
$tabletitle 	  = 'LOF Demand Distribution'			;	// Set table title
$tablehead1 	  = 'Category,'							;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

// Generate first line
$output .= "ROs with LOF,";
$output .= '"'.$percent_LOF. '%' . '",';
$output .= '"'.$percent_LOF2. '%' . '"';
$output .="\n";

// Generate second line
$output .= "SI ROs with LOF,";
$output .= '"'.$percent_SILOF. '%' . '",';
$output .= '"'.$percent_SILOF2. '%' . '"';
$output .="\n";

/*------------------------------------------------------------------------------------------------------------*/
// LOF B export
$tabletitle 	  = 'LOF Baseline Distribution'					;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

// Generate first line
$output .= "Average Labor,";
$output .= '"'. '$'.$averagelabor. '",';
$output .= '"'. '$'.$averagelabor2. '"';
$output .="\n";

// Generate second line
$output .= "Average Parts,";
$output .= '"'. '$'.$averageparts. '",';
$output .= '"'. '$'.$averageparts2. '"';
$output .="\n";

// Generate third line
$output .= "Avg Labor & Parts,";
$output .= '"'. '$'.$averagepartspluslabor. '",';
$output .= '"'. '$'.$averagepartspluslabor2. '"';
$output .="\n";
/*------------------------------------------------------------------------------------------------------------*/
// SI export
$tabletitle 	  = 'Single Issue Distribution'					;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

// Generate first line
$output .= "Single Issue ROs,";
$output .= '"'.$percentsingle. '%' . '",';
$output .= '"'.$percentsingle2. '%' . '"';
$output .="\n";

// Generate second line
$output .= "Multiple Issue ROs,";
$output .= '"'.$percentmultiple. '%' . '",';
$output .= '"'.$percentmultiple2. '%' . '"';
$output .="\n";
/*------------------------------------------------------------------------------------------------------------*/
// SI C export
$tabletitle 	  = 'Single Issue Category Distribution'		;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

// Generate first line
$output .= "Level 1 Services,";
$output .= '"'.$percent_level1. '%' . '",';
$output .= '"'.$percent_level12. '%' . '"';
$output .="\n";

// Generate second line
$output .= "Wear Maintenance,";
$output .= '"'.$percent_wm. '%' . '",';
$output .= '"'.$percent_wm2. '%' . '"';
$output .="\n";

// Generate third line
$output .= "Repair Services,";
$output .= '"'.$percent_repair. '%' . '",';
$output .= '"'.$percent_repair2. '%' . '"';
$output .="\n";
/*------------------------------------------------------------------------------------------------------------*/
// Svc D export
$tabletitle 	  = 'Service Demand Distribution'				;	// Set table title
$tablehead1 	  = 'Category,'									;	// Set first table header title (rest are defined in comparison_exportbody.php)

include ('templates/comparison_exportall_body.php');

// Generate first line
$output .= "Level 1 Demand,";
$output .= '"'.$percent_level1_sd. '%' . '",';
$output .= '"'.$percent_level1_sd2. '%' . '"';
$output .="\n";

// Generate second line
$output .= "Level 2 Demand,";
$output .= '"'.$percent_level2_sd. '%' . '",';
$output .= '"'.$percent_level2_sd2. '%' . '"';
$output .="\n";

// Generate third line
$output .= "Full Service,";
$output .= '"'.$percent_full_sd. '%' . '",';
$output .= '"'.$percent_full_sd2. '%' . '"';
$output .="\n";
/*------------------------------------------------------------------------------------------------------------*/
// Generate last export line
include ('templates/dealer_exportfooter.php');
?>