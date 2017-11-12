<?php
/*-----------------------------------------Set report variables-----------------------------------------------*/
$chartarraytitle1 = 'LOF Baseline'							;	// Set title to appear in chart legend
$chartarraytitle2 = 'Avg Dollars'							;	// Set title to appear in chart legend
$chart_div		  = 'lofbchart'								;   // Set chart div name
$chart_color1	  = '#00933B'								;	// Set 1st group chart color
$chart_color2     = '#CCCCCC'								;   // Set 2nd group chart color
$chart_callback   = 'drawlofbchart'							;   // Set chart callback variable
$chart_height	  = '617'									;	// Set chart height within specified area
$chart_areaheight = '90%'									;   // Set chart area
$chart_areawidth  = '90%'									;   // Set chart area width
$chart_arealeft	  = '48'									;   // Set left chart area distance from border
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