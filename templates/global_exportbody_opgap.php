<?php
// Generate heading names for export function
$output .= "\n";
$output .= "\n";
$output .= constant('MANUF').' Global (' .$globalsurvey_description. 's)';
$output .= "\n";
$output .= "\n";

// Subheadings
if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] != "") {
	$tablehead2 = constant('ENTITY')." Grp,";
	$output .= $tabletitle;
	$output .= "\n";
	$output .= constant('ENTITY')."s Selected:,";
	$output .= $_SESSION['multidealercodes'];
	$output .= "\n";
	$output .= "\n";
} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] != "") {
	$tablehead2 = $_SESSION['regionname'].' Region,';
	$output .= $tabletitle. ' - ' .$_SESSION['regionname']. ' Region';
	$output .= "\n";
	$output .= "(" .$_SESSION['dealerIDrocount']. ' of ' .$_SESSION['totalregiondealers']. ' total '.constant('ENTITYLCASE').'s have been surveyed)';
	$output .= "\n";
	$output .= "\n";
} else {
	$tablehead2 = "All ".constant('ENTITY')."s,";
	$output .= $tabletitle. ' - All '.constant('ENTITY').'s';
	$output .= "\n";
	$output .= "(Total Active ".constant('ENTITY')."s: " .$totaldealers_persurvey. ")";
	$output .= "\n";
	$output .= "\n";
}
$output .= $tablehead1;
$output .= $tablehead2;
$output .= $tablehead3;
$output .= $tablehead4;
$output .="\n";