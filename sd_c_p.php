<?php 
/* ----------------------------------------------------------------------------------*
   Program: demand1and2queryprintview.php

   Purpose: Report of demand 1 & 2
   History:
    Date		Description									            by
	08/07/2014	Convert to mysqli										Matt Holland
	11/03/2014	Add survey type processing ($comparisonsurveyindex_id)	Matt Holland
				for report
    11/17/2014	Incorporate new template includes format				Matt Holland
	12/01/2014	Rewrote with standard query included					Matt Holland
	12/17/2014	Updated chart variables and standardized more includes	Matt Holland
	01/14/2015	Updated Service Demand portion - changed to column chartMatt Holland
 *-----------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');	
include('templates/db_cxn.php');

// Initialize default variables
$dealerID = $_SESSION['dealerID']; // Initialize $dealerID variable
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable

// Include page / chart variable settings
include ('templates/chart_specs.php');

/*----Set survey variables and globals for report with include-------*
$surveyindex_id 				= $_SESSION['comparisonsurveyindex_id'] 
$comparisonsurvey_description 	= $_SESSION['comparisonsurvey_description'] 
$_SESSION['lastpagecomparisonreports']	 // Returns processes to last page
$totaldealers_persurvey 			 	 // Total dealer count per survey
---------------------------------------------------------------------*/
include('templates/setsurveyglobals_comparisonreports.php');

// Generate serviceID strings for queries
include ('templates/sd_strings.php');

/*--------------------------------------------------Multidealer Queries-----------------------------------------------------*/
// Check to see if multidealer variables are set
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
	// To compute the total # rows for denominator, set 1
	include ('templates/query_totalros_md1.php');
	// Queries set 1
	include ('templates/query_svcd_md1.php');
	
	// To compute the total # rows for denominator, set 2
	include ('templates/query_totalros_md2.php');
	// Queries set 1
	include ('templates/query_svcd_md2.php');

/*--------------------------------------------------------Single Dealer queries------------------------------------------------------*/	
// Check to see if single dealer variables are set
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// To compute the total # rows for denominator, set 1
	include ('templates/query_totalros_md1.php');
	// Queries set 1
	include ('templates/query_svcd_md1.php');
	
	// To compute the total # rows for denominator, set 2
	include ('templates/query_totalros_global.php');
	// Queries set 1
	include ('templates/query_svcd_global.php');

/*------------------------------------------------------------Default query set--------------------------------------------------------*/
// If no globals are set, run default queries
} else {
	// Swap $dealerID variable
	$dealerIDs1 = $dealerID;
	// To compute the total # rows for denominator, set 1
	include ('templates/query_totalros_md1.php');
	// Queries set 1
	include ('templates/query_svcd_md1.php');
	
	// To compute the total # rows for denominator, set 2
	include ('templates/query_totalros_global.php');
	// Queries set 1
	include ('templates/query_svcd_global.php');
	
} // End else statement

// Summary set 1
include ('templates/query_svcd_summary.php');
// Summary set 2
include ('templates/query_svcd_summary2.php');

/*-------------------------------------Build Google chart------------------------------------------*/
include ('templates/chart_comparison_array.php');

/*  Generate array elements for Level 1 Demand  */
$rows = array();
$temp = array();
$temp[] = array('v' =>  'Level 1 Demand');
$temp[] = array('v' => number_format($percent_level1_sd,0));
$temp[] = array('v' => number_format($percent_level1_sd2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for Level 2 Demand  */	
$temp = array();
$temp[] = array('v' =>  'Level 2 Demand'); 
$temp[] = array('v' => number_format($percent_level2_sd,0));
$temp[] = array('v' => number_format($percent_level2_sd2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for other portion  */
$temp = array();
$temp[] = array('v' =>  'Full Service'); 
$temp[] = array('v' => number_format($percent_full_sd,0));
$temp[] = array('v' => number_format($percent_full_sd2,0));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/header_printreports.php')		;
include ('templates/columnchart_comparison.php')	;
include ('templates/printview_comparisonbody.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Level 1 Demand		    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level1_sd.'%'.   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level1_sd2.'%'.  '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Level 2 Demand 	    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level2_sd.'%'.   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level2_sd2.'%'.  '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Full Service		    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_full_sd.'%'.	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_full_sd2.'%'.    '</td>
						</tr>';
include ('templates/footer_printview.php');
?>