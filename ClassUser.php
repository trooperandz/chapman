<?php

/* --------------------------------------------------------------------------*
	Program:	classuser.php

    Purpose:
				Set up a user class for login
	History:
	Date		Description										by
	09/12/2014	Remove addr,city,zip,state,phone,phonetype.		Matt Holland
 *---------------------------------------------------------------------------*/
class User {

	public $userID;
	public $email;
	public $firstName;
	public $lastName;
	public $admin_user; // boolean
	public $isLoggedIn_acura = false;
	public $errorType = "fatal";

	function __construct() {
		if (session_id() == "") {
			session_start();
		}
		if (isset($_SESSION['isLoggedIn_acura']) && $_SESSION['isLoggedIn_acura'] == true) {
		$this->_initUser();
		}
	} //end __construct

	public function authenticate($user,$pass) {
		if (session_id() == "") {
			session_start();
		}
		$_SESSION['isLoggedIn_acura'] = false;
		$this->isLoggedIn_acura = false;
		$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DB);
			if ($mysqli->connect_errno) {
				error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
					return false;
			}

			$safeUser = $mysqli->real_escape_string($user);
			$incomingPassword = $mysqli->real_escape_string($pass);
            $query = "SELECT * from user WHERE user_email = '$safeUser' AND user_type_id = 1";
			//$query = "SELECT * from Customer WHERE email = '{$safeUser}'";
            //$query = "SELECT * from Customer WHERE email = '{$safeUser}' AND active = 1";
			$findResult = $mysqli->query($query);
			$findRow = $findResult->fetch_assoc();
			if (isset($findRow['user_active'])) {
				$_SESSION['useractive'] = $findRow['user_active'];
			}
			if (!$result = $mysqli->query($query)) {
				error_log("Cannot retrieve account for {$user}");
				$_SESSION['error'][] = "You are not authorized to proceed";
				return false;
			} // Will be only one row, so no while() loop needed

			$row = $result->fetch_assoc();
			$dbPassword = $row['user_pass'];
			if (crypt($incomingPassword,$dbPassword) != $dbPassword) {
				error_log("Passwords for {$user} don't match");
				return false;
			}

			$this->admin_user = $row['user_admin'];
			$this->userID = $row['user_id'];
			$this->email = $row['user_email'];
			$this->firstName = $row['user_fname'];
			$this->lastName = $row['user_lname'];
			$this->isLoggedIn_acura = true;

			$this->_setSession();

			return true;
	} // end function authenticate

	private function _setSession() {
		if (session_id() == '') {
			session_start();
		}

		$_SESSION['admin_user'] = $this->admin_user;
		$_SESSION['userID'] = $this->userID;
		$_SESSION['email'] = $this->email;
		$_SESSION['firstName'] = $this->firstName;
		$_SESSION['lastName'] = $this->lastName;
		$_SESSION['isLoggedIn_acura'] = $this->isLoggedIn_acura;
	} // end function setSession

	private function _initUser() {
		if (session_id() == '') {
			session_start();
		}
		$this->admin_user = $_SESSION['admin_user'];
		$this->userID = $_SESSION['userID'];
		$this->email = $_SESSION['email'];
		$this->firstName = $_SESSION['firstName'];
		$this->lastName = $_SESSION['lastName'];
		$this->isLoggedIn_acura = $_SESSION['isLoggedIn_acura'];
	} // end function initUser

	public function emailPass($user) {
		$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DB);
		if ($mysqli->connect_errno) {
			error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
			return false;
		}
		// first, lookup the user to see if they exist.
		$safeUser = $mysqli->real_escape_string($user);
        $query = "SELECT * FROM user WHERE user_email = '$safeUser'";
		//$query = "SELECT userID, email FROM Customer WHERE email = '{$safeUser}' ";
		if (!$result = $mysqli->query($query)) {
			$_SESSION['error'][] = "Incorrect username or password";
			return false;
		}

		if ($result->num_rows == 0) {
			return false;
		}

		$row = $result->fetch_assoc();
		$user_id = $row['user_id'];
        $user_name = $row['user_name'];
		$hash = uniqid("",TRUE);
		$safeHash = $mysqli->real_escape_string($hash);
		$insertQuery = "INSERT INTO reset_pass (email_id, user_name, pass_key, reset_active, create_date) VALUES ($user_id, '$user_name', '$safeHash', 1, NOW())";
		if (!$mysqli->query($insertQuery)) {
			error_log("Problem inserting resetPassword row for " . $user_id);
			$_SESSION['error'][] = "Unknown problem";
			return false;
		}

		$urlHash = urlencode($hash);
		$site = "repairordersurvey.com/".strtolower(MANUF);
		$resetPage = "/reset.php";
		$fullURL = $site . $resetPage . "?user=" . $urlHash;

		//set up things related to the e-mail
		$to = $row['user_email'];
		$subject = "Password Reset for RO Survey";
		$message = "You have requested a password reset for the RO Survey system.\r\n\r\n";
		$message .= "Please go to this link to reset your password:\r\n";
		$message .= $fullURL;
		$headers = "From: repairordersurvey.com\r\n";
		mail($to, $subject, $message, $headers);
		return true;

	} //end function emailPass

	public function validateReset($formInfo) {
		$pass1 = $formInfo['password1'];
 		$pass2 = $formInfo['password2'];
		if ($pass1 != $pass2) {
			$this->errorType = "nonfatal";
			$_SESSION['error'][] = "Passwords don't match";
			return false;
		}

		$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DB);
		if ($mysqli->connect_errno) {
			error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
			return false;
		}

		$decodedHash = urldecode($formInfo['hash']);
		$safeEmail = $mysqli->real_escape_string($formInfo['email']);
		$safeHash = $mysqli->real_escape_string($decodedHash);
        $query = "SELECT u.user_id, u.user_email from user u
                  LEFT JOIN reset_pass r
                  ON (u.user_id = r.email_id)
                  WHERE r.reset_active = 1
                  AND r.pass_key = '$safeHash'
                  AND u.user_email = '$safeEmail'
                  AND u.user_id = r.email_id";
		/*$query = "SELECT c.userID as userID, c.email as email FROM Customer c, resetPassword r
				  WHERE " . "r.status = 'A' AND r.pass_key = '{$safeHash}' " . " AND c.email = '{$safeEmail}' " . " AND c.userID = r.email_id";*/
		if (!$result = $mysqli->query($query)) {
			$_SESSION['error'][] = "Unknown Error";
			$this->errorType = "fatal";
			error_log("database error: " . $formInfo['email'] . " - " . $formInfo['hash']);
			return false;
		} else if ($result->num_rows == 0) {
			$_SESSION['error'][] = "Username does not exist";
			$this->errorType = "fatal";
			error_log("Link not active: " . $formInfo['email'] . " - " . $formInfo['hash']);
			return false;
		} else {
			$row = $result->fetch_assoc();
			$user_id = $row['user_id'];
			if ($this->_resetPass($user_id,$pass1)) {
				return true;
			} else {
				$this->errorType = "nonfatal";
				$_SESSION['error'][] = "Error resetting password";
				error_log("Error resetting password: " . $user_id);
				return false;
			}
		}
	}// end function validateReset

	private function _resetPass($user_id,$pass) {
		$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DB);
		if ($mysqli->connect_errno) {
			error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
			return false;
		}

		$safeUser = $mysqli->real_escape_string($user_id);
		$newPass = crypt($pass);
		$safePass = $mysqli->real_escape_string($newPass);
        $query = "UPDATE user SET user_pass = '$safePass' WHERE user_id = '$safeUser'";
		/*$query = "UPDATE Customer SET password = '{$safePass}' " .
				 "WHERE userID = '{$safeUser}'";*/
		if (!$mysqli->query($query)) {
			return false;
		} else {
			return true;
		}
	} //end function _resetPass

	public function logout() {
		$this->isLoggedIn_acura = false;
		if (session_id() == '') {
			session_start();
		}

		$_SESSION['isLoggedIn_acura'] = false;
		foreach ($_SESSION as $key => $value) {
			$_SESSION[$key] = "";
			unset($_SESSION[$key]);
		}

		$_SESSION = array ();
		if (ini_get("session.use_cookies")) {
			$cookieParameters = session_get_cookie_params();
			setcookie(session_name(), '', time() - 28800,
			$cookieParameters['path'],
			$cookieParameters['domain'],
			$cookieParameters['secure'],
			$cookieParameters['httponly']
			);
		} //end if

		session_destroy();
	} //end function logout
} // end class User