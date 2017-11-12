<?php 
require_once("functions.inc");
include ('templates/login_check.php');	
/* -------------------------------------------------------------------------------------------*
   Program: ms_g_p.php

   Purpose: Report of Mileage Spread column chart and data chart
   History:
    Date		Description														by
	08/03/2014	Update to mysqli & one query									Matt Holland
	09/04/2014	Incorporate regional functionality								Matt Holland
	11/17/2014	Incorporate new template includes format						Matt Holland
	12/17/2014	Updated chart variables and standardized more includes			Matt Holland
*---------------------------------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

$dealercode = $_SESSION['dealercode'];					// Dealercode set at login
$dealerID = $_SESSION['dealerID'];  					// Dealer ID set at login

// Include page / chart variable settings
include ('templates/chart_specs.php');

/*----Set survey variables and globals for report with include-------*
$_SESSION['globalsurveyindex_id'] 	  = $globalsurveyindex_id;
$_SESSION['globalsurvey_description'] = $globalsurvey_description;
$_SESSION['lastpageglobalreports']	 // Returns processes to last page
$totaldealers_persurvey 			 // Total dealer count per survey
---------------------------------------------------------------------*/
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
	// Query for total dealer rows
	include ('templates/query_totalros_md1.php');

	// Mileage Spread query	
	include ('templates/query_ms_md1.php');

/*------------------------------------------------------------global queries--------------------------------------------------------*/
} else {
	//Query for total dealer rows
	include ('templates/query_totalros_global.php');

	// Mileage Spread query	
	include ('templates/query_ms_global.php');
	// Rename variables for use below
	$totalros = $totalros2;
	$resultms = $resultms2;
}

include ('templates/header_printreports.php');

?>
<script>
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
	var data = new google.visualization.arrayToDataTable([
		['Task', 'Hours per Day'],
		<?php
		$resultms->data_seek(0);
		for ($i=0; $i<$msrows; $i++) {
			$item = $resultms->fetch_row();
			if ($i == $msrows - 1) {
				echo "['".$item[0]."'," .$item[1]."]";
			} else {
				echo "['".$item[0]."'," .$item[1]."],";
			}
		}
		?>
	]);
	
	var options = {                                          
					is3D: false,
					fontSize: <?=$chart_fontsize; ?>,
					legend: {position: 'labeled'},
					chartArea: {height: "<?=$chart_areaheight?>", width: "<?=$chart_areawidth?>", top: <?=$chart_top?>, left: <?=$chart_arealeft?>},
					height: <?=$chart_height; ?>,
					pieSliceBorderColor: '<?=$chart_border?>',
					tooltip: {text: 'percentage'},	
				};
	// Instantiate and draw our chart, passing in some options.
	//do not forget to check ur div ID
	var chart = new google.visualization.PieChart(document.getElementById('<?=$chart_div?>'));	
	chart.draw(data, options);
	}
	$(window).resize(function(){
	<?=$chart_callback?>();
	});
</script>
<?php

include ('templates/printview_globalbody.php')		;

$resultms->data_seek(0);		
for ($j = 0 ; $j < $msrows ; ++$j)
{
			$row = $resultms->fetch_row();
		  echo '<tr>
					<td>' .$row[0].     '</td>
					<td>' .$row[1].     '</td>
					<td>' .$row[2].'%'. '</td>
				</tr>';
}        
include ('templates/footer_printview.php');
?>