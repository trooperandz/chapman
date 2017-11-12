<?php 
/* -----------------------------------------------------------------------------*
   Program: ym_c_si.php

   Purpose: Produce comparison Single Issue Model Year report

	History:
    Date		Description										by
	01/22/2015	Created SI report from original YM report		Matt Holland
 *-----------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');

include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']   ; // Initialize $dealerID variable
$dealercode = $_SESSION['dealercode'] ; // Initialize $dealercode variable

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
	// Query for first set of total dealer rows
	include ('templates/query_totalros_si_md1.php');
	
	//  Query for first set dealer model year	
	include ('templates/query_ym_si_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_si_md2.php');

	//  Query for second set dealer model year	
	include ('templates/query_ym_si_md2.php');	

/*--------------------------------------------------------Single Dealer queries------------------------------------------------------*/	
// Check to see if single dealer variables are set
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
	// Query for first set of total dealer rows
	include ('templates/query_totalros_si_md1.php');

	//  Query for first set dealer model year	
	include ('templates/query_ym_si_md1.php');

	// Query for global dealer rows
	include ('templates/query_totalros_si_global.php');

	// Query for global model year
	include ('templates/query_ym_si_global.php');
	
/*------------------------------------------------------------Default query set--------------------------------------------------------*/
// If no globals are set, run default queries
} else {
	// Set $dealerIDs1
	$dealerIDs1 = $dealerID;
	// Query for first set of total dealer rows
	include ('templates/query_totalros_si_md1.php');

	//  Query for first set dealer model year	
	include ('templates/query_ym_si_md1.php');

	// Query for global dealer rows
	include ('templates/query_totalros_si_global.php');

	// Query for global model year
	include ('templates/query_ym_si_global.php');
} // End default else statement

/*-------------------------------------Build Google chart------------------------------------------*/
include ('templates/chart_comparison_array.php');

/*  For single rows and all rows, hold in arrays for later to eliminate another set of queries  */
$dealerhold1 = array(array());
$dealerhold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($r = $resultmy_si->fetch_row()) {
	$ra = $resultmy2_si->fetch_row();
	$dealerhold1[$row][0] = $r[0];
	$dealerhold1[$row][1] = $r[1];
	$dealerhold1[$row][2] = $r[2];
	$dealerhold2[$row][0] = $ra[0];
	$dealerhold2[$row][1] = $ra[1];
	$dealerhold2[$row][2] = $ra[2];
	
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$r[2]   = number_format($r[2],0);
	$temp[] = array('v' => $r[2]);
	$ra[2]   = number_format($ra[2],0);
	$temp[] = array('v' => $ra[2]);
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include('templates/header_reports.php')				;
include('templates/columnchart_comparison.php')		;
include('templates/menubar_comparison_reports.php')	; 
include('templates/comparisonbody.php')				;
  		  
for ($row = 0; $row < $myrows; ++$row)
{
	echo				'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][0].     '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][2].'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold2[$row][2].'%'. '</td>
						</tr>';
}						
include('templates/footer_reports.php');
?>