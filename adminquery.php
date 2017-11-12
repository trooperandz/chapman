<?php // enterrofoundation.php
require_once("functions.inc");
include ('templates/login_check.php');
echo $_SESSION['email'];
}
?>

<?php
/* ----------------------------------------------------------------------*
   Program: 

 *-----------------------------------------------------------------------*/
	

require_once 'connect-strongarm.php';

$db_server = mysql_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db(DB_NAME)
	or die("Unable to select database: " . mysql_error());

$email = $_SESSION['email'];	

/*  Find if session email is authorized  */

$query = "SELECT COUNT(*)
		FROM Customer
		WHERE email = $email";
		
$result = mysql_query($query);
if (!$result) die ("Database access failed: " .mysql_error());	
	while($r = mysql_fetch_row($result)) {
	$rowstotal = $r[0];
	}
echo $rowstotal;
	
?>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
</div>

<footer style=" font-size: 15px; text-align: center; background-color: #000000; color: #D8D8D8;">
	&copy;&nbsp; Service Operations Specialists Inc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12211 West Markham&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Little Rock, AR 72211&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;866-406-9707
</footer>
    
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
