<?php
// Consolidate computations
$totalrows = $total_level1 + $total_wm + $total_repair;
if ($totalrows == 0) { $totalrows =1; }
$percent_level1 = ($total_level1/$totalrows)*100;
$percent_wm 	= ($total_wm/$totalrows)*100;
$percent_repair = ($total_repair/$totalrows)*100;
