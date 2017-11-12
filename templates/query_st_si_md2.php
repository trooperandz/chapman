<?php
// Services query
$query = "SELECT services.servicedescription, COUNT(servicerendered.servicerenderedID), FORMAT(COUNT(servicerendered.servicerenderedID)/$totalros2_si*100,2)
FROM services LEFT JOIN servicerendered ON (services.serviceID = servicerendered.serviceID AND dealerID IN($dealerIDs2) AND surveyindex_id IN ($surveyindex_id) AND singleissue = 1)
WHERE services.serviceID IN($Lhstring) AND services.rosurvey_svc = 1
GROUP BY services.servicedescription ORDER BY servicesort";
$resultst2_si = $mysqli->query($query);
if (!$resultst2_si) { $_SESSION['error'][] = "Longhorn query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$strows = $resultst2_si->num_rows;
$columns_total = $resultst2_si->field_count;