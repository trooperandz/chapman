<?php
$query = "SELECT AVG(labor), AVG(parts), AVG(labor)+ AVG(parts) FROM repairorder WHERE dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id)
		  AND labor IS NOT NULL AND parts IS NOT NULL";
$result= $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Baseline query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
// Define results
while($r = $result->fetch_row()) {
	$averagelabor 		   = round($r[0],2);
	$averageparts 		   = round($r[1],2);
	$averagepartspluslabor = round(($averagelabor + $averageparts),2);
}

// If values are NULL, must set to zero so that chart does not display a 'string' error
if ($averagelabor == NULL) {
	$averagelabor = 0;
}

if ($averageparts == NULL) {
	$averageparts = 0;
}

if ($averagepartspluslabor == NULL) {
	$averagepartspluslabor = 0;
}