<?php 
/* ------------------------------------------------------------------------*
	Program: dealercodeswitch_process.php
	
	Purpose: Change current dealer code via enterrofoundation request
 
	Outputs: $_SESSION['dealerID'], $_SESSION['dealercode'],
			 $_SESSION['repairordercount']
			 
	History:
    Date		Description									by
	08/05/2014	Initial design and coding					M.T.Holland
	08/13/2014	Add check for zero records, issue message	M.T.Holland

 *-------------------------------------------------------------------------*/
	require_once('functions.inc'); 
	include ('templates/login_check.php');
	/*  Prevent access if they haven't submitted the form */
	if (!isset($_POST['dealercodesubmit'])) { 
		die (header("Location: ".$_SESSION['lastpagedealerreports'])); 
	} 
	
	/* Reset global errors */
	if (isset($_SESSION['error'])) { 
		unset($_SESSION['error']);
	} 
	
	/*  Validate dealer to switch to was entered  */
	$_SESSION['error'] = array();
	$required = array("dealercodechange"); 
	
	/*  Check required fields  */
	foreach ($required as $requiredField) {
	if (!isset($_POST[$requiredField]) || $_POST[$requiredField] == "") { 
		$_SESSION['error'][] = "No dealer value was entered"; 
		} 
	}
	
	/*  Return to enterrofoundation if dealer not entered  */
	if (count($_SESSION['error']) > 0) { 
		die (header("Location: ".$_SESSION['lastpagedealerreports'])); 
		} 
	else {
		/*  Set session global for dealercode after   */
		/*  dealercode lookup     to see if it exists */

		$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DB);
		if ($mysqli->connect_errno) { 
			$_SESSION['error'][] = "Error connecting to database";
			$_SESSION['error'][] = $mysql->connect_error;
			die (header("Location: ".$_SESSION['lastpagedealerreports'])); 
		} 
		
		$safedealercode = $mysqli->real_escape_string($_POST['dealercodechange']);  
		$dealerquery = "SELECT dealerID, dealercode FROM dealer WHERE dealercode = '{$safedealercode}'";
		if (!$dealerresult = $mysqli->query($dealerquery)) {
			$_SESSION['error'][] = "Error reading dealer table in login_process"; 
			$_SESSION['error'][] = $mysqli->error; 
			die (header("Location: ".$_SESSION['lastpagedealerreports'])); 
		}
		if ($dealerresult->num_rows == 0) { 
			/* Could not find dealer code user specified at signin */
			$_SESSION['error'][] = "Dealer " .$safedealercode. " does not exist"; 
			die (header("Location: ".$_SESSION['lastpagedealerreports'])); 
			} 
		else {
			/*  Found dealer code OK  */
			$dealerrow = $dealerresult->fetch_assoc();
			$_SESSION['dealerID'] = $dealerrow['dealerID'];
			$_SESSION['dealercode'] = $dealerrow['dealercode'];
	
			/*  Now get number of repair orders for this dealerID  */
			$repairquery =	"SELECT COUNT(ronumber) FROM repairorder 
						WHERE dealerID = {$_SESSION['dealerID']}";
			if (!$repairresult = $mysqli->query($repairquery)) {
				/* Cannot read repairorder file to get count */
				$_SESSION['error'][] = "Error reading repair orders for counts in dealercodeswitch_process";
				$_SESSION['repairordercount'] = 0;
				die (header("Location: ".$_SESSION['lastpagedealerreports'])); 
				}
			else {
				/*  Get count of repair orders  */
				$repairrow = $repairresult->fetch_row();
				$_SESSION['repairordercount'] = $repairrow[0];
				if ($repairrow[0] == 0) {
					$_SESSION['error'][] = "Dealer ".$_SESSION['dealercode']." has no records";
				}
				die (header("Location: ".$_SESSION['lastpagedealerreports'])); 
			} 					
		}
	}
 ?>