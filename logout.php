<?php
	// add this to every page that needs to be protected.
	
	require_once("functions.inc");
	$user = new User;
	$user->logout();
	die(header("Location: index.php"));
?>