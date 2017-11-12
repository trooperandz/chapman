<?php 
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: demand1and2queryglobal.php

   Purpose: Report of demand 1 & 2
   History:
    Date		Description										by
	01/24/2014	Initial design and coding						Matt Holland
	04/24/2014	Implement pie chart w/google charts.			Matt Holland
	05/01/2014	Eliminate duplicate queries for two reports.	Matt Holland
	08/04/2014	Convert to mysqli								Matt Holland
	10/27/2014	Add survey type processing ($surveyindex_id)	Matt Holland
				for report and menu
	11/06/2014	Updated template includes to contain 
				standardized report variables					Matt Holland			
	12/03/2014	Updated with standard query includes			Matt Holland
	12/17/2014	Updated chart variables and standardized 
				more includes									Matt Holland
	01/14/2015	Updated Service Demand portion - changed to 
				column chart									Matt Holland			
 *-----------------------------------------------------------------------*/	

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']; 	// Initialize $dealerID variable
$dealerIDs1 = $_SESSION['dealerID'];  	// Initialize dealer variable for query includes
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable
$userID		= $user->userID;			// Initialize user

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

// Include page / chart variable settings
include ('templates/chart_specs.php');

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

// Generate serviceID strings for queries
include ('templates/sd_strings.php');

/*---------------------------------------------Dealer queries---------------------------------------------------*/

// To compute the total # rows for denominator	
include ('templates/query_totalros_md1.php');

// SVC D queries
include ('templates/query_svcd_md1.php');

/*  Consolidate computations  */
include ('templates/query_svcd_summary.php');

include ('templates/header_reports.php');
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
include ('templates/menubar_dealer_reports.php');
include ('templates/dealerbody.php')			;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 1 Demand		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level1_sd. 		 				'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level1_sd,2).'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 2 Demand		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level2_sd. 		 				'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level2_sd,2).'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Full Service		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_full_sd. 		 				  	'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_full_sd,2).'%'.  	'</td>
						</tr>';
include ('templates/footer_reports.php');
?>