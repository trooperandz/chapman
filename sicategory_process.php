<?php 
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

$level1box = $_POST['level1box'];
if (isset($level1box) && !empty($level1box)) {
	$level1count = count($level1box);
	echo 'You selected ' .$level1count. ' checkboxes: <br>';
	$level1services = "";
	for ($i=0; $i<$level1count; $i++) {
		if ($i == ($level1count-1)) {
			$level1services .= $level1box[$i];
		} else {
			$level1services .= $level1box[$i] . ',';
		}
	}
	echo $level1services, '<br>';
	// Update si_category table with services string
	$query = "UPDATE si_category SET category_string = '$level1services' WHERE category_id = 1";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "si_category UPDATE query failed.  See administrator.";
		die(header("Location: setadminvalues.php"));
	}
	
// Echo names of services that were selected
$query = "SELECT servicedescription FROM services WHERE serviceID IN ($level1services)
	      ORDER BY servicesort";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "services SELECT query failed.  See administrator.";
}
$l1rows = $result->num_rows;
$level1array = array(array());
$level1 = 0;
while ($level1value = $result->fetch_assoc()) {
	$level1array[$level1] = $level1value['servicedescription'];
	$level1 += 1;
}

echo 'You selected the following services: <br>';
$level1services = "";
for ($i=0; $i<$l1rows; $i++) {
	if ($i == $l1rows-1) {
		$level1services .= $level1array[$i];
	} else {
		$level1services .= $level1array[$i] . ', ';
	}
}
	echo $level1services. '<br>';
// Save $level1services as magic variable
$_SESSION['level1services']	= $level1services;
} else {
		$_SESSION['error'][] = "You did not select any Level 1 options.";
		die(header("Location: setadminvalues.php"));
}
echo '<br><br><br>';
/*---------------------------------------------------------------------------*/
$wearmaintbox = $_POST['wearmaintbox'];
if (isset($wearmaintbox) && !empty($wearmaintbox)) {
	$wearmaintcount = count($wearmaintbox);
	echo 'You selected ' .$wearmaintcount. ' checkboxes: <br>';
	$wearmaintservices = "";
	for ($i=0; $i<$wearmaintcount; $i++) {
		if ($i == ($wearmaintcount-1)) {
			$wearmaintservices .= $wearmaintbox[$i];
		} else {
			$wearmaintservices .= $wearmaintbox[$i] . ',';
		}
	}
	echo $wearmaintservices, '<br>';
	// Update si_category table with services string
	$query = "UPDATE si_category SET category_string = '$wearmaintservices' WHERE category_id = 2";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "si_category UPDATE query failed.  See administrator.";
		die(header("Location: setadminvalues.php"));
	}
	
// Echo names of services that were selected
$query = "SELECT servicedescription FROM services WHERE serviceID IN ($wearmaintservices)
	      ORDER BY servicesort";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "services SELECT query failed.  See administrator.";
}
$wmrows = $result->num_rows;

$wearmaintarray = array(array());
$wearmaint = 0;
while ($wearmaintvalue = $result->fetch_assoc()) {
	$wearmaintarray[$wearmaint] = $wearmaintvalue['servicedescription'];
	$wearmaint += 1;
}

echo 'You selected the following services: <br>';
$wearmaintservices = "";
for ($i=0; $i<$wmrows; $i++) {
	if ($i == $wmrows-1) {
		$wearmaintservices .= $wearmaintarray[$i];
	} else {
		$wearmaintservices .= $wearmaintarray[$i] . ', ';
	}
}
	echo $wearmaintservices. '<br>';
// Save $print as magic variable
$_SESSION['wearmaintservices']	= $wearmaintservices;
} else {
		$_SESSION['error'][] = "You did not select any Wear Maintenance options.";
		die(header("Location: setadminvalues.php"));
}

echo '<br><br><br>';
/*---------------------------------------------------------------------------*/
$repairbox = $_POST['repairbox'];
if (isset($repairbox) && !empty($repairbox)) {
	$count = count($repairbox);
	echo 'You selected ' .$count. ' checkboxes: <br>';
	$repairservices = "";
	for ($i=0; $i<$count; $i++) {
		if ($i == ($count-1)) {
			$repairservices .= $repairbox[$i];
		} else {
			$repairservices .= $repairbox[$i] . ',';
		}
	}
	echo $repairservices, '<br>';
	
	// Update si_category table with services string
	$query = "UPDATE si_category SET category_string = '$repairservices' WHERE category_id = 3";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "si_category UPDATE query failed.  See administrator.";
		die(header("Location: setadminvalues.php"));
	}
	
// Echo names of services that were selected
$query = "SELECT servicedescription FROM services WHERE serviceID IN ($repairservices)
	      ORDER BY servicesort";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "services SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;

$array = array(array());
$repair = 0;
while ($repairvalue = $result->fetch_assoc()) {
	$array[$repair] = $repairvalue['servicedescription'];
	$repair += 1;
}

echo 'You selected the following services: <br>';
$repairservices = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows-1) {
		$repairservices .= $array[$i];
	} else {
		$repairservices .= $array[$i] . ', ';
	}
}
	echo $repairservices. '<br>';
// Save $repairservices as magic variable
$_SESSION['repairservices']	= $repairservices;
} else {
	$_SESSION['error'][] = "You did not select any repair services.";
	die(header("Location: setadminvalues.php"));
}
$_SESSION['success'][] = "Single Issue categories updated successfully";
die(header("Location: setadminvalues.php"));
/*---------------------------------------------------------------------------THIS IS WRONG-------------------------------*

// Read all services to see if a duplicate is selected
$query = "SELECT serviceID FROM services";
$result = $mysqli->query($query);
if (!$result){
	$_SESSION['error'][] = "services SELECT query failed.  See administrator.";
	die(header("Location: setadminvalues.php"));
}
$totalrows = $result->num_rows;
echo '$totalrows: ' .$totalrows. '<br>';

// Read all services selected to see if a duplicate was selected
$query = "SELECT serviceID FROM services WHERE serviceID IN($level1services)";
$result = $mysqli->query($query);
if (!$result){
	$_SESSION['error'][] = "services SELECT query failed.  See administrator.";
	die(header("Location: setadminvalues.php"));
}
$L1rows = $result->num_rows;

$query = "SELECT serviceID FROM services WHERE serviceID IN($wearmaintservices)";
$result = $mysqli->query($query);
if (!$result){
	$_SESSION['error'][] = "services SELECT query failed.  See administrator.";
	die(header("Location: setadminvalues.php"));
}
$wmrows = $result->num_rows;

$query = "SELECT serviceID FROM services WHERE serviceID IN($repairservices)";
$result = $mysqli->query($query);
if (!$result){
	$_SESSION['error'][] = "services SELECT query failed.  See administrator.";
	die(header("Location: setadminvalues.php"));
}
$repairrows = $result->num_rows;
// Add rows to get total so can compare to $totalrows
$selectrows = $L1rows + $wmrows + $repairrows;
echo '$selectrows: ' .$selectrows. '<br>';

// If $selectrows > $totalrows, a duplicate was selected.
if ($selectrows > $totalrows) {
	$_SESSION['error'][] = "You selected a duplicate category value.";
	die(header("Location: setadminvalues.php"));
}
*----------------------------------------------------------------------------------------------------*/





?>

