<?php
//  Query for second dealer model year set
$query = "SELECT COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros*100,1)
FROM yearmodel LEFT JOIN repairorder ON (yearmodel.yearmodelID = repairorder.yearmodelID AND repairorder.dealerID = $dealerID and repairorder.surveyindex_id = $surveyindex_id)
WHERE yearmodel.yearmodelID IN ($yearmodel_string2)";
$resultmy_set2 = $mysqli->query($query);	
if (!$resultmy_set2) die ("Database access failed: " .$mysqli->error);	
$myrows_set2 = $resultmy_set2->num_rows;
$columns_total_set2 = $resultmy_set2->field_count;	

$bucket = $resultmy_set2->fetch_row();
// echo '$bucket item 0: '.$bucket[0].'<br>';
// echo '$bucket item 1: '.$bucket[1].'<br>';