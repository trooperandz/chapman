<?php
// Query for total service rows
$query = "SELECT * FROM servicerendered WHERE surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Total services global query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$totalsvcs2 = $result->num_rows;