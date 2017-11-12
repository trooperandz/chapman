<?php
// Single issue count
$query = "SELECT * FROM repairorder WHERE singleissue = 1 AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "SI query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$totalsingle = $result->num_rows;

// Multiple issue count
$query = "SELECT * FROM repairorder WHERE singleissue = 0 AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "SI query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}	
$totalmultiple = $result->num_rows;