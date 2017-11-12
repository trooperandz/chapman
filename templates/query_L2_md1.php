<?php
// Query services and level_two_analysis tables to ensure serviceIDs are present in both tables
$query = "SELECT L2_value, serviceID FROM level_two_analysis
WHERE surveyindex_id IN($surveyindex_id) AND serviceID IN
(SELECT serviceID FROM services WHERE servicelevel = 2 AND serviceID NOT IN (21,22,24,25))";
$resultL2 = $mysqli->query($query);
$rows = $resultL2->num_rows;

// Fetch serviceID string
$L2svcs = $resultL2->fetch_assoc();
$serviceIDs2 = $L2svcs['serviceID'];

// Query services and servicerendered tables with above defined serviceID string
$query = "SELECT services.servicedescription, COUNT(servicerendered.servicerenderedID), FORMAT(COUNT(servicerendered.servicerenderedID)/$totalros*100,2)
FROM services LEFT JOIN servicerendered ON (services.serviceID = servicerendered.serviceID AND dealerID IN($dealerIDs1) AND surveyindex_id IN ($surveyindex_id))
WHERE services.serviceID IN($serviceIDs2)
GROUP BY services.servicedescription ORDER BY servicesort";
$resultst2 = $mysqli->query($query);
if (!$resultst2) { $_SESSION['error'][] = "Longhorn query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$strows2 = $resultst2->num_rows;
$columns_total = $resultst2->field_count;