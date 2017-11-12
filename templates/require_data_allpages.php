<?php
require_once("functions.inc");
$user = new User;
if (!$user->isLoggedIn) {
	die(header("Location: loginform.php"));
}