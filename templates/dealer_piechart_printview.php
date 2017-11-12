<script>
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
	
	function drawChart() {
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
	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));	
	chart.draw(data, options);
	}
	$(window).resize(function(){
	drawChart();
	});
</script>