<?php
$user = new User;
if (!$user->isLoggedIn_acura) {
	die(header("Location: index.php"));
}