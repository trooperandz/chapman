<?php 	
/* -----------------------------------------------------------------------------*
   Program: lofb_g_p.php

   Purpose: Report of LOF Baseline column chart and data chart
   History:
    Date		Description											by
	08/03/2014	Update to mysqli & one query						Matt Holland
	09/04/2014	Incorporate regional functionality					Matt Holland
	11/17/2014	Incorporate new template includes format			Matt Holland
	12/17/2014	Updated chart variables and standardized 			Matt Holland
				more includes	
	02/27/2015	Added global variable initialization include		Matt Holland
				Added chart specs include and changed size/font/etc
				Added chart draw include - changed draw method
				to mimic Google developer method instead of JSON
*-------------------------------------------------------------------------------*/

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
	include ('templates/query_lofb_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {	
	include ('templates/query_lofb_global.php');
	// Reset variable names for use in body template
	$averagelabor 			= $averagelabor2			;
	$averageparts 			= $averageparts2			;
	$averagepartspluslabor 	= $averagepartspluslabor2	;
}

// Set header - scripts and style
include ('templates/header_printreports.php');

// Draw the chart
include ('templates/chart_draw_lofb_dg.php');

// Include report body
include ('templates/printview_globalbody.php')	;

echo'			<tr>
 					<td>'. '$' .number_format($averagelabor,2). 		    '</td>
 					<td>'. '$' .number_format($averageparts,2). 		    '</td>
 					<td>'. '$' .number_format($averagepartspluslabor,2). 	'</td>
 				</tr>';
include ('templates/footer_printview.php');				
?>