<?php	
/* ----------------------------------------------------------------------------------*
   Program: ym_g_p.php

   Purpose: Report of Model Year column chart and data chart
   History:
    Date		Description												by
	08/03/2014	Update to mysqli & one query							Matt Holland
	09/04/2014	Incorporate regional functionality						Matt Holland
	11/17/2014	Incorporate new template includes format				Matt Holland
	12/17/2014	Updated chart variables and standardized 			
				more includes											Matt Holland
	02/27/2015	Added global variable initialization include			Matt Holland
				Added chart specs include and changed size/font/etc
				Added chart draw include - changed draw method
				to mimic Google developer method instead of JSON
*-------------------------------------------------------------------------------------*/

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
	include ('templates/query_totalros_md1.php');
	// Year Model query	
	include ('templates/query_ym_md1.php');	
/*------------------------------------------------*/
} else {
	// Query for total dealer rows
	include ('templates/query_totalros_global.php');
	// Year Model query	
	include ('templates/query_ym_global.php');
	// Reset variable name for global report
	$totalros = $totalros2;
	$resultmy = $resultmy2;
}

// Set header - scripts and style
include ('templates/header_printreports.php');

// Draw the chart
include ('templates/chart_draw_ym_g.php');

// Include report body
include ('templates/printview_globalbody.php')	;
	
$resultmy->data_seek(0);		
for ($j = 0 ; $j < $myrows ; ++$j)
{
			$row = $resultmy->fetch_row();
		  echo '<tr>
					<td>' .$row[0].     '</td>
					<td>' .$row[1].     '</td>
					<td>' .$row[2].'%'. '</td>
				</tr>';
}
include ('templates/footer_printview.php');
?>