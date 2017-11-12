<?php
// To compute the total # rows for denominator	
$query = "SELECT * FROM repairorder WHERE surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "Total ROs global query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$totalros2 = $result->num_rows;