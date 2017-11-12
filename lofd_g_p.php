<?php
/* -----------------------------------------------------------------------------*
   Program: lofd_g_p.php

   Purpose: Report of LOF demand pie chart and data chart
   History:
    Date		Description											by
	08/03/2014	Update to mysqli & one query						Matt Holland
	11/17/2014	Incorporate new template includes format			Matt Holland
	12/17/2014	Updated chart variables and standardized 			Matt Holland
				more includes	
	02/27/2015	Made dealer page variable initialization an include	Matt Holland	
				Made chart variables an include
				Redesigned chart draw instructions - imitated after
				Google developer example.  Also made the inside bar
				description include the $ sign.  Made font larger	
				Made chart specifications an include
*--------------------------------------------------------------------------------*/

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
	include ('templates/query_lofd_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_global.php');	
	include ('templates/query_lofd_global.php');
	// Reset variables for use in global body
	$totalros = $totalros2;
	$LOFrows 			= $LOFrows2		  	  ;
	$SILOFrows 			= $SILOFrows2		  ;
	$percent_LOF		= $percent_LOF2	      ;
	$percent_SILOF		= $percent_SILOF2	  ;
}

// Summary query
include ('templates/query_lofd_summary.php');

// Set header - scripts and style
include ('templates/header_printreports.php');

// Draw the chart
include ('templates/chart_draw_lofd_dg.php');

// Include report body
include ('templates/printview_globalbody.php');

echo'			<tr>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">   ROs With LOF 	   						</td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$LOFrows. 		  				   '</td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .number_format($percent_LOF,2).'%'.   '</td>
				</tr>
				<tr>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    ROs With Only LOF 	        	    </td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$SILOFrows. 	   					   '</td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .number_format($percent_SILOF,2).'%'. '</td>
				</tr>';
include ('templates/footer_printview.php');				
?>