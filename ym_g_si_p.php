<?php	
/* -----------------------------------------------------------------------------*
   Program: ym_g_si_p.php

   Purpose: Produce printer-friendly global Single Issue Model Year report

	History:
    Date		Description											by
	01/22/2015	Created SI report from original YM report			Matt Holland
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
include ('templates/header_printreports.php');

// Draw the chart
include ('templates/chart_draw_ym_g_si.php');

// Include report body
include ('templates/printview_globalbody.php');
	
$resultmy_si->data_seek(0);		
for ($j = 0 ; $j < $myrows ; ++$j)
{
			$row = $resultmy_si->fetch_row();
		  echo '<tr>
					<td>' .$row[0].     '</td>
					<td>' .$row[1].     '</td>
					<td>' .$row[2].'%'. '</td>
				</tr>';
}
include ('templates/footer_printview.php');
?>