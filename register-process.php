<?php 
require_once('functions.inc'); 
include ('templates/login_check.php');
	
	// prevent access if they haven't submitted the form. 
	if (!isset($_POST['submit'])) { 
		die(header("Location: register.php"));
	} 
	
$_SESSION['formAttempt'] = true;
	
	if (isset($_SESSION['error'])) { 
	unset($_SESSION['error']); 
	} 
	
$_SESSION['error'] = array(); 
$required = array("lname","fname","email","password1","password2"); 
	
// Check required fields 
	foreach ($required as $requiredField) {
		if (!isset($_POST[$requiredField]) || $_POST[$requiredField] == "") { 
		$_SESSION['error'][] = $requiredField . " is required."; 
		} 
	} 
	
	if (!preg_match('/^[\w .]+$/',$_POST['fname'])) {
		$_SESSION['error'][] = "First Name must be letters and numbers only.";
	}
	
	if (!preg_match('/^[\w .]+$/',$_POST['lname'])) {
		$_SESSION['error'][] = "Last Name must be letters and numbers only.";
	}
	
	if (isset($_POST['state']) && $_POST['state'] != "") {
		if (!is_valid_state($_POST['state'])) {
			$_SESSION['error'][] = "Please choose a valid state";
		}
	}
	
	if (isset($_POST['zip']) && $_POST['zip'] != "") {
		if (!is_valid_zip($_POST['zip'])) {
			$_SESSION['error'][] = "Zip code error.";
		}
	}
	
	if (isset($_POST['phone']) && $_POST['phone'] != "") {
		if (!preg_match('/^[\d]+$/',$_POST['phone'])) {
			$_SESSION['error'][] = "Phone number should be digits only.";
		} else if (strlen($_POST['phone']) < 10) {
			$_SESSION['error'][] = "Phone number must be at least 10 digits";
		}
		if (!isset($_POST['phonetype']) || $_POST['phonetype'] == "") {
			$_SESSION['error'][] = "Please choose a phone number type";
		} else {
			$validPhoneTypes = array("work","home");
			if (!in_array($_POST['phonetype'],$validPhoneTypes)) {
				$_SESSION['error'][] = "Please choose a valid phone number type.";
			}
		}
	}	

	if (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) { 
			$_SESSION['error'][] = "Invalid e-mail address"; 
		} 
	
	if ($_POST['password1'] != $_POST['password2']) {
		$_SESSION['error'][] = "Passwords don't match";
	}
	
	//final disposition	
	if (count($_SESSION['error']) > 0) { 
		die(header("Location: register.php")); 
	} else { 
		if(registerUser($_POST)) {
			unset($_SESSION['formAttempt']);
			die(header("Location: success.php"));
		} else {
			error_log("Problem registering user: {$_POST['email']}");
				$_SESSION['error'][] = "Problem registering account";
				die(header("Location: register.php"));
		}
	}
function registerUser($userData) {
	$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DB);
	
	if ($mysqli->connect_errno) {
	error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
		return false;
	}
	
	$email = $mysqli->real_escape_string($_POST['email']);
	
	//check for an existing user
	$findUser = "SELECT userID from Customer where email = '{$email}'";
	$findResult = $mysqli->query($findUser);
	$findRow = $findResult->fetch_assoc();
	if (isset($findRow['userID']) && $findRow['userID'] != "") {
		$_SESSION['error'][] = "A user with that e-mail address already exists";
			return false;
	}
	
	$lastName = $mysqli->real_escape_string($_POST['lname']);
	$firstName = $mysqli->real_escape_string($_POST['fname']);
	$cryptedPassword = crypt($_POST['password1']);
	$password = $mysqli->real_escape_string($cryptedPassword);
	
	if (isset($_POST['addr'])) {
		$street = $mysqli->real_escape_string($_POST['addr']);
	} else {
		$street = "";
	}
	
	if(isset($_POST['city'])) {
		$city = $mysqli->real_escape_string($_POST['city']);
	} else {
		$city = "";
	}
	
	if(isset($_POST['state'])) {
		$state = $mysqli->real_escape_string($_POST['state']);
	} else {
		$state = "";
	}
	
	if(isset($_POST['zip'])) {
		$zip = $mysqli->real_escape_string($_POST['zip']);
	} else {
		$zip = "";
	}
	
	if(isset($_POST['phone'])) {
		$phone = $mysqli->real_escape_string($_POST['phone']);
	} else {
		$phone = "";
	}
	
	if(isset($_POST['phonetype'])) {
		$phoneType = $mysqli->real_escape_string($_POST['phonetype']);
	} else {
		$phoneType = "";
	}
	
	$query = "INSERT INTO Customer (email,create_date,password,last_name,first_name,street,city,state,zip,phone,phone_type) " . "
			VALUES ('{$email}',NOW(),'{$password}','{$lastName}','{$firstName}','{$street}','{$city}','{$state}','{$zip}','{$phone}','{$phoneType}')";
			
	if ($mysqli->query($query)) {
		$userID = $mysqli->insert_userID;
		error_log("Inserted {$email} as ID {$userID}");
		return true;
	} else {
		error_log("Problem inserting {$query}");
		return false;
	}
} //end function registerUser

?>	