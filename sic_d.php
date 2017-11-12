<?php 
require_once("functions.inc");
include ('templates/login_check.php');
/* --------------------------------------------------------------------------*
   Program: singleissuecategory.php

   Purpose: Report of single issue categories
   History:
    Date		Description										by
	01/24/2014	Initial design and coding						Matt Holland
	05/01/2014	Eliminate duplicate queries for two reports.	Matt Holland
	10/22/2014	Add service strings to queries					Matt Holland
	10/27/2014	Add survey type processing ($surveyindex_id)	Matt Holland
				for report and menu
	11/06/2014	Updated template includes to contain 
				standardized report variables					Matt Holland			
	12/03/2014	Updated with standard query includes			Matt Holland
	12/17/2014	Updated chart variables and standardized 
				more includes									Matt Holland
 *--------------------------------------------------------------------------*/

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

/*----------------------------------------------------------------------------------------------------*/
// SI Category string processing
include('templates/sicategory_string.php');

// Include page / chart variable settings
include ('templates/chart_specs.php');

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

/*---------------------------------------------Dealer queries---------------------------------------------------*/
// SIC query
include ('templates/query_sic_md1.php');

// Consolidate computations
include ('templates/query_sic_summary.php');

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
			['Level 1 Services', <?=$percent_level1?>, 'color: #8B4789', '<?=number_format($percent_level1,0)?>%' ],
			['Wear Maintenance', <?=$percent_wm?>, 'color: #1A4081', '<?=number_format($percent_wm,0)?>%' ],
			['Repair Services', <?=$percent_repair?>, 'color: #8C8C8C', '<?=number_format($percent_repair,0)?>%' ]
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
include ('templates/dealerbody.php');

echo'					<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Level 1 Services 						</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_level1.  					   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_level1,2).'%'. '</td>
						</tr>				
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Wear Maintenance					</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_wm.  					   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_wm,2).'%'. '</td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Repair Services						</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_repair.  					   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_repair,2).'%'. '</td>
						</tr>';
include ('templates/footer_reports.php');
?>