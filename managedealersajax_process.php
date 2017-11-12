<?php
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

// Initiate magic variables
$dealerID = $_SESSION['dealerID'];
$dealercode = $_SESSION['dealercode'];

/*  Edit Requested, read user record  */
	if (   (isset($_POST['dealername'])	&& $_POST['dealername'] !=""	)
		 &&(isset($_POST['dealercode']) && $_POST['dealercode'] !=""	)
		 &&(isset($_POST['dealerstate'])&& $_POST['dealerstate']!=""	)
		 &&(isset($_POST['regionID'])   && $_POST['regionID']	!=""	) ) {
	
	$dealername  = $mysqli->real_escape_string($_POST['dealername']		);
	$dealercode  = $mysqli->real_escape_string($_POST['dealercode']		);
	$dealerstate = $mysqli->real_escape_string($_POST['dealerstate']	);
	$regionID	 = $mysqli->real_escape_string($_POST['regionID']		);
	
	$query = "UPDATE dealer SET		dealername 		= '$dealername'	,
									dealercode 		= '$dealercode'	,
									dealerstate 	= '$dealerstate',
									regionID	 	= '$regionID'	
									WHERE dealerID 	=  $dealerID   ";
	/* Check for completion of Update and issue message if failure */
	if (!$mysqli->query($query)) {
		/* ERROR - user not inserted */
		$_SESSION['error'][] = "Dealer ". $dealercode. " was not updated";
		$_SESSION['error'][] = $mysqli->error;
		} else {
			$_SESSION['error'][] = "Dealer ". $dealercode. " has been updated";
			die (header("Location: managedealersajax.php"));
	}	
} else {
	$_SESSION['error'][] = "You left a form field blank";
	die(header("Location: managedealersajax.php"));
}


?>