<?php
// Compute total dealer ROs
$query = "SELECT * FROM repairorder WHERE dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Total ROs dealer query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$totalros = $result->num_rows;