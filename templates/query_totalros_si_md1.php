<?php
// Compute total dealer ROs
$query = "SELECT * FROM repairorder WHERE dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id) AND singleissue = 1";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Total ROs dealer query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$totalros_si = $result->num_rows;