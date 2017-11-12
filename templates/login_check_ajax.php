<?php
$user = new User;
if (!$user->isLoggedIn_acura) {
	exit("error_login");
}