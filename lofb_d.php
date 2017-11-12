<?php 
/* --------------------------------------------------------------------------*
   Program: lofbaselinequery_column.php

   Purpose: Report of average $$ for labor and parts - global
   History:
    Date		Description											by
	01/24/2014	Initial design and coding							Matt Holland
	05/01/2014	Eliminate duplicate queries for two reports.		Matt Holland
	08/04/2014	Convert to mysqli									Matt Holland
	10/27/2014	Add survey type processing ($surveyindex_id)		Matt Holland
				for report and menu
	11/06/2014	Updated template includes to contain 
				standardized report variables						Matt Holland
	12/03/2014	Updated with standard query includes				Matt Holland
	12/17/2014	Updated chart variables and standardized 	
				more includes										Matt Holland
	02/27/2015	Made dealer page variable initialization an include	Matt Holland	
				Made chart variables an include
				Redesigned chart draw instructions - imitated after
				Google developer example.  Also made the inside bar
				description include the $ sign.  Made font larger	
				Made chart specifications an include		
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

// Set last page variable in case of process file instruction
include ('templates/lastpagevariable_dealerreports_include.php');

// Queries
include ('templates/query_lofb_md1.php');

// Set header - scripts and style
include ('templates/header_reports.php');

// Draw the chart
include ('templates/chart_draw_lofb_dg.php');

// Include menubar
include ('templates/menubar_dealer_reports.php'); 

// Include report body
include ('templates/dealerbody.php');

echo'				<tr>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averagelabor,2). 		   '</td>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averageparts,2). 		   '</td>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averagepartspluslabor,2). '</td>
 					</tr>
					</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>
	        </div>	
		</div>
	</div>
</div>';
include ('templates/footer_reports.php');
?>