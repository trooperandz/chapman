<?php
/* -----------------------------------------------------------------------------*
   Program: managedealersupdate_process.php

   Purpose: Update dealers - editing dealers

	History:
    Date			Description										by
	06/20/2014		Initial design and coding						Matt Holland
	12/11/2014		Updated car picture with php constant			Matt Holland
	01/07/2015		Added city field to input and updated form
					and edited header die instructions				Matt Holland
	01/08/2015		Added sticky footer								Matt Holland				
 *------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

/* Set user ID */
$userID = $user->userID;

/*  Edit Requested, read dealer record  */
$_SESSION['edit'] = TRUE;
$editdealer = $_SESSION['editdealer'];
$query = "SELECT dealerID, dealername, dealercode, dealercity, dealerstate, regionID FROM dealer WHERE dealercode = '{$editdealer}'";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "Dealer query failed.  See administrator.";
		die (header("Location: managedealers.php"));
	}	
	$findRow = $result->fetch_assoc();
	if (isset($findRow['dealercode']) && $findRow['dealercode'] != "") {
		$dealerID    = $findRow['dealerID']		;  // Save for update query
		$dealername  = $findRow['dealername']	;
		$dealercode  = $findRow['dealercode']	;
		$dealercity  = $findRow['dealercity']	;
		$dealerstate = $findRow['dealerstate']	;
		$regionID    = $findRow['regionID']		;
		
		// Save region name for form value echo
		$query = "SELECT region FROM dealerregion WHERE regionID = $regionID";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "Dealer ". $dealercode. " record not read correctly.  See administrator.";
			die(header("Location: managedealers.php"));
		}
		// Retreive region name
		$value = $result->fetch_assoc();
		$region = $value['region'];	
	} else {
		$_SESSION['error'][] = constant('ENTITY'). ' ' .$editdealer." NOT Read after edit";
	}
	
/*  If user hits register then Update user */
if	(isset($_POST['submit'])) { 
	/*   Fill in variables with dealer table values added */
	$dealername		= $mysqli->real_escape_string($_POST['dealername']);
	$dealercode		= $mysqli->real_escape_string($_POST['dealercode']);
	$dealercity		= $mysqli->real_escape_string($_POST['dealercity']);
	$dealerstate	= $mysqli->real_escape_string($_POST['dealerstate']);
	$regionID		= $mysqli->real_escape_string($_POST['regionID']);
	
	// Update dealer table with form information
	$query = "UPDATE dealer SET	dealercode = '$dealercode', dealername = '$dealername', dealercity = '$dealercity', dealerstate = '$dealerstate', regionID = '$regionID'
			  WHERE dealerID 	=  $dealerID";
										
	/* Check for completion of Update and issue message if failure */
	if (!$mysqli->query($query)) {
		/* ERROR - user not inserted */
		$_SESSION['error'][] = "Dealer ". $dealercode. " was not updated.  See administrator.";
		die(header("Location: managedealersupdate_process.php"));
	} else {
		$_SESSION['success'][] = "Dealer ". $dealercode. " has been updated";
		die (header("Location: managedealers.php"));
	}	
}
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - Admin</title>
    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
</head>

<body>
<div class="wrapper">
	<div class="fixed">
	 <nav class="top-bar" data-topbar>
	  <ul class="title-area">
		<li class="name">
		  <h1><a href=""> <?php echo constant('MANUF');?> - Admin</a></h1>
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
				<li class="divider"></li>
				<li><a href="manageusers.php">Manage Users</a></li>
				<li class="divider"></li>
				<li><a href="managedealers.php">Manage <?php echo constant('ENTITY');?>s</a></li>
				<li class="divider"></li>
				<li><a href="setadminvalues.php">System Defaults</a></li>
				<li class="divider"></li>
				<li><a href="enterrofoundation.php">Return to Survey</a></li>
				<li class="divider"></li>
				<li><a href="index.php">Logout</a></li>
				<li class="divider"></li>
			  </ul>
			</li>
		  </ul>
		</section>
	</nav> 
	</div>

	<div class="row">
		<div class="small-12 medium-12 large-12 columns">
				<h2 style="margin-top: 20px;"> Edit <?php echo constant('ENTITY');?> </h2>
				<p>	 &nbsp;	</p>
		</div>
	</div>
	<form data-abide method="post" action="managedealersupdate_process.php">
		<div class="row">
			<div class="small-12 medium-4 large-4 columns">
				<p><img src="<?php echo constant('PIC_MENUS');?>"></p>
			</div>
		<div class="small-12 medium-4 large-4 columns">
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
						<label><?php echo constant('ENTITY');?> Name
							<input type="text" required value="<?php echo $dealername; ?>" id="dealername" name="dealername" autofocus>
						</label>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<label><?php echo constant('ENTITY');?> Code
						<input type="text" required value="<?php echo $dealercode; ?>" id="dealercode" name="dealercode" pattern="number">
					</label>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<label><?php echo constant('ENTITY');?> City
						<input type="text" required value="<?php echo $dealercity; ?>" id="dealercity" name="dealercity">
					</label>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<label><?php echo constant('ENTITY');?> State
						<select required id="dealerstate" name="dealerstate">
							<option value="<?php echo $dealerstate; ?>"><?php echo $dealerstate; ?></option>
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
					<small class="error">Please enter the <?php echo constant('ENTITYLCASE');?>'s state</small>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<label><?php echo constant('ENTITY');?> Region
						<select required id="regionID" name="regionID">
							<option value="<?php echo $regionID; ?>"><?php echo $region; ?></option>
							<option value="1">Central	</option>
							<option value="2">East		</option>
							<option value="3">West		</option>
						</select>
					</label>
					<small class="error">Please enter the <?php echo constant('ENTITYLCASE');?>'s region</small>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<?php
						// Standard error/success messages to display
						if (isset($_SESSION['error'])) {
							$num_errors = sizeof($_SESSION['error']);
							for ($i=0; $i < $num_errors; $i++) {
								echo '<h6 style="color: #FF0000; font-weight: bold; font-size: 15px;">' .$_SESSION['error'][$i]. '</h6>';
							} //end foreach 
							unset($_SESSION['error']);
						}
						if (isset($_SESSION['success'])) {
							$num_success = sizeof($_SESSION['success']);
							for ($i=0; $i < $num_success; $i++) {
								echo '<h6 style="color: #228B22; font-weight: bold; font-size: 15px;">' .$_SESSION['success'][$i]. '</h6>';
							} //end foreach 
							unset($_SESSION['success']);
						}
					?>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<div class="row">
						<div class="small-9 medium-9 large-9 columns">
							<input type="submit" id="submit" name="submit" value="Save Changes &raquo;" class="tiny button radius">
						</div>
						<div class="small-3 medium-3 large-3 columns">
							<h6><a href="managedealers.php">Cancel</a></h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="small-12 medium-4 large-4 columns">
		
		</div>
	</div>
	</form>
	<div class="row">
		<div class="small-12 medium-12 large-12 columns">
			<p> &nbsp; </p>
		</div>
	</div>
<div class="push"></div>  <!--pushes down footer so does not overlap anything-->	
</div> <!--End div 'wrapper'-->

<footer>
	<span class="footer_span"><span class="copyright">&copy; <?php echo date('Y'); ?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>