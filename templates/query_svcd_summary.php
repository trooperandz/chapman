<?php
/*  Consolidate computations  */
$total_level1_sd 	 = abs($total_level1a - $total_level1b);
if ($totalros > 0) {
	$percent_level1_sd 	 = number_format(($total_level1_sd/$totalros)*100,2);
} else {
	$percent_level1_sd 	 = 0;
}

$total_level2_sd 	 = abs($total_level2a - $total_level2b);
if ($totalros > 0) {
	$percent_level2_sd 	 = number_format(($total_level2_sd/$totalros)*100,2);
} else {
	$percent_level2_sd   = 0;
}	

// $total_full_sd 	 = ($total_full_L1a - $total_full_L1b) + $total_full_L3;
$total_full_sd 	 	 = $total_full_L3;
if ($totalros > 0) {
	$percent_full_sd  	 = number_format(($total_full_sd/$totalros)*100,2);
} else {
	$percent_full_sd     = 0;
}	