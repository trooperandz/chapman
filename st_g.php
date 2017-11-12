<?php
/* -----------------------------------------------------------------------------------*
   Program: servicetypequeryandchartglobal.php

   Purpose: Report of mileage spread bar chart and data chart
   History:
    Date		Description												by
	08/02/2014	Update to mysqli & one query							Matt Holland
	08/14/2014	Add multidealer selection								Matt Holland
	08/17/2014	Fix multidealer prob when dealer not found.				Matt Holland
	08/17/2014	Mispelled $_SESSION as $SESSION							Matt Holland
	10/27/2014	Add survey type processing ($globalsurveyindex_id)		Matt Holland
				for report and menu
	10/28/2014	Added standard includes to be used across all global	Matt Holland
				reports including customized report variables
	11/05/2014	Changed surveyindex_id = $globalsurveyindex_id to
				surveyindex_id IN ($globalsurveyindex_id)
				to accommodate select all surveys process				Matt Holland
	11/07/2014	Added report type processing for service string			Matt Holland
	11/26/2014	Changed $globalsurveyindex_id to $surveyindex_id		Matt Holland
	11/26/2014	Changed queries to includes								Matt Holland
	12/17/2014	Updated chart variables and standardized more includes	Matt Holland
*--------------------------------------------------------------------------------------*/

// Required system includes
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include ('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_global_vars.php');

/*----------------------------------------Retrieve report type for service string----------------------------------------------*/
include('templates/set_reporttype_global.php');

/*---------------------------------------------Service categories processing---------------------------------------------------*/
// Query longhorn_svcs table to get Longhorn category values
include('templates/st_string_processing.php');

// Include page / chart variable settings
include ('templates/chart_specs.php');

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
	include ('templates/query_st_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_global.php');	
	include ('templates/query_st_global.php');
	// Reset $resultst2 for use below
	$resultst = $resultst2;
}

include ('templates/header_reports.php')	;
?>
<script>
// Load the Visualization API and the column chart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.arrayToDataTable([
		['Service Type', 'Total ROs', { role: 'style' }, { role: 'annotation' } ],
		<?php
		$resultst->data_seek(0);
		for ($i=0; $i<$strows; $i++) {
			$item = $resultst->fetch_row();
			// Ensure that data column zero is not null. If null, will receive a chart error
			if ($item[2] == '') {
				$item[2] = 0;
			}
			// Build chart array elements
			if ($i<9) {
				echo "['".$item[0]."',".$item[2].",'color: #3369E8','".number_format($item[2],0)."'],";
			} elseif ( ($i>8) && ($i<=23) ) {
				echo "['".$item[0]."',".$item[2].",'color: #008B8B','".number_format($item[2],0)."'],";
			} elseif ( ($i>23) && ($i<26) ) {
				echo "['".$item[0]."',".$item[2].",'color: #FF8C00','".number_format($item[2],0)."'],";
			} else {
				echo "['".$item[0]."',".$item[2].",'color: #FF8C00','".number_format($item[2],0)."']";
			}	
		}
		?>
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

<script>
// Function for checking/unchecking all services menu selections
	$(document).ready(function() {
		$('#selectall').click(function(event) { // on click
			if(this.checked) { // Check select status
				$('.longhornbox').each(function() {	// Loop through each checkbox
					this.checked = true;  // Select all checkboxes with class "longhornbox"
				});
			} else {
				$('.longhornbox').each(function() { // Loop through each checkbox
					this.checked = false; // deselect all checkboxes
				});
			}
		});
	});
</script>

<?php
include ('templates/menubar_global_longhorn_report.php');
include ('templates/globalbody.php')					;
	
$resultst->data_seek(0);	
for ($j=0; $j<$strows; ++$j) {
     $item = $resultst->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
include ('templates/footer_reports.php');
?>