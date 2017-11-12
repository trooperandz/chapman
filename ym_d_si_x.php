<?php
/* ----------------------------------------------------------------------*
   Program: ym_d_si_x.php

   Purpose: Export Single Issue Model Year data in csv format
   History:
    Date		Description									by
	01/22/2015	Created SI report from original YM report	Matt Holland
	03/10/2015	Incorporated new ym_string processing to	Matt Holland
				include bucket year in congruence with main
				Model Year system reporting format.
				Added new includes: system, database and
				default variable initialization
*-----------------------------------------------------------------------*/

// Required system includes
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

/*------------------------------------------------------------------------------------------------------------*/

// Set year model strings for queries
include ('templates/ym_string.php');
include ('templates/ym_string2.php');
// echo '$yearmodel_string: '.$yearmodel_string.'<br>';
// echo '$yearmodel_string2: '.$yearmodel_string2.'<br>';

/*-----------------------------------Set report variables for includes----------------------------------------*/
 
$tabletitle 	  = 'Single Issue Model Year Distribution'	;	// Set table title
$tablehead1 	  = 'Model Year,'							;	// Set first table header title
$tablehead2 	  = 'Total ROs,'							;	// Set second table header title
$tablehead3 	  = 'Percentage'							;	// Set third table header title
$filename1		  = 'SIModelYearDistribution.csv'			;	// Set file name for header instruction		 
/*---------------------------------------------Dealer query---------------------------------------------------*/

// Query for total dealer rows
include ('templates/query_totalros_si_md1.php');

//  Query for dealer model year	
include ('templates/query_ym_si_dlr.php');

//  Query for dealer model year	
include ('templates/query_ym_si_dlr2.php');

/*---------------------------------------------Build Export----------------------------------------------------*/
include ('templates/dealer_exportbody.php');

//Get records from table
while ($row = $resultmy_si->fetch_array()) {
	for ($i=0; $i < $columns_total; $i++){
		if ($i == 2) {
		$output .='"'.$row[2].'%'.'",';
		} else {
		$output .='"'.$row["$i"].'",';
		}
	}
	$output .="\n";
}
// Get records from second query
$output .='"'.$bucket_year.'",';
$output .='"'.$bucket_si[0].'",';
$output .='"'.$bucket_si[1].'%'.'",';
$output .="\n";
include ('templates/dealer_exportfooter.php');
?>