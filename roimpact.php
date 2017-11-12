<?php
/* ------------------------------------------------------------------------------*
   Program: roimpact.php

   Purpose: Display Express assessment values and allow user to adjust inputs

   History:
    Date		Description											by
	10/03/2014	Initial design and coding.							Matt Holland
	10/09/2014	Adjust form and queries for altered tables			Matt Holland
	12/11/2014	Added php constant function for car picture			Matt Holland
	03/17/2015	Added surveyindex_id functionality					Matt Holland
	05/27/2015	Re-designed per Marc Wollard.  Added inputs
				directly in tables.  Integrated AJAX for form
				submit.  Added another bay type (High Tech Repair)	Matt Holland
	06/19/2015	Changed menubar to menubar_dealer_reports as the	Matt Holland
				'Select Values' dropdown is no longer applicable
				with the new on-screen AJAX-functional table inputs.
				Also altered bay_volume table to be one row of
				absolute values instead of on a per-dealer basis
		
 --------------------------------------------------------------------------------*/
 
// Standard system includes
require_once("functions.inc");
include ('templates/login_check.php');

// DB connection
include('templates/db_cxn.php');

// Initialize default variables
$dealerID   	= $_SESSION['dealerID']		; 	// Initialize $dealerID variable
$dealerIDs1 	= $_SESSION['dealerID']		;  	// Initialize dealer variable for query includes
$dealercode 	= $_SESSION['dealercode']	;  	// Initialize $dealercode variable
$userID			= $user->userID				;	// Initialize user
$user_firstname = $user->firstName			;	// Initialize user first name

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

/*--------------------------------------$currentyear processing - for display-------------------------------------*/

// Get survey start year from 'surveys' table - If set to zero (which happens if and when the above survey processing inserts entry into surveys table), set $currentyear to server year
$query = "SELECT survey_start_yearmodelID FROM surveys WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Survey search error.  Please see administrator.';
	die(header('Location: enterrofoundation.php'));
}
$lookup = $result->fetch_assoc();
$currentyearID = $lookup['survey_start_yearmodelID'];
if ($currentyearID != 0) {
	// If not set to zero then was previously set by user. Set $currentyearID to survey_start_yearmodelID listed in table
	$query = "SELECT modelyear FROM yearmodel WHERE yearmodelID = $currentyearID";
	$result = $mysqli->query($query);
	if (!$result) {
	$_SESSION['error'][] = 'Survey search error.  Please see administrator.';
	//die(header('Location: enterrofoundation.php'));
	}
	// Fetch actual modelyear and set $currentyear to result
	$lookup = $result->fetch_assoc();
	$currentyear = $lookup['modelyear'];
} else {
	// If no start year record, then set to server year
	$currentyear = date('Y');
	// If it is after August, introduce next year selection by increasing $currentyear by 1
	$month = date('m');
	if ($month > 8) {
		$currentyear = $currentyear+1;
	}
}

/*--------------------------------------Query of express_effect table-------------------------------------*/
// Select values from express_effect table
$query = "SELECT monthly_ros, days_week, total_bays, true_L1bays, true_L2bays
		  FROM express_effect WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
		  
$result = $mysqli->query($query);
if(!$result) { $_SESSION['error'][] = "Query of express_effect failed.  See administrator.";} 

$num_rows = $result->num_rows;
// Check to see if query returned rows; if no rows then issue note and set values to zero so that they are not null
if($num_rows == 0) {
	$monthly_ros 	= 0;
	$days_week		= 0;
	$total_bays	 	= 0;
	$true_L1bays    = 0;
	$true_L2bays    = 0;
} else {
		// Set variable values from query for use below
		$express_value = $result->fetch_assoc()			;
		$monthly_ros 	= $express_value['monthly_ros']	;
		$days_week		= $express_value['days_week']	;
		$total_bays	 	= $express_value['total_bays']	;
		$true_L1bays 	= $express_value['true_L1bays'] ;
		$true_L2bays 	= $express_value['true_L2bays'] ;
}	

/*--------------------------------------Query of bay_volume table-------------------------------------*/	
$query = "SELECT maxvol_L1bay, maxvol_L2bay, maxvol_htrbay FROM bay_volume";		  
$result = $mysqli->query($query);
if(!$result) { 
	$_SESSION['error'][] = "Bays query failed.  See administrator.";
	die(header("Location: enterrofoundation.php"));
} 
$num_rows = $result->num_rows;

// Retrieve max bay values
$bay_volume = $result->fetch_assoc();
$maxvol_L1bay  = $bay_volume['maxvol_L1bay'] ;
$maxvol_L2bay  = $bay_volume['maxvol_L2bay'] ;
$maxvol_htrbay = $bay_volume['maxvol_htrbay'];
	
/*----------------------------- Queries for Level 1 and Level 2 demand percentages ----------------------------*/

// Generate serviceID strings for queries
include ('templates/sd_strings.php');

// To compute the total # rows for denominator	
include ('templates/query_totalros_md1.php');

// SVC D queries
include ('templates/query_svcd_md1.php');

/*  Consolidate computations  */
include ('templates/query_svcd_summary.php');
/*-------------------------------------------------------------------------------------------------------------*/

// Compute current service stats for report
$L1percentdemand		= $percent_level1_sd.'%'				;
$L1monthly_ros 			= ($monthly_ros*$percent_level1_sd)/100	;
$L2percentdemand		= $percent_level2_sd.'%'				;
$L2monthly_ros 			= ($monthly_ros*$percent_level2_sd)/100	;

// Compute volume per day and precise bays needed
if ($days_week == 0) {
	$L1daily_ros 	= 0;
	$L2daily_ros 	= 0;
	$L1precise_bays = 0;
	$L2precise_bays = 0;
	$L1actual_bays  = 0;
	$L2actual_bays  = 0;
} else {
	$L1daily_ros 	= ($L1monthly_ros / ($days_week * 4.25));
	$L2daily_ros 	= ($L2monthly_ros / ($days_week * 4.25));
	$L1precise_bays = number_format(($L1daily_ros / ($maxvol_L1bay*.8)),2);
	// Run calculations for actual bays needed
	if ($L1precise_bays < .8) {
		$L1actual_bays = 1;
	} elseif ($L1precise_bays >= .8 && $L1precise_bays < 1.6) {
		$L1actual_bays = 2;
	} elseif ($L1precise_bays >= 1.6 && $L1precise_bays < 2.4) {
		$L1actual_bays = 3;
	} elseif ($L1precise_bays >= 2.4 && $L1precise_bays < 3.2) {
		$L1actual_bays = 4;
	} elseif ($L1precise_bays >= 3.2 && $L1precise_bays < 4) {
		$L1actual_bays = 5;
	} elseif ($L1precise_bays >= 4 && $L1precise_bays < 4.8) {
		$L1actual_bays = 6;
	} elseif ($L1precise_bays >= 4.8 && $L1precise_bays < 5.6) {
		$L1actual_bays = 7;
	} elseif ($L1precise_bays >= 5.6 && $L1precise_bays < 6.4) {
		$L1actual_bays = 8;
	} elseif ($L1precise_bays >= 6.4 && $L1precise_bays < 7.2) {
		$L1actual_bays = 9;	
	} elseif ($L1precise_bays >= 7.2 && $L1precise_bays < 8) {
		$L1actual_bays = 10;
	} else {
		$L1actual_bays = 10;
		$L1actual_bays = $L1actual_bays.'+';
	}
	$L2precise_bays = number_format(($L2daily_ros / ($maxvol_L2bay*.8)),2);
	// Run calculations for actual bays needed
	if ($L2precise_bays < .8) {
		$L2actual_bays = 1;
	} elseif ($L2precise_bays >= .8 && $L2precise_bays < 1.6) {
		$L2actual_bays = 2;
	} elseif ($L2precise_bays >= 1.6 && $L2precise_bays < 2.4) {
		$L2actual_bays = 3;
	} elseif ($L2precise_bays >= 2.4 && $L2precise_bays < 3.2) {
		$L2actual_bays = 4;
	} elseif ($L2precise_bays >= 3.2 && $L2precise_bays < 4) {
		$L2actual_bays = 5;
	} elseif ($L2precise_bays >= 4 && $L2precise_bays < 4.8) {
		$L2actual_bays = 6;
	} elseif ($L2precise_bays >= 4.8 && $L2precise_bays < 5.6) {
		$L2actual_bays = 7;
	} elseif ($L2precise_bays >= 5.6 && $L2precise_bays < 6.4) {
		$L2actual_bays = 8;
	} elseif ($L2precise_bays >= 6.4 && $L2precise_bays < 7.2) {
		$L2actual_bays = 9;	
	} elseif ($L2precise_bays >= 7.2 && $L2precise_bays < 8) {
		$L2actual_bays = 10;
	} else {
		$L2actual_bays = 10;
		$L2actual_bays = $L2actual_bays.'+';
	}
}

// Compute bay outputs
$L1bay_output = ($maxvol_L1bay * $true_L1bays);
$L2bay_output = ($maxvol_L2bay * $true_L2bays);
$htrbay_output= ($maxvol_htrbay * ($total_bays - $true_L1bays - $true_L2bays));

// Compute total shop throughputs
$conv_tput	  = ($maxvol_htrbay * $total_bays);
$express_tput = ($L1bay_output + $L2bay_output + $htrbay_output);


?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - <?php echo constant('MANUF')?></title>
	<link rel="icon" href="img/sos_logo3.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/responsive-tables.css" media="screen" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
	<link rel="stylesheet" href="css/menubar_dealerchange.css"/>
	<style>
		.print_title {
			font-size: 17px;
		}
		.main_subtitle1 {
			color: #00008B; 
			font-size: 23px;
		}
		.main_subtitle2 {
			color: gray;
			font-size: 18px;
		}
		.error {
			color: #FF0000; 
			font-weight: bold; 
			font-size: 15px;
		}
		.form_error {
			display: none;
		}
		.success {
			color: #228B22; 
			font-weight: bold; 
			font-size: 15px;
		}
		.table_title {
			color: #808080; 
			text-align: center;
		}
		.table_current {
			border-collapse: collapse; 
			margin-left: auto; 
			margin-right: auto; 
			padding-bottom: 0px;
		}
		.table_current thead {
			background-color: #C00000;
		}
		.table_current th {
			color: #FFFFFF; 
			font-weight: normal; 
			text-align: center; 
			width:150px; 
			height: 20px;
			font-size: 17px;
		}
		.table_current_body td {
			height: 20px;
			font-size: 17px;
			text-align: center;
		}
		.table_current_body tr.row2 {
			background-color: #D8D8D8;
		}
		.ro_subheading {
			padding: 0px; 
			margin-top: 0px; 
			font-size: 17px; 
			text-align: center;
		}
		.table_revamped {
			border-collapse: collapse; 
			margin-left: auto; 
			margin-right: auto;
		}
		.th1 {
			color: #FFFFFF; 
			font-weight: normal; 
			text-align: center; 
			width:250px; 
			height: 20px; 
			background-color: #778899;
			font-size: 17px;
		}
		.th2 {
			color: #FFFFFF; 
			font-weight: normal; 
			text-align: center; 
			width:250px; 
			height: 20px; 
			background-color: #0266C8;
			font-size: 17px;
		}	
		
		.th2_throughput {
			color: #FFFFFF; 
			font-weight: normal; 
			text-align: center; 
			width:250px; 
			height: 20px; 
			background-color: #FF8C00;
			font-size: 17px;
		}
		.th3 {
			color: #FFFFFF; 
			font-weight: normal; 
			text-align: center; 
			width:250px; 
			height: 20px;
			background-color: #333333;
			font-size: 17px;
		}
		.th3_throughput {
			color: #FFFFFF; 
			font-weight: normal; 
			text-align: center; 
			width:250px; 
			height: 20px;
			background-color: #173870;
			font-size: 17px;
		}
		.th4 {
			color: #FFFFFF; 
			font-weight: normal; 
			text-align: center; 
			width:250px; 
			height: 20px; 
			background-color: green;
			font-size: 17px;
		}
		.th_orange {
			color: #FFFFFF; 
			font-weight: normal; 
			text-align: center; 
			width:250px; 
			height: 20px;
			background-color: #C00000;
			font-size: 16px;
		}
		input[type="text"] {
			margin: 0;
			text-align: center;
			background-color: #afe5f5;
			font-size: 17px;
		}
		.select_week {
			margin: 0;
			background-color: #afe5f5;
			font-size: 17px;
			padding-left: 95px;
		}
		input[type="submit"]:hover {
			cursor: pointer;
		}
		.table_revamped_title {
			color: #808080; 
			text-align: center; 
			margin-top: 30px;
		}
		.table_revamped td {
			height: 20px;
			font-size: 17px;
			text-align: center;
		}
		.revamped_td2 {
			height: 20px; 
			border-left: 1px solid #CCCCCC;
			text-align: center;
			font-size: 17px;
		}
		.ro_subheading2 {
			padding: 0px; 
			margin-top: 0px; 
			font-size: 17px;
			color: blue;
			font-weight: bold;
		}
		.ro_subheading3 {
			padding: 0px; 
			margin-top: 0px; 
			font-size: 14px;
			font-weight: 600;
		}
		.express_bay_ratio {
			color: blue;
			font-weight: 600;
		}
		.table_throughput {
			color: #808080; 
			text-align: center; 
			margin-top: 30px;
		}
		.td_green {
			height: 20px; 
			color: green; 
			font-weight: bold; 
			border-left: 1px solid #CCCCCC;
		}
		.td_green_span {
			color: #000000;
		}
	</style>
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script>
		$(document).ready(function() { 
			$("#dealertable").tablesorter(); 
			
			$("form#metrics_form").submit(function(event) {
				
				// Remove previous submission error
				$(".form_error").hide();
				
				// Get inputs
				var one   = document.getElementById("ros")	;
				var three = document.getElementById("week")	;
				var four  = document.getElementById("bays")	;
				var submit= "metrics_submit";
				
				console.log(one.value);
				console.log(three.value);
				console.log(four.value);
				console.log(submit);
				
				// Establish validation rule
				var input_req   = /^[0-9]{1,}$/;
				
				// Initiate error and focus arrays
				var errors = [];
				var focus  = [];
				
				// Test inputs against validation
				if (!input_req.test(one.value) || !input_req.test(three.value) || !input_req.test(four.value)) {
					errors.push("metrics_error");
				}
				
				// Focus on specific error input
				if (!input_req.test(one.value)) {
					focus.push("ros");
				}
				if (!input_req.test(three.value)) {
					focus.push("week");
				}
				if (!input_req.test(four.value)) {
					focus.push("bays");
				}
				
				console.log(focus);
				
				// Build data string
				var dataString = { monthly_ros:one.value, days_week:three.value, total_bays:four.value, submit:submit };
				
				console.log(dataString);
				
				// Loop through errors to achieve correct input focus
				if (errors.length > 0 ){
					for (var i=0; i<errors.length; i++){
						document.getElementById(errors[i]).style.display="inline";
					}
					document.getElementById(focus[0]).focus();
					return false;
				} else {
					// AJAX Code To Submit Form.
					$.ajax({
						type: "POST",
						url: "metrics_process.php",
						data: dataString,
						cache: false,
						success: function(returndata){
							if (returndata == "error") {
								document.getElementById("metrics_process_error").style.display="inline";
								//document.getElementById("monthly_ros").focus();
								console.log(returndata);
							} else if (returndata == "login_error") {
								document.getElementById("metrics_login_error").style.display="inline";
								console.log(returndata);
							} else if (returndata == "query_error") {
								document.getElementById("metrics_query_error").style.display="inline";
								console.log(returndata);
							} else {
								console.log(returndata);
								$('#update_currstats_table').html($('#update_currstats_table' , returndata).html())		;
								$('#update_currentshop_table').html($('#update_currentshop_table' , returndata).html())	;
								$('#update_revamp_table').html($('#update_revamp_table' , returndata).html())			;
								$('#update_tput_table').html($('#update_tput_table' , returndata).html())				;
								$('#metrics_success').fadeIn( 300 ).delay( 3500 ).fadeOut( 400 )						;
							}
						}
					});
				}
				event.preventDefault();
			});
			 
			/*-------------------Start of revamp_form AJAX submit--------------------*/
			 
			$("form#revamp_form").submit(function(event) {
				
				// Remove previous submission error
				$(".form_error").hide();
				
				// Get inputs. var four = $total_bays
				var true_L1bays = Number(document.getElementById("true_L1bays").value);
				var true_L2bays = Number(document.getElementById("true_L2bays").value);
				var four  		= Number(document.getElementById("bays").value) 	  ;
				var submit		= "revamp_submit"									  ;
				
				var L1_L2_totalbays = true_L1bays + true_L2bays;
				
				console.log(L1_L2_totalbays);
				console.log(true_L1bays);
				console.log(true_L2bays);
				console.log(submit);
				
				// If sum of L1 and L2 bays is greater than total_bays, issue error
				if (L1_L2_totalbays > four) {
					document.getElementById("totalbays_error").style.display="inline";
					document.getElementById("true_L1bays").focus();
					return false;
				}
				
				// Establish validation rule
				var input_req   = /^[0-9]{1,}$/;
				
				// Initiate error and focus arrays
				var errors = [];
				var focus  = [];
				
				// Test inputs against validation
				if (!input_req.test(true_L1bays) || !input_req.test(true_L2bays)) {
					errors.push("revamp_error");
				}
				
				// Focus on specific error input
				if (!input_req.test(true_L1bays)) {
					focus.push("true_L1bays");
				}
				if (!input_req.test(true_L2bays)) {
					focus.push("true_L2bays");
				}
				
				// Build data string
				var dataString = { true_L1bays:true_L1bays, true_L2bays:true_L2bays, submit:submit };
				
				console.log(dataString);
				
				// Loop through errors to achieve correct input focus
				if (errors.length > 0 ){
					for (var i=0; i<errors.length; i++){
						document.getElementById(errors[i]).style.display="inline";
					}
					document.getElementById(focus[0]).focus();
					return false;
				} else {
					// AJAX Code To Submit Form.
					$.ajax({
						type: "POST",
						url: "metrics_process.php",
						data: dataString,
						cache: false,
						success: function(returndata){
							if (returndata == "error") {
								document.getElementById("revamp_process_error").style.display="inline";
								console.log(returndata);
							} else if (returndata == "login_error") {
								document.getElementById("revamp_login_error").style.display="inline";
								console.log(returndata);
							} else if (returndata == "query_error") {
								document.getElementById("revamp_query_error").style.display="inline";
								console.log(returndata);
							} else {
								$('#update_revamp_table').html($('#update_revamp_table' , returndata).html());
								$('#update_tput_table').html($('#update_tput_table' , returndata).html())	 ;
								$('#revamp_success').fadeIn( 300 ).delay( 3500 ).fadeOut( 400 )				 ;
								//$('input:text').val('');
								console.log(returndata);
							}
						}
					});
				}
				event.preventDefault();
			});			
		});
	</script>
  </head>
  <body>
<div class="wrapper">
<?php
include('templates/menubar_dealer_reports.php');
?>

<div class="row">
	<div class="small-12 medium-10 large-10 columns">
		<h2>Accelerated Metrics -
		
		<span class="main_subtitle1">
			<?php
			if(isset($_SESSION['survey_description'])) { 
				echo constant('ENTITY'). ' '.$_SESSION['dealercode'].'&nbsp;<span class="main_subtitle2" > ('.$currentyear.' &nbsp;'.$_SESSION['survey_description']. ')</span>'; 
			}
			?>
		</span>
		
		</h2>
	</div>
	<div class="small-12 medium-2 large-2 columns">
		<h5 class="print_title"><a href="">Printer Friendly</a></h5><br><br>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
			<?php
			// Standard error/success messages to display
			if (isset($_SESSION['error'])) {
				$num_errors = sizeof($_SESSION['error']);
				for ($i=0; $i < $num_errors; $i++) {
					echo '<h6 class="error">' .$_SESSION['error'][$i]. '</h6>';
				} //end foreach 
				unset($_SESSION['error']);
			}
			if (isset($_SESSION['success'])) {
				$num_success = sizeof($_SESSION['success']);
				for ($i=0; $i < $num_success; $i++) {
					echo '<h6 class="success">' .$_SESSION['success'][$i]. '</h6>';
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
	<div class="small-12 medium-6 large-6 columns">
		<h5 class="table_title">Input Metrics - <?php echo constant('ENTITY').' '.$dealercode;?> </h5>
		<form id="metrics_form" action="#">
		<table class="table_current" style="border: 0;">
			<thead>
				<tr>
					<th style="width: 220px; background-color: #008B8B;">Metric</th>
					<th style="width: 220px; background-color: #008B8B;">Input Value</th>
				</tr>
			</thead>
			<div id="update_metrics_table">
			<tbody>
				<tr style="height: 40px;">
					<td class="revamped_td2"> Avg Monthly ROs </td>
					<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> <input type="text" id="ros" value="<?php echo $monthly_ros; ?>" /> </td>
				</tr>
				<tr style="height: 40px;">
					<td class="revamped_td2" style="background-color: #D8D8D8;"> Days Open Per Week </td>
					<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;">
						<select id="week" class="select_week">
							<option value="<?php echo $days_week;?>"><?php echo $days_week;?></option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
						</select>
					</td>
				</tr>
				<tr style="height: 40px; border-bottom: 1px solid #CCCCCC;">
					<td class="revamped_td2"> Total Shop Bays </td>
					<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> <input type="text" id="bays" value="<?php echo $total_bays; ?>" /> </td>
				</tr>
				<tr style="border-bottom: none;">
					<td style="background-color: #FFFFFF; border-left: none; height: 30px; padding: .077em .625em 0em .625em;">
						<small style="color: blue; font-size: 12px;">*Based off of a standard 8-hr shift</small>
						<small style="color: blue; font-size: 12px;">*Contact the manager for exceptions</small>
					</td>
					<td style="background-color: #FFFFFF; border-right: none; height: 30px; padding: .077em .625em 0em .625em;"><h4 style="margin: 0; padding: 0; text-align: right;">
					<input type="submit" id="metrics_submit" value="Submit" /></h4></td>
				</tr>
				<tr>
					<td style="background-color: #FFFFFF; border-left: none; height: 30px; padding: .077em .625em .077em .625em;">
						<small class="form_error" id="metrics_success" style="font-size: 14px; color: green;">*Metrics updated successfully!</small>
						<small class="form_error" id="metrics_error" style="font-size: 14px; color: red;">*Error: Enter a number</small>
						<small class="form_error" id="metrics_process_error" style="font-size: 14px; color: red;">*Error: A processing error has occurred</small>
						<small class="form_error" id="metrics_login_error" style="font-size: 14px; color: red;">*Error: Session expired. Please log in again!</small>
						<small class="form_error" id="metrics_query_error" style="font-size: 14px; color: red;">*Query error: Please see administrator!</small>
					</td>
					<td style="background-color: #FFFFFF; border-right: none; height: 30px; padding: .077em .625em .077em .625em;">
					</td>
				</tr>
			</tbody>
			</div>
		</table>
		</form>
	</div>
	<div class="small-12 medium-6 large-6 columns">
	<h5 class="table_title">Current Service Stats - <?php echo constant('ENTITY').' '.$dealercode;?> </h5>
		<div id="update_currstats_table">
		<table class="table_current">
			<thead>
				<tr>
					<th style="width: 230px;">Metric</th>
					<th style="width: 230px;">Value</th>
				</tr>
			</thead>
			<tbody>
				<tr style="height: 30px;">
					<td class="revamped_td2"> L1 Eligible %				    </td>
					<td class="revamped_td2"><?php echo $L1percentdemand;?> </td>
				</tr>
				<!-- taken out per Mark O on 4/20/16 
				<tr style="background-color: #D8D8D8; height: 40px;">
					<td class="revamped_td2"> L2 Eligible % 				</td>
					<td class="revamped_td2"><?php //echo $L2percentdemand;?>	</td>
				</tr>
				-->
				<tr style="height: 30px; background-color: #D8D8D8;">
					<td class="revamped_td2"> L1 Vol Per Month / Day 						  </td>
					<td class="revamped_td2"> <?php echo number_format($L1monthly_ros,0).' / '.round($L1daily_ros);?> </td>
				</tr>
				<!--
				<tr style="height: 30px;">
					<td class="revamped_td2"> L2 Vol Per Month / Day						  </td>
					<td class="revamped_td2"> <?php //echo number_format($L2monthly_ros,0).' / '.round($L2daily_ros);?> </td>
				</tr>
				-->
				<tr style="height: 30px;">
					<td class="revamped_td2"> Precise / Actual L1 Bays		</td>
					<td class="revamped_td2"> <?php echo $L1precise_bays.' / '.$L1actual_bays;?>	</td>
				</tr>
				<!--
				<tr style="height: 30px;">
					<td class="revamped_td2"> Precise / Actual L2 Bays		</td>
					<td class="revamped_td2"> <?php //echo $L2precise_bays.' / '.$L2actual_bays;?>	</td>
				</tr>
				-->
			</tbody>
		</table>
		</div>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<hr>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-10 large-10 columns">
		<h2 class="small-only-text-center"> Accelerated Volume Analysis</h2>
	</div>
	<div class="small-12 medium-2 large-2 columns">
		<a href=""> Export Data </a><br><br>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-6 medium-centered large-6 large-centered columns">
		<h5 class="table_revamped_title">Current Shop Structure - Dealer <?php echo $dealercode;?></h5>
		<div id="update_currentshop_table">
		<table class="table_revamped" style="border: 0;">
			<thead>
				<tr>	
					<th class="th1">Category</th>
					<th class="th2">Standard Bays</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="border-left: 1px solid #CCCCCC;"> Max Capacity:										 </td>
					<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> <?php echo $maxvol_htrbay; ?> </td>
				</tr>
				<tr style="background-color: #D8D8D8;">
					<td> Current Bays:									 </td>
					<td class="revamped_td2"> <?php echo $total_bays; ?> </td>
				</tr>
				<tr style="border-bottom: 1px solid #CCCCCC;">
					<td style="border-left: 1px solid #CCCCCC;"> Bay Output:													   </td>
					<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> <?php echo $maxvol_htrbay * $total_bays; ?> </td>
				</tr>
				<tr style="background-color: #FFFFFF;">
					<td></td>
					<td style="color: blue;">Maximum Volume: <?php echo $maxvol_htrbay * $total_bays; ?> </td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12">
		<h5 class="table_revamped_title">Revamped Shop Structure - Dealer <?php echo $dealercode;?> &nbsp; 
			<small class="form_error" id="revamp_error" style="font-size: 14px; color: red;">*Error: Input must be a number</small>
			<small class="form_error" id="revamp_process_error" style="font-size: 14px; color: red;">*Error: A processing error has occurred</small>
			<small class="form_error" id="revamp_success" style="font-size: 14px; color: green;">*Bay totals updated successfully!</small>
			<small class="form_error" id="totalbays_error" style="font-size: 14px; color: red;">*Error: Bays cannot be greater than total shop bays!</small>
			<small class="form_error" id="revamp_login_error" style="font-size: 14px; color: red;">*Error: Session expired. Please log in again!</small>
			<small class="form_error" id="revamp_query_error" style="font-size: 14px; color: red;">*Query error: Please see administrator!</small>
		</h5>
		<form id="revamp_form" action="#">
		<div id="update_revamp_table">
		<table class="responsive table_revamped" style="border: 0;">
			<thead>
				<tr>	
					<th class="th1">Category</th>
					<th class="th4">Level 1 Bays</th>
					<th class="th3">Level 2 Bays</th>
					<th class="th2">High-Tech Bays</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="border-left: 1px solid #CCCCCC;"> Max Capacity: </td>
					<td class="revamped_td2"> <?php echo $maxvol_L1bay; ?> </td>
					<td class="revamped_td2"> <?php echo $maxvol_L2bay; ?> </td>
					<td class="revamped_td2"> <?php echo $maxvol_htrbay; ?> </td>
				</tr>
				<tr style="background-color: #D8D8D8;">
					<td>Recommended Bays:</td>
					<td class="revamped_td2"><input type="text" id="true_L1bays" value="<?php echo $true_L1bays;?>" /></td>
					<td class="revamped_td2"><input type="text" id="true_L2bays" value="<?php echo $true_L2bays;?>" /></td>
					<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"><?php echo ($total_bays - $true_L1bays - $true_L2bays);?></td>
				</tr>
				<tr style="border-bottom: 1px solid #CCCCCC;">
					<td style="border-left: 1px solid #CCCCCC;">Bay Output:</td>
					<td class="revamped_td2"> <?php echo $L1bay_output;?> </td>
					<td class="revamped_td2"> <?php echo $L2bay_output;?> </td>
					<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> <?php echo $htrbay_output;?> </td>
				</tr>
				<tr style="background-color: #FFFFFF;">
					<td></td>
					<td></td>
					<td style="text-align: right;"><input type="submit" id="revamp_submit" value="Submit"/></td>
					<td style="color: blue;">Maximum Volume: <?php echo $express_tput; ?> </td>
				</tr>
			</tbody>
		</table>
		</div>
		</form>
	</div>
</div>
	
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		&nbsp;
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<h5 class="table_throughput">Vehicle Throughput Impact - Dealer <?php echo $dealercode;?></h5>
		<div id="update_tput_table">
		<table class="responsive table_revamped">
			<thead>
				<tr>	
					<th class="th1" style="font-size: 16px;">Category</th>
					<th class="th2_throughput" style="font-size: 16px;">Max Throughput Conv Shop</th>
					<th class="th3_throughput" style="font-size: 16px;">Max Throughput Express Shop</th>
					<th class="th_orange" style="font-size: 16px;">Addtl Volume Day/Month/Yr</th>
				</tr>
			</thead>
			<tbody>
				<tr>				
					<td class="revamped_td2">Total Vehicles</td>
					<td class="revamped_td2"> <?php echo $conv_tput; ?>    </td>
					<td class="revamped_td2"> <?php echo $express_tput; ?> </td>
					<td class="td_green"> 
						<?php echo number_format(($express_tput - $conv_tput),0);?>
						<span class="td_green_span"> &nbsp; / &nbsp; </span>
						<?php echo number_format(($express_tput - $conv_tput)*($days_week * 4.25),0);?>
						<span class="td_green_span"> &nbsp; / &nbsp; </span>
						<?php echo number_format((($express_tput - $conv_tput)*($days_week * 4.25)*12),0);?>
					</td>				
				</tr>
			</tbody>
		</table>
		</div>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
</div>

<div class="push"></div>  	
</div> 

<footer>
	<span class="footer_span"><span class="copyright">&copy; <?php echo date('Y');?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>

	<script src="js/foundation.min.js"></script>
	<script src="js/responsive-tables.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>