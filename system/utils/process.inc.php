<?php
/**
 * File: process.inc.php
 * Purpose: This file processes dealer entries,
 * PHP version 5.5.29
 * @author   Matthew Holland
 * 
 * History:
 *   Date			Description						by
 *   06/27/2016		Initial design & coding	    	Matt Holland
 */

// Include necessary files. Note: $_SESSION['last_page'] is included in the file below.
include_once "../../functions.inc.php";

// Create a lookup array for form actions
$actions = array (
			  'add_dealer' => array (
			  	'object'  => 'Dealer',
			  	'method1' => 'processDealerEntry',
			  	//'header'  => 'Location: '.$_SESSION['last_page']
			  	'header'  => '../../managedealers.php'
			  ),
		      
		      'edit_dealer' => array (
			  	'object'  => 'Dealer',
			  	'method1' => 'processDealerEntry',
			  	//'header'  => 'Location: '.$_SESSION['last_page']
			  	'header'  => '../../managedealers.php'
			  )
		   );

// Make sure that the requested action exists in the lookup array
if (isset($actions[$_POST['action']])) {
	$use_array = $actions[$_POST['action']];
	
	if ($_POST['action'] == 'add_dealer') {
		$obj = new $use_array['object']($mysqli, $pdo);
		if(!$obj->$use_array['method1']()) {
			//$_SESSION['error'][] = "There was an error adding dealer ".$_SESSION['process_dealercode']."!";
			die(header('Location: '.$use_array['header']));
		} else {
			$_SESSION['success'][] = "Dealer ".$_SESSION['process_dealercode']." was added successfully!";
			die(header('Location: '.$use_array['header']));
		}
	}
	if ($_POST['action'] == 'edit_dealer') {
		$obj = new $use_array['object']($mysqli, $pdo);
		if(!$obj->$use_array['method1']()) {
			//$_SESSION['error'][] = "There was an error updating dealer ".$_SESSION['process_dealercode']."!";
			die(header('Location: '.$use_array['header']));
		} else {
			$_SESSION['success'][] = "Dealer ".$_SESSION['process_dealercode']." was updated successfully!";
			die(header('Location: '.$use_array['header']));
		}
	}
} else {
	exit("you entered the else block in process.inc.php");
	// Redirect to the main index page if the action is invalid
	header("Location: ../../index.php");
	//exit;
}
/*
function __autoload($class_name) {
	$filename = "../../sys/class/class." .$class_name. ".inc.php";
	if (file_exists($filename)) {
		include_once $filename;
	}
}*/
?>