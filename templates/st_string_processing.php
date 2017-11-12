<?php
$query = "SELECT longhorn_string FROM longhorn_svcs WHERE userID = $userID AND report_type_id = $report_type_id";
$result = $mysqli->query($query);
$rows = $result->num_rows;

if($rows == 0) {
	// Set service defaults if there are no values in table for $userID
	$query = "SELECT serviceID FROM services ORDER BY servicesort ASC";
	$result2 = $mysqli->query($query);
	if (!$result2) {
		$_SESSION['error'][] = "longhorn_svcs SELECT query failed.  See administrator.";
		die(header("Location: enterrofoundation.php"));
	}
	$rows = $result2->num_rows;
	// Get service defaults from table as a string.  DO NOT hard code.
	$svcarray = array(array());
	$index = 0;
	while ($value = $result2->fetch_assoc()) {
		$svcarray[$index] = $value['serviceID'];
		$index += 1;
	}
	$Lhstring = "";
	for ($i=0; $i<$rows; $i++) {
		if ($i == $rows-1) {
			$Lhstring .= $svcarray[$i];
		} else {
			$Lhstring .= $svcarray[$i]. ", ";
		}
	}
	//echo '$Lhstring1: ' .$Lhstring. '<br>';
} else {
$Lhvalue = $result->fetch_assoc();
$Lhstring = $Lhvalue['longhorn_string'];
//echo '$Lhstring2: ' .$Lhstring. '<br>';
}
//echo 'Longhorn services: ' .$Lhstring. '<br>';