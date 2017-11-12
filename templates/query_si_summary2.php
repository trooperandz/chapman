<?php
// Consolidate computations
$totalrows = $totalsingle2 + $totalmultiple2;
if ($totalrows == 0) { $totalrows = 1; }
$percentsingle2   = number_format(($totalsingle2/$totalrows)*100,2);
$percentmultiple2 = number_format(($totalmultiple2/$totalrows)*100,2);