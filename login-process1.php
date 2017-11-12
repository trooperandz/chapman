<?php 
/* ------------------------------------------------------------------------*
   Program: login-process.php

   Purpose: Process & verify userid, password, dealer ID from loginform
   
   History:
    Date		Description									by
	04/23/2014	Initial design and coding					Matt Holland
	05/19/2014	Integrate dealer verification at login.		Matt Holland
	05/19/2014	Get repair order count at login for display.Matt Holland
	07/01/2014	Generate user not active message. 			Matt Holland
	07/23/2014	Clean up error messages	and handling.		Matt Holland
	01/07/2015	Changed die(header) instruction to revert
				to index.php instead of loginform.php		Matt Holland
	08/29/2016	Removed Foundation data-abide, and replaced
				with sticky form $_SESSION vars 			Matt Holland
 *-------------------------------------------------------------------------*/
	require_once('functions.inc'); 
	
	// prevent access if they haven't submitted the form. 
	if (!isset($_POST['submit'])) { 
		die(header("Location: index.php")); 
	} 
	/*  Default switch to show messages  */
	$_SESSION['formAttempt'] = true;
	
	if (isset($_SESSION['error'])) { 
		unset($_SESSION['error']);
	} 
	
	$_SESSION['error'] = array();
	$required = array("email","password", "dealercode"); 

	// Set Sticky form vars
	$_SESSION['login_email'] = $_POST['email'];
	$_SESSION['login_pass'] = $_POST['password'];
	$_SESSION['login_code'] = $_POST['dealercode'];
	
	// Check required fields 
	foreach ($required as $requiredField) {
	if (!isset($_POST[$requiredField]) || $_POST[$requiredField] == "") { 
		$_SESSION['error'][] = "*".$requiredField . " is required."; 
		} 
	}
	
	if (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) { 
		$_SESSION['error'][] = "*Invalid e-mail address!"; 
	} 
		
	if (count($_SESSION['error']) > 0) { 
		die(header("Location: index.php")); 
		} 
	else {
		$user = new User;
		if ($user->authenticate($_POST['email'],$_POST['password'])) {
			/*  Set session global for dealercode, set to UNDEFINED if not entered  */
			if (isset($_POST['dealercode'])) {
				// first, lookup the dealercode to see if it exists. 
				$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DB);
				if ($mysqli->connect_errno) { 
					$_SESSION['error'][] = "Error connecting to database";
					$_SESSION['error'][] = $mysql->connect_error;
					} 
				$safedealercode = $mysqli->real_escape_string($_POST['dealercode']);  
				$query = "SELECT dealerID, dealercode FROM dealer WHERE dealercode = '{$safedealercode}'";
				if (!$result = $mysqli->query($query)) {
					$_SESSION['error'][] = "Error reading dealer table in login_process"; 
					$_SESSION['error'][] = $mysql->error; 
					die(header("Location: index.php")); 
				}
				if ($result->num_rows == 0) { 
					/* Could not find dealer code user specified at signin */
					$_SESSION['error'][] = "*Dealer code does not exist! Please contact the administrator."; 
					die(header("Location: index.php")); 
					} 
				else {
					/* Found dealer code OK */
					$row = $result->fetch_assoc();
					$_SESSION['dealerID'] = $row['dealerID'];
					$_SESSION['dealercode'] = $row['dealercode'];
				
					/* Now get number of repair orders for this dealerID */
					$query =	"SELECT COUNT(ronumber) FROM repairorder 
								WHERE dealerID = {$_SESSION['dealerID']}";
					if (!$result = $mysqli->query($query)) {
						/* Cannot read repairorder file to get count */
						$_SESSION['error'][] = "Error reading repair order for counts in login_process";
						$_SESSION['error'][] = $mysql->error; 
						die(header("Location: index.php")); 
						}
					else {
						/* Get count of repair orders */
						$row = mysqli_fetch_row($result);
						$_SESSION['repairordercount'] = $row[0];
					} 					
					if ($_SESSION['useractive'] == 1) {
						/* SUCCESS Continue to file maintenance module */	
						$_SESSION['formAttempt'] = FALSE;
						// Unset sticky form vars upon successful login
						unset($_SESSION['login_email'], $_SESSION['login_pass'], $_SESSION['login_code']);
						// Redirect to main RO entry page
						die(header("Location: enterrofoundation.php")); 
						}
					else {
						/* ERROR - User is inactive, return to login module */
						$_SESSION['error'][] = "Sorry, you are not authorized.";
						die(header("Location: index.php")); 
					}	
				}
				}
			else {
				$_SESSION['error'][] = "Dealer ".$safedealercode. " not in system"; 
//				$_SESSION['dealercode'] = 'UNDEFINED';
				die(header("Location: index.php")); 
			}
			$_SESSION['error'][] = "No dealer code entered"; 
			die(header("Location: index.php")); 
			} 
		else { 
			$_SESSION['error'][] = "Incorrect username or password"; 
			die(header("Location: index.php")); 
		} 
	} 
?>