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
		$resultmy->data_seek(0);
		for ($i=0; $i<$myrows; $i++) {
			$item = $resultmy->fetch_row();
			echo "['".$item[0]."'," .$item[1]."],";
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