<?php
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

/* Set dealer ID */	
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;
		
// Check if form fields have been entered and file has been uploaded
if (isset($_FILES['uploaded_file'])
 && isset($_POST['title']) && $_POST['title'] !=''
 && isset($_POST['description']) && $_POST['description'] !='') {
 
 // Sanitize input variables and set for query below
 $title 	  = $mysqli->real_escape_string($_POST['title']);
 $description = $mysqli->real_escape_string($_POST['description']);
 
 // Save user input for sticky form
 $_SESSION['file_title'] 	   = $title;
 $_SESSION['file_description'] = $description;
 
    // Make sure the file was sent without errors
    if($_FILES['uploaded_file']['error'] == 0) {
		$file = $_FILES['uploaded_file'];
		$size = $file['size'];
		// Make sure file is not over a certain size
		if ($size>15000000) {
			$_SESSION['error'][] = 'The uploaded file was too large.';
			die(header('Location: filemanager.php'));
		}
		
		// Validate file type using PHP's Fileinfo extension
		$fileinfo  = finfo_open(FILEINFO_MIME_TYPE);
		$file_mime = finfo_file($fileinfo, $file['tmp_name']);

		switch ($file_mime) {
			case 'application/pdf':
				$file_type = 'PDF';
				break;
			case 'application/vnd.ms-powerpoint':
				$_SESSION['error'][] = 'Try again! That file was a .ppt file.  File must be a .pptx file.';
				die(header('Location: filemanager.php'));
				break;
			case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
				$file_type = 'PPTX';
				break;
			default:
				$_SESSION['error'][] = 'Error: The file type was not .pdf, or .pptx';
				die(header('Location: filemanager.php'));
		}
		/*
		if (finfo_file($fileinfo, $file['tmp_name']) !== 'application/pdf' ) {
			$_SESSION['error'][] = 'The uploaded file was not a pdf.';
			die(header('Location: filemanager.php'));
		}
		*/
		finfo_close($fileinfo);
		
		// Create the file's new name and destination if there were no errors.  Append a unique identifier to name.
		$tmp_name = sha1($file['name']) . uniqid('', true);
		$dest = constant('FILE_DIR').$tmp_name.'_tmp';
		
		// Move the file
		if (move_uploaded_file($file['tmp_name'], $dest)) {
			// Store the data in the session for later use:
			$_SESSION['uploaded_file']['tmp_name'] 	= $tmp_name;
			$_SESSION['uploaded_file']['size'] 		= $size;
			$_SESSION['uploaded_file']['file_name'] = $file['name'];
				
			// Print a success message
			$_SESSION['success'][] = $_SESSION['uploaded_file']['file_name'].' has been uploaded!';
			
						
			// Insert file information (not the actual file) into the database
			$fn 		= $_SESSION['uploaded_file']['file_name'];
			$tmp_name	= $_SESSION['uploaded_file']['tmp_name'];
			$size		= (int)$_SESSION['uploaded_file']['size'];
			
			$query = "INSERT INTO files (title, description, tmp_name, file_name, file_type, size, mime, create_date)
					  VALUES ('$title', '$description', '$tmp_name', '$fn', '$file_type', $size, '$file_mime', NOW())";
			$result = $mysqli->query($query);
			if (!$result) {
				$_SESSION['error'][] = "File information was not inserted.";
			}
			
			// Rename the file and have the _tmp removed from the name
			if ($mysqli->affected_rows === 1) {
				$original = constant('FILE_DIR').$tmp_name.'_tmp';
				$dest	  = constant('FILE_DIR').$tmp_name;
				rename ($original, $dest);
			} else {
				// Delete the file so that in the case of a query error there will not be a file on the server without a corresponding database reference
				unlink ($dest);
			}
			
			// Unset sticky form elements upon successful upload
			unset($file, $_SESSION['uploaded_file']);
			unset($_SESSION['file_title']);
			unset($_SESSION['file_description']);
			
			// Return back to main input screen after upload and table insertion were successful
			die(header("Location: " .$_SESSION['lastpagefilebin']));
		} else {
			$_SESSION['error'][] = 'The file could not be moved.';
			// Remove from the temporary directory so as not to clutter
			unlink($file['tmp_name']);
		}
	} else {
		$_SESSION['error'][] = 'There was a file upload error.  Please try again.';
		die(header("Location: " .$_SESSION['lastpagefilebin']));
	}
} else {
	$_SESSION['error'][] = "Form error: The file was too large or an unknown processing error occurred.";
	die(header("Location: " .$_SESSION['lastpagefilebin']));
}
?>