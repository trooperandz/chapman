<?php  //generic file for generic functions and other includes

session_start();

require_once("templates/dbstuff.inc");
require_once("validation.inc");
require_once("ClassUser.php");

// Define manufacturer title constant
define('MANUF', 'Acura');

// Define entity type constant
define('ENTITY', 'Dealer');
define('ENTITYLCASE', 'dealer');

// Define photos
define('PIC_ENTERRO', 'img/acura_enterro.jpg');
define('PIC_MENUS', 'img/acura_main.jpg');
define('PIC_AUTH', 'img/unauthorized.jpg');

// Define user information.
// Type 1 == SOS, Team 1 == Acura, Dealer 0 to comply with user table
define('USER_TYPE', 1);
define('USER_TEAM', 1);
define('USER_DEALER', 0);

// Define file directory for filemanager.php
define('FILE_DIR', '/home/sosfirm/public_html/acura/pres/');

?>