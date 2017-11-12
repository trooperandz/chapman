<?php
$query = "SELECT DISTINCT dealerID FROM repairorder WHERE surveyindex_id = $globalsurveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Failed to retrieve total dealer count.  repairorder SELECT query failed.  See administrator.";
}
$totaldealers_persurvey = $result->num_rows;
// echo '$totaldealers_persurvey: ' .$totaldealers_persurvey. '<br>';
?>