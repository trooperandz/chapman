<?php
require_once("functions.inc");
include ('templates/login_check.php');
?>
<html>
<head>
<title>All Repair Orders - VW <?php echo $_SESSION['dealercode'] ?></title>
<style type="text/css">
	.query {
		float: left;
		}
	.sidebar {
		float: right;
		margin-right: .5%;
		font-weight: bold;
		color: #4169E1;
		}
	.sidebar a:visited {
		color: #4169E1;
		}
	.sidebar a:hover {
		color: #B22222;
		}	
	.sidebar a:link {
		text-decoration: none;
		}
	.query p {
		font-size: 15;
		margin-left: 1.6%;
		}
	.header {
		color: #D3D3D3;
		background-color: #A52A2A;
		}
	.logout {
		font-size: medium;
		float: right;
		padding: 10px 7px 1px 0px;
		}
	.logout a:link {
		color: #FFFFFF;
		text-decoration: none;
		}
	.logout a:visited {
		color: #FFFFFF;
		}
	.footer {
		clear: both;
		color: #FFCC00;
		background-color: #000000;
		text-align: center;
		position: bottom;
		}	
</style>
</head>
<body>
<h1 class="header">All Repair Orders - VW <?php echo $_SESSION['dealercode'] ?></h1>
<hr>
<div class="sidebar">
	<a href="enterro.php">RO Entry Form</a><br>
	<a href="logout.php">Logout</a>
</div>	
<div class="query">	
<p>
<?php //querystrongarm.php
require_once 'connect-strongarm.php';
$db_server = mysql_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db(DB_NAME)
	or die ("Unable to select database: " . mysql_error());

$dealerID = $_SESSION['dealerID'];
	
$query = 	"SELECT ronumber, modelyear, carmileage, singleissue, labor, parts FROM repairorder, yearmodel, mileagespread
			WHERE repairorder.mileagespreadID = mileagespread.mileagespreadID AND repairorder.yearmodelID = yearmodel.yearmodelID
			AND dealerID = $dealerID
			ORDER BY ronumber";
		
$result = mysql_query($query);
			
if (!$result) die ("Database QUERY of Repair Order failed: " .mysql_error());
			
$rows = mysql_num_rows($result);
echo 'Total Repair Orders: ' . $rows  . '<br><br>';

for ($j = 0 ; $j < $rows ; ++$j)
{
	
	$row = mysql_fetch_row($result);
	if ($row[3] == 0) {
		$singleissue = 'No';
		}
	else { 
		$singleissue = 'Yes';
	}
	echo 'RO Number: ' .    $row[0] .      '<br />';
	echo 'Model Year: ' .   $row[1] .      '<br />';
	echo 'Mileage: ' .      $row[2] .      '<br />';
	echo 'Single Issue? '  .$singleissue . '<br />';
	echo 'Labor: ' .        $row[4] .      '<br />';
	echo 'Parts: ' .        $row[5] .      '<br />';

	$query2 = 	"SELECT servicedescription, addsvc FROM servicerendered
				NATURAL JOIN services
				WHERE $row[0] = servicerendered.ronumber AND dealerID = $dealerID";
	$result2 = mysql_query($query2);
	
	if (!$result2) die ("Database QUERY of Service Rendered failed" .mysql_error());
	
	$rows2 = mysql_num_rows($result2);

	for ($i = 0; $i < $rows2; ++$i) 
	{
		$row2 = mysql_fetch_row($result2);
		if ($row2[1] == 0) { 
			$addsvc = '';
			}
		else {
			$addsvc = "  - Additional Service";
		}
		echo 'Service:   ' .    $row2[0] .  $addsvc  . '<br />';
	}
	echo '<br />';
	

}	
?>
</p>
</div> <!-- end div query -->
	<div class="footer">
		<p> Service Operations Specialists Inc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12211 West Markham, Little Rock, AR 72211&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;866-406-9707</p>
	</div>	
</body>
</html>
