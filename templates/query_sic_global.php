<?php
// Level 1 query	
$query = "SELECT ronumber FROM servicerendered WHERE serviceID IN ($L1string) AND singleissue = 1 AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "SI Category query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$total_level12 = $result->num_rows;
				
// Wear mtn query
$query = "SELECT ronumber FROM servicerendered WHERE serviceID IN ($wmstring) AND singleissue = 1 AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "SI Category query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$total_wm2 = $result->num_rows;

// Repair query
$query = "SELECT ronumber FROM servicerendered WHERE serviceID IN ($repairstring) AND singleissue = 1 AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "SI Category query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$total_repair2 = $result->num_rows;