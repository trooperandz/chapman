<?php
// Services query
$query = "SELECT services.servicedescription, COUNT(servicerendered.servicerenderedID), FORMAT(COUNT(servicerendered.servicerenderedID)/$totalros2*100,2)
FROM services LEFT JOIN servicerendered ON (services.serviceID = servicerendered.serviceID AND dealerID IN($dealerIDs2) AND surveyindex_id IN ($surveyindex_id))
WHERE services.serviceID IN($Lhstring) AND services.rosurvey_svc = 1
GROUP BY services.servicedescription ORDER BY servicesort";
$resultst2 = $mysqli->query($query);
if (!$resultst2) { $_SESSION['error'][] = "Longhorn query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$strows = $resultst2->num_rows;
$columns_total = $resultst2->field_count;