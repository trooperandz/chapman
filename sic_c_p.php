<?php 
require_once("functions.inc");
include ('templates/login_check.php');

/* ---------------------------------------------------------------------------------*
   Program: singleissuecategoryprintviewcomparison.php

   Purpose: Report of single issue categories
   History:
    Date		Description												by
	08/07/2014	Convert to mysqli										Matt Holland
	10/22/2014	Add service strings to queries							Matt Holland
	11/03/2014	Add survey type processing ($comparisonsurveyindex_id)	Matt Holland
				for report
    11/17/2014	Incorporate new template includes format				Matt Holland
	12/01/2014	Rewrote to include standard query include				Matt Holland

 *----------------------------------------------------------------------------------*/
 
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

/*----------------------------------------------------------------------------------------------------*/
// SI Category string processing
include('templates/sicategory_string.php');


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
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_md2.php');

/*--------------------------------------------------------Single Dealer queries------------------------------------------------------*/	
// Check to see if single dealer variables are set
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_global.php');

/*------------------------------------------------------------Default query set--------------------------------------------------------*/
// If no globals are set, run default queries
} else {
	// Swap $dealerID variable
	$dealerIDs1 = $dealerID;
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_global.php');
} // End else statement
	
// Summary set 1
include ('templates/query_sic_summary.php');
// Summary set 2
include ('templates/query_sic_summary2.php');


/*-------------------------------------Build Google chart------------------------------------------*/
include ('templates/chart_comparison_array.php');

$rows = array();
$temp = array();
/*  Generate array elements for Level 1 Services  */
$temp[] = array('v' =>  'Level 1 Services');
$temp[] = array('v' => number_format($percent_level1,0));
$temp[] = array('v' => number_format($percent_level12,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for total wear maintenance */
$temp = array();
$temp[] = array('v' =>  'Wear Maintenance');
$temp[] = array('v' => number_format($percent_wm,0));
$temp[] = array('v' => number_format($percent_wm2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for total repair */
$temp = array();
$temp[] = array('v' =>  'Repair Services');
$temp[] = array('v' => number_format($percent_repair,0));
$temp[] = array('v' => number_format($percent_repair2,0));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/header_printreports.php')		;
include ('templates/columnchart_comparison.php')	;
include ('templates/printview_comparisonbody.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    Level 1 Services	   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level1.'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level12.'%'.'</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Wear Maintenance 	 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_wm.'%'. 		'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_wm2.'%'. 		'</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Repair Services	 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_repair.'%'.	'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_repair2.'%'.  '</td>
						</tr>';
include ('templates/footer_printview.php');						
?>