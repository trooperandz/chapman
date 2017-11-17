<?php
/* Program:	 db-cred.inc.php
 * Created:  02/26/2016 by Matt Holland
 * Purpose:  Define system-wide settings that may be changed easily
 *			 Define useful constants that may be used by multiple scripts
			 Start the session
 * Methods:  N/A
 * Updates:	 
 *
**/

/**
 * Create an empty array to store constants
 *
 * Note: Initializing $C as an empty array is a
 * safeguard against any tainted pieces of data
 * being stored in $C and defined as constants.
 * This is a good habit, especially when dealing
 * with sensitive data.
 */
$C = array();

// The database host URL
$C['DB_HOST'] = 'localhost';

// The database username
$C['DB_USER'] = 'sosfirm_acurates';

// The database password
$C['DB_PASS'] = 'Trooper4#';

// The database name to work with
$C['DB_NAME'] = 'sosfirm_acuratest';
?>