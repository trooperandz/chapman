<?php
// Consolidate computations
$totalrows = $totalsingle + $totalmultiple;
if ($totalrows == 0) { $totalrows = 1; }
$percentsingle = ($totalsingle/$totalrows)*100;
$percentmultiple = ($totalmultiple/$totalrows)*100;