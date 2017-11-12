<?php
require_once("functions.inc");
include ('templates/login_check.php');

// Check if a file has been uploaded
if(isset($_FILES['uploaded_file'])) {
    // Make sure the file was sent without errors
    if($_FILES['uploaded_file']['error'] == 0) {
        // Connect to the database
        $mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);
		if ($mysqli->connect_errno) {
		error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
		return false; }

		/* Set dealer ID */	
		$dealerID = $_SESSION['dealerID'];

		/* Set user ID */
		$userID = $user->userID;
		
        // Gather all required data
        $name = $mysqli->real_escape_string($_FILES['uploaded_file']['name']);
        $mime = $mysqli->real_escape_string($_FILES['uploaded_file']['type']);
        $data = $mysqli->real_escape_string(file_get_contents($_FILES  ['uploaded_file']['tmp_name']));
        $size = intval($_FILES['uploaded_file']['size']);
	}
	
	$allowedfile = array('application/pdf', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');
	if(in_array($_FILES['uploaded_file']['type'], $allowedfile)) {
 
		// Create the SQL query
		$query = "INSERT INTO `file` (`name`, `mime`, `size`, `data`, `created`)
				  VALUES ('{$name}', '{$mime}', {$size}, '{$data}', NOW())";
	 
		// Execute the query
		$result = $mysqli->query($query);
	 
		// Check if it was successfull
		if($result) {
			$_SESSION['success'][] = "Success! " .$_FILES['uploaded_file']['name']. " was successfully added.";
		} else {
			$_SESSION['error'][] = "Query error: failed to insert the file.";
		}
	} else {
		$_SESSION['error'][] = "Error: File type must be .pdf or .pptx";
    }
 
// Close the mysql connection
$mysqli->close();

}
else {
	$_SESSION['error'][] = "Error: the form would not accept the file. Files must be 20,000,000 bytes or less.";
}
die(header("Location: " .$_SESSION['lastpagefilebin']));
?>