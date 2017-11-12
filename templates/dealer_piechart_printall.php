<!--------------------------------------------------------------------------*
   Program: dealer_piechart_printall.php

   Purpose: Provide include template for dealer printall report pie charts
   History:
    Date		Description										by
	11/16/2014	Initial design and coding						Matt Holland
	12/04/2014	Added $chart_callback and $chart_div			Matt Holland
*--------------------------------------------------------------------------->
<script>
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(<?=$jsonTable?>);
	var options = {	
					is3D: false,
					fontSize: <?php echo $chartfont_size; ?>,
					legend: {position: 'labeled'},
					chartArea: {height: "<?=$chartarea_height?>", width: "90%", top: <?=$chart_top?>},
					height: <?php echo $chart_height; ?>,
					pieSliceBorderColor: '<?=$chart_border?>',
					tooltip: {text: 'percentage'},	
				};
	// Instantiate and draw our chart, passing in some options.
	//do not forget to check ur div ID
	var chart = new google.visualization.PieChart(document.getElementById('<?=$chart_div?>'));	
	chart.draw(data, options);
	}
</script>