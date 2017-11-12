<?php // unsetmultidealer.php
require_once("functions.inc");
include ('templates/login_check.php');
// Database connection
include ('templates/db_cxn.php');

// Unset multidealer and regional magic variables
if (isset($_POST['viewallsubmit'])) {
	unset($_SESSION['multidealer']);
	unset($_SESSION['multidealercodes']);
	unset($_SESSION['regiondealerIDs']);
}

die (header("Location: ".$_SESSION['lastpageglobalreports']))
?>	