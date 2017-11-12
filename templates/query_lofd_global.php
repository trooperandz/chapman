<?php
//Build string of all services that are not LOF.  Need for the 'ROs With Only LOF' category
$query = "SELECT serviceID FROM services WHERE serviceID != 1
          ORDER BY serviceID ASC";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$rows = $result->num_rows;
$LOF_values = array();
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$LOF_values[$index]['serviceID'] = $lookup['serviceID'];
	$index += 1;
}
$LOF_string = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows - 1) {
		$LOF_string .= $LOF_values[$i]['serviceID'];
	} else {
		$LOF_string .= $LOF_values[$i]['serviceID'].',';
	}
}
// echo '$LOF_string: '.$LOF_string.'<br>';
// die();

//Build string of all services.  The second query result will be subtracted from this.
$query = "SELECT serviceID FROM services
          ORDER BY serviceID ASC";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$rows = $result->num_rows;
$LOF_all_values = array();
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$LOF_all_values[$index]['serviceID'] = $lookup['serviceID'];
	$index += 1;
}
$LOF_all_string = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows - 1) {
		$LOF_all_string .= $LOF_all_values[$i]['serviceID'];
	} else {
		$LOF_all_string .= $LOF_all_values[$i]['serviceID'].',';
	}
}
// echo '$LOF_all_string: '.$LOF_all_string.'<br>';
// die();

$query = "SELECT ronumber FROM servicerendered WHERE serviceID = 1 AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$LOFrows2 = $result->num_rows;

// echo '$LOFrows2: '.$LOFrows2.'<br>';

$query = "SELECT DISTINCT dealerID, ronumber FROM servicerendered WHERE serviceID IN ($LOF_all_string) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$LOF_all_rows2 = $result->num_rows;

// echo '$LOF_all_rows2: '.$LOF_all_rows2.'<br>';

$query = "SELECT DISTINCT dealerID, ronumber FROM servicerendered WHERE serviceID IN ($LOF_string) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$LOF_other_rows2 = $result->num_rows;

// echo '$LOF_other_rows2: '.$LOF_other_rows2.'<br>';

$SILOFrows2 = $LOF_all_rows2 - $LOF_other_rows2;
// echo '$SILOF_rows2: '.$SILOF_rows2.'<br>';