<?php 
/* -----------------------------------------------------------------------------*
   Program: opgap_dealer.php

   Purpose: Produce Level One analysis dealer report

	History:
    Date				Description									by
	12/06/2014			Initial design & coding						Matt Holland
	12/17/2014			Updated chart variables and standardized 
						more includes								Matt Holland
 *-----------------------------------------------------------------------------*/
 
// Require instructions for all pages
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/dealer_setvariables.php');

/*------------------------------------------------------------------------------------------------*/
// State queries

// Query repairorder table for L1 ($result)
include ('templates/query_totalros_md1.php');
// Query level_one_analysis, services and servicerendered tables ($resultL1, $resultst1, $strows1)
include ('templates/query_L1_md1.php');
//Query level_two_analysis, services and servicerendered tables  ($resultL1, $resultst2, $strows2)
include ('templates/query_L2_md1.php');

/*------------------------------------------------------------------------------------------------*/
// Set L1 report variables
$chartarraytitle1 = 'Level 1 Metrics'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Level1'								;	// Set title to appear in chart legend
$chart_div		  = 'L1chart'								;   // Set chart div name
$chart_color1	  = '#ff8c00'								;	// Set 1st group chart color
$chart_color2     = '#3369e8'								;   // Set 2nd group chart color
$chart_callback   = 'drawL1chart'							;   // Set chart callback variable
$chart_height	  = '650'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_text		  = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Level 1 Operating Gap'					;	// Set top report title
$chart_fontsize	  = '13'									;	// Set chart font size
$chart_barwidth	  = '64%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'opgap_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'opgap_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'L1table'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Level 1 Operating Data'				;	// Set table title
$tablehead1 	  = 'L1 Service'							;	// Set first table header title
$tablehead2 	  = constant('ENTITY').' ' .$dealercode		;	// Set second table header title
$tablehead3 	  = 'L1 Metric'								;	// Set third table header title
$tablehead4		  = 'Op. Gap'								;   // Set fourth table header title
/*------------------------------------------------------------------------------------------------*/
// Build body and chart
include('templates/chart_L1dealer_array.php')		;
include('templates/header_reports.php')				;
include('templates/columnchart_comparison.php')		; 
include('templates/menubar_dealer_reports.php')		;
include('templates/top_panel_reports.php')			;
include('templates/error_message.php')				;
include('templates/dealer_opgap_body.php')			;

for ($j=0; $j<$strows1; ++$j) {
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][0].     								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][2].'%'. 								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold2[$j].'%'. 									'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format(($hold1[$j][2] - $hold2[$j]),2).'%'. '</td>
						</tr>';
}						
echo'				 </table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------*/
// Set L2 report variables
$chartarraytitle1 = 'Level 2 Metrics'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Level2'								;	// Set title to appear in chart legend
$chart_div		  = 'L2chart'								;   // Set chart div name
$chart_color1	  = '#D34836'								;	// Set 1st group chart color
$chart_color2     = '#3369e8'								;   // Set 2nd group chart color
$chart_callback   = 'drawL2chart'							;   // Set chart callback variable
$chart_height	  = '650'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_text		  = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Level 2 Operating Gap'					;	// Set top report title
$chart_fontsize	  = '13'									;	// Set chart font size
$chart_barwidth	  = '64%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'opgap_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'opgap_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'L2table'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Level 2 Operating Data'				;	// Set table title
$tablehead1 	  = 'L2 Service'							;	// Set first table header title
$tablehead2 	  = constant('ENTITY').' ' .$dealercode		;	// Set second table header title
$tablehead3 	  = 'L2 Metric'								;	// Set third table header title
$tablehead4		  = 'Op. Gap'								;   // Set fourth table header title
/*------------------------------------------------------------------------------------------------*/
// Build body and chart
include('templates/chart_L2dealer_array.php')			;
include('templates/columnchart_comparison.php')			; 
include('templates/dealer_opgap_body.php')				;

for ($j=0; $j<$strows2; ++$j) {
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][0].     								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][2].'%'. 								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold2[$j].'%'. 									'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format(($hold1[$j][2] - $hold2[$j]),2).'%'. '</td>
						</tr>';
}						
include('templates/footer_reports.php');
?>