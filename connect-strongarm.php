<?php

DEFINE ('DB_USER', 'nissansurvey');
DEFINE ('DB_PSWD', 'Trooper4#');
DEFINE ('DB_HOST', 'nissansurvey.db.12383117.hostedresource.com');
DEFINE ('DB_NAME', 'nissansurvey');

$dbcon = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$dbcon) {
	die('error connecting to database');
	}
	

?>