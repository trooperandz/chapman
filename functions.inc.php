<?php
/**
 * Program: functions.inc.php
 * Purpose: Provide main program initializations for necessary files, set contants, set Class file autoload instruction
 * PHP version 5.5.29
 * @author Matthew Holland
 *
 * History:
 * Date			Description													By
 * Feb 2014		Initial design and coding 									Matt Holland
 * 06/24/16		Added spl autoload instruction, renamed functions.inc.php 	Matt Holland
 */

session_start();

require_once("templates/dbstuff.inc.php");
require_once("validation.inc");
//require_once("ClassUser.php");

// Define manufacturer folder root
define('MANUF_ROOT', 'acura');

// Define manufacturer title constant
define('MANUF', 'Acura');

// Define entity type constant
define('ENTITY', 'Dealer');
define('ENTITYLCASE', 'dealer');

// Define admin email for error reporting etc.
define('ADMIN_EMAIL', 'mtholland10@gmail.com');

// Define photos
define('PIC_ENTERRO', 'img/acura_enterro.jpg');
define('PIC_MENUS', 'img/acura_main.jpg');
define('PIC_AUTH', 'img/unauthorized.jpg');

// Define file directory for filemanager.php
define('FILE_DIR', '/home/sosfirm/public_html/acura/pres/');

// Define user information.
// Type 1 == SOS, Team 1 == Acura, Dealer 0 to comply with user table
define('USER_TYPE', 1);
define('USER_TEAM', 1);
define('USER_DEALER', 0);

// Create mysqli db connection object
// Note: These will be passed to class files as protected properties
$mysqli = new mysqli(DBHOST, DBUSER, DBPASS, DB);

// Create pdo connection object and set PDO error modes so that errors are reported and emailed
$dsn = 'mysql:dbname='.DB.';host='.DBHOST;
$pdo = new PDO($dsn, DBUSER, DBPASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Automatically load class files when called (prevents the need for having to write includes for every one of them on every page)
spl_autoload_register(function ($class_name) {
	$filename = $_SERVER['DOCUMENT_ROOT']."/".MANUF_ROOT."/system/class/Class".$class_name;
	//echo 'autoload file path: '.$filename.'<br>';
    include $filename. '.php';
});
?>