<?php
// Database connection
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);
if ($mysqli->connect_errno) { die ("Cannot connect to database");}
?>