<?php
//  Query for dealer model year	
$query = "SELECT yearmodel.modelyear, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros_si*100,1)
FROM yearmodel LEFT JOIN repairorder ON (yearmodel.yearmodelID = repairorder.yearmodelID AND repairorder.dealerID = $dealerID and repairorder.surveyindex_id = $surveyindex_id AND repairorder.singleissue = 1)
WHERE yearmodel.yearmodelID IN ($yearmodel_string) 
GROUP BY yearmodel.yearmodelID ORDER BY yearmodel.yearmodelID DESC";
$resultmy_si = $mysqli->query($query);	
if (!$resultmy_si) die ("Database access failed: " .$mysqli->error);	
$myrows = $resultmy_si->num_rows;
$columns_total = $resultmy_si->field_count;