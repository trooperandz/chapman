<?php 
require_once("functions.inc");
include ('templates/login_check.php');

/* -----------------------------------------------------------------------------------------*
   Program: dealerregionglobal_process.php 

   Purpose: Validate and process dealer region selections from global reports 
   pages then set globals for dealercodes and dealerIDs appropriately.
   
   Outputs: $_SESSION['regionname']			- region name
			$_SESSION['totalregiondealers'] - count of total dealers in region
			$_SESSION['regiondealerIDs']	- all dealer IDs in region
			$_SESSION['dealerIDrocount']	- count of dealers in region that have records
			$_SESSION['error'][]        	= any errors to display
			
	Action:
			Invokes page in global $_SESSION['lastpageglobalreports']

	History:
    Date		Description											by
	08/28/2014	Initial design and coding.							Matt Holland
	09/10/2014	Fix subscripting error on $_SESSION['error'].		Matt Holland
	10/28/2014	Add survey type functionality						Matt Holland
	
 *------------------------------------------------------------------------------------------*/

// Database connection
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);
if ($mysqli->connect_errno) {
	die("Database connection failed, errno= ".$mysqli->connect_errno);
}

// Initialize $globalsurveyindex_id variable
if (isset($_SESSION['globalsurveyindex_id'])) {
	$globalsurveyindex_id = $_SESSION['globalsurveyindex_id'];
} else {
	$_SESSION['error'][] = "Error: $globalsurveyindex_id has not been set.  See administrator";
	die(header("Location: enterrofoundation.php"));
}
//echo '$globalsurveyindex_id: ' .$globalsurveyindex_id. '<br>';

	// Return posted item
if (isset($_POST['dealerregionID']) && $_POST['dealerregionID'] != "") {
	$dealerregionID = $mysqli->real_escape_string($_POST['dealerregionID']);
	
	// Query to retrieve region name for global variable
	$query = "SELECT region FROM dealerregion WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) {$_SESSION['error'][] = "Dealer region was not found";
		die (header("Location: " .$_SESSION['lastpageglobalreports'])); }
	$returnrow = $result->fetch_assoc();
	// Set dealer region global variable
	$_SESSION['regionname'] = $returnrow['region'];
	$regionname = $_SESSION['regionname'];
	
	// Query to retrieve total dealer count from specified POST regionID
	$query = "SELECT dealerID FROM dealer WHERE regionID = '{$dealerregionID}'";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
		die (header("Location: " .$_SESSION['lastpageglobalreports'])); }
	// Set total count of dealers in POST regionID
	$_SESSION['totalregiondealers'] = $result->num_rows;
	$row = $result->fetch_assoc();
	
	if ($row) {
		$regiondealerIDs = $row['dealerID'];
		while ($row = $result->fetch_assoc()) {
			$regiondealerIDs .=',';
			$regiondealerIDs .= $row['dealerID'];
		}
	}
	$_SESSION['regiondealerIDs'] = $regiondealerIDs;
	
	// Query the dealers in respective region to see if they have records in repairorder
	$query = "SELECT DISTINCT dealerID FROM repairorder WHERE dealerID IN($regiondealerIDs) and surveyindex_id IN ($globalsurveyindex_id)";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
	die (header("Location: " .$_SESSION['lastpageglobalreports'])); }
	// Total dealer count for dealers in specified region WHERE surveyindex_id = $globalsurveyindex_id
	$_SESSION['dealerIDrocount'] = $result->num_rows;
	// echo '$_SESSION[dealerIDrocount]: ' .$_SESSION['dealerIDrocount']. '<br>';
	
	unset ($_SESSION['multidealercodes']);
	unset ($_SESSION['multidealer']);
	
	die (header("Location: " .$_SESSION['lastpageglobalreports']));

	// Return all 
} else {
	$_SESSION['error'][] = "You did not select a region";
	die (header("Location: " .$_SESSION['lastpageglobalreports']));
}
?>	