<?php
// Services query
$query = "SELECT services.servicedescription, COUNT(servicerendered.servicerenderedID), FORMAT(COUNT(servicerendered.servicerenderedID)/$totalros_si*100,2)
FROM services LEFT JOIN servicerendered ON (services.serviceID = servicerendered.serviceID AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id) AND singleissue = 1)
WHERE services.serviceID IN($Lhstring) AND services.rosurvey_svc = 1
GROUP BY services.servicedescription ORDER BY servicesort";
$resultst_si = $mysqli->query($query);
if (!$resultst_si) { $_SESSION['error'][] = "Longhorn query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$strows = $resultst_si->num_rows;
$columns_total = $resultst_si->field_count;