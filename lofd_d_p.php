<?php
/* ------------------------------------------------------------------------------*
   Program: lofd_d_p.php

   Purpose: Printer-friendly format of lof demand report
   History:
    Date		Description											by
	08/06/2014	Convert to mysqli									Matt Holland
	08/26/2014	Revamp format with external stylesheet				Matt Holland
	11/08/2014	Update to include dealer template includes			Matt Holland
	12/03/2014	Updated with standard query includes				Matt Holland
	12/17/2014	Updated chart variables and standardized 		
				more includes										Matt Holland
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
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

// Include page / chart variable settings
include ('templates/chart_specs.php');

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

// Query total dealer ROs
include ('templates/query_totalros_md1.php');
	
// Query LOF D data
include ('templates/query_lofd_md1.php');
			
// Summarize query data
include ('templates/query_lofd_summary.php');

// Set header - scripts and style
include ('templates/header_printreports.php');

// Draw the chart
include ('templates/chart_draw_lofd_dg.php');

// Include report body
include ('templates/dealerbody_printview.php');
 
echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    ROs With LOF         </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$LOFrows. 		  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_LOF.'%'.   '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    ROs With Only LOF   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$SILOFrows. 	   	 '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_SILOF.'%'.'</td>
						</tr>';
include ('templates/footer_printview.php');
?>