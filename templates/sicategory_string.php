<?php
// Query si_category table to get Level 1 category values
$query = "SELECT category_string FROM si_category WHERE category_id = 1";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;
if($rows == 0) {
	$_SESSION['error'][] = "There are no Level 1 service defaults.";
}
$L1value = $result->fetch_assoc();
$L1string = $L1value['category_string'];
//echo 'L1 services: ' .$L1string. '<br>';

// Query si_category table to get Wear Maint category values
$query = "SELECT category_string FROM si_category WHERE category_id = 2";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;
if($rows == 0) {
	$_SESSION['error'][] = "There are no Wear Maintenance service defaults.";
}
$wmvalue = $result->fetch_assoc();
$wmstring = $wmvalue['category_string'];
//echo 'WM services: ' .$wmstring. '<br>';

// Query si_category table to get Repair category values
$query = "SELECT category_string FROM si_category WHERE category_id = 3";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;
if($rows == 0) {
	$_SESSION['error'][] = "There are no Repair service defaults.";
}
$repairvalue = $result->fetch_assoc();
$repairstring = $repairvalue['category_string'];
//echo 'Repair services: ' .$repairstring. '<br>';
?>