<?php
require_once("functions.inc");
include ('templates/login_check.php');
?>

<?php
// Connect to the database
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);
if ($mysqli->connect_errno) {
error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
return false; }

/* Set dealer ID */	
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;
 
// Query for a list of all existing files
$sql = "SELECT id, name, mime, size, created FROM file";
$result = $mysqli->query($sql);
 
// Check if it was successfull
if($result) {
    // Make sure there are some files in there
    if($result->num_rows == 0) {
        echo '<p>There are no files in the database</p>';
    }
    else {
        // Print the top of a table
        echo '<table width="100%">
				<thead>
                <tr>
                    <td><b>Name</b></td>
                    <td><b>Mime</b></td>
                    <td><b>Size (bytes)</b></td>
                    <td><b>Created</b></td>
                    <td><b>&nbsp;</b></td>
                </tr>
				</thead>';
 
        // Print each file
        while($row = $result->fetch_assoc()) {
            echo "
				<tbody>
                <tr>
                    <td>{$row['name']}</td>
                    <td>{$row['mime']}</td>
                    <td>{$row['size']}</td>
                    <td>{$row['created']}</td>
                    <td><a href='get_file.php?id={$row['id']}'>Download</a></td>
                </tr>
				</tbody>";
        }
 
        // Close table
        echo '</table>';
    }
 
    // Free the result
    $result->free();
}
else
{
    echo 'Error! SQL query failed:';
    echo "<pre>{$mysqli->error}</pre>";
}
 
// Close the mysql connection
$mysqli->close();
?>

