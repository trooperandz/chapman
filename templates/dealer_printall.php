<?php // enterrofoundation.php
require_once("functions.inc");
$user = new User;
if (!$user->isLoggedIn) {
	die(header("Location: loginform.php"));
}
/* ----------------------------------------------------------------------*
   Program: yearmodelqueryandchartprintview.php

   Purpose: Printer-friendly format of lof baseline report
   History:
    Date		Description									by
	08/06/2014	Convert to mysqli							Matt Holland
	08/26/2014	Revamp format with external stylesheet		Matt Holland
	11/08/2014	Update to include dealer template includes	Matt Holland
	11/16/2014	Update yearmodel_string processing			Matt Holland
	12/03/2014	Update with standard query includes			Matt Holland
 *-----------------------------------------------------------------------*/
// Database connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID 	= $_SESSION['dealerID']		;  // Initialize $dealerID variable
$dealerIDs1 = $_SESSION['dealerID']		;  // Initialize $dealerIDs1 for queries
$dealercode = $_SESSION['dealercode']	;  // Initialize $dealercode variable
$userID		= $user->userID				;  // Initialize user

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

/* Set last page dealer variable */
include ('templates/lastpagevariable_dealerreports_include.php');

// Set $currentyear for yearmodel_string processing
$currentyear = date('Y');
$month = date('m');
if ($month > 8) {
	$currentyear = date('Y')+1;
}
/*---------------------------------Retrieve yearmodel_string for queries--------------------------------------*/

// First check to see if yearmodel_string for $dealerID, $surveyindex_id and $userID has been entered
$query = "SELECT yearmodel_string FROM yearmodel_strings WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id and userID = $userID";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Line 55: yearmodel_strings SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;

if ($rows == 0) {
	// Now check to see if default string has been entered (would occur if an RO for particular $dealerID and $surveyindex_id has been previously entered)
	$query = "SELECT yearmodel_string FROM yearmodel_strings WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id and userID = 0";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "Line 64: yearmodel_strings SELECT query failed.  See administrator.";
	}
	$rows = $result->num_rows;
	if ($rows > 0) {
		$yearmodel_stringvalue = $result->fetch_assoc();
		$yearmodel_string	   = $yearmodel_stringvalue['yearmodel_string'];
	} else {
		// If no rows from above query, report must generate its own string based on current year
		$query = "SELECT yearmodelID, modelyear FROM yearmodel WHERE modelyear <= $currentyear";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "Line 75: yearmodel_strings SELECT query failed.  See administrator.";
		}
		$rows = $result->num_rows;
		// If no rows, alert user that value is not in table
		if ($rows == 0) {
			$_SESSION['error'][] = "The current year does not exist in the yearmodel table.  See administrator.";
			die(header("Location: enterrofoundation.php"));
		} else {
			// If rows > 0 , create default string for page
			$yearmodel_stringvalue = array(array());
			$index = 0;
			while ($value = $result->fetch_assoc()) {
				$yearmodel_stringvalue[$index]['yearmodelID'] = $value['yearmodelID'];
				$index += 1;
			}
			$yearmodel_string = "";
			for ($i=0; $i<$rows; $i++) {
				if ($i == $rows-1) {
					$yearmodel_string .= $yearmodel_stringvalue[$i]['yearmodelID'];
				} else {
					$yearmodel_string .= $yearmodel_stringvalue[$i]['yearmodelID']. ', ';
				}
			}
		}
	}		
// If yearmodel_string has been set for current $dealerID, $survey type & $userID, obtain yearmodel_string from query
} else {
	$yearmodel_stringvalue = $result->fetch_assoc();
	$yearmodel_string = $yearmodel_stringvalue['yearmodel_string'];
}
 //echo '$yearmodel_string: ' .$yearmodel_string. '<br>';
 
 /*---------------------------------------------Service categories processing---------------------------------------------------*/
include('templates/set_reporttype_dealer.php');

// Query longhorn_svcs table to get Longhorn category values
$query = "SELECT longhorn_string FROM longhorn_svcs WHERE userID = $userID AND report_type_id = $report_type_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "longhorn_svcs SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;
if($rows == 0) {
	// Set service defaults if there are no values in table for $userID
	$query = "SELECT serviceID FROM services ORDER BY servicesort ASC";
	$result2 = $mysqli->query($query);
	if (!$result2) {
		$_SESSION['error'][] = "longhorn_svcs SELECT query failed.  See administrator.";
		die(header("Location: enterrofoundation.php"));
	}
	$rows = $result2->num_rows;
	// Get service defaults from table as a string.  DO NOT hard code.
	$svcarray = array(array());
	$index = 0;
	while ($value = $result2->fetch_assoc()) {
		$svcarray[$index] = $value['serviceID'];
		$index += 1;
	}
	$Lhstring = "";
	for ($i=0; $i<$rows; $i++) {
		if ($i == $rows-1) {
			$Lhstring .= $svcarray[$i];
		} else {
			$Lhstring .= $svcarray[$i]. ", ";
		}
	}
	//echo '$Lhstring: ' .$Lhstring. '<br>';
} else {
$Lhvalue = $result->fetch_assoc();
$Lhstring = $Lhvalue['longhorn_string'];
//echo '$Lhstring: ' .$Lhstring. '<br>';
}
//echo 'Longhorn services: ' .$Lhstring. '<br>';
/*-------------------------------------------------------SI Category string processing----------------------------------------------*/
// Query si_category table to get Level 1 category values
$query = "SELECT category_string FROM si_category WHERE category_id = 1";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;
if($rows == 0) {
	$_SESSION['error'][] = "There are no Level 1 service defaults.";
}
$L1value = $result->fetch_assoc();
$L1string = $L1value['category_string'];
//echo 'L1 services: ' .$L1string. '<br>';

// Query si_category table to get Wear Maint category values
$query = "SELECT category_string FROM si_category WHERE category_id = 2";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;
if($rows == 0) {
	$_SESSION['error'][] = "There are no Wear Maintenance service defaults.";
}
$wmvalue = $result->fetch_assoc();
$wmstring = $wmvalue['category_string'];
//echo 'WM services: ' .$wmstring. '<br>';

// Query si_category table to get Repair category values
$query = "SELECT category_string FROM si_category WHERE category_id = 3";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;
if($rows == 0) {
	$_SESSION['error'][] = "There are no Repair service defaults.";
}
$repairvalue = $result->fetch_assoc();
$repairstring = $repairvalue['category_string'];
//echo 'Repair services: ' .$repairstring. '<br>';

/*----------------------------------------------------------------------------------------------------*/
// State all dealer queries

// Model Year Queries
	// Query for first set of total dealer rows
	include ('templates/query_totalros_md1.php');

	//  Query for first set dealer model year	
	include ('templates/query_ym_dlr.php');	
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');	
/*-----------------------------------------------------*/
// Longhorn Queries	
	// Query for first set of services
	include ('templates/query_totalsvcs_md1.php');

	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');		
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');
/*----------------------------------------------------------------------------------------------------*/
// Consolidate computations

/*  Consolidate LOF D computations  */
include ('templates/query_lofd_summary.php');

/*  Consolidate SI computations  */
include ('templates/query_si_summary.php');

/*  Consolidate SI C computations */
include ('templates/query_sic_summary.php');

/*  Consolidate SVC D computations */
include ('templates/query_svcd_summary.php'); 

/*----------------------------------------------------------------------------------------------------*/
// Set MY values
$chartarraytitle1 = 'Model Year'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Model Year'							;	// Set title to appear in chart legend
$chart_callback   = 'drawyearmodelchart'					;   // Set chart callback variable
$chart_div		  = 'yearmodelchart'						;   // Set chart div for chart rendering
$chart_title	  = 'Model Year Distribution'				;	// Set top report title
$chartarea_height = '81%'									;   // Set chart area
$chart_top		  = '60'									;	// Set chart distance from chart area top
$chartfont_size	  = '14'									;	// Set chart font size
$chartcolor1	  = '#D34836'								;	// Set 1st group chart color
$barwidth		  = '64%'									;	// Set chart bar width
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$exportanchor  	  = 'csvyearmodelexport.php'				;	// Set export anchor reference
$printeranchor 	  = 'yearmodelqueryandchartprintview.php'	; 	// Set printer friendly anchor reference
$tabletitle 	  = 'Model Year Data'						;	// Set table title
$tablehead1 	  = 'Model Year'							;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title
$table_id		  = 'yearmodeltable'						;	// Set table id (for tablesorter functionality)

// MY Body
include ('templates/chart_dealercolumn_array.php');

$rows = array();
while($r = $resultmy->fetch_row()) {
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (int) $r[2]);
	$rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/header_printreports.txt');
include ('templates/dealer_columnchart_printall.php');
include ('templates/dealerbody_printall.php');

$resultmy->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy->fetch_row();
	 
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}
include ('templates/dealer_footer_printall.php');

/*----------------------------------------------------------------------------------------------------*/
// Set MS values  
$chartarraytitle1 = 'Mileage Spread'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Mileage Spread'							;	// Set title to appear in chart legend
$chart_callback   = 'drawmileagechart'							;   // Set chart callback variable
$chart_div		  = 'mileagespreadchart'						;   // Set chart div for chart rendering
$chart_title	  = 'Mileage Spread Distribution'				;	// Set top report title
$chartarea_height = '81%'										;   // Set chart area
$chart_top		  = '60'										;	// Set chart distance from chart area top
$tabletitle 	  = 'Mileage Spread Data'						;	// Set table title
$chartfont_size	  = '13'										;	// Set chart font size
$chartcolor1	  = '#EEB211'									;	// Set 1st group chart color
$barwidth		  = '55%'										;	// Set chart bar width
$chart_text		  = '0'											;	// Set chart text as slanted (1) or not slanted (0)
$exportanchor  	  = 'csvmileageexport.php'						;	// Set export anchor reference
$printeranchor 	  = 'mileagespreadqueryandchartprintview.php'	; 	// Set printer friendly anchor reference
$tablehead1 	  = 'Mileage Spread'							;	// Set first table header title
$tablehead2 	  = 'Total ROs'									;	// Set second table header title
$tablehead3 	  = 'Percentage'								;	// Set third table header title
$table_id		  = 'mileagetable'								;	// Set table id (for tablesorter functionality)

// MY Body
include ('templates/chart_dealercolumn_array.php');

$rows = array();
while($r = $resultms->fetch_row()) {
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (int) $r[2]);
	$rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/dealer_columnchart_printall.php');
include ('templates/dealerbody_printall.php');

$resultms->data_seek(0);	
for ($j=0; $j<$msrows; ++$j) {
     $item = $resultms->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}
include ('templates/dealer_footer_printall.php');

/*----------------------------------------------------------------------------------------------------*/
// Set ST values
$chartarraytitle1 = 'Service Type'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Service'								;	// Set title to appear in chart legend
$chart_callback   = 'drawservicechart'						;   // Set chart callback variable
$chart_div		  = 'servicetypechart'						;   // Set chart div for chart rendering
$chart_title	  = 'Longhorn Distribution'					;	// Set top report title
$chartarea_height = '81%'									;   // Set chart area
$chart_top		  = '45'									;	// Set chart distance from chart area top
$tabletitle 	  = 'Longhorn Data'							;	// Set table title
$chartfont_size	  = '13'									;	// Set chart font size
$chartcolor1	  = '#3369e8'								;	// Set 1st group chart color
$barwidth		  = '64%'									;	// Set chart bar width
$chart_text		  = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$exportanchor  	  = 'csvservicesexport.php'					;	// Set export anchor reference
$printeranchor 	  = 'servicetypequeryandchartprintview.php'	; 	// Set printer friendly anchor reference
$tablehead1 	  = 'Longhorn'								;	// Set first table header title
$tablehead2 	  = 'Total Services'						;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// ST Body
include ('templates/chart_dealercolumn_array.php');

$rows = array();
while($r = $resultst->fetch_row()) {
	$temp = array();
	$temp[] = array('v' => $r[0]);
	$temp[] = array('v' => (int) $r[2]);
	$rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/dealer_columnchart_printall.php');
include ('templates/dealerbody_printall.php');

$resultst->data_seek(0);	
for ($j=0; $j<$strows; ++$j) {
     $item = $resultst->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}

include ('templates/dealer_footer_printall.php');

/*----------------------------------------------------------------------------------------------------*/
// Set LOF D values
$chartarraytitle1 = 'LOF Demand'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% LOF Demand'							;	// Set title to appear in chart legend
$chart_callback   = 'drawlofdemandchart'					;   // Set chart callback variable
$chart_div		  = 'lofdemandchart'						;   // Set chart div for chart rendering
$chart_title	  = 'LOF Demand Distribution'				;	// Set top report title
$chartarea_height = '75%'									;   // Set chart area
$chart_top		  = '85'									;	// Set chart distance from chart area top
$chart_height	  = '585'									;	// Set pie area height
$chart_border	  = '#FFFFFF'								;   // Set piechart slice border color
$tabletitle 	  = 'LOF Demand Data'						;	// Set table title
$chartfont_size	  = '15'									;	// Set chart font size
$exportanchor  	  = 'csvlofdemandexport.php'				;	// Set export anchor reference
$printeranchor 	  = 'lofdemandqueryprintview.php'			; 	// Set printer friendly anchor reference
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title
$table_id		  = 'lofdemandtable'						;	// Set table id (for tablesorter functionality)

// Build LOF D body
$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'MI ROs With LOF', 'type' => 'string'),
	array('label' => '% LOF', 'type' => 'number'),
    array('label' => 'SI ROs With LOF', 'type' => 'string'),
	array('label' => '% SI LOF', 'type' => 'number'),
	array('label' => 'ROs With NO LOF', 'type' => 'string'),
	array('label' => '% Other', 'type' => 'number')
);

/*  Generate array elements for ROs with LOF  */
$rows = array();
$temp = array();
$temp[] = array('v' =>  'MI ROs With LOF');
$percentMILOF = ($MILOFrows/$totalros)*100;
$temp[] = array('v' => $percentMILOF);
$rows[] = array('c' => $temp);
/*  Generate array elements for SI ROs with LOF  */	
$temp = array();
$temp[] = array('v' =>  'SI ROs With LOF');
$percentSILOF = ($SILOFrows/$totalros)*100;
$temp[] = array('v' => $percentSILOF);
$rows[] = array('c' => $temp);
/*  Generate array elements for ROs with no LOF   */	
$temp = array();
$temp[] = array('v' =>  'ROs With No LOF');
$percentother_lofd = ($total_other_lofd/$totalros)*100;
$temp[] = array('v' => $percentother_lofd);
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/dealer_piechart_printall.php');
include ('templates/dealerbody_printall.php');

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">   Total ROs With LOF    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$MILOFrows. 		  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_MILOF.'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    SI ROs With LOF 	  </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$SILOFrows. 	   	 '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_SILOF.'%'.'</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">          Other	           </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$total_other_lofd. 	  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_other_lofd.'%'.'</td>
						</tr>';

include ('templates/dealer_footer_printall.php');

/*----------------------------------------------------------------------------------------------------*/
// Set LOF B values
$chartarraytitle1 = 'LOF Baseline'							;	// Set title to appear in chart legend
$chartarraytitle2 = '$$ Average Dollars'					;	// Set title to appear in chart legend
$chart_callback   = 'drawlofbaselinechart'					;   // Set chart callback variable
$chart_div		  = 'lofbaselinechart'						;   // Set chart div for chart rendering
$chart_title	  = 'LOF Baseline Distribution'				;	// Set top report title
$chartarea_height = '81%'									;   // Set chart area
$chart_top		  = '60'									;	// Set chart distance from chart area top
$tabletitle 	  = 'LOF Baseline Data'						;	// Set table title
$chartfont_size	  = '14'									;	// Set chart font size
$chartcolor1	  = '#00933B'								;	// Set 1st group chart color
$barwidth		  = '40%'									;	// Set chart bar width
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$exportanchor  	  = 'csvlofbaselineexport.php'				;	// Set export anchor reference
$printeranchor 	  = 'lofbaselinequery_columnprintview.php'	; 	// Set printer friendly anchor reference
$tablehead1 	  = 'Average Labor'							;	// Set first table header title
$tablehead2 	  = 'Average Parts'							;	// Set second table header title
$tablehead3 	  = 'Avg Labor & Parts'						;	// Set third table header title
$table_id		  = 'lofbaselinetable'						;	// Set table id (for tablesorter functionality)

// Build LOF B body
include ('templates/chart_dealercolumn_array.php');

$rows = array();
$temp = array();
/*  Generate array elements for average labor  */
$temp[] = array('v' =>  'Average Labor');
$temp[] = array('v' => (float) number_format($averagelabor,2));
$rows[] = array('c' => $temp);
/*  Generate array elements for average parts  */	
$temp = array();
$temp[] = array('v' =>  'Average Parts');
$temp[] = array('v' => (float) number_format($averageparts,2));
$rows[] = array('c' => $temp);
/*  Generate array elements for average parts+labor */
$temp = array();
$temp[] = array('v' =>  'Average Parts & Labor');
$temp[] = array('v' => (float) number_format($averagepartspluslabor,2));
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/dealer_columnchart_printall.php');
include ('templates/dealerbody_printall.php');

echo'				<tr>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averagelabor,2). 		   '</td>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averageparts,2). 		   '</td>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averagepartspluslabor,2). '</td>
 					</tr>';

include ('templates/dealer_footer_printall.php');

/*----------------------------------------------------------------------------------------------------*/
// Set SI values
$chartarraytitle1 = 'Single Issue Occurrence'				;	// Set title to appear in chart legend
$chartarraytitle2 = '% Occurrence'							;	// Set title to appear in chart legend
$chart_callback   = 'drawsingleissuechart'					;   // Set chart callback variable
$chart_div		  = 'singleissuechart'						;   // Set chart div for chart rendering
$chart_title	  = 'Single Issue Occurrence Distribution'	;	// Set top report title
$chartarea_height = '75%'									;   // Set chart area
$chart_top		  = '85'									;	// Set chart distance from chart area top
$chart_height	  = '585'									;	// Set pie area height
$chart_border	  = '#FFFFFF'								;   // Set piechart slice border color
$tabletitle 	  = 'Single Issue Occurrence Data'			;	// Set table title
$chartfont_size	  = '15'									;	// Set chart font size
$exportanchor  	  = 'csvsingleissuequery.php'				;	// Set export anchor reference
$printeranchor 	  = 'singleissuequeryprintview.php'			; 	// Set printer friendly anchor reference
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build SI body
$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'Single Issue Occurrence', 'type' => 'string'),
	array('label' => '% Single Issue', 'type' => 'number'),
    array('label' => 'Multiple Issue Occurrence', 'type' => 'string'),
	array('label' => '% Multiple Issue', 'type' => 'number')
);

/*  Generate array elements for single issue  */
$rows = array();
$temp = array();
$temp[] = array('v' =>  'Single Issue');
$percent_single = ($totalsingle/$totalrows)*100; 
$temp[] = array('v' => $percent_single);
$rows[] = array('c' => $temp);
/*  Generate array elements for multiple issue  */	
$temp = array();
$temp[] = array('v' =>  'Multiple Issue');
$percent_multiple = ($totalmultiple/$totalrows)*100;
$temp[] = array('v' => $percent_multiple);
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/dealer_piechart_printall.php');
include ('templates/dealerbody_printall.php');

echo'				<tr>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">           Single Issue ROs. 	           </td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalsingle.   					  '</td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentsingle,2).'%'. '</td>
					</tr>  
					<tr>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">          Multiple Issue ROs. 		    </td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalmultiple.                       '</td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentmultiple,2).'%'.'</td>
					</tr>';
					
include ('templates/dealer_footer_printall.php');


/*----------------------------------------------------------------------------------------------------*/
// Set SI C values
$chartarraytitle1 = 'Single Issue Category'				;	// Set title to appear in chart legend
$chartarraytitle2 = '% Service'							;	// Set title to appear in chart legend
$chart_callback   = 'drawsingleissuecatchart'			;   // Set chart callback variable
$chart_div		  = 'singleissuecatchart'				;   // Set chart div for chart rendering
$chart_title	  = 'Single Issue Category Distribution';	// Set top report title
$chartarea_height = '81%'								;   // Set chart area
$chart_top		  = '60'								;	// Set chart distance from chart area top
$tabletitle 	  = 'Single Issue Category Data'		;	// Set table title
$chartfont_size	  = '14'								;	// Set chart font size
$chartcolor1	  = '#666666'							;	// Set 1st group chart color
$barwidth		  = '45%'								;	// Set chart bar width
$chart_text		  = '0'									;	// Set chart text as slanted (1) or not slanted (0)
$exportanchor  	  = 'csvsingleissuecategory.php'		;	// Set export anchor reference
$printeranchor 	  = 'singleissuecategoryprintview.php'	; 	// Set printer friendly anchor reference
$tablehead1 	  = 'Category'							;	// Set first table header title
$tablehead2 	  = 'Total ROs'							;	// Set second table header title
$tablehead3 	  = 'Percentage'						;	// Set third table header title
$table_id		  = 'singleissuecattable'				;	// Set table id (for tablesorter functionality)

// Build SI C body
include ('templates/chart_dealercolumn_array.php');

$rows = array();
$temp = array();
/*  Generate array elements for Level 1 Services  */
$temp[] = array('v' =>  'Level 1 Services');
$temp[] = array('v' => (int) $percent_level1);
$rows[] = array('c' => $temp);
/*  Generate array elements for total wear maintenance */
$temp = array();
$temp[] = array('v' =>  'Wear Maintenance');
$temp[] = array('v' => (int) $percent_wm);
$rows[] = array('c' => $temp);
/*  Generate array elements for total repair */
$temp = array();
$temp[] = array('v' =>  'Repair Services');
$temp[] = array('v' => (int) $percent_repair);
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/dealer_columnchart_printall.php');
include ('templates/dealerbody_printall.php');

echo'					<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Level 1 Services 						 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_level1.  						'</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_level1,2).'%'.	'</td>
						</tr>				
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Wear Maintenance						</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_wm.  						   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_wm,2).'%'.     '</td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Repair Services						</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_repair.  					   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_repair,2).'%'. '</td>
						</tr>';

include ('templates/dealer_footer_printall.php');

/*----------------------------------------------------------------------------------------------------*/
// Set SVC D values
$chartarraytitle1 = 'Service Demand'				;	// Set title to appear in chart legend
$chartarraytitle2 = '% Demand'						;	// Set title to appear in chart legend
$chart_callback   = 'drawservicedemandchart'		;   // Set chart callback variable
$chart_div		  = 'servicedemandchart'			;   // Set chart div for chart rendering
$chart_title	  = 'Service Demand Distribution'	;	// Set top report title
$chartarea_height = '75%'							;   // Set chart area
$chart_top		  = '85'							;	// Set chart distance from chart area top
$chart_height	  = '560'							;	// Set pie area height
$chart_border	  = '#FFFFFF'						;   // Set piechart slice border color
$tabletitle 	  = 'Service Demand Data'			;	// Set table title
$chartfont_size	  = '15'							;	// Set chart font size
$exportanchor  	  = 'csvdemand1and2export.php'		;	// Set export anchor reference
$printeranchor 	  = 'demand1and2queryprintview.php'	; 	// Set printer friendly anchor reference
$tablehead1 	  = 'Category'						;	// Set first table header title
$tablehead2 	  = 'Total ROs'						;	// Set second table header title
$tablehead3 	  = 'Percentage'					;	// Set third table header title
$table_id		  = 'servicedemandtable'			;	// Set table id (for tablesorter functionality)

// Build SVC D body
$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'Level 1 Demand', 'type' => 'string'),
	array('label' => '% L1', 'type' => 'number'),
    array('label' => 'Level 2 Demand', 'type' => 'string'),
	array('label' => '% L2', 'type' => 'number'),
	array('label' => 'Full Service', 'type' => 'string'),
	array('label' => '% Other', 'type' => 'number')
);

/*  Generate array elements for Level 1 Demand  */
$rows = array();
$temp = array();
$temp[] = array('v' =>  'Level 1 Demand');
$percentlevel1_sd = ($total_level1_sd/$totalros)*100;
$temp[] = array('v' => $percentlevel1_sd);
$rows[] = array('c' => $temp);
/*  Generate array elements for Level 2 Demand  */	
$temp = array();
$temp[] = array('v' =>  'Level 2 Demand');
$percentlevel2 = ($total_level2/$totalros)*100;
$temp[] = array('v' => $percentlevel2);
$rows[] = array('c' => $temp);
/*  Generate array elements for other portion  */
$temp = array();
$temp[] = array('v' =>  'Full Service');
$percentother_sd = ($total_other_sd/$totalros)*100;
$temp[] = array('v' => $percentother_sd);
$rows[] = array('c' => $temp);

$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/dealer_piechart_printall.php');
include ('templates/dealerbody_printall.php');

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 1 Demand		  					   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level1_sd. 		 				  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level1_sd,2).'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 2 Demand		  					   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level2. 		 				  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level2,2).'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Full Service		  					   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_other_sd. 		 				  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_other_sd,2).'%'.  '</td>
						</tr>';
echo'					
			</tbody>
		</table>
	</div>
</div>

<hr>

<footer id="footer2">
	&copy;&nbsp; Service Operations Specialists Inc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12211 West Markham&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Little Rock, AR 72211&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;866-406-9707
</footer>

</body>
</html>';     
?>