<?php
require_once("functions.inc");
include ('templates/login_check.php');
/* ------------------------------------------------------------------------------------------------------------*
   Program: regioncomparison_process.php 

   Purpose: Validate and process region selections from comparison reports pages to 
			compare regions, then set magic variables.
   
   Outputs: $_SESSION['compareregionIDs1']			-String of all dealers in first region selection
			$_SESSION['compareregionIDs2']			-String of all dealers in second region selection
			$_SESSION['regionname1']				-Returns region name of first selection
			$_SESSION['regionname2']				-Returns region name of second selection
			$_SESSION['totalregiondealers1']		-Returns total # dealers in first region selection
			$_SESSION['totalregiondealers2']		-Returns total # dealers in second region selection
			$_SESSION['regionrocount1']				-Returns total # dealers in first region selection with ROs
			$_SESSION['regionrocount2']				-Returns total # dealers in second region selection with ROs

	Action:
			Invokes page in global $_SESSION['lastpagecomparisonreports']

	History:
    Date		Description												by
	09/30/2014	Initial design and coding								Matt Holland
	11/20/2014	Added $comparisonsurveyindex_id to queries for RO count Matt Holland
 *------------------------------------------------------------------------------------------------------------*/

// Database connection
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);
if ($mysqli->connect_errno) {
die("Database connection failed, errno= ".$mysql->connect_errno);
}

// Invoke $comparisonsurveyindex_id magic variable for dealer counts.  If not set, fatal error; return to enterrofoundation.
if (isset($_SESSION['comparisonsurveyindex_id'])) {
	$comparisonsurveyindex_id = $_SESSION['comparisonsurveyindex_id'];
} else {
	$_SESSION['error'][] = "Error: $comparisonsurveyindex_id variable is not set.  See administrator.";
	die(header("Location: enterrofoundation.php"));
}

// Return posted item - first select field
if (isset($_POST['compareregion1']) && !empty($_POST['compareregion1'])) {
	$dealerregionID = $mysqli->real_escape_string($_POST['compareregion1']);
	
	// Query to retrieve region name for global variable
	$query = "SELECT region FROM dealerregion WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) {$_SESSION['error'][] = "Dealer region was not found";
		die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$returnrow = $result->fetch_assoc();
	// Set dealer region global variable
	$_SESSION['regionname1'] = $returnrow['region']; // Magic variable for region selection name, field 1
	
	// Get dealerIDs from dealer table which are in specified regionID
	$query = "SELECT dealerID FROM dealer WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
		die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$_SESSION['totalregiondealers1'] = $result->num_rows;  // Magic variable for total # dealers in region selection, field 1
	$row = $result->fetch_assoc();
	
	if ($row) {
		$compareregionIDs1 = $row['dealerID'];
		while ($row = $result->fetch_assoc()) {
			$compareregionIDs1 .=',';
			$compareregionIDs1 .= $row['dealerID'];
		}
	}
	$_SESSION['compareregionIDs1'] = $compareregionIDs1;  // Magic variable for string of dealerIDs in first region selection
	
	// Query the dealers to see if they have records in repairorder
	$query = "SELECT DISTINCT dealerID FROM repairorder WHERE dealerID IN($compareregionIDs1) AND surveyindex_id IN ($comparisonsurveyindex_id)";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
	die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$_SESSION['regionrocount1'] = $result->num_rows;  // Magic variable for total dealers in region with ROs, first selection
/*	
echo'
$_SESSION[compareregionIDs1] = ' .$_SESSION['compareregionIDs1']. '<br>	
$_SESSION[regionname1] = '	   .$_SESSION['regionname1']. '<br>		
$_SESSION[totalregiondealers1] = ' .$_SESSION['totalregiondealers1']. '<br>
$_SESSION[regionrocount1] = ' .$_SESSION['regionrocount1']. '<br>';
*/
	
} else {
	$_SESSION['error'][] = "You did not select a region in field 1";
	die (header("Location: " .$_SESSION['lastpagecomparisonreports']));
}

// Return posted item - second select field
if (isset($_POST['compareregion2']) && !empty($_POST['compareregion2'])) {
	$dealerregionID = $mysqli->real_escape_string($_POST['compareregion2']);
	
	// Query to retrieve region name for global variable
	$query = "SELECT region FROM dealerregion WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) {$_SESSION['error'][] = "Dealer region was not found";
		die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$returnrow = $result->fetch_assoc();
	// Set dealer region global variable
	$_SESSION['regionname2'] = $returnrow['region']; // Magic variable for region selection name, field 2
	
	// Get dealerIDs from dealer table which are in specified regionID
	$query = "SELECT dealerID FROM dealer WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
		die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$_SESSION['totalregiondealers2'] = $result->num_rows;  // Magic variable for total # dealers in region selection, field 2
	$row = $result->fetch_assoc();
	
	if ($row) {
		$compareregionIDs2 = $row['dealerID'];
		while ($row = $result->fetch_assoc()) {
			$compareregionIDs2 .=',';
			$compareregionIDs2 .= $row['dealerID'];
		}
	}
	$_SESSION['compareregionIDs2'] = $compareregionIDs2;  // Magic variable for string of dealerIDs in field 2
	
	// Query the dealers to see if they have records in repairorder
	$query = "SELECT DISTINCT dealerID FROM repairorder WHERE dealerID IN($compareregionIDs2) AND surveyindex_id IN ($comparisonsurveyindex_id)";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
	die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$_SESSION['regionrocount2'] = $result->num_rows;  // Magic variable for total dealers in region with ROs, field 2
/*	
echo'
$_SESSION[compareregionIDs2] = ' .$_SESSION['compareregionIDs2']. '<br>	
$_SESSION[regionname2] = '	   .$_SESSION['regionname2']. '<br>		
$_SESSION[totalregiondealers2] = ' .$_SESSION['totalregiondealers2']. '<br>
$_SESSION[regionrocount2] = ' .$_SESSION['regionrocount2']. '<br>';
*/

// Unset all possible comparison menu query variables so that report displays correctly
unset ($_SESSION['compareglobalIDs']		);
unset ($_SESSION['comparedealer1IDs']		);
unset ($_SESSION['comparedealer2IDs']		);
unset ($_SESSION['comparedealerregion1IDs']	);
unset ($_SESSION['compareregiondealerIDs1']	);
unset ($_SESSION['regionvsglobalIDs']		);

} else {
	$_SESSION['error'][] = "You did not select a region in field 2";
	die (header("Location: " .$_SESSION['lastpagecomparisonreports']));
}

die (header("Location: " .$_SESSION['lastpagecomparisonreports']));