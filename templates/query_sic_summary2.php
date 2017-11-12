<?php
// Consolidate computations
$totalrows = $total_level12 + $total_wm2 + $total_repair2;
if ($totalrows == 0) { $totalrows =1; }
$percent_level12 	= number_format(($total_level12/$totalrows)*100,2)	;
$percent_wm2 		= number_format(($total_wm2/$totalrows)*100,2)		;
$percent_repair2 	= number_format(($total_repair2/$totalrows)*100,2)	;