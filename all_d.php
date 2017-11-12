<?php 
require_once("functions.inc");
include ('templates/login_check.php');
/*------------------------------------------------------------------------------------------------------------*
   Program: all_d.php

   Purpose: Export all dealer data
   History:
    Date			Description													by
	07/12/2014		Initial design and coding									Matt Holland
	08/14/2014		Convert to mysqli											Matt Holland
	11/19/2014		Rewrote to include template includes						Matt Holland
	12/03/2014		Updated with standard query and body includes				Matt Holland
	12/12/2014		Added L1 & L2 Opgap reports									Matt Holland
	12/17/2014		Updated chart variables and standardized more includes		Matt Holland
	01/14/2015		Updated Service Demand portion - changed to column chart	Matt Holland
	01/22/2015		Added YM Single Issue report								Matt Holland
	01/22/2015		Added MS Single Issue report								Matt Holland
	01/23/2015		Added ST Single Issue report								Matt Holland
	02/03/2015		Changed (int) to number_format(xx,2) for correct display
					in charts (YM, MS, ST)										Matt Holland
	02/25/2015		Change YM & MS charts from column charts to pie charts		Matt Holland
	02/26/2015		Integrated programming of YM chart with 'bucket' year		Matt Holland
/*------------------------------------------------------------------------------------------------------------*/
// Database connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']; 	// Initialize $dealerID variable
$dealerIDs1 = $_SESSION['dealerID'];  	// Initialize dealer variable for query includes
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable
$userID		= $user->userID;			// Initialize user

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

/*------------------------------------------------------------------------------------------------*/
// Retrieve yearmodel_string for queries
include ('templates/ym_string.php');
include ('templates/ym_string2.php');
 
/*------------------------------------------------------------------------------------------------*/
// Service categories processing
include ('templates/set_reporttype_dealer.php');
include ('templates/st_string_processing.php');

/*------------------------------------------------------------------------------------------------*/
// SI Category string processing
include ('templates/sicategory_string.php');

/*------------------------------------------------------------------------------------------------*/
// Generate serviceID strings for demand queries
include ('templates/sd_strings.php');

/*------------------------------------------------------------------------------------------------*/

// State all dealer queries
/*-----------------------------------------------------*/
// Total ROs Queries
	// Query for total dealer rows
	include ('templates/query_totalros_md1.php');
	// Query for total single issue dealer rows
	include ('templates/query_totalros_si_md1.php');
/*-----------------------------------------------------*/
// Model Year Queries
	//  Query for first set dealer model year	
	include ('templates/query_ym_dlr.php');	
	include ('templates/query_ym_dlr2.php');	
	//  Query for first set dealer SI model year	
	include ('templates/query_ym_si_dlr.php');
	include ('templates/query_ym_si_dlr2.php');
/*-----------------------------------------------------*/
// Mileage Spread Queries
	//  Query for first set dealer mileage spread	
	include ('templates/query_ms_md1.php');	
	include ('templates/query_ms_si_md1.php');	
/*-----------------------------------------------------*/
// Longhorn Queries	
	//  Query for first set dealer Longhorn	
	include ('templates/query_st_md1.php');		
	include ('templates/query_st_si_md1.php');
/*-----------------------------------------------------*/
// LOF D Queries
	// Query for first set dealer LOF data
	include ('templates/query_lofd_md1.php');
/*-----------------------------------------------------*/
// LOF B Queries
	// Query for first LOF Baseline set
	include ('templates/query_lofb_md1.php');
/*-----------------------------------------------------*/
// SI Queries
	// Query first dealer set
	include ('templates/query_si_md1.php');
/*-----------------------------------------------------*/
// SI C Queries
	// First query set
	include ('templates/query_sic_md1.php');
/*-----------------------------------------------------*/
// SVC D Queries
	// Queries set 1
	include ('templates/query_svcd_md1.php');
/*-----------------------------------------------------*/
// L1 & L2 OpGap Queries
	include ('templates/query_L1_md1.php');
	include ('templates/query_L2_md1.php');
/*----------------------------------------------------------------------------------------------------*/
// Consolidate computations

/*  Consolidate LOF D computations  */
include ('templates/query_lofd_summary.php');

/*  Consolidate SI computations  */
include ('templates/query_si_summary.php');

/*  Consolidate SI C computations */
include ('templates/query_sic_summary.php');

/*  Consolidate SVC D computations */
include ('templates/query_svcd_summary.php'); 
 
/*----------------------------------------------------------------------------------------------------*/
// Begin body of page
include ('templates/header_reports.php')			;
include ('templates/menubar_dealer_reports.php')	;
include ('templates/top_panel_reports.php')			;
include ('templates/error_message.php')				;

/*-----------------------------------------Set report variables-----------------------------------------------*/
// Set YM Values
$chartarraytitle1 = 'Model Year'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Model Year'							;	// Set title to appear in chart legend
$chart_div		  = 'ymchart'								;   // Set chart div name
$chart_border	  = '#FFFFFF'								;   // Set piechart slice border color
$chart_callback   = 'drawymchart'							;   // Set chart callback variable
$chart_height	  = '700'									;	// Set pie area height
$chart_areaheight = '100%'									;   // Set chart area
$chart_areawidth  = '100%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_title	  = 'Model Year Distribution'				;	// Set top report title
$chart_fontsize	  = '17'									;	// Set chart font size
$exportanchor  	  = 'ym_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'ym_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'ymtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Model Year Data'						;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build MY body
?>
<script>
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
	var data = new google.visualization.arrayToDataTable([
		['Model Year', '% Model Year'],
		<?php
		$resultmy->data_seek(0);
		for ($i=0; $i<$myrows; $i++) {
			$item = $resultmy->fetch_row();
			echo "['".$item[0]."'," .$item[1]."],";
		}
		echo "['".$bucket_year."'," .$bucket[0]."]";
		?>
	]);
	
	var options = {                                          
					is3D: false,
					fontSize: <?=$chart_fontsize; ?>,
					legend: {position: 'labeled'},
					chartArea: {height: "<?=$chart_areaheight?>", width: "<?=$chart_areawidth?>", top: <?=$chart_top?>},
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
include ('templates/dealerbody_viewall.php');
// Reset first result data set internal pointer
$resultmy->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}
// Reset second result data set internal pointer
$resultmy_set2->data_seek(0);
$bucket = $resultmy_set2->fetch_row();
echo					'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket_year.    '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket[1].'%'.  '</td>
						</tr>';        
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

/*-----------------------------------------Set report variables-----------------------------------------------*/
// Set YM SI Values
$chartarraytitle1 = 'SI Model Year'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% SI Model Year'						;	// Set title to appear in chart legend
$chart_div		  = 'ymsichart'								;   // Set chart div name
$chart_border	  = '#FFFFFF'								;   // Set piechart slice border color
$chart_callback   = 'drawymsichart'							;   // Set chart callback variable
$chart_height	  = '700'									;	// Set pie area height
$chart_areaheight = '100%'									;   // Set chart area
$chart_areawidth  = '100%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_title	  = 'Single Issue Model Year Distribution'	;	// Set top report title
$chart_fontsize	  = '17'									;	// Set chart font size
$exportanchor  	  = 'ym_d_si_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'ym_d_si_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'ymsitable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Model Year Data'			;	// Set table title
$tablehead1 	  = 'Model Year'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build MY SI body
?>
<script>
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(<?=$chart_callback?>);
	
	function <?=$chart_callback?>() {
	// Create our data table out of JSON data loaded from server.
	var data = new google.visualization.arrayToDataTable([
		['SI Model Year', '% SI Model Year'],
		<?php
		$resultmy_si->data_seek(0);
		for ($i=0; $i<$myrows; $i++) {
			$item = $resultmy_si->fetch_row();
			echo "['".$item[0]."'," .$item[1]."],";
		}
		echo "['".$bucket_year."'," .$bucket_si[0]."]";
		?>
	]);
	
	var options = {                                          
					is3D: false,
					fontSize: <?=$chart_fontsize; ?>,
					legend: {position: 'labeled'},
					chartArea: {height: "<?=$chart_areaheight?>", width: "<?=$chart_areawidth?>", top: <?=$chart_top?>},
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
include ('templates/dealerbody_viewall.php');
// Reset first result data set internal pointer
$resultmy_si->data_seek(0);	
for ($j=0; $j<$myrows; ++$j) {
     $item = $resultmy_si->fetch_row();
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[1]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$item[2].'%'.    '</td>
						</tr>';
}
// Reset second result data set internal pointer
$resultmy_si_set2->data_seek(0);
$bucket_si = $resultmy_si_set2->fetch_row();
echo					'<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket_year.    '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket_si[0]. 	   '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$bucket_si[1].'%'.  '</td>
						</tr>';        
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

/*----------------------------------------------------------------------------------------------------*/
// Set MS values
$chartarraytitle1 = 'Mileage Spread'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Mileage Spread'						;	// Set title to appear in chart legend
$chart_div		  = 'mschart'								;   // Set chart div name
$chart_border	  = '#FFFFFF'								;   // Set piechart slice border color
$chart_callback   = 'drawmschart'							;   // Set chart callback variable
$chart_height	  = '700'									;	// Set pie area height
$chart_areaheight = '100%'									;   // Set chart area
$chart_areawidth  = '100%'									;   // Set chart area width
$chart_top		  = '20'									;	// Set chart distance from chart area top
$chart_title	  = 'Mileage Spread Distribution'			;	// Set top report title
$chart_fontsize	  = '17'									;	// Set chart font size
$exportanchor  	  = 'ms_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'ms_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'mstable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Mileage Spread Data'					;	// Set table title
$tablehead1 	  = 'Mileage Spread'						;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build MS body
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
					chartArea: {height: "<?=$chart_areaheight?>", width: "<?=$chart_areawidth?>", top: <?=$chart_top?>},
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
include ('templates/dealerbody_viewall.php');

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
/*----------------------------------------------------------------------------------------------------*/
// Set MS SI values
$chartarraytitle1 = 'Mileage Spread'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Mileage Spread'							;	// Set title to appear in chart legend
$chart_div		  = 'mssichart'									;   // Set chart div name
$chart_border	  = '#FFFFFF'									;   // Set piechart slice border color
$chart_callback   = 'drawmssichart'								;   // Set chart callback variable
$chart_height	  = '700'										;	// Set pie area height
$chart_areaheight = '100%'										;   // Set chart area
$chart_areawidth  = '100%'										;   // Set chart area width
$chart_top		  = '20'										;	// Set chart distance from chart area top
$chart_title	  = 'Single Issue Mileage Spread Distribution'	;	// Set top report title
$chart_fontsize	  = '17'										;	// Set chart font size
$exportanchor  	  = 'ms_d_si_x.php'								;	// Set export anchor reference
$printeranchor 	  = 'ms_d_si_p.php'								; 	// Set printer friendly anchor reference
$tableid		  = 'mssitable'									;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Mileage Spread Data'			;	// Set table title
$tablehead1 	  = 'Mileage Spread'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'								;	// Set third table header title

// Build MS SI body
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
					chartArea: {height: "<?=$chart_areaheight?>", width: "<?=$chart_areawidth?>", top: <?=$chart_top?>},
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
include ('templates/dealerbody_viewall.php');

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

/*----------------------------------------------------------------------------------------------------*/
// Set ST values
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
$exportanchor  	  = 'st_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'st_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sttable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Longhorn Data'							;	// Set table title
$tablehead1 	  = 'Service Type'							;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title

// Build ST body

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
include ('templates/dealerbody_viewall.php');

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

/*----------------------------------------------------------------------------------------------------*/
// Set ST SI values
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
$exportanchor  	  = 'st_d_si_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'st_d_si_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'stsitable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Longhorn Data'			;	// Set table title
$tablehead1 	  = 'Service Type'							;	// Set first table header title
$tablehead2 	  = 'Total SI ROs'							;	// Set second table header title
$tablehead3 	  = 'Frequency'								;	// Set third table header title

// Build ST SI body

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
include ('templates/dealerbody_viewall.php');

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

/*----------------------------------------------------------------------------------------------------*/
// Set LOF D values
$chartarraytitle1 = 'LOF Demand'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% LOF Demand'							;	// Set title to appear in chart legend
$chart_div		  = 'lofdchart'								;   // Set chart div name
$chart_color1	  = '#ff8c00'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawlofdchart'							;   // Set chart callback variable
$chart_height	  = '617'									;	// Set chart height within specified area
$chart_areaheight = '90%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft	  = '45'									;   // Set left chart area distance from border
$chart_top		  = '15'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'LOF Demand Distribution'				;	// Set top report title
$chart_fontsize	  = '18'									;	// Set chart font size
$chart_barwidth	  = '50%'									;	// Set chart bar width
$chart_gridcount  = '15'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'lofd_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'lofd_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'lofdtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'LOF Demand Data'						;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title


// Draw the chart
include ('templates/chart_draw_lofd_dg.php');

include ('templates/dealerbody_viewall.php');
echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">   ROs With LOF 	   	   </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$LOFrows.	 		  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_LOF.'%'.	  '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">    ROs With Only LOF    </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$SILOFrows.		  '</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'  .$percent_SILOF.'%'. '</td>
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
/*----------------------------------------------------------------------------------------------------*/
// Set LOF B values
$chartarraytitle1 = 'LOF Baseline'							;	// Set title to appear in chart legend
$chartarraytitle2 = 'Avg Dollars'							;	// Set title to appear in chart legend
$chart_div		  = 'lofbchart'								;   // Set chart div name
$chart_color1	  = '#00933B'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawlofbchart'							;   // Set chart callback variable
$chart_height	  = '617'									;	// Set chart height within specified area
$chart_areaheight = '90%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft	  = '45'									;   // Set left chart area distance from border
$chart_top		  = '15'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'LOF Baseline Distribution'				;	// Set top report title
$chart_fontsize	  = '18'									;	// Set chart font size
$chart_barwidth	  = '45%'									;	// Set chart bar width
$chart_gridcount  = '15'									;   // Set number of gridlines
$chart_numformat  = '$\''									;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'lofb_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'lofb_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'lofbtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'LOF Baseline Data'						;	// Set table title
$tablehead1 	  = 'Average Labor'							;	// Set first table header title
$tablehead2 	  = 'Average Parts'							;	// Set second table header title
$tablehead3 	  = 'Avg Labor & Parts'						;	// Set third table header title

// Draw the chart
include ('templates/chart_draw_lofb_dg.php');

include ('templates/dealerbody_viewall.php');
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
/*----------------------------------------------------------------------------------------------------*/
// Set SI values
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
$chart_fontsize	  = '18'									;	// Set chart font size
$exportanchor  	  = 'si_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'si_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'sitable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Single Issue Occurrence Data'			;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title

// Build SI body
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


include ('templates/piechart_dg.php')		;
include ('templates/dealerbody_viewall.php');
echo'				<tr>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">           Single Issue ROs 	           </td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$totalsingle.   					  '</td>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percentsingle,2).'%'. '</td>
					</tr>  
					<tr>
						<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">          Multiple Issue ROs 		        </td>
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
/*----------------------------------------------------------------------------------------------------*/
// Set SIC values
$chartarraytitle1 = 'Single Issue Category'					;	// Set title to appear in chart legend
$chartarraytitle2 = '% Single Issue Category'				;	// Set title to appear in chart legend
$chart_div		  = 'sicchart'								;   // Set chart div name
$chart_color1	  = '#8B4789'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawsicchart'							;   // Set chart callback variable
$chart_height	  = '617'									;	// Set chart height within specified area
$chart_areaheight = '90%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft	  = '45'									;   // Set left chart area distance from border
$chart_top		  = '15'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Single Issue Category Distribution'	;	// Set top report title
$chart_fontsize	  = '18'									;	// Set chart font size
$chart_barwidth	  = '55%'									;	// Set chart bar width
$chart_gridcount  = '15'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'sic_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'sic_d_p.php'							; 	// Set printer friendly anchor reference
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
include ('templates/dealerbody_viewall.php');
echo'					<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Level 1 Services 						</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_level1.  					   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_level1,2).'%'. '</td>
						</tr>				
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Wear Maintenance					</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_wm.  					   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_wm,2).'%'. '</td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #CCCCCC;">                        Repair Services						</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$total_repair.  					   '</td>		
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format($percent_repair,2).'%'. '</td>
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
/*----------------------------------------------------------------------------------------------------*/
// Set SVC D values
$chartarraytitle1 = 'Service Demand'						;	// Set title to appear in chart legend
$chartarraytitle2 = '% Demand'								;	// Set title to appear in chart legend
$chart_div		  = 'sdcchart'								;   // Set chart div name
$chart_color1	  = '#008B8B'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawsdchart'							;   // Set chart callback variable
$chart_height	  = '617'									;	// Set chart height within specified area
$chart_areaheight = '90%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft	  = '45'									;   // Set left chart area distance from border
$chart_top		  = '15'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'Service Demand Distribution'			;	// Set top report title
$chart_fontsize	  = '18'									;	// Set chart font size
$chart_barwidth	  = '50%'									;	// Set chart bar width
$chart_gridcount  = '15'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'sd_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'sd_d_p.php'							; 	// Set printer friendly anchor reference
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
include ('templates/dealerbody_viewall.php');
echo'					<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 1 Demand		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level1_sd. 		 				'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level1_sd,2).'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Level 2 Demand		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_level2_sd. 		 				'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .number_format($percent_level2_sd,2).'%'. '</td>
						</tr>
						<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">		Full Service		  					 </td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">'   .$total_full_sd. 		 				  	'</td>
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
$exportanchor  	  = 'opgap_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'opgap_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'L1table'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Level 1 Operating Data'				;	// Set table title
$tablehead1 	 = 'L1 Service'								;	// Set first table header title
$tablehead2 	 = constant('ENTITY').' ' .$dealercode		;	// Set second table header title
$tablehead3 	 = 'L1 Metric'								;	// Set third table header title
$tablehead4		 = 'Op. Gap'								;   // Set fourth table header title

// Build body and chart
include('templates/chart_L1dealer_array.php')	;
include('templates/columnchart_comparison.php')	;
include('templates/dealer_opgap_body.php')		;

for ($j=0; $j<$strows1; ++$j) {
echo				   '<tr>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][0].     								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold1[$j][2].'%'. 								'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .$hold2[$j].'%'. 									'</td>
							<td style="text-align: center; border-bottom: 1px solid #CCCCCC;">' .number_format(($hold1[$j][2] - $hold2[$j]),2).'%'. '</td>
						</tr>';
}						
echo'				 </table>
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
$exportanchor  	  = 'opgap_d_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'opgap_d_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'L2table'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'Level 2 Operating Data'				;	// Set table title
$tablehead1 	  = 'L2 Service'							;	// Set first table header title
$tablehead2 	  = constant('ENTITY').' ' .$dealercode		;	// Set second table header title
$tablehead3 	  = 'L2 Metric'								;	// Set third table header title
$tablehead4		  = 'Op. Gap'								;   // Set fourth table header title

/*------------------------------------------------------------------------------------------------*/
// Build body and chart
include('templates/chart_L2dealer_array.php')		;
include('templates/columnchart_comparison.php')		; 
include('templates/dealer_opgap_body.php')			;

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