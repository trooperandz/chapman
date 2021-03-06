<?php 
require_once("functions.inc");
include ('templates/login_check.php');
	
/* ----------------------------------------------------------------------*
   Program: demand1and2queryprintviewglobal.php

   Purpose: Report of Service Demand pie chart and data chart
   History:
    Date		Description									by
	08/03/2014	Update to mysqli & one query				Matt Holland
	09/04/2014	Incorporate regional functionality			Matt Holland
	12/01/2014	Rewrote with standard query includes		Matt Holland
	12/17/2014	Updated chart variables and standardized 	Matt Holland
				more includes
	01/14/2015	Updated Service Demand portion - changed to 
				column chart								Matt Holland
*-----------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];	// Dealercode set at login
$dealerID = $_SESSION['dealerID'];  	// Dealer ID set at login

// Include page / chart variable settings
include ('templates/chart_specs.php');


/*----Set survey variables and globals for report with include-------*
$_SESSION['globalsurveyindex_id'] 	  = $surveyindex_id;
$_SESSION['globalsurvey_description'] = $globalsurvey_description;
$_SESSION['lastpageglobalreports']	 // Returns processes to last page
$totaldealers_persurvey 			 // Total dealer count per survey
---------------------------------------------------------------------*/
include('templates/setsurveyglobals_globalreports.php');

// Generate serviceID strings for queries
include ('templates/sd_strings.php');

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
	include ('templates/query_svcd_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_global.php');	
	include ('templates/query_svcd_global.php');
	// Reset variables for use below
	$total_level1a = $total_level1a2	;
	$total_level1b = $total_level1b2	;
	$total_level2a = $total_level2a2	;
	$total_level2b = $total_level2b2	;
	$total_full_L1a= $total_full_L1a2	;
	$total_full_L1b= $total_full_L1b2	;
	$total_full_L3 = $total_full_L32	;
	$total_full_sd = $total_full_sd2	;
	$totalros 	   = $totalros2     	;
}
// Query Summary
include ('templates/query_svcd_summary.php');

include ('templates/header_printreports.php')	;
?>
<script>
// Load the Visualization API and the column chart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.arrayToDataTable([
			['Category', 'Total ROs', { role: 'style' }, { role: 'annotation' } ],
			['Level 1 Demand', <?=$percent_level1_sd?>, 'color: #173870', '<?=number_format($percent_level1_sd,0)?>%' ],
			['Level 2 Demand', <?=$percent_level2_sd?>, 'color: #008B8B', '<?=number_format($percent_level2_sd,0)?>%' ],
			['Full Service', <?=$percent_full_sd?>, 'color: #778899', '<?=number_format($percent_full_sd,0)?>%' ]
	]);
	
	var options = {                                          
					legend: {position: 'none'},
					height: <?=$chart_height?>,
					fontSize: <?=$chart_fontsize?>,
					chartArea: {height: "<?=$chart_areaheight?>", width: "<?=$chart_areawidth?>", top: <?=$chart_top?>, left: <?=$chart_arealeft?>},
					bar: {groupWidth: "<?=$chart_barwidth?>"},
					vAxis: {viewWindowMode: 'maximized', gridlines:{count: <?=$chart_gridcount?>}, minValue: <?=$chart_minvalue?>, format: "<?=$chart_numformat?>"},
					hAxis: {slantedText: <?=$chart_text?>, slantedTextAngle: <?=$chart_textangle?>},
					colors: ['<?=$chart_color1?>']
				};
	// Instantiate and draw our chart, passing in some options.
	//do not forget to check ur div ID
	var chart = new google.visualization.ColumnChart(document.getElementById('<?=$chart_div?>'));	
	chart.draw(data, options);
	}
	$(window).resize(function(){
	<?=$chart_callback?>();
	});
</script>
<?php
include ('templates/printview_globalbody.php')	;

echo'			<tr>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 1 Demand		  					 </td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level1_sd. 		 				'</td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level1_sd,2).'%'. '</td>
				</tr>
				<tr>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 2 Demand		  				  	 </td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level2_sd. 		 				'</td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level2_sd,2).'%'. '</td>
				</tr>
				<tr>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Full Service		  					 </td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_full_sd. 		 					'</td>
					<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_full_sd,2).'%'.  	'</td>
				</tr>';
include ('templates/footer_printview.php');
?>				