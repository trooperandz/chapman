<?php
$query = "SELECT mileagespread.carmileage, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros_si*100,2)
FROM mileagespread LEFT JOIN repairorder ON (mileagespread.mileagespreadID = repairorder.mileagespreadID AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id) AND singleissue = 1)
GROUP BY mileagespread.carmileage ORDER BY mileagespread.mileagespreadID";
$resultms_si = $mysqli->query($query);
if (!$resultms_si) { $_SESSION['error'][] = "Mileage Spread query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$msrows = $resultms_si->num_rows;
$columns_total = $resultms_si->field_count;