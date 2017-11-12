<?php
$query = "SELECT auto_age.auto_age_label, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros2*100,2) FROM auto_age
LEFT JOIN repairorder ON ( auto_age.model_age = repairorder.model_age AND dealerID IN($dealerIDs2) AND surveyindex_id IN ($surveyindex_id))
GROUP BY auto_age.auto_age_label ORDER BY auto_age.auto_ageID";
$resultmy2 = $mysqli->query($query);
if (!$resultmy2) { $_SESSION['error'][] = "Year Model query failed. See administrator";
die (header("Location: enterrofoundation.php"));}
$myrows = $resultmy2->num_rows;
$columns_total = $resultmy2->field_count;