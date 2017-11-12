<?php 
require_once("functions.inc")			;
include ('templates/login_check.php')	;
include ('templates/db_cxn.php')		;

if (isset($_SESSION['surveyindex_id'])) {
	$surveyindex_id = $_SESSION['surveyindex_id'];
}

if (isset($_POST['L1value']) && $_POST['L1value'] !=""
	&&
	isset($_POST['L2value']) && $_POST['L2value'] !="") {
		$L1values = $_POST['L1value'];
		$L2values = $_POST['L2value'];
		$L1valuescount = count($L1values);
		$L2valuescount = count($L2values);
		
		// Take POST and turn L1values array into comma delimited string
		$L1vals = "";
		for ($i=0; $i<$L1valuescount; $i++) {
			if ($i == $L1valuescount-1) {
				$L1vals .= $L1values[$i];
			} else {
				$L1vals .= $L1values[$i].',';
			}
		}
		//echo '$L1vals: ' .$L1vals. '<br>';
		// Take POST and turn Lsvalues array into comma delimited string
		$L2vals = "";
		for ($i=0; $i<$L2valuescount; $i++) {
			if ($i == $L2valuescount-1) {
				$L2vals .= $L2values[$i];
			} else {
				$L2vals .= $L2values[$i].',';
			}
		}
		//echo '$L2vals: ' .$L2vals. '<br>';
		
		// If POST is successful, update level_one_analysis and level_two_analysis tables
		$query = "UPDATE level_one_analysis SET L1_value = '$L1vals' WHERE surveyindex_id = $surveyindex_id";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "Query failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		}
		//Now update level_two_analysis table
		$query = "UPDATE level_two_analysis SET L2_value = '$L2vals' WHERE surveyindex_id = $surveyindex_id";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "Query failed.  See administrator.";
			die(header("Location: setadminvalues.php"));
		}
		// When update is successful, return to setadminvalues.php
		$_SESSION['success'][] = "Op Gap values updated successfully.";
		die(header("Location: setadminvalues.php"));
} else {
	$_SESSION['error'][] = "You left a form field blank.";
}
?>