<script type="text/javascript">
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(<?=$jsonTable?>);
	var options = {                                          
					is3D: false,
					fontSize: 15,
					legend: {position: 'labeled'},
					chartArea: {height: "80%", width: "90%", top: 55},
					height: 560,
					pieSliceBorderColor: '#FFFFFF',
					tooltip: {text: 'percentage'},
				};
	// Instantiate and draw our chart, passing in some options.
	//do not forget to check ur div ID
	var chart = new google.visualization.PieChart(document.getElementById('<?=$chart_div?>'));	
	chart.draw(data, options);
	}
</script>
