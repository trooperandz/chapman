<?php 
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: lofb_g_p.php

   Purpose: Report of single and multiple issue counts
   History:
    Date		Description									by
	08/06/2014	Convert to mysqli							Matt Holland
	12/01/2014	Rewrote to include standard query includes	Matt Holland

 *-----------------------------------------------------------------------*/
 
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
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	//  Query for second LOF Baseline set
	include ('templates/query_lofb_md2.php');
/*--------------------------------------------------------Single Dealer queries------------------------------------------------------*/	
// Check to see if single dealer variables are set
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	// Query for second LOF Baseline set
	include ('templates/query_lofb_global.php');
/*------------------------------------------------Default query set-------------------------------------------*/
} else {
	// Swap $dealerID variable
	$dealerIDs1 = $dealerID;
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	// Query for second LOF Baseline set
	include ('templates/query_lofb_global.php');
} // End else statement
/*-------------------------------------Build Google chart------------------------------------------*/
include ('templates/chart_comparison_array.php');

$rows = array();
$temp = array();
/*  Generate array elements for average labor  */
$temp[] = array('v' =>  'Average Labor');
$temp[] = array('v' => (float)number_format($averagelabor,2));
$temp[] = array('v' => (float)number_format($averagelabor2,2));
$rows[] = array('c' => $temp);
/*  Generate array elements for average parts  */	
$temp = array();
$temp[] = array('v' =>  'Average Parts');
$temp[] = array('v' => (float)number_format($averageparts,2));
$temp[] = array('v' => (float)number_format($averageparts2,2));
$rows[] = array('c' => $temp);
/*  Generate array elements for average parts+labor */
$temp = array();
$temp[] = array('v' =>  'Average Parts & Labor');
$temp[] = array('v' => (float)number_format($averagepartspluslabor,2));
$temp[] = array('v' => (float)number_format($averagepartspluslabor2,2));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/header_printreports.php')		;
include ('templates/columnchart_comparison.php')	;
include ('templates/printview_comparisonbody.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Average Labor					    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averagelabor,2).  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averagelabor2,2). '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Average Parts 					    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averageparts,2).  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averageparts2,2). '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Avg Labor & Parts				 		    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averagepartspluslabor,2). '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averagepartspluslabor2,2).'</td>
						</tr>';
include ('templates/footer_printview.php');
?>