<?php
require_once("functions.inc");
include ('templates/login_check.php');
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - Admin</title>
    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/responsive-tables.css" media="screen" />
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script>
		$(document).ready(function() { 
			$("#dealertable").tablesorter(); 
			} 
		);
	</script>
  </head>
  <body>
<div class="fixed">
 <nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href=""> Nissan File Manager </a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
	<!-- Right Nav Section -->
	<ul class="right">
        <li class="divider"></li>
        <li class="has-dropdown">
          <a href="#"><?php echo "Welcome, {$user->firstName}"; ?></a>
          <ul class="dropdown">
			<li class="divider"></li>
			<li><a href="manageusers.php">Manage Users</a></li>
			<li class="divider"></li>
			<li><a href="managedealers.php">Manage Dealers</a></li>
			<li class="divider"></li>
			<li><a href="enterrofoundation.php">Return to Survey</a></li>
			<li class="divider"></li>
            <li><a href="logout.php">Logout</a></li>
            <li class="divider"></li>
          </ul>
        </li>
      </ul>
    </section>
</nav> 
</div>

<?php
$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);

if ($mysqli->connect_errno) {
	error_log("Cannot connect to MySQL: " . $mysqli->connect_error);
		return false;
	}

/* Set dealer ID */	
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;

$query = "SELECT id, filename, filetype FROM upload";
$result = $mysqli->query($query) or die('Error, query failed');
if($result->num_rows == 0) {
	echo "Database is empty <br>";
} else {
	while(list($id, $filename, $filetype) = $result->fetch_array()) {
?>

		<a href="download.php?id=<?php echo $id;?>"> <?php echo $filename.$filetype;?> </a> <br>
		
<?php
	}
}

?>

<?php

if(isset($_GET['id'])) { // if id is set then get the file with the id from database
	$query = "SELECT filename, filetype, filesize, filecontent FROM upload WHERE id = '$id' ";
	$result = $mysqli->query($query) or die('Error, query failed');
	list($filename, $filetype, $filesize, $filecontent) = $result->fetch_array();

	header("Content-length: " .$filesize);
	header("Content-type: " .$filetype);
	header("Content-Disposition: attachment; filename= " .$filename);
	echo $filecontent;
}
 ?>
<footer style=" font-size: 15px; text-align: center; background-color: #000000; color: #D8D8D8;">
	&copy;&nbsp; Service Operations Specialists Inc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12211 West Markham&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Little Rock, AR 72211&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;866-406-9707
</footer>		
		
<!--<script src="js/vendor/jquery.js"></script> -->
    <script src="js/foundation.min.js"></script>
	<script src="js/responsive-tables.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>