<?php
/* ------------------------------------------------------------------------------*
   Program: metrics_process.php

   Purpose: Display Express assessment values and allow user to adjust inputs

   History:
    Date			Description										by
	05/28/2015		Initial design and coding.						Matt Holland
	06/18/2015		Changed weeks per month constant from			Matt Holland
					4.29 to 4.25
 --------------------------------------------------------------------------------*/
 
// Standard system includes
require_once("functions.inc");
include('templates/login_check.php');

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

// Retrieve submit id to determine code route
$submit	= $mysqli->real_escape_string($_POST['submit']);

if ($submit == 'metrics_submit') {
	$monthly_ros = $mysqli->real_escape_string($_POST['monthly_ros']);
	$days_week = $mysqli->real_escape_string($_POST['days_week']);
	$total_bays = $mysqli->real_escape_string($_POST['total_bays']);
	
	$query = "SELECT * FROM express_effect WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
	$result = $mysqli->query($query);
	if (!$result) {
		exit("query_error");
	}
	$rows = $result->num_rows;
	
	if ($rows == 0 ) {
		// If rows == 0 then insert new record
		$query = "INSERT INTO express_effect (dealerID, monthly_ros, days_week, total_bays, surveyindex_id, create_date, userID)
				VALUES ('$dealerID', '$monthly_ros', '$days_week', '$total_bays', '$surveyindex_id', NOW(), '$userID')";
		$result = $mysqli->query($query);
		if (!$result) {
			exit("query_error");
		}
	} elseif ($rows == 1) {
		// If rows == 1, update existing record
		$query = "UPDATE express_effect
				SET monthly_ros = '$monthly_ros', days_week = '$days_week', total_bays = '$total_bays'
				WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
		$result = $mysqli->query($query);
		if (!$result) {
			exit("query_error");
		}
	} else {
		// If rows > 1, you have a problem on your hands :(
		exit("error");
	}
	
	/*--------------------------------------Query of express_effect table-------------------------------------*/
	// Select values from express_effect table
	$query = "SELECT monthly_ros, days_week, total_bays, true_L1bays, true_L2bays
			FROM express_effect WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
			
	$result = $mysqli->query($query);
	if(!$result) { 
		exit("query_error");
	} 
	
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
	
	// If all express_effect table values for perspective dealer are zero, issue message that they have not been set
	if (	$monthly_ros	== 0
		&&	$days_week		== 0
		&&	$total_bays		== 0
		&&  $true_L1bays    == 0
		&&  $true_L2bays    == 0) {
		$_SESSION['error'][] = "*Assessment values for Dealer " .$dealercode. " have not been entered";
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
	$L1percentdemand	= $percent_level1_sd.'%'				;
	$L1monthly_ros 		= ($monthly_ros*$percent_level1_sd)/100	;
	$L2percentdemand	= $percent_level2_sd.'%'				;
	$L2monthly_ros 		= ($monthly_ros*$percent_level2_sd)/100	;
	
	// Compute volume per day and precise bays needed
	if ($days_week == 0) {
		$L1daily_ros 	= 0;
		$L2daily_ros 	= 0;
		$L1precise_bays = 0;
		$L2precise_bays = 0;
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
	
	// Echo Current Service Stats table
	echo'
	<div>
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
						<td class="revamped_td2"> L1 Eligible %		   </td>
						<td class="revamped_td2"> '.$L1percentdemand.' </td>
					</tr>
					<!--
					<tr style="background-color: #D8D8D8; height: 40px;">
						<td class="revamped_td2"> L2 Eligible % 	   </td>
						<td class="revamped_td2"> './*$L2percentdemand.*/' </td>
					</tr>-->
					<tr style="background-color: #D8D8D8; height: 30px;">
						<td class="revamped_td2"> L1 Vol Per Month / Day 			    </td>
						<td class="revamped_td2"> '.number_format($L1monthly_ros,0).' / '.round($L1daily_ros).' </td>
					</tr>
					<!--
					<tr style="background-color: #D8D8D8; height: 30px;">
						<td class="revamped_td2"> L2 Vol Per Month / Day			    </td>
						<td class="revamped_td2"> './*number_format($L2monthly_ros,0).' / '.round($L2daily_ros).*/' </td>
					</tr>-->
					<tr style="height: 30px;">
						<td class="revamped_td2"> Precise / Actual L1 Bays </td>
						<td class="revamped_td2"> '.$L1precise_bays.' / '.$L1actual_bays.' </td>
					</tr>
					<!--
					<tr style="background-color: #D8D8D8; height: 30px;">
						<td class="revamped_td2"> Precise / Actual L2 Bays </td>
						<td class="revamped_td2"> './*$L2precise_bays.' / '.$L2actual_bays.*/' </td>
					</tr>-->
				</tbody>
			</table>
		</div>
		<div id="update_currentshop_table">
			<table class="responsive table_revamped" style="border: 0;">
				<thead>
					<tr>	
						<th class="th1">Category</th>
						<th class="th2">Standard Bays</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border-left: 1px solid #CCCCCC;"> Max Capacity: </td>
						<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> '.$maxvol_htrbay.' </td>
					</tr>
				<tr style="background-color: #D8D8D8;">
					<td> Current Bays: </td>
					<td class="revamped_td2"> '.$total_bays.' </td>
				</tr>
				<tr style="border-bottom: 1px solid #CCCCCC;">
					<td style="border-left: 1px solid #CCCCCC;"> Bay Output: </td>
					<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> '.($maxvol_htrbay * $total_bays).' </td>
				</tr>
				<tr>
					<td></td>
					<td style="color: blue;">Maximum Volume: '.($maxvol_htrbay * $total_bays).' </td>
				</tr>
				</tbody>
			</table>
		</div>
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
						<td class="revamped_td2"> '.$maxvol_L1bay.' </td>
						<td class="revamped_td2"> '.$maxvol_L2bay.' </td>
						<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> '.$maxvol_htrbay.' </td>
					</tr>
					<tr style="background-color: #D8D8D8;">
						<td>Recommended Bays:</td>
						<td class="revamped_td2"><input type="text" id="true_L1bays" value='.$true_L1bays.' /></td>
						<td class="revamped_td2"><input type="text" id="true_L2bays" value='.$true_L2bays.' /></td>
						<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> '.($total_bays - $true_L1bays - $true_L2bays).' </td>
					</tr>
					<tr style="border-bottom: 1px solid #CCCCCC;">
						<td style="border-left: 1px solid #CCCCCC;">Bay Output:</td>
						<td class="revamped_td2"> '.$L1bay_output.' </td>
						<td class="revamped_td2"> '.$L2bay_output.' </td>
						<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> '.$htrbay_output.' </td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style="text-align: right;"><input type="submit" id="revamp_submit" value="Submit"/></td>
						<td style="color: blue;">Maximum Volume: '.$express_tput.' </td>
					</tr>
				</tbody>
			</table>
		</div>
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
						<td class="revamped_td2"> '.$conv_tput.' </td>
						<td class="revamped_td2"> '.$express_tput.' </td>
						<td class="td_green"> 
							'.number_format(($express_tput - $conv_tput),0).'
							<span class="td_green_span"> &nbsp; / &nbsp; </span>
							'.number_format(($express_tput - $conv_tput)*($days_week * 4.25),0).'
							<span class="td_green_span"> &nbsp; / &nbsp; </span>
							'.number_format((($express_tput - $conv_tput)*($days_week * 4.25)*12),0).'
						</td>				
					</tr>
				</tbody>
			</table>
		</div>
	</div>';
} elseif ($submit == 'revamp_submit') {
	// This is the route taken if the revamp table submit button was pushed
	$true_L1bays = $mysqli->real_escape_string($_POST['true_L1bays']);
	$true_L2bays = $mysqli->real_escape_string($_POST['true_L2bays']);
	
	$query = "SELECT * FROM express_effect WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
	$result = $mysqli->query($query);
	if (!$result) {
		exit("query_error");
	}
	$rows = $result->num_rows;
	
	if ($rows == 0 ) {
		// If rows == 0 then insert new record
		$query = "INSERT INTO express_effect (dealerID, true_L1bays, true_L2bays, surveyindex_id, create_date, userID)
				  VALUES ('$dealerID', '$true_L1bays', '$true_L2bays', '$surveyindex_id', NOW(), '$userID')";
		$result = $mysqli->query($query);
		if (!$result) {
			exit("query_error");
		} 
	} elseif ($rows == 1) {
		// If rows == 1, update existing record
		$query = "UPDATE express_effect
				SET true_L1bays = '$true_L1bays', true_L2bays = '$true_L2bays'
				WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
		$result = $mysqli->query($query);
		if (!$result) {
			exit("query_error");
		}
	} else {
		// If rows > 1, you have a problem on your hands :(
		exit("error");
	}
	
	/*--------------------------------------Query of express_effect table-------------------------------------*/
	// Select values from express_effect table for update of revamp and throughput tables
	$query = "SELECT total_bays, days_week, true_L1bays, true_L2bays
			FROM express_effect WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
			
	$result = $mysqli->query($query);
	if(!$result) { 
		exit("query_error");
	} 
	
	$num_rows = $result->num_rows;
	
	// Check to see if query returned rows; if no rows then issue note and set values to zero so that they are not null
	if($num_rows == 0) {
		$total_bays	 	= 0;
		$days_week		= 0;
		$true_L1bays    = 0;
		$true_L2bays    = 0;
	} else {
		// Set variable values from query for use below
		$express_value = $result->fetch_assoc()			;
		$total_bays	 	= $express_value['total_bays']	;
		$days_week		= $express_value['days_week']	;
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
	
	// Compute bay outputs
	$L1bay_output = ($maxvol_L1bay * $true_L1bays);
	$L2bay_output = ($maxvol_L2bay * $true_L2bays);
	$htrbay_output= ($maxvol_htrbay * ($total_bays - $true_L1bays - $true_L2bays));
	
	// Compute total shop throughputs
	$conv_tput	  = ($maxvol_htrbay * $total_bays);
	$express_tput = ($L1bay_output + $L2bay_output + $htrbay_output);

	// Echo revamped table
	echo'
	<div>
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
						<td class="revamped_td2"> '.$maxvol_L1bay.' </td>
						<td class="revamped_td2"> '.$maxvol_L2bay.' </td>
						<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> '.$maxvol_htrbay.' </td>
					</tr>
					<tr style="background-color: #D8D8D8;">
						<td>Recommended Bays:</td>
						<td class="revamped_td2"><input type="text" id="true_L1bays" value='.$true_L1bays.' /></td>
						<td class="revamped_td2"><input type="text" id="true_L2bays" value='.$true_L2bays.' /></td>
						<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> '.($total_bays - $true_L1bays - $true_L2bays).' </td>
					</tr>
					<tr style="border-bottom: 1px solid #CCCCCC;">
						<td style="border-left: 1px solid #CCCCCC;">Bay Output:</td>
						<td class="revamped_td2"> '.$L1bay_output.' </td>
						<td class="revamped_td2"> '.$L2bay_output.' </td>
						<td class="revamped_td2" style="border-right: 1px solid #CCCCCC;"> '.$htrbay_output.' </td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style="text-align: right;"><input type="submit" id="revamp_submit" value="Submit"/></td>
						<td style="color: blue;">Maximum Volume: '.$express_tput.' </td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="update_tput_table">
			<table class="responsive table_revamped">
				<thead>
					<tr>	
						<th class="th1">Category</th>
						<th class="th2_throughput" style="font-size: 16px;">Max Throughput Conv Shop</th>
						<th class="th3_throughput" style="font-size: 16px;">Max Throughput Express Shop</th>
						<th class="th_orange" style="font-size: 16px;">Addtl Volume Day/Month/Yr</th>
					</tr>
				</thead>
				<tbody>
					<tr>				
						<td class="revamped_td2" style="height: 20px; border-left: 1px solid #CCCCCC; text-align: center; font-size: 17px; width: 250px;">Total Vehicles</td>
						<td class="revamped_td2"> '.$conv_tput.' </td>
						<td class="revamped_td2"> '.$express_tput.' </td>
						<td class="td_green"> 
							'.number_format(($express_tput - $conv_tput),0).'
							<span class="td_green_span"> &nbsp; / &nbsp; </span>
							'.number_format(($express_tput - $conv_tput)*($days_week * 4.25),0).'
							<span class="td_green_span"> &nbsp; / &nbsp; </span>
							'.number_format((($express_tput - $conv_tput)*($days_week * 4.25)*12),0).'
						</td>				
					</tr>
				</tbody>
			</table>
		</div>	
	</div>';
} else {
	echo 'error';
}
?>