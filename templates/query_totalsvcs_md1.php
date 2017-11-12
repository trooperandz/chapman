<?php
// Query for total service rows
$query = "SELECT * FROM servicerendered WHERE dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Total services dealer query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$totalsvcs = $result->num_rows;