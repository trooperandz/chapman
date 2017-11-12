<?php 
/* -----------------------------------------------------------------------------*
   Program: lofd_d.php

   Purpose: Report of LOF demand pie chart and data chart
   History:
    Date		Description										by
	01/24/2014	Initial design and coding							Matt Holland
	04/24/2014	Implement pie chart w/google charts.				Matt Holland
	05/01/2014	Eliminate duplicate queries for two reports.		Matt Holland
	08/03/2014	Update to mysqli & one query						Matt Holland
	10/27/2014	Add survey type processing ($surveyindex_id)		Matt Holland
				for report and menu	
	11/06/2014	Updated template includes to contain 	
				standardized report variables						Matt Holland
	12/03/2014	Updated with standard query includes				Matt Holland
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
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

// Include page / chart variable settings
include ('templates/chart_specs.php');

// Set last page variable in case of process file instruction
include ('templates/lastpagevariable_dealerreports_include.php');

// Query total dealer ROs
include ('templates/query_totalros_md1.php');
	
// Query LOF D data
include ('templates/query_lofd_md1.php');
			
// Summarize query data
include ('templates/query_lofd_summary.php');

// Set header - scripts and style
include ('templates/header_reports.php');

// Draw the chart
include ('templates/chart_draw_lofd_dg.php');

// Include menubar
include ('templates/menubar_dealer_reports.php');

// Include report body
include ('templates/dealerbody.php');
 
echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">   ROs With LOF 	       </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$LOFrows.	 		  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_LOF.'%'.	  '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    ROs With Only LOF    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$SILOFrows.		  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_SILOF.'%'. '</td>
						</tr>		
				    </tbody>
				 </table>
			</div>
			<div class="medium-3 large-3 columns">
				<p> </p>
			</div>
		</div>
	</div>
</div>';
include ('templates/footer_reports.php');
?>