<?php
/* -----------------------------------------------------------------------------*
   Program: setadminvalues.php

   Purpose: Manage system default settings

	History:
    Date			Description										by
	10/10/2014		Initial design and coding						Matt Holland
	12/11/2014		Updated car picture with php constant			Matt Holland
	12/11/2014		Added db_cxn.php include						Matt Holland
	01/05/2014		Added opgap processing							Matt Holland
	01/08/2015		Added sticky footer								Matt Holland
	01/19/2015		Added selection menu to lock a survey			Matt Holland
 *------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include('templates/db_cxn.php');

$dealerID 			= $_SESSION['dealerID']			 	;  	// Initiate dealerID magic variable
$userID 			= $user->userID			     		;	// Initiate $userID magic variable
$dealercode 		= $_SESSION['dealercode']	 		;	// Initiate dealercode magic variable 
$surveyindex_id 	= $_SESSION['surveyindex_id']		;  	// Initiate surveyindex_id magic variable
$survey_description = $_SESSION['survey_description']	; 	// Initiate survey_description variable

include ('templates/lastpagevariable_dealerreports_include.php');  // Set last page variable 	

// Query bay volumes for display in form
$query = "SELECT maxvol_ebay, maxvol_sbay FROM bay_volume WHERE dealerID = $dealerID";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "SELECT FROM bay_volume query failed.  See administrator.";
}
$rows = $result->num_rows;
// Set values to zero so user can see that they have not been set

if ($rows == 0) {
	$maxvol_ebay = 0;
	$maxvol_sbay = 0;
} else {	
	// Set variables from query for form display if rows not equal to zero
	$bay_item 		= $result->fetch_assoc();
	$maxvol_ebay 	= $bay_item['maxvol_ebay'];
	$maxvol_sbay	= $bay_item['maxvol_sbay'];
}

// Query express bay ratio threshold for display in form
$query = "SELECT bay_test_ratio FROM bay_ratio";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "bay_ratio query failed.  See administrator.";
}
$rows = $result->num_rows;
// Set values to zero so user can see that they have not been set
if ($rows == 0) {
	$bay_test_ratio = 0;
// Set bay ratio variable from query for form display if rows not equal to zero
} else {
	$bay_ratio_value = $result->fetch_assoc();
	$bay_test_ratio  = $bay_ratio_value['bay_test_ratio'];
}

// Query for average monthly ROs for display in form
$query = "SELECT monthly_ros, days_week, hrs_ebay, hrs_sbay, total_bays, total_ebays FROM express_effect  WHERE dealerID = $dealerID";
$result = $mysqli->query($query);
if (!$result) {	
	$_SESSION['error'][] = "monthlyro_total table query failed.  See administrator.";
}
$rows = $result->num_rows;
// Set values to zero so user can see that they have not been set

if ($rows == 0) {
	$monthly_ros = 0;
	$days_week	 = 0;
	$hrs_ebay	 = 0;
	$hrs_sbay	 = 0;
	$total_bays  = 0;
	$total_ebays = 0;
} else {
// Set variables to table results
	$express_value = $result->fetch_assoc();
	$monthly_ros = $express_value['monthly_ros'];
	$days_week	 = $express_value['days_week']  ;
	$hrs_ebay	 = $express_value['hrs_ebay']   ;
	$hrs_sbay	 = $express_value['hrs_sbay']   ;
	$total_bays  = $express_value['total_bays'] ;
	$total_ebays = $express_value['total_ebays'];	
}
/*----------------------------------------Longhorn & SI Category menu creation--------------------------------------*/
// Query services table to retrieve values and labels for all checkboxes
$query = "SELECT serviceID, service_nickname FROM services
		  ORDER BY servicesort ASC";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "services SELECT query failed.";
}

$array = array(array());
$index = 0;
while ($checkboxlookup = $result->fetch_assoc()) {
	$array[$index]['serviceID'] = (int)$checkboxlookup['serviceID'];
	$array1[$index]['service_nickname'] = $checkboxlookup['service_nickname'];
	$index += 1;
}
/*-------------------------------------------------L1 category array list-------------------------------------------*/
// Query si_category table to get category names for menu
$query = "SELECT category_string FROM si_category WHERE category_id = 1";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$L1rows = $result->num_rows;
if ($L1rows == 0) {
	$_SESSION['error'][] = "There are no Level 1 defaults set.";
}
$L1values = $result->fetch_assoc();
$L1str = explode(',', $L1values['category_string']);
$L1array = array();
$indx = 0;
foreach ($L1str as $num) {
	$indx += 1;
	$L1array[$indx]	= (int)$num;
}
/*-------------------------------------------------Wear Maintenance category array list-------------------------------------------*/
// Query si_category table to get category names for menu
$query = "SELECT category_string FROM si_category WHERE category_id = 2";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$wmrows = $result->num_rows;
if ($wmrows == 0) {
	$_SESSION['error'][] = "There are no Wear Maintenance defaults set.";
}
$wmvalues = $result->fetch_assoc();
$wmstr = explode(',', $wmvalues['category_string']);
$wmarray = array();
$indx = 0;
foreach ($wmstr as $num) {
	$indx += 1;
	$wmarray[$indx]	= (int)$num;
}
/*-------------------------------------------------Repair category array list-------------------------------------------*/	

// Query si_category table to get category names for menu
$query = "SELECT category_string FROM si_category WHERE category_id = 3";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "si_category SELECT query failed.  See administrator.";
}
$repairrows = $result->num_rows;
if ($repairrows == 0) {
	$_SESSION['error'][] = "There are no Repair service defaults set.";
}
$repairvalues = $result->fetch_assoc();
$repairstr = explode(',', $repairvalues['category_string']);
$repairarray = array();
$indx = 0;
foreach ($repairstr as $num) {
	$indx += 1;
	$repairarray[$indx]	= (int)$num;
}

/*----------------------------------------------------------Select Survey menu processing--------------------------------------------*/
// Generate modal menu items from table
$query = "SELECT surveyindex_id, survey_description FROM survey_index";
$surveyresult = $mysqli->query($query);
if (!$surveyresult) {
	$_SESSION['error'][] = "survey_index SELECT query failed. See administrator.";
}

$survey = array(array());
$index = 0;
while ($value = $surveyresult->fetch_assoc()) {
	$survey[$index]['surveyindex_id'] 	  = $value['surveyindex_id'];
	$survey[$index]['survey_description'] = $value['survey_description'];
	$index += 1;
}
// Grab survey_description for display in Model Year Start menu
$query = "SELECT survey_description FROM survey_index WHERE surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "survey_index SELECT query failed.  See administrator.";
}
$descriptionvalue 	= $result->fetch_assoc()			 	 ;
$survey_description = $descriptionvalue['survey_description'];

/*----------------------------------------------------------OpGap form processing--------------------------------------------*/

// Query level_one_analysis1 table to get L1 values and serviceIDs
$query = "SELECT L1_value, serviceID FROM level_one_analysis WHERE surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = " level_one_analysis query failed.  See administrator.";
}
// Set up array of values from L1_value table field
$L1values = $result->fetch_assoc();
$L1value  = explode(',', $L1values['L1_value']);

$L1_values  = array();
$index = 0;
foreach ($L1value as $num) {
	$L1_values[$index] = (int)$num;
	$index += 1;
}

// Retrieve serviceID labels
$L1svcs = $L1values['serviceID'];
$query = "SELECT servicedescription FROM services WHERE serviceID IN ($L1svcs)";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = " services query failed.  See administrator.";
}
$rows = $result->num_rows;
// Create an array of service descriptions
$L1svcs = array();
$index = 0;
while ($items = $result->fetch_assoc()) {
	$L1svcs[$index]['servicedescription'] = $items['servicedescription'];
	$index += 1;
}

// Query level_two_analysis1 table to get L2 values and serviceIDs
$query = "SELECT L2_value, serviceID FROM level_two_analysis WHERE surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = " services query failed.  See administrator.";
}
$rows = $result->num_rows;
// Set up array of values from L2_value table field
$L2values = $result->fetch_assoc();
$L2value  = explode(',', $L2values['L2_value']);

$L2_values = array();
$index = 0;
foreach ($L2value as $num) {
	$L2_values[$index] = (int)$num;
	$index += 1;
}
// Retrieve serviceID labels
$L2svcs = $L2values['serviceID'];
$query = "SELECT servicedescription FROM services WHERE serviceID IN ($L2svcs)";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = " services query failed.  See administrator.";
}
// Set up array of service descriptions
$L2svcs = array();
$index = 0;
while ($items = $result->fetch_assoc()) {
	$L2svcs[$index]['servicedescription'] = $items['servicedescription'];
	$index += 1;
}
/*----------------------------------------Lock survey menu generation--------------------------------------------*/
// Get list of dealercodes for menu dropdown
$query = "SELECT dealerID, dealercode FROM dealer";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Dealer query failed.  See administrator.";
}
$dlr_rows = $result->num_rows;
$dlr_info = array(array());
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$dlr_info[$index]['dealerID']   = $lookup['dealerID']  ; 
	$dlr_info[$index]['dealercode'] = $lookup['dealercode'];
	$index += 1;
}

/*-----------------------Functions--------------------------*
    Function:	echoservicebox
	Purpose: 
		echo services input box.  If service is
		in Single Issue Category table then show box as checked
	Inputs:
		$servicevalue:	 	numeric associated with service
		$servicenickname: 	description of service 
		$services:			array of services for order	
 *----------------------------------------------------------*/
function echoserviceboxL1($servicevalue, $servicenickname, &$services) {
	$key = array_search($servicevalue, $services);	/* see if this service box is in si_category table */
	if ($key == FALSE) {
echo'			<input type="checkbox" class="level1" id="level1box[]" name="level1box[]" value='.$servicevalue. '> '.$servicenickname.' <br>';
	} else {
echo'			<input type="checkbox" class="level1" id="level1box[]" name="level1box[]" value='.$servicevalue. ' checked> '.$servicenickname.' <br>';
	}
}
function echoserviceboxWM($servicevalue, $servicenickname, &$services) {
	$key = array_search($servicevalue, $services);	/* see if this service box is in si_category table */
	if ($key == FALSE) {
echo'			<input type="checkbox" class="wearmaint" id="wearmaintbox[]" name="wearmaintbox[]" value='.$servicevalue. '> ' .$servicenickname. '<br>';
	} else {
echo'			<input type="checkbox" class="wearmaint" id="wearmaintbox[]" name="wearmaintbox[]" value='.$servicevalue. ' checked> ' .$servicenickname. '<br>';
	}
}
function echoserviceboxREPAIR($servicevalue, $servicenickname, &$services) {
	$key = array_search($servicevalue, $services);	/* see if this service box is in si_category table */
	if ($key == FALSE) {
echo'			<input type="checkbox" class="repair" id="repairbox[]" name="repairbox[]" value='.$servicevalue. '> ' .$servicenickname. '<br>';
	} else {
echo'			<input type="checkbox" class="repair" id="repairbox[]" name="repairbox[]" value='.$servicevalue. ' checked> ' .$servicenickname. '<br>';
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
	<link rel="stylesheet" href="css/menubar_dealerchange.css" />
    <script src="js/vendor/modernizr.js"></script>
 </head>
<body>
<div class="wrapper">
<div class="fixed">
<nav class="top-bar" data-topbar>
  <!-- Title -->
	<ul class="title-area">
		<li class="name"><h1><a><?php echo constant('MANUF');?> - Admin </a></h1></li>
		<!-- Mobile Menu Toggle -->
		<li class="toggle-topbar menu-icon"><a><span>Menu</span></a></li>
	</ul>
  <!-- Top Bar Section -->
	<section class="top-bar-section">
    <!-- Top Bar Left Nav Elements -->
    <ul class="left">
	<li class="divider"></li>
		<li><a data-reveal-id="myModal" style="color: #46BCDE; font-weight: bold;">Select Survey &raquo</a></li>
		<li class="divider"></li>
		<div id="myModal" class="small reveal-modal" style="background-color: #ffffff;" data-reveal>
			<div class="row">
				<div class="medium-12 large-12 columns">
				<!--<h5 style=" text-align: center; color: #000000;">Select Survey</h5>
				<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">-->
					<div class="medium-1 large-1 columns">
						<p> </p>
					</div>
					<div class="medium-10 large-10 columns">
						<form method="post" action="selectsurvey_process.php">
							<!--<fieldset style="padding-bottom: 2px; background-color: #333333;">-->
							<h6 style="color: #008cba; text-align: center;">Select <?php echo constant('ENTITY');?> Survey </h6>
							<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">
							<select style="margin-top: 30px; margin-bottom: 20px;" name="survey_selection" id="survey_selection">
								<option value="">Select survey...</option>
<?php
for ($i=0; $i<3; $i++) {
echo'							<option value= '.$survey[$i]['surveyindex_id']. '>' .$survey[$i]['survey_description']. '</option>';
}
?>								
							</select>
							<input type="submit" class="tiny button radius" value="Submit">
							<!--</fieldset>-->
						</form>
					</div>
					<div class="medium-1 large-1 columns">
						<p>  </p>
					</div>
				</div>
			</div>
		    <a class="close-reveal-modal" style="font-size: 19px;">&#215;</a>
		</div>
		<li class="divider"></li>
		<!-- Search | has-form wrapper -->
		<li class="has-form">
		<form method="post" action="dealercodeswitch_process.php">
			<div class="row collapse">
				<div class="small-6 medium-8 large-8 columns">
						<select class="collapse_select" id="dealercodechange" name="dealercodechange">
							<option value="">Select Dealer </option>
							<?php
							for ($i=0; $i<$dlr_rows; $i++) {
								echo '<option style="width: auto;" value='.$dlr_info[$i]['dealercode'].'>'.$dlr_info[$i]['dealercode'].'</option>';
							}
							?>
						</select>	
				</div>
				<div class="small-2 medium-4 large-4 columns">
					<input type="submit" value="Go" class="alert button postfix collapse_submit" id="dealercodesubmit" name="dealercodesubmit" >
				</div>
				<div class="small-4 columns">
					
				</div>
			</div>
		</form>
		</li>
    </ul>
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
			<h2 style="margin-top: 20px;"> <?php echo constant('MANUF');?> System Defaults</h2>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<!-- Error & Success messages -->
		<?php
		if (isset($_SESSION['error'])) {
			$num_errors = sizeof($_SESSION['error']);
			for ($i=0; $i < $num_errors; $i++) {
				echo '<h6 style="color: #FF0000; font-size: 15px;">' .$_SESSION['error'][$i]. '</h6>';
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
		&nbsp;
	</div>
</div>
<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-4 large-4 columns">
				<img src="<?php echo constant('PIC_MENUS');?>">
			</div>
			<div class="small-12 medium-4 large-4 columns">
				<form method="POST" action="lock_survey_process.php">
				<fieldset style="padding-bottom: 2px; background-color: #f2f2f2;">
					<h5 style=" text-align: center; color: #008cba;">Survey Access Control</span></h5>
					<hr style="margin-top: 0px; border-color: #909090;">
					
					<label>Dealer Code:
					<select id="dlr_id" name="dlr_id">
						<?php
						if (isset($_SESSION['dlr_code'])) {
							echo '<option value='.$_SESSION['dlr_id'].'>'.$_SESSION['dlr_code'].'</option>';
						} else {
							echo '<option value="">Select...</option>';
						}
						for ($i=0; $i<$dlr_rows; $i++) {
						echo '<option value='.$dlr_info[$i]['dealerID'].'>'.$dlr_info[$i]['dealercode'].'</option>';
						}
						?>
					</select>	
					</label>
					
					<label>Survey Type
					<select id="survey_id" name="survey_id">
						<?php
						if (isset($_SESSION['survey_desc'])) {
							echo '<option value='.$_SESSION['survey_id'].'>'.$_SESSION['survey_desc'].'</option>';
						} else {
							echo '<option value="">Select...</option>';
						}
						for ($i=0; $i<3; $i++) {
						echo'<option value='.$survey[$i]['surveyindex_id'].'>'.$survey[$i]['survey_description'].'</option>';
						}
						?>
					</select>
					</label>
					
					<label>Survey Locked?
					<select id="survey_lock" name="survey_lock">
						<?php
							if (isset($_SESSION['survey_lock'])) {
								if ($_SESSION['survey_lock'] == 1) {
								echo '<option value='.$_SESSION['survey_lock'].'> Yes </option>';
								} else {
								echo '<option value='.$_SESSION['survey_lock'].'> No </option>';
								}
							} else {
								echo '<option value="">Select...</option>';
							}
						?>
						<option value='1'>Yes </option>
						<option value='0'>No  </option>
					</select>
					</label>
					
					<!--<hr style="border-color: #909090;">	-->
					<input type="submit" value="Submit" class="tiny button radius">
				</fieldset>
				</form>
			</div>
			<div class="medium-4 large-4 columns">
				<p> </p>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<hr style="border-color: #909090;">
		<h5 style="font-size: 17px; color: #228B22; font-weight: bold;">Express Metrics: <?php echo constant('ENTITY').' '.$dealercode;?></h5>
	</div>
</div>

<form data-abide method="post" action="expressimpactadmin_process.php">
<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-4 large-4 columns">
				<fieldset style="padding-bottom: 2px; background-color: #f2f2f2;">
					<h5 style=" text-align: center; color: #008cba;">Service Center Data</span></h5>
					<hr style="margin-top: 0px; border-color: #909090;">
					
					<label>Days Open Per Week:
					<input type="number" required name="days_week" id="days_week" value="<?php echo $days_week; ?>">
					</label>
					
					<label>Shift Hours : Express Bay
					<input type="number" required name="hrs_ebay" id="hrs_ebay" value="<?php echo $hrs_ebay; ?>">
					</label>
					
					<label>Shift Hours: Standard Bay
					<input type="number" required name="hrs_sbay" id="hrs_sbay" value="<?php echo $hrs_sbay; ?>">
					</label>
					
					<hr style="border-color: #909090;">	
				</fieldset>
			</div>
			<div class="small-12 medium-4 large-4 columns">
				<fieldset style="padding-bottom: 2px; background-color: #f2f2f2;">
						<h5 style=" text-align: center; color: #008cba;">Set Bay Metrics</h5>
						<hr style="margin-top: 0px; border-color: #909090;">
						
						<label>Max Volume - L1 Express Bay
						<input type="number" required value="<?php echo $maxvol_ebay; ?>" id="maxvol_ebay" name="maxvol_ebay">
						</label>
							
						<label>Max Volume - Standard Bay
						<input type="number" required value="<?php echo $maxvol_sbay; ?>" id="maxvol_sbay" name="maxvol_sbay">
						</label>
						
						<label>Express Ratio Threshold
						<input type="number" required value="<?php echo $bay_test_ratio; ?>" id="bay_test_ratio" name="bay_test_ratio">
						</label>
						
						<hr style="border-color: #909090;">	
				</fieldset>
			</div>
			<div class="small-12 medium-4 large-4 columns">
				<fieldset style="padding-bottom: 2px; background-color: #f2f2f2;">
					<h5 style=" text-align: center; color: #008cba;">Set Service Metrics</h5>
					<hr style="margin-top: 0px; border-color: #909090;">
							
					<label>Monthly Repair Orders
					<input type="number" required value="<?php echo $monthly_ros; ?>" id="monthly_ros" name="monthly_ros">
					</label>
					
					<label>Total Shop Bays
					<input type="number" required value="<?php echo $total_bays; ?>" id="total_bays" name="total_bays">
					</label>
						
					<label>Total Express Bays
					<input type="number" required value="<?php echo $total_ebays; ?>" id="total_ebays" name="total_ebays">
					</label>

					<hr style="border-color: #909090;">
					</fieldset>
			</div>
		</div>	
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<input type="submit" class="tiny button radius" value="Submit">
	</div>
</div>
</form>

<!--------------------------------------------Operating Gap system defaults----------------------------------------------->
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<hr style="border-color: #909090;">
		<h5 style="font-size: 17px; color: #228B22; font-weight: bold;">Operating Gap Values (<?php echo $survey_description; ?>s)</h5>
	</div>
</div>

<form data-abide method="post" action="opgap_admin_process.php">
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<fieldset style="background-color: #f2f2f2;">
		<div class="row">
<?php
			for ($i=0; $i<3; $i++) {
			echo'
			<div class="small-12 medium-4 large-4 columns">
				<label>'.$L1svcs[$i]['servicedescription'].'
				<input type="number" required value='.$L1_values[$i].' id="L1value[]" name="L1value[]" >
				</label>
			</div>';
			}
?>			
		</div>	
		<div class="row">
<?php
			for ($i=3; $i<6; $i++) {
			echo'
			<div class="small-12 medium-4 large-4 columns">
				<label>'.$L1svcs[$i]['servicedescription'].'
				<input type="number" required value='.$L1_values[$i].' id="L1value[]" name="L1value[]">
				</label>
			</div>';
			}
?>
		</div>	
		<div class="row">
<?php
			for ($i=6; $i<7; $i++) {
			echo'
			<div class="small-12 medium-4 large-4 columns">
				<label>'.$L1svcs[$i]['servicedescription'].'
				<input type="number" required value='.$L1_values[$i].' id="L1value[]" name="L1value[]">
				</label>
			</div>';
			}
			for ($i=0; $i<2; $i++) {
			echo'
			<div class="small-12 medium-4 large-4 columns">
				<label>'.$L2svcs[$i]['servicedescription'].'
				<input type="number" required value='.$L2_values[$i].' id="L2value[]" name="L2value[]">
				</label>
			</div>';
			}
?>		
		</div>	
		<div class="row">
<?php
			for ($i=2; $i<5; $i++) {
			echo'
			<div class="small-12 medium-4 large-4 columns">
				<label>'.$L2svcs[$i]['servicedescription'].'
				<input type="number" required value='.$L2_values[$i].' id="L2value[]" name="L2value[]">
				</label>
			</div>';
			}
?>			
		</div>	
		<div class="row">
<?php
			for ($i=5; $i<8; $i++) {
			echo'
			<div class="small-12 medium-4 large-4 columns">
				<label>'.$L2svcs[$i]['servicedescription'].'
				<input type="number" required value='.$L2_values[$i].' id="L2value[]" name="L2value[]">
				</label>
			</div>';
			}
?>			
		</div>
		</fieldset>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				
				<p style="font-size: 12px; color: blue;">*Note: Whole numbers represent percentages</p>
				<input type="submit" value="Submit" class="tiny button radius">
			</div>
		</div>
	</div>
</div>
</form>

<!----------------------------------------Single Issue Category system defaults------------------------------------------->
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<hr style="border-color: #909090;">
		<h5 style="font-size: 17px; color: #228B22; font-weight: bold;">Single Issue Category Groupings</h5>
	</div>
</div>

<form method="post" action="sicategory_process.php">
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
	<h5>Level 1 Services:</h5>
			<fieldset style="padding-left: 0rem; padding-right: 2rem; background-color: #f2f2f2;">
			<div class="row">
				<div class="small-1 medium-1 large-1 columns">
					<p> </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
					<?php
					for ($i=0; $i<6; $i++) {
					echoserviceboxL1($array[$i]['serviceID'], $array1[$i]['service_nickname'], $L1array);
					}
					?>     
				</div>
				<div class="small-5 medium-2 large-2 columns">
					<?php
					for ($i=6; $i<8; $i++) {
					echoserviceboxL1($array[$i]['serviceID'], $array1[$i]['service_nickname'], $L1array);
					}
					for ($i=8; $i<12; $i++) {
					echoserviceboxL1($array[$i]['serviceID'], $array1[$i]['service_nickname'], $L1array);
					}
					?>
				</div>
				<div class="small-1 medium-1 large-1 columns">
					<p> </p>
				</div>
				<div class="small-5 medium-3 large-3 columns">
					<?php
					for ($i=12; $i<18; $i++) {
					echoserviceboxL1($array[$i]['serviceID'], $array1[$i]['service_nickname'], $L1array);
					}
					?>        
				</div>
				<div class="small-5 medium-2 large-2 columns">
					<?php
					for ($i=18; $i<20; $i++) {
					echoserviceboxL1($array[$i]['serviceID'], $array1[$i]['service_nickname'], $L1array);
					}
					for ($i=20; $i<23; $i++) {
					echoserviceboxL1($array[$i]['serviceID'], $array1[$i]['service_nickname'], $L1array);
					}
					?>
				</div>
			</div>
			</fieldset>
			<div class="row">	
				<div class="small-12 medium-12 large-12 columns">
					<input type="checkbox" id="selectall_level1"><label for="selectall_level1"> Select All </label>
				</div>
			</div>
		<!--</div>-->
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> </p>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
	<h5>Wear Maintenance:</h5>
		<fieldset style="padding-left: 0rem; padding-right: 2rem; background-color: #f2f2f2;">
			<div class="row">
				<div class="small-1 medium-1 large-1 columns">
					<p>   </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
					<?php
					for ($i=0; $i<6; $i++) {
					echoserviceboxWM($array[$i]['serviceID'], $array1[$i]['service_nickname'], $wmarray);
					}
					?>     
				</div>
				<div class="small-5 medium-2 large-2 columns">
					<?php
					for ($i=6; $i<8; $i++) {
					echoserviceboxWM($array[$i]['serviceID'], $array1[$i]['service_nickname'], $wmarray);
					}
					for ($i=8; $i<12; $i++) {
					echoserviceboxWM($array[$i]['serviceID'], $array1[$i]['service_nickname'], $wmarray);
					}
					?>
				</div>
				<div class="small-1 medium-1 large-1 columns">
					<p> </p>
				</div>
				<div class="small-5 medium-3 large-3 columns">
					<?php
					for ($i=12; $i<18; $i++) {
					echoserviceboxWM($array[$i]['serviceID'], $array1[$i]['service_nickname'], $wmarray);
					}
					?>        
				</div>
				<div class="small-5 medium-2 large-2 columns">
					<?php
					for ($i=18; $i<20; $i++) {
					echoserviceboxWM($array[$i]['serviceID'], $array1[$i]['service_nickname'], $wmarray);
					}
					for ($i=20; $i<23; $i++) {
					echoserviceboxWM($array[$i]['serviceID'], $array1[$i]['service_nickname'], $wmarray);
					}
					?>
				</div>
			</div>
			</fieldset>
		<div class="row">	
			<div class="small-12 medium-12 large-12 columns">
				<input type="checkbox" id="selectall_wearmaint"><label for="selectall_wearmaint"> Select All </label>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> </p>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
	<h5>Repair Services:</h5>
		<fieldset style="padding-left: 0rem; padding-right: 2rem; background-color: #f2f2f2;">
			<div class="row">
				<div class="small-1 medium-1 large-1 columns">
					<p>   </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
					<?php
					for ($i=0; $i<6; $i++) {
					echoserviceboxREPAIR($array[$i]['serviceID'], $array1[$i]['service_nickname'], $repairarray);
					}
					?>     
				</div>
				<div class="small-5 medium-2 large-2 columns">
					<?php
					for ($i=6; $i<8; $i++) {
					echoserviceboxREPAIR($array[$i]['serviceID'], $array1[$i]['service_nickname'], $repairarray);
					}
					for ($i=8; $i<12; $i++) {
					echoserviceboxREPAIR($array[$i]['serviceID'], $array1[$i]['service_nickname'], $repairarray);
					}
					?>
				</div>
				<div class="small-1 medium-1 large-1 columns">
					<p> </p>
				</div>
				<div class="small-5 medium-3 large-3 columns">
					<?php
					for ($i=12; $i<18; $i++) {
					echoserviceboxREPAIR($array[$i]['serviceID'], $array1[$i]['service_nickname'], $repairarray);
					}
					?>        
				</div>
				<div class="small-5 medium-2 large-2 columns">
					<?php
					for ($i=18; $i<20; $i++) {
					echoserviceboxREPAIR($array[$i]['serviceID'], $array1[$i]['service_nickname'], $repairarray);
					}
					for ($i=20; $i<23; $i++) {
					echoserviceboxREPAIR($array[$i]['serviceID'], $array1[$i]['service_nickname'], $repairarray);
					}
					?>
				</div>
			</div>
			</fieldset>
		<div class="row">	
			<div class="small-12 medium-12 large-12 columns">
				<input type="checkbox" id="selectall_repair"><label for="selectall_repair"> Select All </label>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<input type="submit" value="Submit" class="tiny button radius" id="" name="">
	</div>
</div>
</form>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<hr style="border-color: #909090;">
	</div>
</div>
<!------------------------------------------------------------------------------------------------------------------------>

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
<?php
// Unset error message globals when form reloads
	unset ($_SESSION['dlr_id'])		;
	unset ($_SESSION['dlr_code'])	;
	unset ($_SESSION['survey_id'])	;
	unset ($_SESSION['survey_desc']);
	unset ($_SESSION['survey_lock']);
?>

	<script src="js/vendor/jquery.js"></script>	
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
	  
		$(document).ready(function() {
			$('#selectall_longhorn').click(function(event) { // on click
				if(this.checked) { // Check select status
					$('.longhorn').each(function() {	// Loop through each checkbox
						this.checked = true;  // Select all checkboxes with class "longhornbox"
					});
				} else {
					$('.longhorn').each(function() { // Loop through each checkbox
						this.checked = false; // deselect all checkboxes
					});
				}
			});
		});
		
		$(document).ready(function() {
			$('#selectall_level1').click(function(event) { // on click
				if(this.checked) { // Check select status
					$('.level1').each(function() {	// Loop through each checkbox
						this.checked = true;  // Select all checkboxes with class "longhornbox"
					});
				} else {
					$('.level1').each(function() { // Loop through each checkbox
						this.checked = false; // deselect all checkboxes
					});
				}
			});
		});
		
		$(document).ready(function() {
			$('#selectall_wearmaint').click(function(event) { // on click
				if(this.checked) { // Check select status
					$('.wearmaint').each(function() {	// Loop through each checkbox
						this.checked = true;  // Select all checkboxes with class "longhornbox"
					});
				} else {
					$('.wearmaint').each(function() { // Loop through each checkbox
						this.checked = false; // deselect all checkboxes
					});
				}
			});
		});
		
		$(document).ready(function() {
			$('#selectall_repair').click(function(event) { // on click
				if(this.checked) { // Check select status
					$('.repair').each(function() {	// Loop through each checkbox
						this.checked = true;  // Select all checkboxes with class "longhornbox"
					});
				} else {
					$('.repair').each(function() { // Loop through each checkbox
						this.checked = false; // deselect all checkboxes
					});
				}
			});
		});
	</script>
    </script>
  </body>
</html>