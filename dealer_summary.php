<?php
/* ---------------------------------------------------------------------------------*
   Program: dealer_summary.php

   Purpose: Display all dealers so user may search by city etc.

	History:
    Date			Description											by
	02/10/2015		Initial design and coding							Matt Holland
	02/13/2015		Revamped query to account for new us_state_list
					table and new state_ID field in dealer table		Matt Holland
 *----------------------------------------------------------------------------------*/
 
require_once("functions.inc");
include('templates/login_check.php');
include('templates/db_cxn.php');

/* Set dealer ID */	
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;

// Get all dealer info
$query = "SELECT dealercode, dealername, dealercity, state_abbrev, dealerzip, dealerphone FROM dealer, us_state_list
		  WHERE dealer.state_ID = us_state_list.state_ID
		  ORDER BY dealercode ASC";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Dealer query failed.  See administrator.";
}
$rows_dlr = $result->num_rows;
// echo '$rows_dlr: '.$rows_dlr.'<br>';
if ($rows_dlr > 0 ) {
	// Create array of results
	$dealers = array();
	$index = 0;
	while ($lookup = $result->fetch_assoc()) {
		$dealers[$index]['dealercode'] 		= $lookup['dealercode']		;
		$dealers[$index]['dealername'] 		= $lookup['dealername']		;
		$dealers[$index]['dealercity'] 		= $lookup['dealercity']		;
		$dealers[$index]['state_abbrev'] 	= $lookup['state_abbrev']	;
		$dealers[$index]['dealerzip'] 		= $lookup['dealerzip']		;
		$dealers[$index]['dealerphone'] 	= $lookup['dealerphone']	;
		$index += 1;
	}
}
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - <?php echo constant('MANUF');?></title>
	<link rel="icon" href="img/sos_logo3.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/foundation.css" />
	<!--<link rel="stylesheet" href="css/tablesort.theme.blue.css" />-->
	<link rel="stylesheet" href="css/responsive-tables.css" media="screen" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
	<link rel="stylesheet" href="css/menubar_dealerchange.css" />
	<!--<link rel="stylesheet" href="css/jquery.dataTables.css" />-->
	<link rel="stylesheet" type="text/css" href="css/dataTables.foundation.css" />
	<style>
		.error {
			color: #FF0000;
			font-size: 15px;
		}
		
		.success {
			color: #228B22; 
			font-size: 15px;
		}
		@media (min-width: 40.063em) {
			.subtitle {
				text-align: right;
			}
		}
		
		.subtitle span {
			color: blue;
		}
		
		table.display {
			font-family: helvetica;
			font-size: 8pt;
			border-collapse: collapse;  
			margin-right: auto;
			margin-left: auto;
		}
		
		table.display thead tr th {
			background-color: whitesmoke;
			font-size: 10pt;
			text-align: center;
			border-left: 1px solid #CCCCCC;  
			width: 150px;
			height: 35px;
		}
		
		table.display tbody td {
			color: #3D3D3D;
			padding: 4px;
			vertical-align: top;
			border-left: 1px solid #CCCCCC;  
			height: 30px;
			text-align: center; 
			border-bottom: 1px solid #CCCCCC; 
		}
		
		@media (min-width: 40.063em) {
			table.display thead tr th  {
				background-image: url(css/bg.gif);
			}
		}

		table.display thead tr th { 
			background-repeat: no-repeat;
			background-position: center right;
			cursor: pointer;
		}
	</style>
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
	<script src="js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="js/dataTables.foundation.js"></script>
	<!--<script type="text/javascript" src="js/tablesorter.js"></script>-->
	<script>
		$(document).ready(function() { 
			$("#dealer_table").DataTable();	
		});
	</script>
	<!--<script type="text/javascript" src="https://www.google.com/jsapi"></script>-->
</head>
<body>
<div class="wrapper">

<div class="fixed">
 <nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href=""> <?php echo constant('MANUF').' - '.constant('ENTITY').' Listing'; ?></a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
	<!-- Right Nav Section -->
	<ul class="right">
        <li class="divider"></li>
        <li class="has-dropdown">
          <a href="#"><?php echo "Welcome, {$user->firstName}"; ?></a>
          <ul class="dropdown">
			<li class="has-dropdown">
			<?php include('templates/menubar_sidecontents.php');?>
          </ul>
        </li>
      </ul>
    </section>
</nav> 
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
			<h2 style="margin-top: 20px;"> <?php echo constant('MANUF').' '.constant('ENTITY').'s'; ?><h6 class="subtitle">Total <?php echo constant('ENTITY').'s: <span>'.number_format($rows_dlr);?> </span></h6> </h2>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p>  </p>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 small-centered columns">
		<table class="responsive display" id="dealer_table">
			<thead>
				<tr>
					<th><a>							Dlr Code	</a></th>
					<th style="width: 230px;"><a>	Name		</a></th>
					<th style="width: 170px;"><a>	City		</a></th>
					<th><a>							State		</a></th>
					<th><a>							Zip Code	</a></th>
					<th><a>							Phone		</a></th>
				</tr>
			</thead>
			<tbody>
			<?php
			if ($rows_dlr > 0) {
				for ($i=0; $i<$rows_dlr; $i++) {
				 echo'<tr>
						<td>'.$dealers[$i]['dealercode'].	'</td>
						<td>'.$dealers[$i]['dealername'].	'</td>
						<td>'.$dealers[$i]['dealercity'].	'</td>
						<td>'.$dealers[$i]['state_abbrev'].	'</td>
						<td>'.$dealers[$i]['dealerzip'].	'</td>
						<td>'.$dealers[$i]['dealerphone'].	'</td>
					 </tr>';
				}
			}
			?>
			</tbody>
		</table>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<p> </p>
			</div>
		</div>
	</div>
</div>
	
<div class="push"></div>  <!--pushes down footer so does not overlap anything-->	
</div> <!--End div 'wrapper'-->
<footer>
	<span class="footer_span"><span class="copyright"><?php echo '&copy;'.date('Y')?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>
<script src="js/foundation.min.js"></script>
<script src="js/responsive-tables.js"></script>
<script>
    $(document).foundation();
</script>
    </script>
  </body>
</html>