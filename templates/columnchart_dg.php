<!--------------------------------------------------------------------------*
   Program: columnchart_dg.php

   Purpose: Provide include template for dealer & global print report column charts
   History:
    Date		Description										by
	11/16/2014	Initial design and coding						Matt Holland
	12/16/2014	Reworked for ALL non-comparison charts			Matt Holland
*--------------------------------------------------------------------------->
<script>
// Load the Visualization API and the column chart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(<?=$jsonTable?>);
	var view = new google.visualization.DataView(data);
    view.setColumns([0, 1,
					{ 
                    type: 'string',
                    role: 'annotation',
					sourceColumn: 1,
					calc: 'stringify',
					}]);
	var options = {                                          
					legend: {position: 'top', alignment: 'end'},
					height: <?=$chart_height?>,
					fontSize: <?=$chart_fontsize?>,
					chartArea: {height: "<?=$chart_areaheight?>", width: "<?=$chart_areawidth?>", top: <?=$chart_top?>},
					bar: {groupWidth: "<?=$chart_barwidth?>"},
					vAxis: {viewWindowMode: 'maximized', gridlines:{count: <?=$chart_gridcount?>}, minValue: <?=$chart_minvalue?>, format: "<?=$chart_numformat?>"},
					hAxis: {slantedText: <?=$chart_text?>, slantedTextAngle: <?=$chart_textangle?>},
					colors: ['<?=$chart_color1?>']
				};
	// Instantiate and draw our chart, passing in some options.
	//do not forget to check ur div ID
	var chart = new google.visualization.ColumnChart(document.getElementById('<?=$chart_div?>'));	
	chart.draw(view, options);
	}
	$(window).resize(function(){
	<?=$chart_callback?>();
	});
</script>