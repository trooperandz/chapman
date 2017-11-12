<?php
/*  Set last page in globals  */
$_SESSION['lastpagefilebin'] = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>