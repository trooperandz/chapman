<?php
require_once("functions.inc");
include ('templates/login_check.php');
?>

<html>
<head>
<title>Repair Order Survey Reports</title>
<style type="text/css">
	* {
		margin: 0;
		padding: 0;
		}
	.body {
		background-color: #B8B8B8;
		}
	.wrapper {
		width: 1200px;
		height: 85%;
		margin: 0 auto;
		background-color: #C8C8C8;
		border: 1px solid #888888;
		margin-top: 3%;
		margin-bottom: 3%;
		border-radius: 20px;
		}
	header h1 {
		color: #D3D3D3;
		background-color: #A52A2A;
		border-bottom: 1.5px solid #484848;
		text-indent: 3%;
		border-radius: 20px 20px 0px 0px;
		}
	p {
		margin-left: 90%;
		margin-right: 1%;
		margin-top: 4px;
		text-align: right;
		}
	.links1 {
		text-align: center;
		margin-top: 3%;
		}
	.links2 {
		text-align: center;
		margin-top: 10%;
		}
	.links3 {
		text-align: center;
		margin-top: 10%;
		}
	.links4 {
		text-align: center;
		margin-top: 15%;
		}
	a {
		color: #000000;
		font-weight: bold;
		}
	a:link {
		text-decoration: none;
		}	
	a:hover {
		color: #A52A2A;
		}
	h2 {
		text-align: center;
		border-bottom: 1px solid #000000;
		}
	.box1 {
		background-color: #FFFFFF;
		float: left;
		margin-top: 3%;
		margin-left: 8%;
		width: 35%;
		height: 30%;
		border-radius:20px;
		}
	.box2 {
		background-color: #FFFFFF;
		float: right;
		margin-top: 3%;
		margin-right: 8%;
		width: 35%;
		height: 30%;
		border-radius:20px;
		}
	.box3 {
		background-color: #FFFFFF;
		float: left;
		margin-top: 5%;
		margin-left: 8%;
		width: 35%;
		height: 30%;
		border-radius:20px;
		}
	.box4 {
		background-color: #FFFFFF;
		float: right;
		margin-top: 5%;
		margin-right: 8%;
		width: 35%;
		height: 30%;
		border-radius:20px;
		}
	.footer {
		clear: both;
		position: bottom;
		bottom: 0;
		background-color: #000000;
		color: #FFCC00;
		text-align: center;
		width: 100%;
		font-size: 15;
		margin-top: 46.6%;
		border-radius: 0px 0px 20px 20px;
		}
</style>	
</head>
<body>
<div class="wrapper">
<header>
	<h1>Repair Order Survey Reports - VW <?php echo $_SESSION['dealercode']?></h1>
</header>	
	<p><?php print "Welcome, {$user->firstName}";?>
	<a href ="enterro.php" target="_blank">Enter ROs</a><br>
	<a href="logout.php">Logout</a>
	</p>
	<div class="box1">
		<h2> General Reports </h2>
		<div class="links1">
		<a href="querystrongarm.php" target="_blank"> View All Repair Orders</a> <br><br>
		<a href="servicetypequeryandchart.php" target="_blank"> Service Type Percentage - Longhorn</a><br><br>
		<a href="yearmodelqueryandchart.php" target="_blank"> Year Model Percentage</a> <br><br>
		<a href="mileagespreadqueryandchart.php" target="_blank"> Mileage Spread Percentage</a>
		</div>
	</div>
	
	<div class="box2">
		<h2> LOF Reports </h2>
		<div class="links2">
		<a href="lofdemandquery.php" target="_blank"> LOF Demand</a> <br><br>
		<a href="lofbaselinequery_column.php" target="_blank"> LOF Baseline</a>
		</div>
	</div>
	
	<div class="box3">
		<h2> Single Issue Reports </h2>
		<div class="links3">
		<a href="singleissuequery.php" target="_blank"> Single Issue Occurrence</a> <br><br>
		<a href="singleissuecategory.php" target="_blank"> Single Issue By Category</a>
		</div>
	</div>
	
	<div class="box4">
		<h2> Service Demand Reports </h2>
		<div class="links4">
		<a href="demand1and2query.php" target="_blank"> Level One and Two Demand</a> <br><br>
		</div>
	</div>
<div class="footer">
	Service Operations Specialists Inc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12211 West Markham, Little Rock, AR 72211&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;866-406-9707
</div> 
</div> <!--end div main wrapper -->
</body>	
</html>
		
			