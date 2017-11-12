<?php
$query = "SELECT mileagespread.carmileage, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros2_si*100,2)
FROM mileagespread LEFT JOIN repairorder ON (mileagespread.mileagespreadID = repairorder.mileagespreadID AND surveyindex_id IN ($surveyindex_id) AND singleissue = 1)
GROUP BY mileagespread.carmileage ORDER BY mileagespread.mileagespreadID";
$resultms2_si = $mysqli->query($query);
if (!$resultms2_si) { $_SESSION['error'][] = "Mileage Spread query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$msrows = $resultms2_si->num_rows;
$columns_total = $resultms2_si->field_count;