<?php
// Check to see if there are ROs.  If not, issue message so that user does not think there was a chart or other error
$query = "SELECT ronumber FROM repairorder WHERE dealerID = $dealerID and surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'There was an RO count error.  Please see administrator.';
}
$rows = $result->num_rows;
if ($rows == 0) {
	$_SESSION['error'][] = 'Dealer '.$dealercode.' has no records for a '.$survey_description;
}