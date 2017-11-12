<?php 
require_once("functions.inc");
include ('templates/login_check.php');
include('templates/db_cxn.php');

$dealerID 	 = $_SESSION['dealerID']	 ;  // Initiate dealerID magic variable
$userID 	 = $user->userID			 ;	// Initiate $userID magic variable
$longhornbox = $_POST['longhornbox']	 ;  // Initiate $_POST variable

if (isset($longhornbox) && !empty($longhornbox)) {
	$longhorncount = count($longhornbox);
	//echo 'You selected ' .$longhorncount. ' checkboxes: <br>';
	$longhornservices = "";
	for ($i=0; $i<$longhorncount; $i++) {
		if ($i == ($longhorncount-1)) {
			$longhornservices .= $longhornbox[$i];
		} else {
			$longhornservices .= $longhornbox[$i] . ',';
		}
	}
	//echo $longhornservices, '<br>';
	
	/*------------------------------------------------------------------------------------*/
	// Initiate report_type_id variable from magic variable (gets set on initial access of all servicetype reports)
	if (isset($_SESSION['report_type_id'])) {
		$report_type_id = $_SESSION['report_type_id'];
	} else {
		$_SESSION['error'][] = "Report type for Longhorn report was not set.  Please see administrator.";
		die(header("Location: enterrofoundation.php"));
	}
	
	/*------------------------------------------------------------------------------------*/
	// Check to see if record with $userID and $report_type_id is already in table
	$query = "SELECT * FROM longhorn_svcs WHERE userID = $userID AND report_type_id = $report_type_id";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "longhorn_svcs SELECT query failed.  See administrator.";
		if ($report_type_id == 1) {
			die(header("Location: ".$_SESSION['lastpagedealerreports']));
		} elseif ($report_type_id == 2) {	
			die(header("Location: ".$_SESSION['lastpageglobalreports']));
		} elseif ($report_type_id == 3) {	
			die(header("Location: ".$_SESSION['lastpagecomparisonreports']));
		}
	}
	$rows = $result->num_rows;
	if ($rows > 0) {
		// Update longhorn_svcs table with services string if $userID record already exists
		$query = "UPDATE longhorn_svcs SET longhorn_string = '$longhornservices', userID = '$userID', report_type_id = '$report_type_id', create_date = NOW() WHERE userID = $userID
			      AND report_type_id = '$report_type_id' ";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "longhorn_svcs UPDATE query failed.  See administrator.";
			if ($report_type_id == 1) {
				die(header("Location: ".$_SESSION['lastpagedealerreports']));
			} elseif ($report_type_id == 2) {	
				die(header("Location: ".$_SESSION['lastpageglobalreports']));
			} elseif ($report_type_id == 3) {	
				die(header("Location: ".$_SESSION['lastpagecomparisonreports']));
			}
		}
	} else {
		// If there is no record with $userID and $report_type_id, insert new one
		$query = "INSERT INTO longhorn_svcs (longhorn_string, userID, report_type_id, create_date ) VALUES ('$longhornservices', '$userID', '$report_type_id', NOW() )";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "longhorn_svcs INSERT query failed.  See administrator.";
			if ($report_type_id == 1) {
				die(header("Location: ".$_SESSION['lastpagedealerreports']));
			} elseif ($report_type_id == 2) {	
				die(header("Location: ".$_SESSION['lastpageglobalreports']));
			} elseif ($report_type_id == 3) {	
				die(header("Location: ".$_SESSION['lastpagecomparisonreports']));
			}
		}
	}
	// Echo names of services that were selected
	$query = "SELECT servicedescription FROM services WHERE serviceID IN ($longhornservices)
			  ORDER BY servicesort";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "services SELECT query failed.  See administrator.";
	}
	$Lhrows = $result->num_rows;
	$Lharray = array(array());
	$index = 0;
	while ($Lhvalue = $result->fetch_assoc()) {
		$Lharray[$index] = $Lhvalue['servicedescription'];
		$index += 1;
	}

	//echo 'You selected the following services: <br>';
	$longhornservices = "";
	for ($i=0; $i<$Lhrows; $i++) {
		if ($i == $Lhrows-1) {
			$longhornservices .= $Lharray[$i];
		} else {
			$longhornservices .= $Lharray[$i] . ', ';
		}
	}
	//echo $longhornservices. '<br>';
	// Save $longhornservices as magic variable
	$_SESSION['longhornservices']	= $longhornservices;
	//$_SESSION['success'][] = "Longhorn services have been inserted successfully.";
	if ($report_type_id == 1) {
		die(header("Location: ".$_SESSION['lastpagedealerreports']));
	} elseif ($report_type_id == 2) {	
		die(header("Location: ".$_SESSION['lastpageglobalreports']));
	} elseif ($report_type_id == 3) {	
		die(header("Location: ".$_SESSION['lastpagecomparisonreports']));
	}
} else {
	$_SESSION['error'][] = "You did not select any service options.";
	if ($report_type_id == 1) {
		die(header("Location: ".$_SESSION['lastpagedealerreports']));
	} elseif ($report_type_id == 2) {	
		die(header("Location: ".$_SESSION['lastpageglobalreports']));
	} elseif ($report_type_id == 3) {	
		die(header("Location: ".$_SESSION['lastpagecomparisonreports']));
	}
}
// echo '<br><br><br>';
?>