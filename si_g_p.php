<?php 
require_once("functions.inc");
include ('templates/login_check.php');
	
/* ----------------------------------------------------------------------*
   Program: si_g_p.php

   Purpose: Report of Single Issue pie chart and data chart
   History:
    Date		Description									by
	08/03/2014	Update to mysqli & one query				Matt Holland
	09/04/2014	Incorporate regional functionality			Matt Holland
	11/17/2014	Incorporate new template includes format	Matt Holland
	12/01/2014	Rewrote to include standard query includes	Matt Holland
	12/17/2014	Updated chart variables and standardized 	Matt Holland
				more includes
*-----------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];					// Dealercode set at login
$dealerID = $_SESSION['dealerID'];  					// Dealer ID set at login

// Include page / chart variable settings
include ('templates/chart_specs.php');

/*----Set survey variables and globals for report with include-------*
$_SESSION['globalsurveyindex_id'] 	  = $surveyindex_id;
$_SESSION['globalsurvey_description'] = $globalsurvey_description;
$_SESSION['lastpageglobalreports']	 // Returns processes to last page
$totaldealers_persurvey 			 // Total dealer count per survey
---------------------------------------------------------------------*/
include('templates/setsurveyglobals_globalreports.php');

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
	include ('templates/query_si_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_si_global.php');
	// Reset variables for use below
	$totalsingle 	= $totalsingle2		;
	$totalmultiple	= $totalmultiple2	;
}
// Summary Query
include ('templates/query_si_summary.php');
/*------------------------------------Build Google Chart--------------------------------*/

$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'Single Issue Occurrence', 'type' => 'string'),
	array('label' => '% Single Issue', 'type' => 'number'),
    array('label' => 'Multiple Issue Occurrence', 'type' => 'string'),
	array('label' => '% Multiple Issue', 'type' => 'number')

);

/*  Generate array elements for single issue  */
$rows = array();
$temp = array();
$temp[] = array('v' =>  'Single Issue');
$percent_single = ($totalsingle/$totalrows)*100;
$temp[] = array('v' => $percent_single);
$rows[] = array('c' => $temp);
/*  Generate array elements for multiple issue  */	
$temp = array();
$temp[] = array('v' =>  'Multiple Issue');
$percent_multiple = ($totalmultiple/$totalrows)*100;
$temp[] = array('v' => $percent_multiple);
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/header_printreports.php')	;
include ('templates/piechart_dg.php')			;
include ('templates/printview_globalbody.php')	;

echo'			<tr>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">           Single Issue ROs 	         	 </td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalsingle.   						'</td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentsingle,2).'%'.   '</td>
				</tr>  
				<tr>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">          Multiple Issue ROs 		  		 </td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalmultiple.                    	'</td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentmultiple,2).'%'. '</td>
				</tr>';		
include ('templates/footer_printview.php');
?>