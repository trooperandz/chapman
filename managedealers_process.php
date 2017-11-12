<?php
/* -----------------------------------------------------------------------------*
   Program: managedealers_process.php

   Purpose: Process new dealers into database

	History:
    Date			Description										by
	06/20/2014		Initial design and coding						Matt Holland
	01/07/2015		Added city input processing						Matt Holland
	02/13/2015		Added several fields for revamped form			Matt Holland
					Added sticky form input							Matt Holland
 *------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

/* Set dealer ID */	
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;

/*  If user hits register then INSTERT new dealer */

// Prevent page access if there is no POST
if	(	(isset($_POST['dealername'])) 	
	&&	(isset($_POST['mngdlrs_dealercode'])) 	
	&&  (isset($_POST['dealeraddress']))
	&&	(isset($_POST['dealercity']))	
	&&	(isset($_POST['state_ID']))	
	&&	(isset($_POST['dealerzip']))	
	&&	(isset($_POST['dealerphone']))	
	&&	(isset($_POST['dealerfax']))	
	&&  (isset($_POST['regionID']))	
	&&	(isset($_POST['district_ID']))
	&&	(isset($_POST['area_ID']))
	){
		/*   Fill in variables with user input values and protect user input */
		$dealername			= $mysqli->real_escape_string($_POST['dealername'])			;
		$mngdlrs_dealercode	= $mysqli->real_escape_string($_POST['mngdlrs_dealercode'])	;
		$dealeraddress		= $mysqli->real_escape_string($_POST['dealeraddress'])		;
		$dealercity			= $mysqli->real_escape_string($_POST['dealercity'])			;
		$state_ID			= $mysqli->real_escape_string($_POST['state_ID'])			;
		$dealerzip			= $mysqli->real_escape_string($_POST['dealerzip'])			;
		$dealerphone		= $mysqli->real_escape_string($_POST['dealerphone'])		;
		$dealerfax			= $mysqli->real_escape_string($_POST['dealerfax'])			;
		$regionID			= $mysqli->real_escape_string($_POST['regionID'])			;
		$district_ID		= $mysqli->real_escape_string($_POST['district_ID'])		;
		$area_ID			= $mysqli->real_escape_string($_POST['area_ID'])			;
		
		// Save variables for sticky form action
		$_SESSION['dealername'] 			= $dealername			;
		$_SESSION['mngdlrs_dealercode'] 	= $mngdlrs_dealercode	;
		$_SESSION['dealeraddress'] 			= $dealeraddress		;
		$_SESSION['dealercity'] 			= $dealercity			;
		$_SESSION['state_ID'] 				= $state_ID				;
		$_SESSION['dealerzip'] 				= $dealerzip			;
		$_SESSION['dealerphone'] 			= $dealerphone			;
		$_SESSION['dealerfax'] 				= $dealerfax			;
		$_SESSION['regionID'] 				= $regionID				;
		$_SESSION['district_ID'] 			= $district_ID			;
		$_SESSION['area_ID'] 				= $area_ID				;
		
		// Unset 'Select' box globals if they are null if they are null so as to prevent query error in managedealers.php
		if ($state_ID == '') {
			unset ($_SESSION['state_ID']);
		}
		if ($regionID == '') {
			unset ($_SESSION['regionID']);
		}
		if ($district_ID == '') {
			unset ($_SESSION['district_ID']);
		}
		if ($area_ID == '') {
			unset ($_SESSION['area_ID']);
		}
		
		// Make sure that all fields are entered before running INSERT query
		if (
		   $_POST['dealername'] 			!=""
		&& $_POST['mngdlrs_dealercode'] 	!=""
		&& $_POST['dealeraddress'] 			!=""
		&& $_POST['dealercity'] 			!=""
		&& $_POST['state_ID']				!=""
		&& $_POST['dealerzip']				!=""
		&& $_POST['dealerphone']			!=""
		&& $_POST['dealerfax']				!=""
		&& $_POST['regionID']   			!=""
		&& $_POST['district_ID']			!=""
		&& $_POST['area_ID']				!=""
		) {	
			/*	First check if duplicate exists for dealer */
			$query = "SELECT dealerID FROM dealer WHERE dealercode = $mngdlrs_dealercode";
			$result = $mysqli->query($query);
			if (!$result) {
				$_SESSION['error'][] = "dealer SELECT query failed.  See administrator.";
				die(header("Location: managedealers.php"));
			}
			$rows = $result->num_rows;
			
			if ($rows > 0) {
				$_SESSION['error'][] = "Dealer code already exists.  Please try again.";
				die(header("Location: managedealers.php"));
			} else {
				// Proceed with INSERT query
				$query = "INSERT INTO dealer (dealername, dealercode, dealeraddress, dealercity, state_ID, dealerzip, dealerphone, dealerfax, regionID, district_ID, area_ID)
						  VALUES ('$dealername', '$mngdlrs_dealercode', '$dealeraddress', '$dealercity', $state_ID, '$dealerzip', '$dealerphone', '$dealerfax', '$regionID', '$district_ID', '$area_ID')";
							 
				/* Check for completion of insert and issue message if failure */
				if (!$mysqli->query($query)) {
					/* ERROR - dealer not inserted */
					$_SESSION['error'][] = "Dealer ". $mngdlrs_dealercode. " was not added";
					$_SESSION['error'][] = $mysqli->error;
					die(header("Location: managedealers.php"));
				} 
				
				$_SESSION['success'][] = "Dealer ". $mngdlrs_dealercode. " has been added";
				
				// Unset sticky form elements upon successful INSERT query
				unset(
				$_SESSION['dealername'],
				$_SESSION['mngdlrs_dealercode'],
				$_SESSION['dealeraddress'],
				$_SESSION['dealercity'],
				$_SESSION['state_ID'],
				$_SESSION['dealerzip'],
				$_SESSION['dealerphone'],
				$_SESSION['dealerfax'],
				$_SESSION['regionID'],
				$_SESSION['district_ID'],
				$_SESSION['area_ID']
				);
				
				// INSERT was successful.  Return back to main input form
				die(header("Location: managedealers.php"));
			}	
		} else {
			// User had NULL inputs.  Return to main form and issue error
			$_SESSION['error'][] = "You left a form field blank.  Please correct and re-submit.";
			die(header("Location: managedealers.php"));
		}
} else {
		// There was no POST;  user should not be able to access form
		$_SESSION['error'][] = 'You are not authorized to access this page';
		die(header('Location: managedealers.php'));
}	
?>