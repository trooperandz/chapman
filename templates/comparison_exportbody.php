<?php
/*------------------------------------------------------------------------------------------------------------*
   Program: comparison_exportbody.php

   Purpose: Build body template for comparison exports
   History:
    Date		Description									by
	11/17/2014	Initial design and coding					Matt Holland
	
/*------------------------------------------------------------------------------------------------------------*/

// Get total count of dealers in repairorder per survey type for comparison reports
$query = "SELECT DISTINCT dealerID FROM repairorder WHERE surveyindex_id IN($surveyindex_id)";
$total_dealers_result = $mysqli->query($query);
if (!$total_dealers_result) {
	$_SESSION['error'][] = "Failed to retrieve total dealer count.  repairorder SELECT query failed.  See administrator.";
}
$rows = $total_dealers_result->num_rows;
$totaldealers_persurvey = $rows;

// Manage Survey Type name processing (required for 'All Survey Types' heading)
if (isset($_SESSION['comparisonsurvey_description'])) {
	if (isset($_SESSION['comparisonsurveyindexid_rows'])) {
		$comparisonsurvey_description = $_SESSION['comparisonsurvey_description'];
		$surveyindexid_rows 		  = $_SESSION['comparisonsurveyindexid_rows'];
		if ($surveyindexid_rows > 1) {
			$comparisonsurvey_description = 'All Survey Type';
		}
	}
}

$output= "";

$output .= "Data Export: " .Date("l F d Y");
$output .= "\n";
if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
	$output .= constant('MANUF')." - ".constant('ENTITY')." Comparison (" .$comparisonsurvey_description. "s)";
} elseif (isset($_SESSION['compareglobalIDs'])) {
	$output .= constant('MANUF')." - Global Comparison (" .$comparisonsurvey_description. "s)";
} elseif ((isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) OR (isset($_SESSION['regionvsglobalIDs'])))	{
	// echo 'Reached spot initial<br>';
	$output .= constant('MANUF')." - Region Comparison (" .$comparisonsurvey_description. "s)";
} else {
	$output .= constant('MANUF')." - ".constant('ENTITY')." Comparison (" .$comparisonsurvey_description. "s)";
}	
	// echo 'Reached spot #1<br>';
	$output .= "\n";
	$output .= "\n";
	$output .= $tabletitle;
	$output .= "\n";
	$output .= "\n";
	
if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
	if ($_SESSION['dealerarraysize1'] > 1 OR $_SESSION['dealerarraysize2'] > 1) {
		$output .= "Group 1 ".constant('ENTITY')."s:,";
		$output .= "\n";
		$output .= $_SESSION['comparedealer1codes'];
		$output .= "\n";
		$output .= "Group 2 ".constant('ENTITY')."s:,";
		$output .= "\n";
		$output .= $_SESSION['comparedealer2codes'];
		$output .= "\n";
		$output .= "\n";
	} else {
		$output .= constant('ENTITY')." " .$_SESSION['comparedealer1codes']. " Vs. ".constant('ENTITY')." " .$_SESSION['comparedealer2codes'];
		$output .= "\n";
		$output .= "\n";
	}
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1 ) {
		$output .= constant('ENTITY')." " .$_SESSION['compareglobalcodes']. " Vs. All Dealers";
		$output .= "\n";
	} else {
		$output .= constant('ENTITY')."s Selected:, ";
		$output .= $_SESSION['compareglobalcodes'];
		$output .= "\n";
	}
	$output .= "(Total active ".constant('ENTITYLCASE')."s: " .$totaldealers_persurvey. ")";
	$output .= "\n";
	$output .= "\n";
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
		$output .= constant('ENTITY')." " .$_SESSION['comparedealerregion1codes']. " Vs. " .$_SESSION['compareregionname1']. " Region"; 
		$output .= "\n";
		$output .= " (" .$_SESSION['comparisondealerIDrocount']. " of " .$_SESSION['totalcompareregiondealers1']. " total ".constant('ENTITYLCASE')."s in the region have records)";
		$output .= "\n";
		$output .= "\n";
	} else {
		// echo 'Reached spot #2;<br>';
		$output = constant('ENTITY')."s Selected:, ";
		$output .= "\n";
		$output .= $_SESSION['comparedealerregion1codes'];
		$output .= "\n";
		$output .= "\n";
		$output .= $_SESSION['compareregionname1']. " Region,";
		$output .= " (" .$_SESSION['comparisondealerIDrocount']. " of " .$_SESSION['totalcompareregiondealers1']. " total ".constant('ENTITYLCASE')."s have records)";
		$output .= "\n";
		$output .= "\n";
	}
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {	
	$output .= "Region 1 Selection:, " .$_SESSION['regionname1']. ','; 
	$output .= " ( " .$_SESSION['regionrocount1']. " of " .$_SESSION['totalregiondealers1']. " total ".constant('ENTITYLCASE')."s have records)";
	$output .= "\n";
	$output .= "Region 2 Selection:, " .$_SESSION['regionname2']. ',';
	$output .= " ( " .$_SESSION['regionrocount2']. " of " .$_SESSION['totalregiondealers1']. " total ".constant('ENTITYLCASE')."s have records)";
	$output .= "\n";
	$output .= "\n";
} elseif (isset($_SESSION['regionvsglobalIDs'])) {	
	$output .= $_SESSION['regionvsglobalname']. " Region vs. All ".constant('ENTITY')."s (" .$totaldealers_persurvey. " total active dealers)";
	$output .= "\n";
	$output .= "(" .$_SESSION['regionvsglobalrocount']. " of " .$_SESSION['totalregionvsglobaldealers']. " total ".constant('ENTITYLCASE')."s in the region have records)";
	$output .= "\n";
	$output .= "\n";
} else {
	$output .= constant('ENTITY')." " .$dealercode. " Vs. All ".constant('ENTITY')."s";
	$output .= "\n";
	$output .= "(Total active ".constant('ENTITYLCASE')."s: " .$totaldealers_persurvey. ")";
	$output .= "\n";
	$output .= "\n";
}
/*--------------------------------------------------------------------------------------------*/
// Table headings

$output .= $tablehead1;

if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
	if ($_SESSION['dealerarraysize1'] == 1 && $_SESSION['dealerarraysize2'] == 1) {
		$output .= constant('ENTITY').' ' .$_SESSION['comparedealer1codes']. ',';
		$output .= constant('ENTITY').' ' .$_SESSION['comparedealer2codes'];
	} else {
		$output .= constant('ENTITY').' Grp 1,';
		$output .= constant('ENTITY').' Grp 2';
	}						
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
		$output .= constant('ENTITY').' ' .$_SESSION['compareglobalcodes']. ',';
		$output .= 'All '.constant('ENTITY').'s';
	} else {
		$output .= constant('ENTITY').' Group,';
		$output .= 'All '.constant('ENTITY').'s';
	}						
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
		$output .= constant('ENTITY').' ' .$_SESSION['comparedealerregion1codes']. ',';
		$output .= $_SESSION['compareregionname1']. ' Region';
	} else {
		$output .= constant('ENTITY').' Group,';
		$output .= $_SESSION['compareregionname1']. ' Region';
	}	
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {
	$output .=  $_SESSION['regionname1']. ' Region,';
	$output .=  $_SESSION['regionname2']. ' Region';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {
	$output .=  $_SESSION['regionvsglobalname']. ' Region,';
	$output .= 'All '.constant('ENTITY').'s';
} else {	
	$output .= constant('ENTITY').' ' .$dealercode. ',';
	$output .= 'All '.constant('ENTITY').'s';
}
$output .="\n";