<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* ----------------------------------------------------------------------*
   Program: csvsingleissueexport.php

   Purpose: Export Single Issue Occurrence data in csv format
   History:
    Date		Description									by
	06/01/2014	Initial design and coding					Matt Holland
	08/03/2014	Update to mysqli & one query				Matt Holland
	11/17/2014	Incorporate new template includes format	
				and yearmodel_string processing				Matt Holland
	12/03/2014	Updated with standard query includes			Matt Holland
 *-----------------------------------------------------------------------*/	

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
 
$tabletitle 	  = 'Single Issue Distribution'		;	// Set table title
$tablehead1 	  = 'Category,'						;	// Set first table header title
$tablehead2 	  = 'Percentage,'					;	// Set second table header title
$tablehead3 	  = 'Total ROs'						;	// Set third table header title
$filename1		  = 'SingleIssueDistribution.csv'	;	// Set file name for header instruction		 

/*---------------------------------------------Dealer query---------------------------------------------------*/
include ('templates/query_si_md1.php');

// Consolidate computations	
include ('templates/query_si_summary.php');

/*---------------------------------------------Build Export---------------------------------------------------*/
include ('templates/dealer_exportbody.php');

// Generate first line
$output .= "Single Issue ROs,"; 
$output .= '"'.$percentsingle. '%' . '",';
$output .= '"'.$totalsingle.'"';
$output .="\n";

// Generate second line
$output .= "Multiple Issue ROs,";
$output .= '"'.$percentmultiple. '%' . '",';
$output .= '"'.$totalmultiple.'"';
$output .="\n";

include ('templates/dealer_exportfooter.php');
?>