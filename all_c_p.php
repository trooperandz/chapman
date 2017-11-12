<?php 
require_once("functions.inc");
include ('templates/login_check.php');
/* ------------------------------------------------------------------------------------------------*
   Program: comparison_printall.php

   Purpose: Compile all comparison print reports into one webpage
   History:
    Date			Description														by
	12/04/2014		Initial design and coding										Matt Holland
	12/17/2014		Updated chart variables and standardized more includes			Matt Holland
	01/14/2015		Updated Service Demand portion - changed to column chart		Matt Holland
	01/22/2015		Added YM Single Issue report									Matt Holland
	01/22/2015		Added MS Single Issue report									Matt Holland
	01/23/2015		Added ST Single Issue report									Matt Holland
 *--------------------------------------------------------------------------------------------------*/

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']   ; // Initialize $dealerID variable
$dealercode = $_SESSION['dealercode'] ; // Initialize $dealercode variable
$userID		= $user->userID			  ; // Initialize $userID variable

/*----Set survey variables and globals for report with include-------*
$surveyindex_id 				= $_SESSION['comparisonsurveyindex_id'] 
$comparisonsurvey_description 	= $_SESSION['comparisonsurvey_description'] 
$_SESSION['lastpagecomparisonreports']	 // Returns processes to last page
$totaldealers_persurvey 			 	 // Total dealer count per survey
---------------------------------------------------------------------*/
include('templates/setsurveyglobals_comparisonreports.php');

/*------------------------------------------------------------------------------------------------*/
// Service categories processing
include ('templates/set_reporttype_comparison.php');
include ('templates/st_string_processing.php');

/*----------------------------------------------------------------------------------------------------*/
// SI Category string processing
include('templates/sicategory_string.php');

/*------------------------------------------------------------------------------------------------*/
// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');

/*----------------------------------------------------------------------------------------------------------*/
// Check to see if multidealer variables are set
if((isset($_SESSION['comparedealer1IDs']) 		&& isset($_SESSION['comparedealer2IDs'])) 		OR 
   (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR
   (isset($_SESSION['compareregionIDs1']) 		&& isset($_SESSION['compareregionIDs2']))) {
	if (isset($_SESSION['comparedealer1IDs']) && isset($_SESSION['comparedealer2IDs'])) {
		$dealerIDs1 = $_SESSION['comparedealer1IDs']; // Initialize all globals to be used in queries
		$dealerIDs2 = $_SESSION['comparedealer2IDs'];
	} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
		$dealerIDs1 = $_SESSION['comparedealerregion1IDs'];
		$dealerIDs2 = $_SESSION['compareregiondealerIDs1'];
	} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {
		$dealerIDs1 = $_SESSION['compareregionIDs1'];
		$dealerIDs2 = $_SESSION['compareregionIDs2'];
	}
/*-----------------------------------------------------*/
// Model Age Queries
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	include ('templates/query_totalros_si_md1.php');

	//  Query for first set dealer Model Age	
	include ('templates/query_ym_md1.php');
	include ('templates/query_ym_si_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_md2.php');
	include ('templates/query_totalros_si_md2.php');

	//  Query for second set dealer Model Age	
	include ('templates/query_ym_md2.php');
	include ('templates/query_ym_si_md2.php');
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');
	include ('templates/query_ms_si_md1.php');

	//  Query for second set dealer mileage spread	
	include ('templates/query_ms_md2.php');
	include ('templates/query_ms_si_md2.php');
/*-----------------------------------------------------*/
// Longhorn Queries	
	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');	
	include ('templates/query_st_si_md1.php');	

	//  Query for second set dealer Longhorn	
	include ('templates/query_st_md2.php');	
	include ('templates/query_st_si_md2.php');
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set dealer LOF data
	include ('templates/query_lofd_md2.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	//  Query for second LOF Baseline set
	include ('templates/query_lofb_md2.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_md2.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_md2.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');

	// Queries set 2
	include ('templates/query_svcd_md2.php');
/*----------------------------------------------------------------------------------------------------------*/
} elseif ((isset($_SESSION['compareglobalIDs'])) OR (isset($_SESSION['regionvsglobalIDs']))) {
	if (isset($_SESSION['compareglobalIDs'])) {
		$dealerIDs1 = $_SESSION['compareglobalIDs'];
	} elseif (isset($_SESSION['regionvsglobalIDs'])) {
		$dealerIDs1 = $_SESSION['regionvsglobalIDs'];
	}
/*-----------------------------------------------------*/
// Model Age Queries
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	include ('templates/query_totalros_si_md1.php');

	//  Query for first set dealer Model Age	
	include ('templates/query_ym_md1.php');
	include ('templates/query_ym_si_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_global.php');
	include ('templates/query_totalros_si_global.php');

	//  Query for second set dealer Model Age	
	include ('templates/query_ym_global.php');
	include ('templates/query_ym_si_global.php');
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');
	include ('templates/query_ms_si_md1.php');	

	//  Query for second set dealer mileage spread	
	include ('templates/query_ms_global.php');
	include ('templates/query_ms_si_global.php');
/*-----------------------------------------------------*/
// Longhorn Queries	
	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');	
	include ('templates/query_st_si_md1.php');	

	//  Query for second set dealer Longhorn	
	include ('templates/query_st_global.php');	
	include ('templates/query_st_si_global.php');	
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set dealer LOF data
	include ('templates/query_lofd_global.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	//  Query for second LOF Baseline set
	include ('templates/query_lofb_global.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_global.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_global.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');

	// Queries set 2
	include ('templates/query_svcd_global.php');
/*----------------------------------------------------------------------------------------------------------*/
} else {

// Swap $dealerID variable
$dealerIDs1 = $dealerID;
/*-----------------------------------------------------*/
// Model Age Queries
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');
	include ('templates/query_totalros_si_md1.php');

	//  Query for first set dealer Model Age	
	include ('templates/query_ym_md1.php');
	include ('templates/query_ym_si_md1.php');

	// Query for second set of total dealer rows
	include ('templates/query_totalros_global.php');
	include ('templates/query_totalros_si_global.php');

	//  Query for second set dealer Model Age	
	include ('templates/query_ym_global.php');
	include ('templates/query_ym_si_global.php');
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');	
	include ('templates/query_ms_si_md1.php');	

	//  Query for second set dealer mileage spread	
	include ('templates/query_ms_global.php');
	include ('templates/query_ms_si_global.php');
/*-----------------------------------------------------*/
// Longhorn Queries	
	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');	
	include ('templates/query_st_si_md1.php');	

	//  Query for second set dealer Longhorn	
	include ('templates/query_st_global.php');	
	include ('templates/query_st_si_global.php');
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');

	// Query for second set dealer LOF data
	include ('templates/query_lofd_global.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');

	//  Query for second LOF Baseline set
	include ('templates/query_lofb_global.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
		
	// Query second dealer set
	include ('templates/query_si_global.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');

	// Second query set
	include ('templates/query_sic_global.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');

	// Queries set 2
	include ('templates/query_svcd_global.php');
	
} // End default else statement
/*----------------------------------------------------------------------------------------------------------*/
// Consolidate computations

/*  Consolidate LOF D computations  */
include ('templates/query_lofd_summary.php');
include ('templates/query_lofd_summary2.php');

/*  Consolidate SI computations  */
include ('templates/query_si_summary.php');
include ('templates/query_si_summary2.php');

/*  Consolidate SI C computations */
include ('templates/query_sic_summary.php');
include ('templates/query_sic_summary2.php');

/*  Consolidate SVC D computations */
include ('templates/query_svcd_summary.php');
include ('templates/query_svcd_summary2.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set MY report title and chart options with specified variables
$chartarraytitle1 = 'Model Age'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Model Age'							;	// Set title to appear in chart legend
$chart_div		  = 'ymchart'								;   // Set chart div name
$chart_color1	  = '#D34836'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawymchart'							;   // Set chart callback variable
$chart_height	  = '575'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Model Age Distribution'				;	// Set top report title
$chart_fontsize	  = '14'									;	// Set chart font size
$chart_barwidth	  = '64%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'ym_c_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'ym_c_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'ymtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Model Age Data'						;	// Set table title
$tablehead1 	  = 'Model Age'							;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build MY body
include ('templates/header_printreports.php');
include ('templates/chart_comparison_array.php');

$dealerhold1 = array(array());
$dealerhold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($r = $resultmy->fetch_row()) {
	$ra = $resultmy2->fetch_row();
	$dealerhold1[$row][0] = $r[0];
	$dealerhold1[$row][1] = $r[1];
	$dealerhold1[$row][2] = $r[2];
	$dealerhold2[$row][0] = $ra[0];
	$dealerhold2[$row][1] = $ra[1];
	$dealerhold2[$row][2] = $ra[2];
	
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (float) number_format($r[2],0));
	$temp[] = array('v' => (float) number_format($ra[2],0));
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php');
include('templates/comparisonbody_printall.php')			 ;

for ($row = 0; $row < $myrows; ++$row)
{
	echo				'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][0].     '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][2].'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold2[$row][2].'%'. '</td>
						</tr>';
}						
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set MY SI report title and chart options with specified variables
$chartarraytitle1 = 'Model Age'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Model Age'							;	// Set title to appear in chart legend
$chart_div		  = 'ymsichart'								;   // Set chart div name
$chart_color1	  = '#D34836'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawymsichart'							;   // Set chart callback variable
$chart_height	  = '575'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Single Issue Model Age Distribution'	;	// Set top report title
$chart_fontsize	  = '14'									;	// Set chart font size
$chart_barwidth	  = '64%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'ym_c_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'ym_c_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'ymsitable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Model Age Data'			;	// Set table title
$tablehead1 	  = 'Model Age'								;	// Set first table header title
$tablehead2 	  = 'Total SI ROs'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build MY chart
$dealerhold1 = array(array());
$dealerhold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($r = $resultmy_si->fetch_row()) {
	$ra = $resultmy2_si->fetch_row();
	$dealerhold1[$row][0] = $r[0];
	$dealerhold1[$row][1] = $r[1];
	$dealerhold1[$row][2] = $r[2];
	$dealerhold2[$row][0] = $ra[0];
	$dealerhold2[$row][1] = $ra[1];
	$dealerhold2[$row][2] = $ra[2];
	
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (float) number_format($r[2],0));
	$temp[] = array('v' => (float) number_format($ra[2],0));
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

for ($row = 0; $row < $myrows; ++$row)
{
	echo				'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][0].     '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][2].'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold2[$row][2].'%'. '</td>
						</tr>';
}						
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set MS report title and chart options with specified variables
$chartarraytitle1 = 'Mileage Spread'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Mileage Spread'						;	// Set title to appear in chart legend
$chart_div		  = 'mschart'								;   // Set chart div name
$chart_color1	  = '#EEB211'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawmschart'							;   // Set chart callback variable
$chart_height	  = '575'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Mileage Spread Distribution'			;	// Set top report title
$chart_fontsize	  = '14'									;	// Set chart font size
$chart_barwidth	  = '55%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'ms_c_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'ms_c_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'mstable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Mileage Spread Data'					;	// Set table title
$tablehead1 	  = 'Mileage Spread'						;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build MS chart
$dealerhold1 = array(array());
$dealerhold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($r = $resultms->fetch_row()) {
	$ra = $resultms2->fetch_row();
	$dealerhold1[$row][0] = $r[0];
	$dealerhold1[$row][1] = $r[1];
	$dealerhold1[$row][2] = $r[2];
	$dealerhold2[$row][0] = $ra[0];
	$dealerhold2[$row][1] = $ra[1];
	$dealerhold2[$row][2] = $ra[2];
	
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (float) number_format($r[2],0));
	$temp[] = array('v' => (float) number_format($ra[2],0));
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

for ($row = 0; $row < $msrows; ++$row)
{
	echo				'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][0].     '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][2].'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold2[$row][2].'%'. '</td>
						</tr>';
}						
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set MS SI report title and chart options with specified variables
$chartarraytitle1 = 'Mileage Spread'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Mileage Spread'							;	// Set title to appear in chart legend
$chart_div		  = 'mssichart'									;   // Set chart div name
$chart_color1	  = '#EEB211'									;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'									;   // Set 2nd group chart color
$chart_callback   = 'drawmssichart'								;   // Set chart callback variable
$chart_height	  = '575'										;	// Set chart height within specified area
$chart_areaheight = '81%'										;   // Set chart area
$chart_areawidth  = '90%'										;   // Set chart area width
$chart_top		  = '60'										;	// Set chart distance from chart area top
$chart_text		  = '0'											;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'										;   // Set x-axis title text angle
$chart_title	  = 'Single Issue Mileage Spread Distribution'	;	// Set top report title
$chart_fontsize	  = '14'										;	// Set chart font size
$chart_barwidth	  = '55%'										;	// Set chart bar width
$chart_gridcount  = '14'										;   // Set number of gridlines
$chart_numformat  = '#\'%\''									;   // Set number format
$chart_minvalue   = '0'											;   // Set chart minimum value
$exportanchor  	  = 'ms_c_si_x.php'								;	// Set export anchor reference
$printeranchor 	  = 'ms_c_si_p.php'								; 	// Set printer friendly anchor reference
$tableid		  = 'mssitable'									;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Mileage Spread Data'			;	// Set table title
$tablehead1 	  = 'Mileage Spread'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'								;	// Set third table header title

// Build MS SI chart
$dealerhold1 = array(array());
$dealerhold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($r = $resultms_si->fetch_row()) {
	$ra = $resultms2_si->fetch_row();
	$dealerhold1[$row][0] = $r[0];
	$dealerhold1[$row][1] = $r[1];
	$dealerhold1[$row][2] = $r[2];
	$dealerhold2[$row][0] = $ra[0];
	$dealerhold2[$row][1] = $ra[1];
	$dealerhold2[$row][2] = $ra[2];
	
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (float) number_format($r[2],0));
	$temp[] = array('v' => (float) number_format($ra[2],0));
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

for ($row = 0; $row < $msrows; ++$row)
{
	echo				'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][0].     '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][2].'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold2[$row][2].'%'. '</td>
						</tr>';
}						
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set ST report title and chart options with specified variables
$chartarraytitle1 = 'Service Type'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Service'								;	// Set title to appear in chart legend
$chart_div		  = 'stchart'								;   // Set chart div name
$chart_color1	  = '#3369e8'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawstchart'							;   // Set chart callback variable
$chart_height	  = '550'									;	// Set chart height within specified area
$chart_areaheight = '80%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_text		  = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Longhorn Distribution'					;	// Set top report title
$chart_fontsize	  = '11'									;	// Set chart font size
$chart_barwidth	  = '85%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'st_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'st_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sttable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Longhorn Data'							;	// Set table title
$tablehead1 	  = 'Service Type'							;	// Set first table header title
$tablehead2 	  = 'Total Services'						;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build ST chart
$dealerhold1 = array(array());
$dealerhold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($r = $resultst->fetch_row()) {
	$ra = $resultst2->fetch_row();
	$dealerhold1[$row][0] = $r[0];
	$dealerhold1[$row][1] = $r[1];
	$dealerhold1[$row][2] = $r[2];
	$dealerhold2[$row][0] = $ra[0];
	$dealerhold2[$row][1] = $ra[1];
	$dealerhold2[$row][2] = $ra[2];
	
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (float) number_format($r[2],0));
	$temp[] = array('v' => (float) number_format($ra[2],0));
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

for ($row = 0; $row < $strows; ++$row)
{
	echo				'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][0].     '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][2].'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold2[$row][2].'%'. '</td>
						</tr>';
}						
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set ST SI report title and chart options with specified variables
$chartarraytitle1= 'Longhorn'								;	// Set title to appear in chart legend
$chartarraytitle2= '% Service'								;	// Set title to appear in chart legend
$chart_div		 = 'stsichart'								;   // Set chart div name
$chart_color1	 = '#3369e8'								;	// Set 1st group chart color
$chart_color2	 = '#CCCCCC'								;	// Set 2nd group chart color
$chart_callback  = 'drawstsichart'							;   // Set chart callback variable
$chart_height	 = '550'									;	// Set chart height within specified area
$chart_areaheight= '80%'									;   // Set chart area
$chart_areawidth = '90%'									;   // Set chart area width
$chart_top		 = '20'										;	// Set chart distance from chart area top
$chart_text		 = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle = '90'										;   // Set x-axis title text angle
$chart_title	 = 'Single Issue Longhorn Distribution'		;	// Set top report title
$chart_fontsize	 = '11'										;	// Set chart font size
$chart_barwidth	 = '85%'									;	// Set chart bar width
$chart_gridcount = '14'										;   // Set number of gridlines
$chart_numformat = '#\'%\''									;   // Set number format
$chart_minvalue  = '0'										;   // Set chart minimum value
$exportanchor  	 = 'st_c_si_x.php'							;	// Set export anchor reference
$printeranchor 	 = 'st_c_si_p.php'							; 	// Set printer friendly anchor reference
$tableid		 = 'stsitable'								;   // Set table id for tablesorter functionality
$tabletitle 	 = 'Single Issue Longhorn Data'				;	// Set table title
$tablehead1 	 = 'Service Type'							;	// Set first table header title

// Build ST SI chart
$dealerhold1 = array(array());
$dealerhold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($r = $resultst_si->fetch_row()) {
	$ra = $resultst2_si->fetch_row();
	$dealerhold1[$row][0] = $r[0];
	$dealerhold1[$row][1] = $r[1];
	$dealerhold1[$row][2] = $r[2];
	$dealerhold2[$row][0] = $ra[0];
	$dealerhold2[$row][1] = $ra[1];
	$dealerhold2[$row][2] = $ra[2];
	
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (float) number_format($r[2],0));
	$temp[] = array('v' => (float) number_format($ra[2],0));
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

for ($row = 0; $row < $strows; ++$row)
{
	echo				'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][0].     '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold1[$row][2].'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$dealerhold2[$row][2].'%'. '</td>
						</tr>';
}						
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set LOF D report title and chart options with specified variables
$chartarraytitle1 = 'LOF Demand'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% LOF Demand'							;	// Set title to appear in chart legend
$chart_div		  = 'lofdchart'								;   // Set chart div name
$chart_color1	  = '#ff8c00'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawlofdchart'							;   // Set chart callback variable
$chart_height	  = '575'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'LOF Demand Distribution'				;	// Set top report title
$chart_fontsize	  = '14'									;	// Set chart font size
$chart_barwidth	  = '50%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'lofd_c_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'lofd_c_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'lofdtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'LOF Demand Data'						;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build LOF D chart
$rows = array();
$temp = array();
/*  Generate array elements for average labor  */
$temp[] = array('v' =>  'ROs With LOF')	;
$temp[] = array('v' => number_format($percent_LOF,0))	;
$temp[] = array('v' => number_format($percent_LOF2,0))	;
$rows[] = array('c' => $temp);
/*  Generate array elements for average parts  */	
$temp = array();
$temp[] = array('v' =>  'SI ROs With LOF')				;
$temp[] = array('v' => number_format($percent_SILOF,0))	;
$temp[] = array('v' => number_format($percent_SILOF2,0));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;"> ROs With LOF      </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_LOF.'%'.  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_LOF2.'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    SI ROs With LOF	  </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_SILOF.'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_SILOF2.'%'.'</td>
						</tr>';
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set LOF B report title and chart options with specified variables
$chartarraytitle1 = 'LOF Baseline'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% LOF Baseline'						;	// Set title to appear in chart legend
$chart_div		  = 'lofbchart'								;   // Set chart div name
$chart_color1	  = '#00933B'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawlofbchart'							;   // Set chart callback variable
$chart_height	  = '575'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'LOF Baseline Distribution'				;	// Set top report title
$chart_fontsize	  = '14'									;	// Set chart font size
$chart_barwidth	  = '40%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '$\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'lofb_c_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'lofb_c_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'lofbtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'LOF Baseline Data'						;	// Set table title
$tablehead1 	  = 'Average Labor'							;	// Set first table header title
$tablehead2 	  = 'Average Parts'							;	// Set second table header title
$tablehead3 	  = 'Avg Labor & Parts'						;	// Set third table header title

// Build LOF B chart
$rows = array();
$temp = array();
/*  Generate array elements for average labor  */
$temp[] = array('v' =>  'Average Labor');
$temp[] = array('v' => (float) number_format($averagelabor,2));
$temp[] = array('v' => (float) number_format($averagelabor2,2));
$rows[] = array('c' => $temp);
/*  Generate array elements for average parts  */	
$temp = array();
$temp[] = array('v' =>  'Average Parts');
$temp[] = array('v' => (float) number_format($averageparts,2));
$temp[] = array('v' => (float) number_format($averageparts2,2));
$rows[] = array('c' => $temp);
/*  Generate array elements for average parts+labor */
$temp = array();
$temp[] = array('v' =>  'Average Parts & Labor');
$temp[] = array('v' => (float) number_format($averagepartspluslabor,2));
$temp[] = array('v' => (float) number_format($averagepartspluslabor2,2));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Average Labor						 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averagelabor,2). '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averagelabor2,2). '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Average Parts 						 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averageparts,2).	'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averageparts2,2). '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Avg Labor & Parts				 			 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averagepartspluslabor,2).'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .'$' .number_format($averagepartspluslabor2,2).'</td>
						</tr>';
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set SI report title and chart options with specified variables
$chartarraytitle1 = 'Single Issue Occurrence'				;	// Set title to appear in chart legend
$chartarraytitle2 = '% Occurrence'							;	// Set title to appear in chart legend
$chart_div		  = 'sichart'								;   // Set chart div name
$chart_color1	  = '#0266C8'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawsichart'							;   // Set chart callback variable
$chart_height	  = '575'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Single Issue Distribution'				;	// Set top report title
$chart_fontsize	  = '14'									;	// Set chart font size
$chart_barwidth	  = '55%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'si_c_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'si_c_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sitable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Data'						;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build SI chart
$rows = array();
$temp = array();
$temp[] = array('v' =>  'Single Issue');
$temp[] = array('v' => number_format($percentsingle,0));
$temp[] = array('v' => number_format($percentsingle2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for multiple issue  */	
$temp = array();
$temp[] = array('v' =>  'Multiple Issue');
$temp[] = array('v' => number_format($percentmultiple,0));
$temp[] = array('v' => number_format($percentmultiple2,0));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    Single Issue ROs    	</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percentsingle.'%'.   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percentsingle2.'%'.  '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    Multiple Issue ROs	 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percentmultiple.'%'.  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percentmultiple2.'%'. '</td>
						</tr>';
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set SI C report title and chart options with specified variables
$chartarraytitle1 = 'Single Issue Category'					;	// Set title to appear in chart legend
$chartarraytitle2 = '% Single Issue Category'				;	// Set title to appear in chart legend
$chart_div		  = 'sicchart'								;   // Set chart div name
$chart_color1	  = '#8B4789'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawsicchart'							;   // Set chart callback variable
$chart_height	  = '575'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Single Issue Category Distribution'	;	// Set top report title
$chart_fontsize	  = '14'									;	// Set chart font size
$chart_barwidth	  = '64%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'sic_c_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'sic_c_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sictable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Category Data'			;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build SI C chart
$rows = array();
$temp = array();
/*  Generate array elements for Level 1 Services  */
$temp[] = array('v' =>  'Level 1 Services');
$temp[] = array('v' => number_format($percent_level1,0));
$temp[] = array('v' => number_format($percent_level12,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for total wear maintenance */
$temp = array();
$temp[] = array('v' =>  'Wear Maintenance');
$temp[] = array('v' => number_format($percent_wm,0));
$temp[] = array('v' => number_format($percent_wm2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for total repair */
$temp = array();
$temp[] = array('v' =>  'Repair Services');
$temp[] = array('v' => number_format($percent_repair,0));
$temp[] = array('v' => number_format($percent_repair2,0));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') ;
include('templates/comparisonbody_printall.php')		  ;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    Level 1 Services	   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level1.'%'. '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level12.'%'.'</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Wear Maintenance </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_wm.'%'. 	 '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_wm2.'%'. 	 '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Repair Services	</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_repair.'%'.  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_repair2.'%'. '</td>
						</tr>';
include ('templates/footer_printall.php');

/*----------------------------------------------------------------------------------------------------------*/
// Set SVC D report title and chart options with specified variables
$chartarraytitle1 = 'Service Demand'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Demand'								;	// Set title to appear in chart legend
$chart_div		  = 'sdchart'								;   // Set chart div name
$chart_color1	  = '#008B8B'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawsdchart'							;   // Set chart callback variable
$chart_height	  = '575'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Service Demand Distribution'			;	// Set top report title
$chart_fontsize	  = '14'									;	// Set chart font size
$chart_barwidth	  = '55%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'sd_c_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'sd_c_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sdtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Service Demand Data'					;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build SI C chart
$rows = array();
$temp = array();
$temp[] = array('v' =>  'Level 1 Demand');
$temp[] = array('v' => number_format($percent_level1_sd,0));
$temp[] = array('v' => number_format($percent_level1_sd2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for Level 2 Demand  */	
$temp = array();
$temp[] = array('v' =>  'Level 2 Demand'); 
$temp[] = array('v' => number_format($percent_level2_sd,0));
$temp[] = array('v' => number_format($percent_level2_sd2,0));
$rows[] = array('c' => $temp);
/*  Generate array elements for other portion  */
$temp = array();
$temp[] = array('v' =>  'Full Service'); 
$temp[] = array('v' => number_format($percent_full_sd,0));
$temp[] = array('v' => number_format($percent_full_sd2,0));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/columnchart_comparison.php') 	;
include('templates/comparisonbody_printall.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Level 1 Demand		   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level1_sd.'%'.  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level1_sd2.'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Level 2 Demand 	   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level2_sd.'%'.  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_level2_sd2.'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">       Full Service		   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_full_sd.'%'.	  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$percent_full_sd2.'%'.   '</td>
						</tr>';
include ('templates/footer_printall_end.php');  
?>