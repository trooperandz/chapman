<?php
// Query services and level_one_analysis tables to ensure serviceIDs are present in both tables
$query = "SELECT L1_value, serviceID FROM level_one_analysis
WHERE surveyindex_id IN($surveyindex_id) AND serviceID IN
(SELECT serviceID FROM services WHERE servicelevel = 1 AND serviceID NOT IN (5))";
$resultL1 = $mysqli->query($query);
$rows = $resultL1->num_rows;

// Fetch serviceID string
$L1svcs = $resultL1->fetch_assoc();
$serviceIDs1 = $L1svcs['serviceID'];

// Query services and servicerendered tables with above defined serviceID string
$query = "SELECT services.servicedescription, COUNT(servicerendered.servicerenderedID), FORMAT(COUNT(servicerendered.servicerenderedID)/$totalros*100,2)
FROM services LEFT JOIN servicerendered ON (services.serviceID = servicerendered.serviceID AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id))
WHERE services.serviceID IN($serviceIDs1)
GROUP BY services.servicedescription ORDER BY servicesort";
$resultst1 = $mysqli->query($query);
if (!$resultst1) { $_SESSION['error'][] = "Longhorn query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$strows1 = $resultst1->num_rows;
$columns_total = $resultst1->field_count;