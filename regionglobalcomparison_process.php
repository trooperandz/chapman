<?php
require_once("functions.inc");
include ('templates/login_check.php');

/* ------------------------------------------------------------------------------------------------------------*
   Program: regionglobalcomparison_process.php 

   Purpose: Validate and process region vs. global selection from comparison reports menu to 
			compare regions to all dealers, then set magic variables.
   
   Outputs: $_SESSION[regionvsglobalname] 			// Selected region name
			$_SESSION[totalregionvsglobaldealers] 	// Total dealers in selected region	
			$_SESSION[regionvsglobalIDs] 			// String of dealerIDs in selected region
			$_SESSION[regionvsglobalrocount] 		// Total dealers in selected region with ROs
			

	Action:
			Invokes page in global $_SESSION['lastpagecomparisonreports']

	History:
    Date		Description												by
	09/30/2014	Initial design and coding								Matt Holland
	11/20/2014	Added $comparisonsurveyindex_id to queries for RO count Matt Holland
 *------------------------------------------------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

// Invoke $comparisonsurveyindex_id magic variable for dealer counts.  If not set, fatal error; return to enterrofoundation.
if (isset($_SESSION['comparisonsurveyindex_id'])) {
	$comparisonsurveyindex_id = $_SESSION['comparisonsurveyindex_id'];
} else {
	$_SESSION['error'][] = "Error: $comparisonsurveyindex_id variable is not set.  See administrator.";
	die(header("Location: enterrofoundation.php"));
}

// Return posted item - first select field
if (isset($_POST['regionvsglobal']) && !empty($_POST['regionvsglobal'])) {
	$dealerregionID = $mysqli->real_escape_string($_POST['regionvsglobal']);
	
	// Query to retrieve region name for global variable
	$query = "SELECT region FROM dealerregion WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) {$_SESSION['error'][] = "Dealer region was not found";
		die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$returnrow = $result->fetch_assoc();
	// Set dealer region global variable
	$_SESSION['regionvsglobalname'] = $returnrow['region']; // Magic variable for region selection name
	
	// Get dealerIDs from dealer table which are in specified regionID
	$query = "SELECT dealerID FROM dealer WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
		die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$_SESSION['totalregionvsglobaldealers'] = $result->num_rows;  // Magic variable for total # dealers in region selection
	$row = $result->fetch_assoc();
	
	if ($row) {
		$regionvsglobalIDs = $row['dealerID'];
		while ($row = $result->fetch_assoc()) {
			$regionvsglobalIDs .=',';
			$regionvsglobalIDs .= $row['dealerID'];
		}
	}
	$_SESSION['regionvsglobalIDs'] = $regionvsglobalIDs;  // Magic variable for string of dealerIDs
	
	// Query the dealers to see if they have records in repairorder
	$query = "SELECT DISTINCT dealerID FROM repairorder WHERE dealerID IN($regionvsglobalIDs) AND surveyindex_id IN ($comparisonsurveyindex_id)";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
	die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$_SESSION['regionvsglobalrocount'] = $result->num_rows;  // Magic variable for total dealers in region with ROs
	
/*	
echo'
$_SESSION[regionvsglobalname] 			= ' .$_SESSION['regionvsglobalname']. 			'<br>	
$_SESSION[totalregionvsglobaldealers] 	= '	.$_SESSION['totalregionvsglobaldealers'].	'<br>		
$_SESSION[regionvsglobalIDs] 			= ' .$_SESSION['regionvsglobalIDs']. 			'<br>
$_SESSION[regionvsglobalrocount] 		= ' .$_SESSION['regionvsglobalrocount']. 		'<br>';
*/

// Unset all possible comparison menu query variables so that report displays correctly
unset ($_SESSION['compareglobalIDs']		);
unset ($_SESSION['comparedealer1IDs']		);
unset ($_SESSION['comparedealer2IDs']		);
unset ($_SESSION['comparedealerregion1IDs']	);
unset ($_SESSION['compareregiondealerIDs1']	);
unset ($_SESSION['compareregionIDs1']		);
unset ($_SESSION['compareregionIDs2']		);
	
} else {
	$_SESSION['error'][] = "You did not select a region";
	die (header("Location: " .$_SESSION['lastpagecomparisonreports']));
}
die (header("Location: " .$_SESSION['lastpagecomparisonreports']));
?>