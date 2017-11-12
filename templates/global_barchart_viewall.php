<script type="text/javascript">
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(<?=$jsonTable?>);
	var options = {                                          
					legend: {position: 'top', alignment: 'end'},
					height: 600,
					fontSize: <?php echo $chartfont_size; ?>,
					chartArea: {height: "81%", width: "90%", top: 35},
					bar: {groupWidth: "<?=$barwidth?>"},
					vAxis: {viewWindowMode: 'maximized'},
					vAxis: {gridlines:{count: 10}},
					hAxis: {slantedText: <?=$chart_text?>},
					colors: ['<?=$chartcolor?>']
				};
	// Instantiate and draw our chart, passing in some options.
	//do not forget to check ur div ID
	var chart = new google.visualization.ColumnChart(document.getElementById('<?=$chart_div?>'));
	chart.draw(data, options);
	}	
</script>