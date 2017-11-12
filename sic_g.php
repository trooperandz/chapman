<?php // enterrofoundation.php
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------------------*
   Program: singleissuecategoryglobal.php

   Purpose: Report of single issue categories
   History:
    Date		Description												by
	07/29/2014	Adapt original report to comparison						Matt Holland
	07/29/2014	Convert to mysqli										Matt Holland
	08/14/2014	Add multidealer selection								Matt Holland
	08/17/2014	Fix multidealer prob when dealer not found.				Matt Holland
	08/17/2014	Mispelled $_SESSION as $SESSION							Matt Holland
	10/22/2014	Add service strings to queries							Matt Holland
	10/27/2014	Add survey type processing ($globalsurveyindex_id)		Matt Holland
				for report and menu
	10/28/2014	Added standard includes to be used across all global	Matt Holland
				reports including customized report variables
	11/05/2014	Changed surveyindex_id = $globalsurveyindex_id to
				surveyindex_id IN ($globalsurveyindex_id)
				to accommodate select all surveys process				Matt Holland
	11/26/2014	Changed $globalsurveyindex_id to $surveyindex_id		Matt Holland
	11/26/2014	Changed queries to includes								Matt Holland
	12/17/2014	Updated chart variables and standardized 				Matt Holland
				more includes
 *-----------------------------------------------------------------------------------*/	

// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];					// Dealercode set at login
$dealerID = $_SESSION['dealerID'];  					// Dealer ID set at login

// Include page / chart variable settings
include ('templates/chart_specs.php');

/*----Set survey variables and globals for report with include-------*
$_SESSION['globalsurveyindex_id'] 	  = $surveyindex_id;
$_SESSION['globalsurvey_description'] = $globalsurvey_description;
$_SESSION['lastpageglobalreports']	 // Returns processes to last page
$totaldealers_persurvey 			 // Total dealer count per survey
---------------------------------------------------------------------*/
include('templates/setsurveyglobals_globalreports.php');
include('templates/sicategory_string.php');

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
	include ('templates/query_sic_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_sic_global.php');	
	// Reset variables for use below
	$total_level1 	= $total_level12	;
	$total_wm	  	= $total_wm2		;
	$total_repair 	= $total_repair2  	;
	$percent_level1 = $percent_level12	;
	$percent_wm		= $percent_wm2		;
	$percent_repair = $percent_repair2  ;
}
// Summary Query
include ('templates/query_sic_summary.php');

include ('templates/header_reports.php')		;
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
include ('templates/menubar_global_reports.php');
include ('templates/globalbody.php')			;

echo'					<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Level 1 Services 							   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_level1.  						  '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_level1,2).'%'.   '</td>
						</tr>				
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Wear Maintenance								</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_wm.  						   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_wm,2).'%'.   '</td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Repair Services								</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_repair.  						   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_repair,2).'%'.    '</td>
						</tr>';
include ('templates/footer_reports.php');
?>