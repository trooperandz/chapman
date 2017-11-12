<?php
// To compute the total # rows for denominator	
$query = "SELECT * FROM repairorder WHERE surveyindex_id IN ($surveyindex_id) AND singleissue = 1";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Total ROs global query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$totalros2_si = $result->num_rows;