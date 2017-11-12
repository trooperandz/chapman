<?php
/* --------------------------------------------------------------------------*
   Program: ym_d.php

   Purpose: Produce dealer Model Year report

	History:
    Date		Description										by
	01/24/2014	Initial design and coding						Matt Holland
	05/01/2014	Eliminate duplicate queries for two reports.	Matt Holland
	08/02/2014	Update to mysqli & one query					Matt Holland
	08/14/2014	Add dealer selection							Matt Holland
	09/15/2014	Fix uninitialized variable problem.				Matt Holland
	10/16/2014	Implement model year and age principle			Matt Holland
	10/27/2014	Add survey type processing ($surveyindex_id)	Matt Holland
				for report and menu
	10/30/2014	Add die() to return to enterrofoundation if
				yearmodel start has not been entered			Matt Holland
	11/05/2014  Add $yearmodel_string processing from
				new yearmodel_strings table						Matt Holland
	11/06/2014	Updated template includes to contain 
				standardized report variables					Matt Holland
	11/16/2014	Updated yearmodel_string logic to allow user
				who has not entered an RO to look at report		Matt Holland
	12/03/2014	Updated queries with standard includes			Matt Holland
	12/17/2014	Updated chart variables and standardized 
				more includes									Matt Holland
	02/25/2015	Changed to pie chart.  Altered values to 		Matt Holland
				include aggregate bucket for 9+ year
				category.  Altered queries to function with
				new pie chart format
	02/28/2015	Standardized report with more includes such as	Matt Holland
				chart_draw_ym_dg.php , chart_specs_ym_d.php ,
				body_ro_check.php
 *---------------------------------------------------------------------------*/

// Required system includes
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

// Include RO check query
include ('templates/body_ro_check.php');

// Include page / chart variable settings
include ('templates/chart_specs.php');
                
// Set last page variable in case of process file instruction
include ('templates/lastpagevariable_dealerreports_include.php');

// Set year model strings for queries
include ('templates/ym_string.php');
include ('templates/ym_string2.php');
// echo '$yearmodel_string: '.$yearmodel_string.'<br>';
// echo '$yearmodel_string2: '.$yearmodel_string2.'<br>';

// Query for total dealer rows
include ('templates/query_totalros_md1.php');

// Query for first set dealer info
include ('templates/query_ym_dlr.php');	

// Query for second set dealer info
include ('templates/query_ym_dlr2.php');

// Set header - scripts and style
include ('templates/header_reports.php');

// Draw the chart
include ('templates/chart_draw_ym_d.php');

// Include menubar
include ('templates/menubar_dealer_reports.php');

// Include report body
include ('templates/dealerbody.php');	

// Reset first result data set internal pointer
$resultmy->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}
// Reset second result data set internal pointer
$resultmy_set2->data_seek(0);
$bucket = $resultmy_set2->fetch_row();
echo					'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket_year.    '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket[1].'%'.  '</td>
						</tr>';
							
include ('templates/footer_reports.php');
?>