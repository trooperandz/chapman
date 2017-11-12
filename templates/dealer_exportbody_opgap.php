<?php
// Generate heading names for export function
$output .= "\n";
$output .= "\n";
$output .= constant('MANUF')." - ".constant('ENTITY')." ". $_SESSION['dealercode']. ' (' .$_SESSION['survey_description']. ') ';
$output .= "\n";
$output .= $tabletitle; 
$output .= "\n";
$output .= "\n";
$output .= $tablehead1;
$output .= $tablehead2;
$output .= $tablehead3;
$output .= $tablehead4;
$output .="\n";