<?php 
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: lofb_d_p.php

   Purpose: Printer-friendly format of lof baseline report
   History:
    Date		Description											by
	08/06/2014	Convert to mysqli									Matt Holland
	08/26/2014	Revamp format with external stylesheet				Matt Holland
	11/16/2014	Update to include dealer template includes			Matt Holland
	12/03/2014	Updated with standard query includes				Matt Holland
	02/27/2015	Made dealer page variable initialization an include	Matt Holland	
				Made chart variables an include
				Redesigned chart draw instructions - imitated after
				Google developer example.  Also made the inside bar
				description include the $ sign.  Made font larger	
				Made chart specifications an include
 *---------------------------------------------------------------------------*/
 
// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

// Include page / chart variable settings
include ('templates/chart_specs.php');

// Set last page variable in case of process file instruction
include ('templates/lastpagevariable_dealerreports_include.php');

// Queries
include ('templates/query_lofb_md1.php');

// Set header - scripts and style
include ('templates/header_printreports.php');

// Draw the chart
include ('templates/chart_draw_lofb_dg.php');

// Include report body
include ('templates/dealerbody_printview.php');

echo'				<tr>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averagelabor,2). 		   '</td>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averageparts,2). 		   '</td>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averagepartspluslabor,2). '</td>
 					</tr>';
include ('templates/footer_printview.php');    
?>