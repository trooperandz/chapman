<?php
$query = "SELECT mileagespread.carmileage, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros2*100,2)
FROM mileagespread LEFT JOIN repairorder ON (mileagespread.mileagespreadID = repairorder.mileagespreadID AND surveyindex_id IN ($surveyindex_id))
GROUP BY mileagespread.carmileage ORDER BY mileagespread.mileagespreadID";
$resultms2 = $mysqli->query($query);
if (!$resultms2) { $_SESSION['error'][] = "Mileage Spread query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$msrows = $resultms2->num_rows;
$columns_total = $resultms2->field_count;