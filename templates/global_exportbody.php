<?php
/*------------------------------------------------------------------------------------------------------------*
   Program: global_exportbody.php

   Purpose: Build export body template
   History:
    Date		Description										by
	11/17/2014	Initial design and coding						Matt Holland
	12/13/2014  Replaced Nissan and Dealer(s) with constants	Matt Holland
	
/*------------------------------------------------------------------------------------------------------------*/

// Get total count of dealers in repairorder per survey type for 
$query = "SELECT DISTINCT dealerID FROM repairorder WHERE surveyindex_id IN($surveyindex_id)";
$total_dealers_result = $mysqli->query($query);
if (!$total_dealers_result) {
	$_SESSION['error'][] = "Failed to retrieve total dealer count.  repairorder SELECT query failed.  See administrator.";
}
$totaldealers_persurvey = $total_dealers_result->num_rows;

// Manage Survey Type name processing (required for 'All Survey Types' heading)
$globalsurvey_description = $_SESSION['globalsurvey_description'];
if ($_SESSION['globalsurveyindexid_rows'] > 1) {
	$globalsurvey_description = 'All Survey Type';
}

$output= "";
$output .= "Data Export: " .Date("l F d Y");
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