<?php 
/*------------------------------------------------------------------------------------------------------------*
   Program: all_g.php

   Purpose: Compile all global reports into one web page
   History:
    Date			Description														by
	07/15/2014		Initial design and coding										Matt Holland
	08/26/2014		Incorporate global if statements								Matt Holland
	09/04/2014  	Incorporate regional if statements								Matt Holland
	11/26/2014		Updated with $surveyindex_id, body and 							Matt Holland
					query includes
	12/01/2014		Added variable resets for global queries						Matt Holland
	12/12/2014		Added L1 & L2 Opgap reports										Matt Holland
	12/17/2014		Updated chart variables and standardized more includes			Matt Holland
	01/14/2015		Updated Service Demand portion - changed to column chart		Matt Holland
	01/22/2015		Added YM Single Issue report									Matt Holland
	01/22/2015		Added MS Single Issue report									Matt Holland
	01/23/2015		Added ST Single Issue report									Matt Holland
	03/02/2015		Revamped includes and changed YM & MS to pie charts				Matt Holland
	03/12/2015		Altered Longhorn chart - larger font and new google array formatMatt Holland
/*------------------------------------------------------------------------------------------------------------*/

// Required system includes
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include ('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_global_vars.php');

/*----------------------------------------------------*/
// Service categories processing
include ('templates/set_reporttype_global.php');
include ('templates/st_string_processing.php');

/*----------------------------------------------------*/
// Set SI C string for queries
include('templates/sicategory_string.php');

/*----------------------------------------------------*/
// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');

/*----------------------------------------------------*/
// Multidealer queries

if (isset($_SESSION['multidealer']) OR isset($_SESSION['regiondealerIDs'])) {
	if (isset($_SESSION['multidealer'])) {
		// Query for total dealer rows
		$dealerIDs1 = $_SESSION['multidealer'];
	} elseif (isset($_SESSION['regiondealerIDs'])) {
		// Query for total dealer rows
		$dealerIDs1 = $_SESSION['regiondealerIDs'];
	}
	/*----------------------------------------------------*/
	// Total ROs (multidealer)
	include ('templates/query_totalros_md1.php');
	// Total SI ROs (multidealer)
	include ('templates/query_totalros_si_md1.php');
	/*----------------------------------------------------*/
	// YM	
	include ('templates/query_ym_md1.php');
	include ('templates/query_ym_si_md1.php');
	/*----------------------------------------------------*/	
	// MS
	include ('templates/query_ms_md1.php');
	include ('templates/query_ms_si_md1.php');
	/*----------------------------------------------------*/	
	// ST
	include ('templates/query_st_md1.php');
	include ('templates/query_st_si_md1.php');
	/*----------------------------------------------------*/	
	// LOF D
	include ('templates/query_lofd_md1.php');
	include ('templates/query_lofd_summary.php');
	/*----------------------------------------------------*/	
	// LOF B
	include ('templates/query_lofb_md1.php');
	/*----------------------------------------------------*/	
	// SI
	include ('templates/query_si_md1.php');
	include ('templates/query_si_summary.php');
	/*----------------------------------------------------*/	
	// SI C
	include ('templates/query_sic_md1.php');
	include ('templates/query_sic_summary.php');
	/*----------------------------------------------------*/	
	// SVC D
	include ('templates/query_svcd_md1.php');
	include ('templates/query_svcd_summary.php');
	/*----------------------------------------------------*/	
	// L1&L2 OpGap
	include ('templates/query_L1_md1.php');
	include ('templates/query_L2_md1.php');

/*------------------------------------------------------------------------------------------------------------*/	
// Global queries
} else {
	/*----------------------------------------------------*/
	// Total ROs (global)
	include ('templates/query_totalros_global.php');
	// Total SI ROs (global)
	include ('templates/query_totalros_si_global.php');
	/*----------------------------------------------------*/	
	// YM
	include ('templates/query_ym_global.php');
	include ('templates/query_ym_si_global.php');
	// Reset variable for use below:
	$resultmy 	 = $resultmy2;
	$resultmy_si = $resultmy2_si;
	/*----------------------------------------------------*/	
	// MS
	include ('templates/query_ms_global.php');
	include ('templates/query_ms_si_global.php');
	// Reset variable for use below:
	$resultms 	 = $resultms2;
	$resultms_si = $resultms2_si;
	/*----------------------------------------------------*/	
	// ST
	include ('templates/query_st_global.php');
	include ('templates/query_st_si_global.php');
	// Reset variable for use below:
	$resultst 	 = $resultst2;
	$resultst_si = $resultst2_si;
	/*----------------------------------------------------*/	
	// LOF D
	include ('templates/query_lofd_global.php') ;
	// Reset variables for use below:
	$totalros  				= $totalros2	    	;
	$totalros_si			= $totalros2_si			;
	$LOFrows 				= $LOFrows2				;
	$SILOFrows 				= $SILOFrows2			;
	$percent_LOF			= $percent_LOF2	  		;
	$percent_SILOF			= $percent_SILOF2	  	;
	include ('templates/query_lofd_summary.php');
	/*----------------------------------------------------*/	
	// LOF B
	include ('templates/query_lofb_global.php');
	// Reset variables for use below:
	$averagelabor 			= $averagelabor2			;
	$averageparts 			= $averageparts2			;
	$averagepartspluslabor 	= $averagepartspluslabor2	;
	/*----------------------------------------------------*/	
	// SI
	include ('templates/query_si_global.php');
	// Reset variables for use below:
	$totalsingle 			= $totalsingle2				;
	$totalmultiple			= $totalmultiple2			;
	$percentsingle			= $percentsingle2			;
	$percentmultiple		= $percentmultiple2 		;
	include ('templates/query_si_summary.php');
	/*----------------------------------------------------*/	
	// SI C
	include ('templates/query_sic_global.php');
	// Reset variables for use below:
	$total_level1 			= $total_level12			;
	$total_wm	  			= $total_wm2				;
	$total_repair 			= $total_repair2  			;
	$percent_level1 		= $percent_level12			;
	$percent_wm				= $percent_wm2				;
	$percent_repair 		= $percent_repair2  		;
	include ('templates/query_sic_summary.php');
	/*----------------------------------------------------*/	
	// SVC D
	include ('templates/query_svcd_global.php');
	// Reset variables for use below:
	$total_level1a 			= $total_level1a2			;
	$total_level1b 			= $total_level1b2			;
	$total_level2a 			= $total_level2a2			;
	$total_level2b 			= $total_level2b2			;
	$total_full_L1a			= $total_full_L1a2			;
	$total_full_L1b			= $total_full_L1b2			;
	$total_full_L3 			= $total_full_L32			;
	$total_full_sd 			= $total_full_sd2			;
	$totalros 	   			= $totalros2     			;
	include ('templates/query_svcd_summary.php');
	/*----------------------------------------------------*/
	// L1&L2 OpGap
	include ('templates/query_L1_global.php');
	include ('templates/query_L2_global.php');
}
/*------------------------------------------------------------------------------------------------------------*/
// YM

// Set chart specifications
include ('templates/chart_specs_ym_g.php');

// Set header - scripts and style
include ('templates/header_reports.php');

// Draw the chart
include ('templates/chart_draw_ym_g.php');

// Include menubar
include ('templates/menubar_global_reports.php');

echo'
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<div class="panel" style="padding-bottom: 10px;">
			<div class="row">
				<div class="small-5 medium-6 large-6 columns" style="float: left;">
					<h3 style="text-align: left; color: #707070; margin-top: 3px;">RO Survey</h3> 
				</div>
				<div class="small-7 medium-6 large-6 columns" style="float: right;">
					<h4 style="text-align: right; margin-top: 8px; font-size: 15px; color: #707070;">' .(Date('l, F d')). '</h4>  
				</div>
			</div>	
		</div>	
	</div>
</div>';
include ('templates/error_message.php');
include ('templates/globalbody_viewall.php');

$resultmy->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
echo 			   '</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';

/*------------------------------------------------------------------------------------------------------------*/
// YM SI

// Set chart specifications
include ('templates/chart_specs_ym_g_si.php');

// Draw the chart
include ('templates/chart_draw_ym_g_si.php');

// Include report body
include ('templates/globalbody_viewall.php');
	
$resultmy_si->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy_si->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
echo 			   '</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';

/*------------------------------------------------------------------------------------------------------------*/
// MS

$chartarraytitle1 = 'Mileage Spread'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Mileage Spread'						;	// Set title to appear in chart legend
$chart_div		  = 'mschart'								;   // Set chart div name
$chart_border	  = '#FFFFFF'								;   // Set piechart slice border color
$chart_callback   = 'drawmschart'							;   // Set chart callback variable
$chart_height	  = '700'									;	// Set pie area height
$chart_areaheight = '100%'									;   // Set chart area
$chart_areawidth  = '100%'									;   // Set chart area width
$chart_arealeft   = '0'										;   // Set chart area left distance
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_title	  = 'Mileage Spread Distribution'			;	// Set top report title
$chart_fontsize	  = '17'									;	// Set chart font size
$exportanchor  	  = 'ms_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'ms_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'mstable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Mileage Spread Data'					;	// Set table title
$tablehead1 	  = 'Mileage Spread'						;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title


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

// Include report body
include ('templates/globalbody_viewall.php');
	
$resultms->data_seek(0);	
for ($j=0; $j<$msrows; ++$j) {
     $item = $resultms->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
echo 			   '</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';

/*------------------------------------------------------------------------------------------------------------*/
// MS SI
$chartarraytitle1 = 'SI Mileage Spread'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% SI Mileage Spread'						;	// Set title to appear in chart legend
$chart_div		  = 'mssichart'									;   // Set chart div name
$chart_border	  = '#FFFFFF'									;   // Set piechart slice border color
$chart_callback   = 'drawmssichart'								;   // Set chart callback variable
$chart_height	  = '700'										;	// Set pie area height
$chart_areaheight = '100%'										;   // Set chart area
$chart_areawidth  = '100%'										;   // Set chart area width
$chart_arealeft   = '0'											;   // Set chart area left distance
$chart_top		  = '20'										;	// Set chart distance from chart area top
$chart_title	  = 'Single Issue Mileage Spread Distribution'	;	// Set top report title
$chart_fontsize	  = '17'										;	// Set chart font size
$exportanchor  	  = 'ms_g_si_x.php'								;	// Set export anchor reference
$printeranchor 	  = 'ms_g_si_p.php'								; 	// Set printer friendly anchor reference
$tableid		  = 'mssitable'									;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Mileage Spread Data'			;	// Set table title
$tablehead1 	  = 'Mileage Spread'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'								;	// Set third table header title


?>

<script>
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
	var data = new google.visualization.arrayToDataTable([
		['Mileage', 'SI ROs'],
		<?php
		$resultms_si->data_seek(0);
		for ($i=0; $i<$msrows; $i++) {
			$item = $resultms_si->fetch_row();
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

// Include report body
include ('templates/globalbody_viewall.php');
	
$resultms_si->data_seek(0);	
for ($j=0; $j<$msrows; ++$j) {
     $item = $resultms_si->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
echo 			   '</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------------------*/
// Set ST report title and chart options with specified variables
$chartarraytitle1 = 'Service Type'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Service'								;	// Set title to appear in chart legend
$chart_div		  = 'stchart'								;   // Set chart div name
$chart_color1	  = '#3369e8'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawstchart'							;   // Set chart callback variable
$chart_height	  = '700'									;	// Set chart height within specified area
$chart_areaheight = '73%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft   = '45'									;   // Set left chart area distance from border
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_text		  = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Longhorn Distribution'					;	// Set top report title
$chart_fontsize	  = '19'									;	// Set chart font size
$chart_barwidth	  = '73%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'st_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'st_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sttable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Longhorn Data'							;	// Set table title
$tablehead1 	  = 'Service Type'							;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title
/*------------------------------------------------------------------------------------------------------------*/
// ST page
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
		$resultst->data_seek(0);
		for ($i=0; $i<$strows; $i++) {
			$item = $resultst->fetch_row();
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
include ('templates/globalbody_viewall.php')	;
	
$resultst->data_seek(0);	
for ($j=0; $j<$strows; ++$j) {
     $item = $resultst->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
echo 			   '</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------------------*/
// Set ST SI report title and chart options with specified variables
$chartarraytitle1 = 'Service Type'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Service'								;	// Set title to appear in chart legend
$chart_div		  = 'stsichart'								;   // Set chart div name
$chart_color1	  = '#3369e8'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawstsichart'							;   // Set chart callback variable
$chart_height	  = '700'									;	// Set chart height within specified area
$chart_areaheight = '73%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft   = '45'									;   // Set left chart area distance from border
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_text		  = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Single Issue Longhorn Distribution'	;	// Set top report title
$chart_fontsize	  = '19'									;	// Set chart font size
$chart_barwidth	  = '73%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'st_g_si_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'st_g_si_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'stsitable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Longhorn Data'			;	// Set table title
$tablehead1 	  = 'Service Type'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title

// ST SI page

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
include ('templates/globalbody_viewall.php')	;
	
$resultst_si->data_seek(0);	
for ($j=0; $j<$strows; ++$j) {
     $item = $resultst_si->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}        
echo 			   '</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------------------*/
// LOFD

// Set chart specifications
include ('templates/chart_specs_lofd_g.php');

// Draw the chart
include ('templates/chart_draw_lofd_dg.php');

// Include report body
include ('templates/globalbody_viewall.php');
					
echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">   ROs With LOF 	   	   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$LOFrows. 		  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_LOF.'%'.   '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    ROs With Only LOF 	</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$SILOFrows. 	   	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_SILOF.'%'.  '</td>
						</tr>	
				    </tbody>
				 </table>
			</div>
			<div class="medium-3 large-3 columns">
				<p> </p>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';

/*------------------------------------------------------------------------------------------------------------*/
// LOFB

// Set chart specifications
include ('templates/chart_specs_lofb_g.php');

// Draw the chart
include ('templates/chart_draw_lofb_dg.php');

// Include report body
include ('templates/globalbody_viewall.php');

echo'				<tr>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averagelabor,2). 		   '</td>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averageparts,2). 		   '</td>
 						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'. '$' .number_format($averagepartspluslabor,2). '</td>
 					</tr>
					</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>
	        </div>	
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------------------*/
// Set SI report title and chart options with specified variables
$chartarraytitle1 = 'Single Issue Occurrence'				;	// Set title to appear in chart legend
$chartarraytitle2 = '% Occurrence'							;	// Set title to appear in chart legend
$chart_div		  = 'sichart'								;   // Set chart div name
$chart_border	  = '#FFFFFF'								;   // Set piechart slice border color
$chart_callback   = 'drawsichart'							;   // Set chart callback variable
$chart_height	  = '700'									;	// Set pie area height
$chart_areaheight = '100%'									;   // Set chart area
$chart_areawidth  = '100%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_title	  = 'Single Issue Occurrence Distribution'	;	// Set top report title
$chart_fontsize	  = '17'									;	// Set chart font size
$exportanchor  	  = 'si_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'si_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sitable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Occurrence Data'			;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build Google chart
$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'Single Issue Occurrence', 'type' => 'string'),
	array('label' => '% Single Issue', 'type' => 'number'),
    array('label' => 'Multiple Issue Occurrence', 'type' => 'string'),
	array('label' => '% Multiple Issue', 'type' => 'number')
);

/*  Generate array elements for single issue  */
$rows = array();
$temp = array();
$temp[] = array('v' =>  'Single Issue');
$percent_single = ($totalsingle/$totalrows)*100;
$temp[] = array('v' => $percent_single);
$rows[] = array('c' => $temp);
/*  Generate array elements for multiple issue  */	
$temp = array();
$temp[] = array('v' =>  'Multiple Issue');
$percent_multiple = ($totalmultiple/$totalrows)*100;
$temp[] = array('v' => $percent_multiple);
$rows[] = array('c' => $temp);


$table['rows'] = $rows;
$jsonTable = json_encode($table);

include ('templates/piechart_dg.php')			;
include ('templates/globalbody_viewall.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">           Single Issue ROs 	           </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalsingle.   					  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentsingle,2).'%'. '</td>
						</tr>  
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">          Multiple Issue ROs 		  	    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalmultiple.                       '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentmultiple,2).'%'.'</td>
						</tr>
					</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>
			</div>
		</div>
	</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------------------*/
// SIC
$chartarraytitle1 = 'Single Issue Category'					;	// Set title to appear in chart legend
$chartarraytitle2 = '% Single Issue Category'				;	// Set title to appear in chart legend
$chart_div		  = 'sicchart'								;   // Set chart div name
$chart_color1	  = '#8B4789'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawsicchart'							;   // Set chart callback variable
$chart_height	  = '610'									;	// Set chart height within specified area
$chart_areaheight = '90%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft	  = '45'									;   // Set left chart area distance from border
$chart_top		  = '25'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Single Issue Category Distribution'	;	// Set top report title
$chart_fontsize	  = '18'									;	// Set chart font size
$chart_barwidth	  = '55%'									;	// Set chart bar width
$chart_gridcount  = '15'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'sic_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'sic_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sictable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Category Data'			;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title


?>
<script>
// Load the Visualization API and the column chart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.arrayToDataTable([
			['Category', 'Total ROs', { role: 'style' }, { role: 'annotation' } ],
			['Level 1 Services', <?=$percent_level1?>, 'color: #8B4789', '<?=number_format($percent_level1,0)?>%' ],
			['Wear Maintenance', <?=$percent_wm?>, 'color: #1A4081', '<?=number_format($percent_wm,0)?>%' ],
			['Repair Services', <?=$percent_repair?>, 'color: #8C8C8C', '<?=number_format($percent_repair,0)?>%' ]
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

// Include report body
include ('templates/globalbody_viewall.php');

echo'					<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Level 1 Services 						 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_level1.  					    '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_level1,2).'%'.  '</td>
						</tr>				
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Wear Maintenance						 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_wm.  						   	'</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_wm,2).'%'.   	'</td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Repair Services						 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_repair.  						'</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_repair,2).'%'.  '</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="medium-3 large-3 columns">
				<p>  </p>
			</div>
		</div>
	 </div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------------------*/
// SVC D
$chartarraytitle1 = 'Service Demand'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Demand'								;	// Set title to appear in chart legend
$chart_div		  = 'sdcchart'								;   // Set chart div name
$chart_color1	  = '#008B8B'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawsdchart'							;   // Set chart callback variable
$chart_height	  = '610'									;	// Set chart height within specified area
$chart_areaheight = '90%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft	  = '45'									;   // Set left chart area distance from border
$chart_top		  = '25'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Service Demand Distribution'			;	// Set top report title
$chart_fontsize	  = '18'									;	// Set chart font size
$chart_barwidth	  = '50%'									;	// Set chart bar width
$chart_gridcount  = '15'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'sd_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'sd_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sdtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Service Demand Data'					;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title


?>
<script>
// Load the Visualization API and the column chart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
    var data = new google.visualization.arrayToDataTable([
			['Category', 'Total ROs', { role: 'style' }, { role: 'annotation' } ],
			['Level 1 Demand', <?=$percent_level1_sd?>, 'color: #173870', '<?=number_format($percent_level1_sd,0)?>%' ],
			['Level 2 Demand', <?=$percent_level2_sd?>, 'color: #008B8B', '<?=number_format($percent_level2_sd,0)?>%' ],
			['Full Service', <?=$percent_full_sd?>, 'color: #778899', '<?=number_format($percent_full_sd,0)?>%' ]
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

// Include report body
include ('templates/globalbody_viewall.php')	;

echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 1 Demand		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level1_sd. 		 			    '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level1_sd,2).'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 2 Demand		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level2_sd. 		 				'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level2_sd,2).'%'.	'</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Full Service		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_full_sd. 		 					'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_full_sd,2).'%'.  	'</td>
						</tr>
					</tbody>
					</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------*/
// Set L1 report variables
$chartarraytitle1 = 'Level 1 Metrics'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Level1'								;	// Set title to appear in chart legend
$chart_div		  = 'L1chart'								;   // Set chart div name
$chart_color1	  = '#ff8c00'								;	// Set 1st group chart color
$chart_color2     = '#3369e8'								;   // Set 2nd group chart color
$chart_callback   = 'drawL1chart'							;   // Set chart callback variable
$chart_height	  = '650'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_text		  = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Level 1 Operating Gap'					;	// Set top report title
$chart_fontsize	  = '13'									;	// Set chart font size
$chart_barwidth	  = '64%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'opgap_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'opgap_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'L1table'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Level 1 Operating Data'				;	// Set table title
$tablehead1 	 = 'L1 Service'								;	// Set first table header title
$tablehead2 	 = constant('ENTITY').' ' .$dealercode		;	// Set second table header title
$tablehead3 	 = 'L1 Metric'								;	// Set third table header title
$tablehead4		 = 'Op. Gap'								;   // Set fourth table header title

/*------------------------------------------------------------------------------------------------*/
// Build chart and body
include ('templates/chart_L1global_array.php')	;
include('templates/columnchart_comparison.php')	;
include('templates/global_opgap_body.php')		;

for ($j=0; $j<$strows1; ++$j) {
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][0].     								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][2].'%'. 								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold2[$j].'%'. 									'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format(($hold1[$j][2] - $hold2[$j]),2).'%'. '</td>
						</tr>';
}						
echo'			</tbody>
				</table>
				</div>
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
<hr>
</div>';
/*------------------------------------------------------------------------------------------------*/
// Set L2 report variables
$chartarraytitle1 = 'Level 2 Metrics'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Level2'								;	// Set title to appear in chart legend
$chart_div		  = 'L2chart'								;   // Set chart div name
$chart_color1	  = '#D34836'								;	// Set 1st group chart color
$chart_color2     = '#3369e8'								;   // Set 2nd group chart color
$chart_callback   = 'drawL2chart'							;   // Set chart callback variable
$chart_height	  = '650'									;	// Set chart height within specified area
$chart_areaheight = '81%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_text		  = '1'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Level 2 Operating Gap'					;	// Set top report title
$chart_fontsize	  = '13'									;	// Set chart font size
$chart_barwidth	  = '64%'									;	// Set chart bar width
$chart_gridcount  = '14'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'opgap_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'opgap_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'L2table'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Level 2 Operating Data'				;	// Set table title
$tablehead1 	  = 'L2 Service'							;	// Set first table header title
$tablehead2 	  = constant('ENTITY').' ' .$dealercode		;	// Set second table header title
$tablehead3 	  = 'L2 Metric'								;	// Set third table header title
$tablehead4		  = 'Op. Gap'								;   // Set fourth table header title
/*------------------------------------------------------------------------------------------------*/
// Build chart and body
include('templates/chart_L2global_array.php')	;
include('templates/columnchart_comparison.php')	; 
include('templates/global_opgap_body.php')		;

for ($j=0; $j<$strows2; ++$j) {
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][0].     								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][2].'%'. 								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold2[$j].'%'. 									'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format(($hold1[$j][2] - $hold2[$j]),2).'%'. '</td>
						</tr>';
}
include ('templates/footer_reports.php');
?>