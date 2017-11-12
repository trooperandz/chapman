<?php
$page_url = basename($_SERVER['PHP_SELF']);
$query = "SELECT chartarraytitle, chartarraytitle2, chart_color1, chart_color2, chart_div, chart_border, chart_callback, chart_height,
		         chart_areaheight, chart_areawidth, chart_arealeft, chart_top, chart_text, chart_textangle, chart_title, chart_fontsize,
				 chart_barwidth, chart_gridcount, chart_numformat, chart_minvalue, exportanchor, printeranchor, tableid, tabletitle,
				 tablehead1, tablehead2, tablehead3
		  FROM page_specs
		  WHERE page_url = '$page_url'";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Page query failed.  See administrator.';
	die(header('Location: enterrofoundation.php'));
}
// Get results from successful query
$lookup = $result->fetch_assoc();
$chartarraytitle	= $lookup['chartarraytitle'];
$chartarraytitle2   = $lookup['chartarraytitle2'];
$chart_color1       = $lookup['chart_color1'];
$chart_color2       = $lookup['chart_color2'];
$chart_div          = $lookup['chart_div'];
$chart_border       = $lookup['chart_border'];
$chart_callback     = $lookup['chart_callback'];
$chart_height       = $lookup['chart_height'];
$chart_areaheight   = $lookup['chart_areaheight'];
$chart_areawidth    = $lookup['chart_areawidth'];
$chart_arealeft     = $lookup['chart_arealeft'];
$chart_top          = $lookup['chart_top'];
$chart_text         = $lookup['chart_text'];
$chart_textangle    = $lookup['chart_textangle'];
$chart_title        = $lookup['chart_title'];
$chart_fontsize     = $lookup['chart_fontsize'];
$chart_barwidth     = $lookup['chart_barwidth'];
$chart_gridcount    = $lookup['chart_gridcount'];
$chart_numformat    = $lookup['chart_numformat'];
$chart_minvalue     = $lookup['chart_minvalue'];
$exportanchor       = $lookup['exportanchor'];
$printeranchor      = $lookup['printeranchor'];
$tableid            = $lookup['tableid'];
$tabletitle         = $lookup['tabletitle'];
$tablehead1         = $lookup['tablehead1'];
$tablehead2         = $lookup['tablehead2'];
$tablehead3         = $lookup['tablehead3'];

/*
echo	$chartarraytitle	.'<br>';
echo	$chartarraytitle2	.'<br>';
echo	$chart_color1    	.'<br>';
echo	$chart_color2    	.'<br>';
echo	$chart_div       	.'<br>';
echo	$chart_border    	.'<br>';
echo	$chart_callback  	.'<br>';
echo	$chart_height    	.'<br>';
echo	$chart_areaheight	.'<br>';
echo	$chart_areawidth 	.'<br>';
echo	$chart_arealeft  	.'<br>';
echo	$chart_top       	.'<br>';
echo	$chart_text      	.'<br>';
echo	$chart_textangle 	.'<br>';
echo	$chart_title     	.'<br>';
echo	$chart_fontsize  	.'<br>';
echo	$chart_barwidth  	.'<br>';
echo	$chart_gridcount 	.'<br>';
echo	$chart_numformat 	.'<br>';
echo	$chart_minvalue  	.'<br>';
echo	$exportanchor    	.'<br>';
echo	$printeranchor   	.'<br>';
echo	$tableid         	.'<br>';
echo	$tabletitle      	.'<br>';
echo	$tablehead1      	.'<br>';
echo	$tablehead2      	.'<br>';
echo	$tablehead3      	.'<br>';
*/