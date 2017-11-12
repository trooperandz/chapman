<?php
//  Query for first dealer model year set
$query = "SELECT yearmodel.modelyear, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros*100,1)
FROM yearmodel LEFT JOIN repairorder ON (yearmodel.yearmodelID = repairorder.yearmodelID AND repairorder.dealerID = $dealerID and repairorder.surveyindex_id = $surveyindex_id)
WHERE yearmodel.yearmodelID IN ($yearmodel_string) 
GROUP BY yearmodel.yearmodelID ORDER BY yearmodel.yearmodelID DESC";
$resultmy = $mysqli->query($query);	
if (!$resultmy) die ("Database access failed: " .$mysqli->error);	
$myrows = $resultmy->num_rows;
$columns_total = $resultmy->field_count;