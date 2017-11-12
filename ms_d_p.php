<?php // enterrofoundation.php
require_once("functions.inc");
include ('templates/login_check.php');
/* -------------------------------------------------------------------------------------------*
   Program: mileagespreadqueryandchartprintview.php

   Purpose: Report of mileage spread bar chart and data chart
   History:
    Date		Description														by
	07/20/2014	Initial design and coding										Matt Holland
	08/06/2014	Convert to mysqli												Matt Holland
	08/27/2014  Apply new css styling											Matt Holland
	11/16/2014	Update to new template includes system							Matt Holland
	12/03/2014	Update with standard query includes								Matt Holland
	12/17/2014	Updated chart variables and standardized more includes			Matt Holland
*---------------------------------------------------------------------------------------------*/

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

// Include page / chart variable settings
include ('templates/chart_specs.php');

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

/*---------------------------------------------Dealer queries---------------------------------------------------*/

// Query for total rows
include ('templates/query_totalros_md1.php');

//  Query for mileage ranges 
include ('templates/query_ms_md1.php');	

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
include ('templates/dealerbody_printview.php');
	
$resultms->data_seek(0);	
for ($j=0; $j<$msrows; ++$j) {
     $item = $resultms->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
include ('templates/footer_printview.php');
?>