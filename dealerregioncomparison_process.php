<?php
require_once("functions.inc");
include ('templates/login_check.php');

/* ------------------------------------------------------------------------------------------------------------*
   Program: dealerregioncomparison_process.php 

   Purpose: Validate and process dealer selections from comparison reports pages to 
			compare to region, then set magic variables.
   
   Outputs: $_SESSION['comparedealerregion1codes']	-List of dealer codes user entered
			$_SESSION['comparedealerregion1IDs']	-List of dealer IDs derived from user-entered dealer codes
			$_SESSION['compareregionname1']			-Returns region name
			$_SESSION['totalcompareregiondealers1'] -Total dealers in region
			$_SESSION['comparisondealerIDrocount']	-Total dealers in region with ROs
			$_SESSION['compareregiondealerIDs1']	-String of all dealers in the region

	Action:
			Invokes page in global $_SESSION['lastpagecomparisonreports']

	History:
    Date		Description												by
	09/06/2014	Initial design and coding								Matt Holland
	09/10/2014	Fix subscripting error on $_SESSION['error'].			Matt Holland
	09/16/2014	Fix uninitialized $compareregiondealerIDs1.				Matt Holland
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

/*-----------------------------------------Dealer IDs entry field processing----------------------------------------*/
$dealer_error = TRUE;  // Sets default value for error

// Processing for 1st post field
if(isset($_POST['comparedealerregion1']) && !empty($_POST['comparedealerregion1'])) {
	$dealer_error = FALSE;  // Sets default value for error
	$keyedmultidealer = $mysqli->real_escape_string($_POST['comparedealerregion1']);
	/* Parse multiple dealers entered */
	$multidealerIDs = "";  // Will hold list of dealerIDs for queries
	$dealerarray = str_getcsv($keyedmultidealer, ",");
	$_SESSION['dealerarraysize'] = sizeof($dealerarray); // Save the size of the array for report heading purposes
	/* Trim whitspace and trim dealers entered */
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
} /* end if */ 
else {
	$_SESSION['error'][] = "Nothing was entered in field 1";
}

if ($dealer_error == FALSE) {
	unset ($_SESSION['compareglobalIDs']	);
	unset ($_SESSION['compareglobalcodes']	);
	unset ($_SESSION['comparedealer1IDs']	);
	unset ($_SESSION['comparedealer1codes']	);
	unset ($_SESSION['comparedealer2IDs']	);
	unset ($_SESSION['comparedealer2codes']	);
	/*  All dealers specified are valid, set $_SESSION variables for multiple dealers  */
	if ($multidealerIDs != "") {
		/*  Set variable to use in queries for "WHERE dealerID IN (xxx,yyy)"  */
		$_SESSION['comparedealerregion1IDs'] = $multidealerIDs;
	}
	$_SESSION['comparedealerregion1codes'] = $keyedmultidealer;
}

/*-------------------------------------Region select results processing-----------------------------------*/

// Return posted item
if (isset($_POST['compareregionID1']) && !empty($_POST['compareregionID1'])) {
	$dealerregionID = $mysqli->real_escape_string($_POST['compareregionID1']);
	
	// Query to retrieve region name for global variable
	$query = "SELECT region FROM dealerregion WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) {$_SESSION['error'][] = "Dealer region was not found";
		die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$returnrow = $result->fetch_assoc();
	// Set dealer region global variable
	$_SESSION['compareregionname1'] = $returnrow['region'];
	
	// Get dealerIDs from dealer table which are in specified regionID
	$query = "SELECT dealerID FROM dealer WHERE regionID = '{$dealerregionID}' ";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "dealerID query failed";
		die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$_SESSION['totalcompareregiondealers1'] = $result->num_rows;
	$row = $result->fetch_assoc();
	
	if ($row) {
		$compareregiondealerIDs1 = $row['dealerID'];
		while ($row = $result->fetch_assoc()) {
			$compareregiondealerIDs1 .=',';
			$compareregiondealerIDs1 .= $row['dealerID'];
		}
	}
	$_SESSION['compareregiondealerIDs1'] = $compareregiondealerIDs1;
	
	// Query the dealers to see if they have records in repairorder
	$query = "SELECT DISTINCT dealerID FROM repairorder WHERE dealerID IN($compareregiondealerIDs1) AND surveyindex_id IN ($comparisonsurveyindex_id)";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "Line 134: dealerID query failed";
	die (header("Location: " .$_SESSION['lastpagecomparisonreports'])); }
	$_SESSION['comparisondealerIDrocount'] = $result->num_rows;
/*	
echo'
$_SESSION[comparedealerregion1codes] = ' .$_SESSION['comparedealerregion1codes']. '<br>
$_SESSION[comparedealerregion1IDs] = ' .$_SESSION['comparedealerregion1IDs']. '<br>	
$_SESSION[compareregionname1] = '	   .$_SESSION['compareregionname1']. '<br>		
$_SESSION[totalcompareregiondealers1] = ' .$_SESSION['totalcompareregiondealers1']. '<br>
$_SESSION[comparisondealerIDrocount] = ' .$_SESSION['comparisondealerIDrocount']. '<br>
$_SESSION[compareregiondealerIDs1] = ' .$_SESSION['compareregiondealerIDs1']. '<br>';
die();
*/	

// Unset all possible comparison menu query variables so that report displays correctly
unset ($_SESSION['compareglobalIDs']	);
unset ($_SESSION['comparedealer1IDs']	);
unset ($_SESSION['comparedealer2IDs']	);
unset ($_SESSION['compareregionIDs1']	);
unset ($_SESSION['compareregionIDs2']	);
unset ($_SESSION['regionvsglobalIDs']	);

} else {
	$_SESSION['error'][] = "You did not select a region";
	die (header("Location: " .$_SESSION['lastpagecomparisonreports']));
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