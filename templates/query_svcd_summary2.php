<?php
/*  Consolidate computations  */
$total_level1_sd2 	= abs($total_level1a2 - $total_level1b2);
$percent_level1_sd2 = number_format(($total_level1_sd2/$totalros2)*100,2);

$total_level2_sd2 	= abs($total_level2a2 - $total_level2b2);
$percent_level2_sd2 = number_format(($total_level2_sd2/$totalros2)*100,2);

// $total_full_sd2 	= ($total_full_L1a2 - $total_full_L1b2) + $total_full_L32;
$total_full_sd2 	=  $total_full_L32;
$percent_full_sd2  	= number_format(($total_full_sd2/$totalros2)*100,2);