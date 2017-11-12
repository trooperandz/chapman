<!--------------------------------------------------------------------------*
   Program: dealer_columnchart_printview.php

   Purpose: Provide include template for dealer print report column charts
   History:
    Date		Description										by
	11/16/2014	Initial design and coding						Matt Holland			
*--------------------------------------------------------------------------->

<script>
// Load the Visualization API and the column chart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
	
	function drawChart() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(<?=$jsonTable?>);
	var options = {                                          
					legend: {position: 'top', alignment: 'end'},
					height: 600,
					fontSize: <?php echo $chartfont_size; ?>,
					chartArea: {height: "<?=$chartarea_height?>", width: "90%", top: <?=$chart_top?>},
					bar: {groupWidth: "<?=$barwidth?>"},
					vAxis: {viewWindowMode: 'maximized'},
					vAxis: {gridlines:{count: 10}},
					hAxis: {slantedText: <?=$chart_text?>},
					colors: ['<?=$chartcolor1?>']
				};
	// Instantiate and draw our chart, passing in some options.
	//do not forget to check ur div ID
	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));	
	chart.draw(data, options);
	}
	$(window).resize(function(){
	drawChart();
	});
</script>	