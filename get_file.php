<?php
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

// Make sure an ID was passed
if(isset($_GET['id'])
 &&(strlen($_GET['id']) === 63) // test string length
 &&(substr($_GET['id'], 0, 1) !=='.')) {
 
	// Does the file exist and is it a file?
	$file = constant('FILE_DIR').$_GET['id'];
	$fs = filesize($file);
	$is_file = is_file($file);
	
	if (file_exists($file) && (is_file($file))) {
		// Get the ID
		$id = $_GET['id'];
	 
		// Get the file information
		$query = "SELECT file_id, title, description, file_name, file_type, size, mime FROM files WHERE tmp_name = '$id'";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = 'There was an error retrieving the file.  See administrator.';
			die(header('Location: '.$_SESSION['lastpagefilebin']));
		}
		$row = $result->fetch_assoc();
		$filename  = $row['file_name'];
		$file_type = $row['file_type'];
		$file_mime = $row['mime'];
		$file_size = filesize($file);
		
		if ($file_mime == 'application/pdf') {
			// Print headers for inline pdf viewing
			header('Content-type: '.$file_mime);
			header('Content-Disposition: inline; filename="'.$filename.'"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');
			readfile($file);
			
			// Terminate the script
			exit();
		} else {
			// Print headers for downloading powerpoint files (browsers cannot inline-view these)
			header('Content-type: '.$file_mime);
			header('Content-transfer-encoding: binary'); 
			header('Content-length: '.$file_size); 
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			readfile($file);
			
			// Terminate the script
			exit();
		}
		
	} else {
		$_SESSION['error'][] = "Error! That file does not exist.  Please see the administrator";
		die(header('Location: '.$_SESSION['laspagefilebin']));
	}
} else {
	$_SESSION['error'][] = "Error! There was a file read error. Please see the administrator.";
	die(header('Location: '.$_SESSION['laspagefilebin']));
}
?>