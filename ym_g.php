<?php
/* ---------------------------------------------------------------------------------*
   Program: ym_g.php

   Purpose: Produce global Year Model report for all or any dealers

	History:
    Date		Description												by
	08/14/2014	Add dealer selection									Matt Holland
	08/17/2014	Fix multidealer prob when dealer not found.				Matt Holland
	09/03/2014	Incorporate regional if statements						Matt Holland
	09/15/2014	Fix uninitialized variable problem.						Matt Holland
	10/27/2014	Add survey type processing ($globalsurveyindex_id)		Matt Holland
				for report and menu
	10/28/2014	Added standard includes to be used across all global	Matt Holland
				reports including customized report variables
	11/05/2014	Changed surveyindex_id = $globalsurveyindex_id to
				surveyindex_id IN ($globalsurveyindex_id)
				to accommodate select all surveys process				Matt Holland
	11/26/2014	Changed $globalsurveyindex_id to $surveyindex_id		Matt Holland
	11/26/2014	Changed queries to includes								Matt Holland
	12/17/2014	Updated chart variables and standardized more includes	Matt Holland
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
	include ('templates/query_totalros_md1.php');
	include ('templates/query_ym_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_global.php');	
	include ('templates/query_ym_global.php');
	// Reset variable name for global report
	$totalros = $totalros2;
	$resultmy = $resultmy2;
}

// Set header - scripts and style
include ('templates/header_reports.php');

// Draw the chart
include ('templates/chart_draw_ym_g.php');

// Include menubar
include ('templates/menubar_global_reports.php');

// Include report body
include ('templates/globalbody.php');

// Build table
$resultmy->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
include ('templates/footer_reports.php');
?>