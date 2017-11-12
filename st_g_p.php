<?php
/* -------------------------------------------------------------------------------------------*
   Program: st_g_p.php

   Purpose: Report of Longhorn column chart and data chart
   History:
    Date		Description														by
	08/03/2014	Update to mysqli & one query									Matt Holland
	09/04/2014	Incorporate regional functionality								Matt Holland
	11/17/2014	Incorporate new template includes format						Matt Holland
	12/17/2014	Updated chart variables and standardized more includes			Matt Holland
*---------------------------------------------------------------------------------------------*/

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

include('templates/setsurveyglobals_globalreports.php');

if (isset($_SESSION['multidealer']) OR isset($_SESSION['regiondealerIDs'])) {
	if (isset($_SESSION['multidealer'])) {
		// Query for total dealer rows
		$dealerIDs1 = $_SESSION['multidealer'];
	} elseif (isset($_SESSION['regiondealerIDs'])) {
		// Query for total dealer rows
		$dealerIDs1 = $_SESSION['regiondealerIDs'];
	}
	/*-------------------------------------------------------------multidealer queries------------------------------------------------*/
	// Query for total service rows
	include ('templates/query_totalros_md1.php');

	// Service Type query	
	include ('templates/query_st_md1.php');
	
/*------------------------------------------------------------global queries--------------------------------------------------------*/
} else {
	// Query for total service rows
	include ('templates/query_totalros_global.php');

	// Service Type query	
	include ('templates/query_st_global.php');
	// Reset $resultst2 for use below
	$resultst = $resultst2;
}

include ('templates/header_printreports.php');
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
<?php
include ('templates/printview_globalbody.php');
			
$resultst->data_seek(0);		
for ($j = 0 ; $j < $strows ; ++$j)
{
			$row = $resultst->fetch_row();
		  echo '<tr>
					<td>' .$row[0].     '</td>
					<td>' .$row[1].     '</td>
					<td>' .$row[2].'%'. '</td>
				</tr>';
}
include ('templates/footer_printview.php');     
?>