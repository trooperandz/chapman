<?php
/*------------------------------------------------------------------------------------------------------------*
   Program: dealer_exportall_body.php

   Purpose: Build body template for dealer exports (for abiding by DRY)
   History:
    Date		Description									by
	11/17/2014	Initial design and coding					Matt Holland
	
/*------------------------------------------------------------------------------------------------------------*/
// Generate main export function
$output .= "\n";
$output .= "----------------------------------------------------";
$output .= "\n";
$output .= "\n";
$output .= constant('MANUF')." - ".constant('ENTITY'). ' '. $_SESSION['dealercode']. ' (' .$_SESSION['survey_description']. ') ';
$output .= "\n";
$output .= $tabletitle; 
$output .= "\n";
$output .= "\n";
$output .= $tablehead1;
$output .= $tablehead2;
$output .= $tablehead3;
$output .="\n";