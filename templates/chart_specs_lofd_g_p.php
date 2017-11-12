<?php
// Set report title and chart options with specified variables
$chartarraytitle1 = 'LOF Demand'							;	// Set title to appear in chart legend
$chartarraytitle2 = '% Demand'								;	// Set title to appear in chart legend
$chart_div		  = 'lofdchart'								;   // Set chart div name
$chart_color1	  = '#ff8c00'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawlofdchart'							;   // Set chart callback variable
$chart_height	  = '590'									;	// Set chart height within specified area
$chart_areaheight = '90%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft	  = '45'									;   // Set left chart area distance from border
$chart_top		  = '30'									;	// Set chart distance from chart area top
$chart_text		  = '0'										;	// Set chart text as slanted (1) or not slanted (0)
$chart_textangle  = '90'									;   // Set x-axis title text angle
$chart_title	  = 'LOF Demand Distribution'				;	// Set top report title
$chart_fontsize	  = '19'									;	// Set chart font size
$chart_barwidth	  = '50%'									;	// Set chart bar width
$chart_gridcount  = '15'									;   // Set number of gridlines
$chart_numformat  = '#\'%\''								;   // Set number format
$chart_minvalue   = '0'										;   // Set chart minimum value
$exportanchor  	  = 'lofd_g_x.php'							;	// Set export anchor reference
$printeranchor 	  = 'lofd_g_p.php'							; 	// Set printer friendly anchor reference
$tableid		  = 'lofdtable'								;   // Set table id for tablesorter functionality
$tabletitle 	  = 'LOF Demand Data'						;	// Set table title
$tablehead1 	  = 'Category'								;	// Set first table header title
$tablehead2 	  = 'Total ROs'								;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title