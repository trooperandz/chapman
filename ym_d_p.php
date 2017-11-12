<?php
/* --------------------------------------------------------------------------*
   Program: ym_d_p.php

   Purpose: Printer-friendly format of lof baseline report
   History:
    Date		Description										by
	08/06/2014	Convert to mysqli								Matt Holland
	08/26/2014	Revamp format with external stylesheet			Matt Holland
	11/08/2014	Update to include dealer template includes		Matt Holland
	11/16/2014	Update yearmodel_string processing				Matt Holland
	12/03/2014	Update with standard query includes				Matt Holland
	12/17/2014	Updated chart variables and standardized		Matt Holland 
				more includes				
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

// Include page / chart variable settings
include ('templates/chart_specs.php');

/* Set last page global variable */
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
include ('templates/header_printreports.php');

// Draw the chart
include ('templates/chart_draw_ym_d.php');

// Include report body
include ('templates/dealerbody_printview.php');

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
include ('templates/footer_printview.php');       
?>