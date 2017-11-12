<?php
// Consolidate computations
$totalrows = $totalsingle + $totalmultiple;
if ($totalrows == 0) { $totalrows = 1; }
$percentsingle   = number_format(($totalsingle/$totalrows)*100,2);
$percentmultiple = number_format(($totalmultiple/$totalrows)*100,2);