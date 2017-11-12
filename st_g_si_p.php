<?php
/*------------------------------------------------------------------------------------------------------------*
   Program: st_g_si_p.php

   Purpose: Printer-friendly single issue Longhorn global report
   History:
    Date		Description														by
	01/22/2015	Created Single Issue report from original ST report				Matt Holland
	03/11/2015	Redesigned chart with new array structure, and introduced		Matt Holland
				colors to highlight L1, L2 and Full Service items
/*------------------------------------------------------------------------------------------------------------*/

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
	// Multidealer queries
	include ('templates/query_totalros_si_md1.php');
	include ('templates/query_st_si_md1.php');
	/*------------------------------------------------*/
	// Global queries
} else {
	include ('templates/query_totalros_si_global.php');	
	include ('templates/query_st_si_global.php');
	// Reset $resultst2 for use below
	$resultst_si = $resultst2_si;
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
		$resultst_si->data_seek(0);
		for ($i=0; $i<$strows; $i++) {
			$item = $resultst_si->fetch_row();
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
			
$resultst_si->data_seek(0);		
for ($j = 0 ; $j < $strows ; ++$j)
{
			$row = $resultst_si->fetch_row();
		  echo '<tr>
					<td>' .$row[0].     '</td>
					<td>' .$row[1].     '</td>
					<td>' .$row[2].'%'. '</td>
				</tr>';
}
include ('templates/footer_printview.php');     
?>