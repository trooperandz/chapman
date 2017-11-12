<?php // multidealerglobal_process.php
require_once("functions.inc");
include ('templates/login_check.php');
/* -------------------------------------------------------------------------*
   Program: multidealerglobal_process.php 

   Purpose: Validate and process dealer selections from global reports pages
			then set globals for dealercodes and dealerIDs appropriately.
   
   Outputs: $_SESSION['multidealer']		- dealer ID list for queries
			$_SESSION['multidealercodes'];	- dealercodes for report titles
			$_SESSION['error'][]        	= any errors to display
			

	Action:
			Invokes page in global $_SESSION['lastpageglobalreports']

	History:
    Date		Description										by
	08/13/2014	Initial design and coding.						Matt Holland
	08/17/2014	Fix multidealer prob when dealer not found.		Matt Holland
	08/18/2014	Make code more understandable.					Matt Holland
	09/10/2014	Add outputs comment for errors.					Matt Holland
	10/28/2014	Add survey functionality (globalsurveyindex_id)	Matt Holland
	
 *---------------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');
// Initiate $globalsurveyindex_id global for query
$globalsurveyindex_id = $_SESSION['globalsurveyindex_id'];

if (isset($_POST['multidealer']) && !empty($_POST['multidealer'])) {
	$dealer_error = FALSE;
	$keyedmultidealer = $mysqli->real_escape_string($_POST['multidealer']);
	/*  Parse multiple dealers entered  */
	$dealerarray = str_getcsv($keyedmultidealer, ",");
	$multidealerIDs = "";		/* holds list of dealer IDs for queries */
	/*  Trim whitespace and validate dealers entered  */
	for ($i=0; $i<sizeof($dealerarray); $i++) {
		$dealerarray[$i] = trim($dealerarray[$i]);
		if (dealer_is_valid($mysqli, $dealerarray[$i], $dealerID)) {
			if (dealer_in_repairorder($mysqli, $dealerID, $globalsurveyindex_id)) {
				$multidealerIDs .= $dealerID;
				if ($i < sizeof($dealerarray)-1) {
					$multidealerIDs .= ",";
					}
				}
			else {
				/*  dealerID is not in repair order table  */
				$dealer_error = TRUE;
				$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." has no records for a " .$_SESSION['globalsurvey_description']. '.';
				}
			}
		else {
			/*  dealerID not in dealer table  */
			$dealer_error = TRUE;
			$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." does not exist";
		}
	}  /* end for */
	
	/*  Set global for multi-dealer to use in query if all dealers OK  */
	if ($dealer_error == FALSE) {
		/*  All dealers specified are valid, set $_SESSION variables for multiple dealers  */
		if ($multidealerIDs != "") {
			/*  Set variable to use in queries for "where dealerid in (xxx,yyy)"  */
			$_SESSION['multidealer'] = $multidealerIDs;
		}
		$_SESSION['multidealercodes'] = $keyedmultidealer;
		}
		unset ($_SESSION['regiondealerIDs']);
	} 
else {
	$_SESSION['error'][] = "Nothing entered in dealer field";
}
die (header("Location: ".$_SESSION['lastpageglobalreports']))
?>

<?php
function dealer_is_valid($mysqli, $dealercode, &$dealerID) {
/*---------------------------------------------------------------*
  Purpose:
		Check whether dealer code is in dealer table and if so 
		return the associated dealer ID.
   Inputs:
		$mysqli 	- mysqli database object
		$dealercode	- dealer code 
  Outputs:
		$dealerID 	- dealerID associated with $dealercode
		Return code:
			TRUE 	- dealercode is in dealer table
			FALSE	- dealercode is not in dealer table
 *--------------------------------------------------------------*/
	$safedealercode = $mysqli->real_escape_string($dealercode); 
	$query = "SELECT dealerID, dealercode FROM dealer WHERE dealercode = '{$safedealercode}'"; 
	if (!$result = $mysqli->query($query)) {
		/*  Query error */
		return false; 
	}
	if ($result->num_rows == 0) { 
		/*  Dealer not found  */
		return false; 
		} 
	else {
		/*  Found dealer, set dealerID to return  */
		$row = $result->fetch_assoc();
		$dealerID = $row['dealerID'];
		return true;
	}
}

function dealer_in_repairorder($mysqli, $dealerid, $globalsurveyindex_id) {
/*--------------------------------------------------------------*
  Purpose:
		Check if any records exist in repair order table 
		with this dealer ID.
   Inputs:
		$mysqli 	- mysqli database object
		$dealerID 	- dealerID associated with $dealercode
  Outputs:
		Return code:
			TRUE 	- $dealerID is in repair order table
			FALSE	- $dealerID is not in repair order table
 *-------------------------------------------------------------*/
	$query ="SELECT COUNT(ronumber) FROM repairorder 
			WHERE dealerID = {$dealerid}  AND surveyindex_id IN($globalsurveyindex_id)";
	if (!$result = $mysqli->query($query)) {
		/* Cannot read repairorder file to get count */
		return FALSE;
		}
	else {
		/* Get count of repair orders */
		$row = mysqli_fetch_row($result);
		if ($row[0] > 0) {
			/*  Dealers were found  */
			return TRUE;
			}
		else {
			/*  No dealers found  */
			return FALSE;
		}
	} 					
}
?>


	