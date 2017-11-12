<?php
$query = "SELECT AVG(labor), AVG(parts), AVG(labor)+ AVG(parts) FROM repairorder WHERE surveyindex_id IN ($surveyindex_id)
		  AND labor IS NOT NULL and parts IS NOT NULL";
$result= $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Baseline query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
// Define results
while($r = $result->fetch_row()) {
	$averagelabor2 		    = round($r[0],2);
	$averageparts2 		    = round($r[1],2);
	$averagepartspluslabor2 = round(($averagelabor2 + $averageparts2),2);
}

// If values are NULL, must set to zero so that chart does not display a 'string' error
if ($averagelabor2 == NULL) {
	$averagelabor2 = 0;
}

if ($averageparts2 == NULL) {
	$averageparts2 = 0;
}

if ($averagepartspluslabor2 == NULL) {
	$averagepartspluslabor2 = 0;
}