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
      <h1><a href=""> Nissan Admin - Manage Dealers</a></h1>
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
			<li><a href="setadminvalues.php">System Values</a></li>
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

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
			<h2 style="margin-top: 20px;"> Register New Dealer </h2>
			<p>	 &nbsp;	</p>
	</div>
</div>
<form data-abide method="post" action="managedealersajax_process.php">
	<div class="row">
		<div class="small-12 medium-4 large-4 columns">
			<p><img src="zwhitespace.jpg" alt=""></p>
		</div>
	<div class="small-12 medium-4 large-4 columns">
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
					<label>Dealer Name
						<input type="text" id="dealername" name="dealername" autofocus>
					</label>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<label>Dealer Code
					<input type="text" id="dealercode" name="dealercode" pattern="number">
				</label>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<label>Dealer State
					<select required id="dealerstate" name="dealerstate">
						<option value="">Select...</option>
						<option value="AK">AK</option>
						<option value="AL">AL</option>
						<option value="AR">AR</option>
						<option value="AZ">AZ</option>
						<option value="CA">CA</option>
						<option value="CO">CO</option>
						<option value="CT">CT</option>
						<option value="DE">DE</option>
						<option value="FL">FL</option>
						<option value="GA">GA</option>
						<option value="HI">HI</option>
						<option value="IA">IA</option>
						<option value="ID">ID</option>
						<option value="IL">IL</option>
						<option value="IN">IN</option>
						<option value="KS">KS</option>
						<option value="KY">KY</option>
						<option value="LA">LA</option>
						<option value="MA">MA</option>
						<option value="MD">MD</option>
						<option value="ME">ME</option>
						<option value="MI">MI</option>
						<option value="MN">MN</option>
						<option value="MO">MO</option>
						<option value="MS">MS</option>
						<option value="MT">MT</option>
						<option value="NC">NC</option>
						<option value="ND">ND</option>
						<option value="NE">NE</option>
						<option value="NH">NH</option>
						<option value="NJ">NJ</option>
						<option value="NM">NM</option>
						<option value="NV">NV</option>
						<option value="NY">NY</option>
						<option value="OH">OH</option>
						<option value="OK">OK</option>
						<option value="OR">OR</option>
						<option value="PA">PA</option>
						<option value="RI">RI</option>
						<option value="SC">SC</option>
						<option value="SD">SD</option>
						<option value="TN">TN</option>
						<option value="TX">TX</option>
						<option value="UT">UT</option>
						<option value="VA">VA</option>
						<option value="VT">VT</option>
						<option value="WA">WA</option>
						<option value="WI">WI</option>
						<option value="WV">WV</option>
						<option value="WY">WY</option>
					</select>
				</label>
				<small class="error">Please enter the dealer's state</small>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<label>Dealer Region
					<select required id="regionID" name="regionID">
						<option value="">Select...	</option>
						<option value="1">Central	</option>
						<option value="2">Midwest	</option>
						<option value="3">Northeast	</option>
						<option value="4">Southeast	</option>
						<option value="5">West		</option>
					</select>
				</label>
				<small class="error">Please enter the dealer's region</small>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
					<?php 
						if (isset($_SESSION['error'])) { 
							foreach ($_SESSION['error'] as $error) { 
								echo '<h5 style="color: red; font-weight: bold;">' .$error. '</h5><br>'; 
							} //end foreach 
							unset($_SESSION['error']);
						} //end if 
					?>  
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
					<p> </p>
					<input type="submit" value="Register Dealer &raquo;" class="tiny button radius">
			</div>
		</div>
	</div>
	<div class="small-12 medium-4 large-4 columns">
	
	</div>
</div>
</form>	
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<h2> Manage Dealers </h2>
	</div>
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

/*  Read all dealer records  */
$query = 	"SELECT dealername, dealercode, dealerstate, region  FROM dealer, dealerregion
			WHERE dealer.regionID = dealerregion.regionID ORDER BY dealername ASC";
			
$result = $mysqli->query($query);

if (!$result) die ("Database access failed: " .$mysqli_error());
	else {
		$rows = $result->num_rows;
	}	
echo	'</div class="row">
			<div class="medium-12 large-12 columns">
				<div class="row">
					<div class="medium-8 large-8 columns">
						<p> &nbsp; </p>
					</div>
					<div class="small-12 medium-4 large-4 columns">
						<h6>Total Dealers: ' .$rows. '</h6>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="medium-12 large-12 columns">
				<div class="row">
					<div class="medium-1 large-1 columns">
						<p> </p>
					</div>	
					<div class="small-12 medium-10 large-10 columns">
						<table id="dealertable" class="tablesorter, responsive" style="margin-left: auto; margin-right: auto; border-collapse: collapse;">
						<thead style="height: 35px; padding: 0px;">
							<tr style="background-color: #e5e5e5; height: 35px; padding: 0px; ">
								<th style="text-align: center; font-size: 10pt; border-left: 1px solid #CCCCCC; width: 125px; height: 35px; padding: 0px;">Action</th>
								<th style="text-align: center; font-size: 10pt; border-left: 1px solid #CCCCCC; width: 125px; height: 35px; padding: 0px;"><a style="color: black;">Dealer Name</a></th>  
								<th style="text-align: center; font-size: 10pt; border-left: 1px solid #CCCCCC; width: 125px; height: 35px; padding: 0px;"><a style="color: black;">Dealer Code</a></th> 
								<th style="text-align: center; font-size: 10pt; border-left: 1px solid #CCCCCC; width: 125px; height: 35px; padding: 0px;"><a style="color: black;">Dealer State</a></th> 
								<th style="text-align: center; font-size: 10pt; border-left: 1px solid #CCCCCC; width: 125px; height: 35px; padding: 0px;"><a style="color: black;">Dealer Region</a></th> 
							</tr>
						</thead>
						<tbody>';
	
for ($j = 0 ; $j < $rows ; ++$j) {
	$row = $result->fetch_row();

/*  Display each dealer in list allowing EDIT   */

	echo					'<tr style="padding: 0px;">
								<td align="center" style="border-bottom: 1px solid #CCCCCC; padding: 0px; height: 60px;">
									<form style="padding: 0px; margin-bottom: 0px;" action="" method="post" >
									<input type="hidden" name="edit" value="yes" />
									<input type="hidden" name="editdealer" value='.$row[1].' />
									<input type="submit" value="Select" class= "tiny button radius" style="margin: 0 0 0rem;" />
									</form>
								</td>
								<td align="center" style="border-bottom: 1px solid #CCCCCC; height: 60px; border-left: 1px solid #CCCCCC; padding: 0px;">' .$row[0]. '</td>
								<td align="center" style="border-bottom: 1px solid #CCCCCC; height: 60px; padding: 0px;">' .$row[1]. '</td>
								<td align="center" style="border-bottom: 1px solid #CCCCCC; height: 60px; padding: 0px;">' .$row[2]. '</td>
								<td align="center" style="border-bottom: 1px solid #CCCCCC; height: 60px; padding: 0px;">' .$row[3]. '</td>
							</tr>';
	}
	echo			   '</tbody>
						</table>
					</div>
					<div class="medium-1 large-1 columns">
						<p> </p>
					</div>
				</div>
			</div>
		</div>';	
	
/*  Close database - End program  */

?>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
</div>

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