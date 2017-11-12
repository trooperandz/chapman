<?php
/*------------------------------------------------------------------------------------------------------------*
   Program: global_exportall_body.php

   Purpose: Build body template for global all export (for abiding by DRY)
   History:
    Date		Description								by
	11/25/2014  Initial design and coding				Matt Holland

/*------------------------------------------------------------------------------------------------------------*/

/*------------------------------------------------------------------------------------------------------------*/
// Build export data
$output .= "\n";
$output .= "---------------------------------------------------------------";
$output .= "\n";
$output .= "\n";

// Main heading
$output .= constant('MANUF').' Global (' .$globalsurvey_description. 's)';
$output .= "\n";

// Subheadings
if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] != "") {
	$output .= $tabletitle;
	$output .= "\n";
	$output .= constant('ENTITY')."s Selected:,";
	$output .= $_SESSION['multidealercodes'];
	$output .= "\n";
	$output .= "\n";
} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] != "") {
	$output .= $tabletitle. ' - ' .$_SESSION['regionname']. ' Region';
	$output .= "\n";
	$output .= "(" .$_SESSION['dealerIDrocount']. ' of ' .$_SESSION['totalregiondealers']. ' total '.constant('ENTITYLCASE').'s have been surveyed)';
	$output .= "\n";
	$output .= "\n";
} else {
	$output .= $tabletitle. ' - All '.constant('ENTITY').'s';
	$output .= "\n";
	$output .= "(Total Active ".constant('ENTITY')."s: " .$totaldealers_persurvey. ")";
	$output .= "\n";
	$output .= "\n";
}	
	$output .= $tablehead1;
	$output .= $tablehead2;
	$output .= $tablehead3;
	$output .="\n";