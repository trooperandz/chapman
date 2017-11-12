<?php
/* ---------------------------------------------------------------------------------*
  Program: ym_g_si.php

   Purpose: Produce global Single Issue Model Year report

	History:
    Date		Description												by
	01/22/2015	Created SI report from original YM report				Matt Holland
	02/27/2015	Added global variable initialization include			Matt Holland
				Added chart specs include and changed size/font/etc
				Added chart draw include - changed draw method
				to mimic Google developer method instead of JSON
 *-----------------------------------------------------------------------------------*/

// Required system includes
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include ('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_global_vars.php');

// Include page / chart variable settings
include ('templates/chart_specs.php');

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
	include ('templates/query_totalros_si_md1.php');
	include ('templates/query_ym_si_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_si_global.php');	
	include ('templates/query_ym_si_global.php');
	// Rename variable for report processing
	$totalros_si = $totalros2_si;
	$resultmy_si = $resultmy2_si;
}

// Set header - scripts and style
include ('templates/header_reports.php');

// Draw the chart
include ('templates/chart_draw_ym_g_si.php');

// Include menubar
include ('templates/menubar_global_reports.php');

// Include report body
include ('templates/globalbody.php');

// Build table
$resultmy_si->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy_si->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
include ('templates/footer_reports.php');
?>