<?php
// Consolidate computations
$totalrows = $total_level1 + $total_wm + $total_repair;
if ($totalrows == 0) { $totalrows =1; }
$percent_level1 = number_format(($total_level1/$totalrows)*100,2);
$percent_wm 	= number_format(($total_wm/$totalrows)*100,2)	 ;
$percent_repair = number_format(($total_repair/$totalrows)*100,2);
