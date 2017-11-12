<?php 
/*-------------------------------------------------------------------------*
Program: sd_strings

Purpose: Generate service demand strings from services table
		 for use service demand queries

History:
    Date			Description									by
	01/14/2015		Initial design and coding					Matt Holland
	
*--------------------------------------------------------------------------*/

// Obtain Level 1 services
$query = "SELECT serviceID FROM services WHERE servicelevel IN(1)";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Service query failed.  See administrator.";
	die(header("Location: enterrofoundation.php"));
}
$rows = $result->num_rows;

// Create array of Level 1 services and convert into comma delimited string
$array = array();
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$array[$index]['serviceID'] = $lookup['serviceID'];
	$index += 1;
}
$L1_svcs = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows-1) {
		$L1_svcs .= $array[$i]['serviceID'];
	} else {
		$L1_svcs .= $array[$i]['serviceID'].',';
	}
}
// echo '$L1_svcs: ' .$L1_svcs. '<br>';

// Obtain Level 2 services
$query = "SELECT serviceID FROM services WHERE servicelevel IN(2)";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Service query failed.  See administrator.";
	die(header("Location: enterrofoundation.php"));
}
$rows = $result->num_rows;

// Create array of Level 2 services and convert into comma delimited string
$array = array();
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$array[$index]['serviceID'] = $lookup['serviceID'];
	$index += 1;
}

$L2_svcs = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows-1) {
		$L2_svcs .= $array[$i]['serviceID'];
	} else {
		$L2_svcs .= $array[$i]['serviceID'].',';
	}
}
// echo '$L2_svcs: ' .$L2_svcs. '<br>';

// Obtain Level 3 services
$query = "SELECT serviceID FROM services WHERE servicelevel IN(3)";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Service query failed.  See administrator.";
	die(header("Location: enterrofoundation.php"));
}
$rows = $result->num_rows;

// Create array of Level 3 services and convert into comma delimited string
$array = array();
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$array[$index]['serviceID'] = $lookup['serviceID'];
	$index += 1;
}
$L3_svcs = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows-1) {
		$L3_svcs .= $array[$i]['serviceID'];
	} else {
		$L3_svcs .= $array[$i]['serviceID'].',';
	}
}
// echo '$L3_svcs: '. $L3_svcs. '<br>';

// Concatenate all Levels to obtain All service string
$all_svcs = $L1_svcs.','.$L2_svcs.','.$L3_svcs;
// echo 'all_svcs: ' .$all_svcs. '<br>';

// Concatenate L2 and L3 to obtain string
$L2_L3_svcs = $L2_svcs.','.$L3_svcs;
// echo 'L2_L3_svcs: ' .$L2_L3_svcs. '<br>';