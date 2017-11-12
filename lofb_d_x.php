<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: csvlofbaselineexport.php

   Purpose: Export LOF Baseline data in csv format
   History:
    Date		Description									by
	06/01/2014	Initial design and coding					Matt Holland
	08/03/2014	Update to mysqli & one query				Matt Holland
	11/17/2014	Incorporate new template includes format	
				and yearmodel_string processing				Matt Holland
	12/03/2014	Updated with standard query includes		Matt Holland
	12/18/2014	Updated includes and body/chart variables	Matt Holland
 *---------------------------------------------------------------------------*/
 
// Database connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   = $_SESSION['dealerID']; 	// Initialize $dealerID variable
$dealerIDs1 = $_SESSION['dealerID'];  	// Initialize dealer variable for query includes
$dealercode = $_SESSION['dealercode'];  // Initialize $dealercode variable
$userID		= $user->userID;			// Initialize user

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

/*-----------------------------------Set report variables for includes----------------------------------------*/
 
$tabletitle 	  = 'LOF Baseline Distribution'		;	// Set table title
$tablehead1 	  = 'Average Labor,'				;	// Set first table header title
$tablehead2 	  = 'Average Parts,'				;	// Set second table header title
$tablehead3 	  = 'Avg Labor & Parts'				;	// Set third table header title
$filename1		  = 'LOFBaselineDistribution.csv'	;	// Set file name for header instruction	
	 
/*---------------------------------------------Dealer query---------------------------------------------------*/
include ('templates/query_lofb_md1.php');

/*---------------------------------------------Build Export---------------------------------------------------*/
include ('templates/dealer_exportbody.php');

// Generate line
$output .= '"'. '$'.$averagelabor. '",';
$output .= '"'. '$'.$averageparts. '",';
$output .= '"'. '$'.$averagepartspluslabor. '"';
$output .="\n";

include ('templates/dealer_exportfooter.php');
?>