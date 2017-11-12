<?php 
require_once("functions.inc");
include ('templates/login_check.php');

/* -------------------------------------------------------------------------*
   Program: multidealercomparison_process.php 

   Purpose: Validate and process dealer selections from comparison reports pages
			then set globals for dealercodes and dealerIDs appropriately.
   
   Outputs: $_SESSION['comparedealer1IDs']			- dealer ID list for group 1 queries
			$_SESSION['comparedealer1codes']		- dealercodes for group 1 report titles
			
			$_SESSION['comparedealer2IDs']			- dealer ID list for group 2 queries		
			$_SESSION['comparedealer2codes']		- dealercodes for group 2 report titles
			
			$_SESSION['dealerarraysize1']			- Save the size of the first user-entered array for report heading purposes
			$_SESSION['dealerarraysize2']			- Save the size of the second user-entered array for report heading purposes

	Action:
			Invokes page in global $_SESSION['lastpagecomparisonreports']

	History:
    Date		Description											by
	08/18/2014	Adapt multidealerglobal_process to comparison file	Matt Holland
	
 *---------------------------------------------------------------------------*/

// Database connection
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);
if ($mysqli->connect_errno) {
	die("Database connection failed, errno= ".$mysql->connect_errno);
}

$dealer_error = TRUE;  // Sets default value for error
$dealer_error2 = TRUE;  // Sets default value for error

// Processing for 1st post field
if (isset($_POST['comparedealer1']) && !empty($_POST['comparedealer1'])) {
	$dealer_error = FALSE;  // Sets default value for error
	$keyedmultidealer = $mysqli->real_escape_string($_POST['comparedealer1']);
	/*  Parse multiple dealers entered  */
	$multidealerIDs = "";  // Will hold list of dealer IDs for queries
	$dealerarray = str_getcsv($keyedmultidealer, ",");
	$_SESSION['dealerarraysize1'] = sizeof($dealerarray); // Save the size of the array for report heading purposes
	/*  Trim whitespace and validate dealers entered  */
	for ($i=0; $i<sizeof($dealerarray); $i++) {
		$dealerarray[$i] = trim($dealerarray[$i]);
		if (dealer_is_valid($mysqli, $dealerarray[$i], $dealerID)) {
			if (dealer_in_repairorder($mysqli, $dealerID)) {
				$multidealerIDs .= $dealerID;
				if ($i < sizeof($dealerarray)-1) {
					$multidealerIDs .= ",";
					}
				}
			else {
				/*  dealerID is not in repair order table  */
				$dealer_error = TRUE;
				$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." from field 1 has no records";
				}
			}
		else {
			/*  dealerID not in dealer table  */
			$dealer_error = TRUE;
			$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." from field 1 does not exist";
		}
	}  /* end for */
	
	/*  Set global for multi-dealer to use in query if all dealers OK  */
	} 
else {
	$_SESSION['error'][] = "Nothing was entered in field 1";
}
// Processing for 2nd post field
if (isset($_POST['comparedealer2']) && !empty($_POST['comparedealer2'])) {
	$dealer_error2 = FALSE;  // Sets default value for error
	$keyedmultidealer2 = $mysqli->real_escape_string($_POST['comparedealer2']);
	/*  Parse multiple dealers entered  */
	$multidealerIDs2 = "";  // Will hold list of dealer IDs for queries
	$dealerarray = str_getcsv($keyedmultidealer2, ",");
	$_SESSION['dealerarraysize2'] = sizeof($dealerarray); // Save the size of the array for report heading purposes
	/*  Trim whitespace and validate dealers entered  */
	for ($i=0; $i<sizeof($dealerarray); $i++) {
		$dealerarray[$i] = trim($dealerarray[$i]);
		if (dealer_is_valid($mysqli, $dealerarray[$i], $dealerID)) {
			if (dealer_in_repairorder($mysqli, $dealerID)) {
				$multidealerIDs2 .= $dealerID;
				if ($i < sizeof($dealerarray)-1) {
					$multidealerIDs2 .= ",";
					}
				}
			else {
				/*  dealerID is not in repair order table  */
				$dealer_error2 = TRUE;
				$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." from field 2 has no records";
				}
			}
		else {
			/*  dealerID not in dealer table  */
			$dealer_error2 = TRUE;
			$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." from field 2 does not exist";
		}
	}  /* end for */

	/*  Set global for multi-dealer to use in query if all dealers OK  */
	}
else {
	$_SESSION['error'][] = "Nothing was entered in field 2";
}
if ($dealer_error == FALSE && $dealer_error2 == FALSE) {
	/*  All dealers specified are valid, set $_SESSION variables for multiple dealers  */
	if ($multidealerIDs2 != "") {
		/*  Set variable to use in queries for "where dealerid in (xxx,yyy)"  */
		$_SESSION['comparedealer2IDs'] = $multidealerIDs2;
	}
	$_SESSION['comparedealer2codes'] = $keyedmultidealer2;
	/*  All dealers specified are valid, set $_SESSION variables for multiple dealers  */
	if ($multidealerIDs != "") {
		/*  Set variable to use in queries for "where dealerid in (xxx,yyy)"  */
		$_SESSION['comparedealer1IDs'] = $multidealerIDs;
	}
	$_SESSION['comparedealer1codes'] = $keyedmultidealer;
	
	// Unset all possible comparison menu query variables so that report displays correctly
	unset ($_SESSION['compareglobalIDs']			);
	unset ($_SESSION['comparedealerregion1IDs']		);				
	unset ($_SESSION['compareregiondealerIDs1']		);
	unset ($_SESSION['compareregionIDs1']			);
	unset ($_SESSION['compareregionIDs2']			);
	unset ($_SESSION['regionvsglobalIDs']			);
}

die (header("Location: ".$_SESSION['lastpagecomparisonreports']))
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

function dealer_in_repairorder($mysqli, $dealerid) {
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
			WHERE dealerID = {$dealerid}";
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


	