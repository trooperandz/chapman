<?php
$query = "SELECT auto_age.auto_age_label, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros*100,2) FROM auto_age
LEFT JOIN repairorder ON ( auto_age.model_age = repairorder.model_age AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id))
GROUP BY auto_age.auto_age_label ORDER BY auto_age.auto_ageID";
$resultmy = $mysqli->query($query);
if (!$resultmy) { $_SESSION['error'][] = "Year Model query failed. See administrator";
die (header("Location: enterrofoundation.php"));}
$myrows = $resultmy->num_rows;
$columns_total = $resultmy->field_count;