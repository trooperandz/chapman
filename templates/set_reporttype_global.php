<?php
// Retrieve report type from report_types table
$query = "SELECT report_type_id FROM report_types WHERE report_description = 'Global'";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "report_types SELECT query failed.  See administrator.";
}
$value = $result->fetch_assoc();
$report_type_id = $value['report_type_id'];
// Save as magic variable for process file
$_SESSION['report_type_id'] = $report_type_id;
//echo '$report_type_id: ' .$report_type_id. '<br>';