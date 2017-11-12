<?php
$query = "SELECT auto_age.auto_age_label, COUNT(repairorder.ronumber), FORMAT(COUNT(repairorder.ronumber)/$totalros2_si*100,2) FROM auto_age
LEFT JOIN repairorder ON ( auto_age.model_age = repairorder.model_age AND surveyindex_id IN ($surveyindex_id) AND singleissue = 1)
GROUP BY auto_age.auto_age_label ORDER BY auto_age.auto_ageID";
$resultmy2_si = $mysqli->query($query);
if (!$resultmy2_si) { $_SESSION['error'][] = "Year Model query failed. See administrator";
die (header("Location: enterrofoundation.php"));}
$myrows = $resultmy2_si->num_rows;
$columns_total = $resultmy2_si->field_count;