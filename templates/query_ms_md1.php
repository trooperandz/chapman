<?php
$query = "SELECT mileagespread.carmileage, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros*100,2)
FROM mileagespread LEFT JOIN repairorder ON (mileagespread.mileagespreadID = repairorder.mileagespreadID AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id))
GROUP BY mileagespread.carmileage ORDER BY mileagespread.mileagespreadID";
$resultms = $mysqli->query($query);
if (!$resultms) { $_SESSION['error'][] = "Mileage Spread query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$msrows = $resultms->num_rows;
$columns_total = $resultms->field_count;