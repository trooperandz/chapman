<?php
// SVC D

// The following two queries below together compute the total # of ROs that have Level 1 services
$query = "SELECT DISTINCT ronumber FROM servicerendered 
WHERE serviceID IN ($all_svcs) AND addsvc = 0 AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Service Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$total_level1a = $result->num_rows;
//echo 'total_level1a: '.$total_level1a.'<br>';

$query = "SELECT DISTINCT ronumber FROM servicerendered 
WHERE serviceID IN ($L2_L3_svcs) AND addsvc = 0 AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Service Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$total_level1b = $result->num_rows;		
//echo 'total_level1b: '.$total_level1b.'<br><br>';
	
//The following three queries below together compute the total # of ROs that have Level 2 services
$query = "SELECT DISTINCT ronumber FROM servicerendered 
WHERE serviceID IN ($L2_svcs) AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Service Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$total_level2a = $result->num_rows;
//echo 'total_level2a: '.$total_level2a.'<br>';

$query = "SELECT DISTINCT ronumber FROM servicerendered WHERE serviceID IN ($L3_svcs) AND addsvc = 0 AND dealerID IN ($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "test query failed.  See administrator.".$mysqli->error;
die (header("Location: enterrofoundation.php"));}
$rows = $result->num_rows;
// echo '$rows: '.$rows.'<br>';
$L3array = array();
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$L3array[$index]['ronumber'] = $lookup['ronumber'];
	$index += 1;
}
$L3_list = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows - 1) {
		$L3_list .= $L3array[$i]['ronumber'];
	} else {
		$L3_list .= $L3array[$i]['ronumber'].',';
	}
}
// echo '$L3_list: '.$L3_list.'<br>';

// If $L3_list is not null, run the query.  Else set $total_level2b = 0.
if ($L3_list != '') {
	$query = "SELECT DISTINCT ronumber FROM servicerendered WHERE serviceID IN ($L2_svcs) AND ronumber IN
			($L3_list)
			AND dealerID IN($dealerIDs1) and surveyindex_id IN ($surveyindex_id)";
	$result = $mysqli->query($query);
	if (!$result) { $_SESSION['error'][] = "test query failed.  See administrator.";
	die (header("Location: enterrofoundation.php"));}
	$total_level2b = $result->num_rows;
	//echo '$total_level2b: '.$total_level2b.'<br><br>';
} else {
	$total_level2b = 0;
}
/*
while ($lookup = $result->fetch_assoc()) {
	echo $lookup['ronumber'].'<br>';
}
*/

// The following query calculates total Level 3 services for 'Full Service' category
$query = "SELECT DISTINCT ronumber FROM servicerendered WHERE serviceID IN ($L3_svcs) AND addsvc = 0 AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Service Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$total_full_L3 = $result->num_rows;
//echo 'total_full_L3: '.$total_full_L3.'<br><br>';