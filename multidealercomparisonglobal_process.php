<?php 
require_once("functions.inc");
include ('templates/login_check.php');

/* ------------------------------------------------------------------------------------*
   Program: multidealercomparisonglobal_process.php 

   Purpose: Validate and process dealer selection from comparison reports pages
			then set globals for dealercodes and dealerIDs appropriately.
   
   Outputs: $_SESSION['compareglobalIDs']			- dealer ID list for global queries
			$_SESSION['compareglobalcodes']			- dealercodes for global titles

	Action:
			Invokes page in global $_SESSION['lastpagecomparisonreports']

	History:
    Date		Description												by
	08/18/2014	Adapt multidealerglobal_process to comparison file		Matt Holland
	
 *-------------------------------------------------------------------------------------*/

// Database connection
include ('templates/db_cxn.php');

// Processing for post field
if (isset($_POST['comparedealerall']) && !empty($_POST['comparedealerall'])) {
	$dealer_error = FALSE;  // Sets default value for error
	$keyedmultidealer = $mysqli->real_escape_string($_POST['comparedealerall']);
	/*  Parse multiple dealers entered  */
	$multidealerIDs = "";  // Will hold list of dealer IDs for queries
	$dealerarray = str_getcsv($keyedmultidealer, ",");
	$_SESSION['dealerarraysize'] = sizeof($dealerarray); // Save the size of the array for report heading purposes
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
				$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." has no records";
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
			$_SESSION['compareglobalIDs'] = $multidealerIDs;
		}
		$_SESSION['compareglobalcodes'] = $keyedmultidealer;
		
		// Unset all possible comparison menu query variables so that report displays correctly
		unset ($_SESSION['comparedealer1IDs']			);
		unset ($_SESSION['comparedealer2IDs']			);	
		unset ($_SESSION['comparedealerregion1IDs']		);				
		unset ($_SESSION['compareregiondealerIDs1']		);
		unset ($_SESSION['compareregionIDs1']			);
		unset ($_SESSION['compareregionIDs2']			);
		unset ($_SESSION['regionvsglobalIDs']			);
	}
	
} else {
	$_SESSION['error'][] = "Nothing was entered in the dealer field";
}

die (header("Location: ".$_SESSION['lastpagecomparisonreports']));


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