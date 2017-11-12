<?php 
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: si_d.php

   Purpose: Report of single and multiple issue counts
   History:
    Date		Description										by
	01/24/2014	Initial design and coding						Matt Holland
	04/24/2014	Implement pie chart w/google charts.			Matt Holland
	05/01/2014	Eliminate duplicate queries for two reports.	Matt Holland
	08/04/2014	Convert to mysqli								Matt Holland
	10/27/2014	Add survey type processing ($surveyindex_id)	Matt Holland
				for report and menu
	11/06/2014	Updated template includes to contain 
				standardized report variables					Matt Holland
	12/03/2014	Updated with standard query includes			Matt Holland
	12/17/2014	Updated chart variables and standardized 
				more includes									Matt Holland
 *-----------------------------------------------------------------------*/	

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']; 	// Initialize $dealerID variable
$dealerIDs1 = $_SESSION['dealerID'];  	// Initialize dealer variable for query includes
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable
$userID		= $user->userID;			// Initialize user

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

// Include page / chart variable settings
include ('templates/chart_specs.php');

// Include RO check query
include ('templates/body_ro_check.php');

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

/*---------------------------------------------Dealer queries---------------------------------------------------*/
include ('templates/query_si_md1.php');

// Consolidate computations	
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

include ('templates/header_reports.php');
include ('templates/piechart_dg.php');
include ('templates/menubar_dealer_reports.php');
include ('templates/dealerbody.php');

echo'				<tr>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">           Single Issue ROs 	           </td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalsingle.   					  '</td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentsingle,2).'%'. '</td>
					</tr>  
					<tr>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">          Multiple Issue ROs	 		    </td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalmultiple.                       '</td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentmultiple,2).'%'.'</td>
					</tr>';
include ('templates/footer_reports.php');
?>