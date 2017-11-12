<?php 
require_once("functions.inc");
include ('templates/login_check.php');

/* ---------------------------------------------------------------------------------*
   Program: singleissuequeryprintviewcomparison.php

   Purpose: Report of single and multiple issue counts
   History:
    Date		Description												by
	08/06/2014	Convert to mysqli										Matt Holland
	11/03/2014	Add survey type processing ($comparisonsurveyindex_id)	Matt Holland
				for report
    11/17/2014	Incorporate new template includes format				Matt Holland
	12/17/2014	Updated chart variables and standardized 
				more includes											Matt Holland
 *----------------------------------------------------------------------------------*/

include('templates/db_cxn.php');
// Initialize default variables
$dealerID = $_SESSION['dealerID']; // Initialize $dealerID variable
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable

// Include page / chart variable settings
include ('templates/chart_specs.php');

/*----Set survey variables and globals for report with include-------*
$comparisonsurveyindex_id 		= $_SESSION['comparisonsurveyindex_id'] 
$comparisonsurvey_description 	= $_SESSION['comparisonsurvey_description'] 
$_SESSION['lastpagecomparisonreports']	 // Returns processes to last page
$totaldealers_persurvey 			 // Total dealer count per survey
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
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_md2.php');
	
/*--------------------------------------------------------Single Dealer queries------------------------------------------------------*/	
// Check to see if single dealer variables are set
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_global.php');
	
/*------------------------------------------------Default query set-------------------------------------------*/
} else {
	// Swap $dealerID variable
	$dealerIDs1 = $dealerID;
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_global.php');
}
/*  Consolidate computations first set  */
include ('templates/query_si_summary.php');

/*  Consolidate computations second set */
include ('templates/query_si_summary2.php');

/*-------------------------------------Build Google chart------------------------------------------*/
include ('templates/chart_comparison_array.php');	

/*  Generate array elements for single issue  */
$rows = array();
$temp = array();
$temp[] = array('v' =>  'Single Issue');
$temp[] = array('v' => number_format($percentsingle,0));
$temp[] = array('v' => number_format($percentsingle2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for multiple issue  */	
$temp = array();
$temp[] = array('v' =>  'Multiple Issue');
$temp[] = array('v' => number_format($percentmultiple,0));
$temp[] = array('v' => number_format($percentmultiple2,0));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/header_printreports.php')		;
include ('templates/columnchart_comparison.php')	;
include ('templates/printview_comparisonbody.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    Single Issue ROs     </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percentsingle.'%'.  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percentsingle2.'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    Multiple Issue ROs	</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percentmultiple.'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percentmultiple2.'%'.'</td>
						</tr>';
include ('templates/footer_printview.php');
?>