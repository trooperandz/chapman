<?php
/* --------------------------------------------------------------------------*
   Program: ym_d_si.php

   Purpose: Produce dealer Single Issue Model Year report

	History:
    Date		Description										by
	01/22/2015	Created SI report from original YM report		Matt Holland
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

// Include RO check query
include ('templates/body_ro_check.php');

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

// Query for total single issue dealer rows
include ('templates/query_totalros_si_md1.php');

// Set year model string for queries
include ('templates/ym_string.php');
include ('templates/ym_string2.php');
// echo '$yearmodel_string: '.$yearmodel_string.'<br>';
// echo '$yearmodel_string2: '.$yearmodel_string2.'<br>';

// Query for first set dealer info
include ('templates/query_ym_si_dlr.php');	
// echo '$myrows: '.$myrows.'<br>';

// Query for second set dealer info
include ('templates/query_ym_si_dlr2.php');
// echo '$myrows_set2: '.$myrows_set2.'<br>';

// Set header - scripts and style
include ('templates/header_reports.php');

// Draw the chart
include ('templates/chart_draw_ym_d_si.php');

// Include menubar
include ('templates/menubar_dealer_reports.php');

// Include report body
include ('templates/dealerbody.php');	

// Reset first result data set internal pointer	
$resultmy_si->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy_si->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}
// Reset second result data set internal pointer
$resultmy_si_set2->data_seek(0);
$bucket_si = $resultmy_si_set2->fetch_row();
echo					'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket_year.    '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket_si[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket_si[1].'%'.  '</td>
						</tr>';
include ('templates/footer_reports.php');
?>