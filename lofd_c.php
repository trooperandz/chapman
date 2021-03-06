<?php 
/* ---------------------------------------------------------------------------------------*
   Program: lofdemandquerycomparison.php

   Purpose: Produce LOF Demand comparison reports for various user selections

	History:
    Date		Description													by
	07/29/2014	Initial design & coding										Matt Holland
	11/03/2014	Add survey type processing ($comparisonsurveyindex_id)		Matt Holland
				for report and menu
	11/03/2014	Added standard includes to be used across all comparison	Matt Holland
				reports including customized report variables
	12/01/2014	Rewrote to include standard query includes					Matt Holland
	12/17/2014	Updated chart variables and standardized 					Matt Holland
				more includes
 *----------------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']; // Initialize $dealerID variable
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable
$userID		= $user->userID;			// Initialize user

// Include page / chart variable settings
include ('templates/chart_specs.php');

/*----Set survey variables and globals for report with include-------*
$surveyindex_id 				= $_SESSION['comparisonsurveyindex_id'] 
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
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_md2.php');
	// Query for second set dealer LOF data
	include ('templates/query_lofd_md2.php');

/*--------------------------------------------------------Single Dealer queries------------------------------------------------------*/	
// Check to see if single dealer variables are set
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_global.php');
	// Query for second set dealer LOF data
	include ('templates/query_lofd_global.php');
	
/*------------------------------------------------Default query set-------------------------------------------*/
} else {
	// Swap $dealerID variable
	$dealerIDs1 = $dealerID;
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_global.php');
	// Query for second set dealer LOF data
	include ('templates/query_lofd_global.php');
	
} // End else statement

/*  Consolidate computations first set  */
include ('templates/query_lofd_summary.php');

/*  Consolidate computations second set */
include ('templates/query_lofd_summary2.php');

/*-------------------------------------Build Google chart------------------------------------------*/
include ('templates/chart_comparison_array.php');	

$rows = array();
$temp = array();
/*  Generate array elements for average labor  */
$temp[] = array('v' =>  'ROs With LOF');
$temp[] = array('v' => number_format($percent_LOF,0));
$temp[] = array('v' => number_format($percent_LOF2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for average parts  */	
$temp = array();
$temp[] = array('v' =>  'SI ROs With LOF');
$temp[] = array('v' => number_format($percent_SILOF,0));
$temp[] = array('v' => number_format($percent_SILOF2,0));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include('templates/header_reports.php')				;
include('templates/columnchart_comparison.php')		;
include('templates/menubar_comparison_reports.php')	; 
include('templates/comparisonbody.php')				;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;"> ROs With LOF      	 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_LOF.'%'.  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_LOF2.'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    SI ROs With LOF	  </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_SILOF.'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_SILOF2.'%'.'</td>
						</tr>';
include('templates/footer_reports.php');
?>