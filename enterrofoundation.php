<?php

/*-----------------------------------------------------------------------------------------*
   Program: enterrofoundation.php - acura

   Purpose: Entry of new repair orders, and update of repair orders.  If
			update is selected both update and delete can be performed in
			the module linked to, enterrofoundation_update.php

	History:
    Date		Description											by
	04/23/2014	Modify for GMC format								Matt Holland
	05/06/2014	Use Foundations to make pretty						Matt Holland
	04/07/2014	Add comments and services in table display			Matt Holland
	05/07/2014	Get repair order count display working.				Matt Holland
	05/07/2014	Fixed dilemma: ronumber validation.					Matt Holland
	07/18/2014	Integrated responsive table design					Matt Holland
	07/21/2014	Validate services and addsvc, add function.			Matt Holland
	07/22/2014  Clean up error handling catch empty fields.			Matt Holland
	07/23/2014	Integrate table sort								Matt Holland
	07/24/2014	Fix erroneous error when entering from menu.		Matt Holland
	07/24/2014	Convert to object oriented MYSQLI.					Matt Holland
	07/28/2014	Remove all refs to formAttempt.						Matt Holland
	08/05/2014	Don't close results on services if no svcs.			Matt Holland
	08/05/2014	add method-"post" to dealercodeswitch form			Matt Holland
	09/12/2014	Isolate add functionality in new module =			Matt Holland
	09/12/2014	enterrofoundationadd_process.php.					Matt Holland
	09/12/2014	Fix $rows2 var name misname at program exit.		Matt Holland
	10/15/2014	Update model year selection with dynamic data		Matt Holland
	10/16/2014	Update processing to include model_age data			Matt Holland
	10/24/2014	Add survey selection to menubar						Matt Holland
	11/18/2014	Add dynamic form generation (arrays) for checkboxes	Matt Holland
	11/21/2014	Change die back to enterrofoundationadd_process.	Matt Holland
	11/21/2014	Remove top php block for efficiency.				Matt Holland
	12/11/2014	Converted all html lines to echos					Matt Holland
	12/11/2014	Added constant function for car image file			Matt Holland
	12/22/2014	Changed RO table to accurately reflect null values	Matt Holland
				as N/A
	01/09/2015	Added sticky footer									Matt Holland
	01/14/2015	Edited $i rows for checkbox display to make
				room for new service 'Recall' and removed space 	Matt Holland
				between checkbox divs
	01/16/2015	Added to code:  check to see if dealerID exists
				before inserting into repairorder					Matt Holland
	01/19/2015	Added to code:  check to see if survey is locked
				for $dealerID and $surveyindex_id					Matt Holland
	01/27/2015	Added dataTables integration for advanced
				searching and pagination							Matt Holland
	01/28/2015	Added 'Lock Survey' button functionality to form	Matt Holland
				Added 'Show/Hide' form link using jQuery			Matt Holland
	02/05/2015	Added sticky form elements (not for checkboxes)		Matt Holland
				Added webpage icon to header (for browser tab)
	02/26/2015	Altered $i increment to add new 'Differential' to	Matt Holland
				basic services list and 'Brake Flush' to 'Other
				Services' list.  Changed 'All Flushes' to 'Other
				Flush'.
	03/06/2015	Added sticky form $_SESSION['enterro_comment'] to 	Matt Holland
				form (left out accidentally).
				Altered $currentyear processing logic
	03/09/2015	Added jQuery to confirm survey year selection if	Matt Holland
				$month is greater than 8. Notifies user that they
				should select the next year to account for new
				models
	03/13/2015	Altered RO success message:  added RO success		Matt Holland
				message to show directly below car picture. Defined
				the session variable as $_SESSION['ro_success']
	03/23/2015	Altered $i increments to make room for 'Other Svc 1'Matt Holland
				and 'Other Svc 2' additional items.
				Moved 'Survey Start Year' selection dropdown into
				main body of page (moved out of menu).
				Added 'Survey Notes' modal dropdown to menu bar.
	04/22/2015	Edited RO table to only show last 5 entries.		Matt Holland
				(for better site performance)
				Removed search bar and paginatino from RO table
 	05/13/2015	Added javascript instructions to check/uncheck	    Matt Holland
				both checkboxes when 'Add' is checked/unchecked
	06/09/2015	Integrated AJAX form submit!! 						Matt Holland
				Altered error msgs, took out 'data abide',
				Added new custom js file for AJAX processing:
				custom_enterrofoundation.js
	08/08/2016	Integrated spinner processor into page 				Matt Holland
*-----------------------------------------------------------------------------------------*/

// Required system includes
require_once("functions.inc");
include('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

/*-----------------------------------------------------------------------------------------*/
// Check to make sure that $dealercode exists (could be the wrong global due to multiple open systems in one browser sharing the $_SESSION['dealerID'] global variable)
// Issue warning if dealer does not exist
$query = "SELECT dealercode FROM dealer WHERE dealerID = $dealerID and dealercode = $dealercode";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Dealer query failed.  See administrator.';
} else {
	$rows = $result->num_rows;
	if ($rows == 0) {
		$_SESSION['error'][] = constant('ENTITY').' '.$dealercode.' does not exist.  Please select another '.constant('ENTITYLCASE');
	}
}
/*-----------------------------------------------------------------------------------------*/
// Survey selection processing

// Generate modal menu items from table
$query = "SELECT surveyindex_id, survey_description FROM survey_index";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Survey query failed.  See administrator.';
} else {
	$survey = array();
	$index = 0;
	while ($value = $result->fetch_assoc()) {
		$survey[$index]['surveyindex_id'] 	  = $value['surveyindex_id'];
		$survey[$index]['survey_description'] = $value['survey_description'];
		$index += 1;
	}
}

// Query surveys table to see if $dealerID has any records in table
$query = "SELECT * FROM surveys WHERE dealerID = $dealerID";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Surveys query failed.  See administrator.';
} else {
	$rows = $result->num_rows;
	// If there are no rows, INSERT Level 1 Assessment entry into survey table for $dealerID
	if ($rows == 0) {
		$query = "INSERT INTO surveys (dealerID, surveyindex_id, create_date, userID)
					VALUES ('$dealerID', 1 , NOW(), '$userID')";
		$result = $mysqli->query($query);
		if (!$result) {
			exit("query_error");
		}
		// Make magic variable first item from survey_index table
		$query = "SELECT surveys.surveyindex_id, survey_index.survey_description FROM survey_index
				INNER JOIN surveys ON surveys.surveyindex_id = survey_index.surveyindex_id
				WHERE surveys.surveyindex_id = 1 AND surveys.dealerID = $dealerID";
		$result = $mysqli->query($query);
		if (!$result) {
			exit("query_error");
		}
		$survey_value = $result->fetch_assoc();
		$surveyindex_id		= $survey_value['surveyindex_id'];
		$survey_description	= $survey_value['survey_description'];
		// Save as magic variables
		$_SESSION['surveyindex_id'] 	= $surveyindex_id;
		$_SESSION['survey_description'] = $survey_description;
		// echo '$survey_description: 	' .$survey_description;
		// echo '$surveyindex_id: 		' .$surveyindex_id	  ;
	// If $dealerID is in surveys table, check to see if survey globals are set
	} else {
		// Check to see if dealer $dealerID has a survey for the global type currently set.  If not, unset global and reset as first row
		if (isset($_SESSION['surveyindex_id']) && isset($_SESSION['survey_description'])) {
			$surveyindex_id = $_SESSION['surveyindex_id'];
			$query = "SELECT surveys.surveyindex_id, survey_index.survey_description FROM survey_index
					INNER JOIN surveys ON surveys.surveyindex_id = survey_index.surveyindex_id
					WHERE surveys.dealerID = $dealerID AND surveys.surveyindex_id = $surveyindex_id";
			$result = $mysqli->query($query);
			if (!$result) {
				exit("query_error");
			}
			$rows = $result->num_rows;
			if ($rows == 0) {
			// unset globals and reset as first row in query
				unset ($_SESSION['surveyindex_id']);
				unset ($_SESSION['survey_description']);
				$query = "SELECT surveys.surveyindex_id, survey_index.survey_description FROM survey_index
					INNER JOIN surveys ON surveys.surveyindex_id = survey_index.surveyindex_id
					WHERE surveys.dealerID = $dealerID";
				$result = $mysqli->query($query);
				if (!$result) {
					exit("query_error");
				}
				$survey_value = $result->fetch_assoc();
				$surveyindex_id		= $survey_value['surveyindex_id'];
				$survey_description = $survey_value['survey_description'];
				// Save as magic variables
				$_SESSION['surveyindex_id'] = $surveyindex_id;
				$_SESSION['survey_description'] = $survey_description;
			}
		} else {
			// If survey globals aren't set then set magic variable as first item from survey_index table
			$query = "SELECT surveys.surveyindex_id, survey_index.survey_description FROM survey_index
					INNER JOIN surveys ON surveys.surveyindex_id = survey_index.surveyindex_id
					WHERE surveys.dealerID = $dealerID";
			$result = $mysqli->query($query);
			if (!$result) {
				exit("query_error");
			}
			$survey_value = $result->fetch_assoc();
			$surveyindex_id		= $survey_value['surveyindex_id'];
			$survey_description = $survey_value['survey_description'];
			// Save as magic variables
			$_SESSION['surveyindex_id'] 	= $surveyindex_id;
			$_SESSION['survey_description'] = $survey_description;
		} // End $_SESSION else statement
	} // End $rows else statement
} // End !$result else statement
/*-----------------------------------------------------------------------------------------*/
// yearmodel selection menu processing

// Get survey start year from 'surveys' table - If set to zero (which happens if and when the above survey processing inserts entry into surveys table), set $currentyear to server year
$query = "SELECT survey_start_yearmodelID FROM surveys WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Surveys query error.  See administrator.';
} else {
	$lookup = $result->fetch_assoc();
	$survey_start_test = $lookup['survey_start_yearmodelID'];
	$currentyearID = $lookup['survey_start_yearmodelID'];
	if ($currentyearID != 0) {
		// If not set to zero then was previously set by user. Set $currentyearID to survey_start_yearmodelID listed in table
		$query = "SELECT modelyear FROM yearmodel WHERE yearmodelID = $currentyearID";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = 'Model Year query failed.  See administrator.';
		} else {
			// Fetch actual modelyear and set $currentyear to result
			$lookup = $result->fetch_assoc();
			$currentyear = $lookup['modelyear'];
		}
	} else {
		$_SESSION['survey_start_error'] = '*Please select the survey start year before proceeding';
		// If $currentyearID = 0, set $currentyear to server year
		$currentyear = date('Y');
		// If it is after August, introduce next year selection by increasing $currentyear by 1
		$month = date('m');
		if ($month > 7) {
			$currentyear = $currentyear+1;
		}
	}
	//echo '$currentyear: '.$currentyear. '<br>';

	// Find $currentyear in yearmodel table and fetch descending results for menu dropdown.  Had to add 1 to $currentyear because query would only return 1 year less than $currentyear
	$query = "SELECT yearmodelID, modelyear  FROM yearmodel WHERE modelyear BETWEEN 2000 AND $currentyear+1
			ORDER BY yearmodelID DESC";

	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = 'Model year query failed.  See administrator.';
	} else {
		$yearmodelrows = $result->num_rows;

		// This is to just test the above query to make sure it started on the correct year
		$ym_lookup = $result->fetch_assoc();
		$ymid_select_start = $ym_lookup['yearmodelID'];
		//echo '$ymid_select_start: ' .$ymid_select_start. '<br>';

		$ymrow = array();
		$i = 0;
		// Execute while loop to fetch results so can echo inside of <select> dropdown
		while ($value = $result->fetch_assoc()) {
			$ymrow[$i]['yearmodelID'] = $value['yearmodelID']	;
			$ymrow[$i]['modelyear'] 	= $value['modelyear']	;
			//echo $ymrow[$i]['yearmodelID'].',';
			//echo $ymrow[$i]['modelyear'].',';
			//echo '<br>';
			$i += 1;
		}
	}
} // End $currentyear surveys query

/*-----------------------------------------------------------------------------------------*/
// Mileage Spread selection menu processing

// Retrieve id's and labels from table
$query = "SELECT mileagespreadID, carmileage FROM mileagespread";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Mileage query failed.  See administrator.';
} else {
	$mileagerows   = $result->num_rows;
	$msrow = array(array());
	$i = 0;
	while ($mileagevalues = $result->fetch_assoc()) {
		$msrow[$i]['mileagespreadID'] = $mileagevalues['mileagespreadID']	;
		$msrow[$i]['carmileage']	    = $mileagevalues['carmileage']		;
		$i += 1;
	}
}
/*-----------------------------------------------------------------------------------------*/
// Survey lock test - check to see if survey is locked for $dealerID and $surveyindex_id.  Issue user message if so
$query = "SELECT locked FROM repairorder WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Lock query failed.  See administrator.';
} else {
	$lookup = $result->fetch_assoc();
	$survey_lock = $lookup['locked'];
	if ($survey_lock == 1) {
		$_SESSION['error'][] = "Note: This survey has been locked.";
	}
}
/*-----------------------------------------------------------------------------------------*/
// Get total RO count for $dealerID, $surveyindex_id
$query = "SELECT * FROM repairorder WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'RO count query failed.  See administrator.';
} else {
	$repairorderrows = $result->num_rows;
	$_SESSION['repairorderrows'] = $repairorderrows;
}
/*-----------------------------------------------------------------------------------------*/
//Checkboxes processing

// Query services table to retrieve values and labels for all checkboxes
$query = "SELECT serviceID, service_nickname FROM services
		  WHERE rosurvey_svc = 1
		  ORDER BY servicesort ASC";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'A program error has occurred.  Please see administrator.';
} else {
	$svcarray = array(array());
	$index = 0;
	while ($checkboxlookup = $result->fetch_assoc()) {
		$svcarray[$index]['serviceID'] = $checkboxlookup['serviceID'];
		$svcarray[$index]['service_nickname'] = $checkboxlookup['service_nickname'];
		$index += 1;
	}
}
/*-----------------------------------------------*
	UPDATE REQUEST ??
    Save ronumber in global and transfer control
 *-----------------------------------------------*/
if (isset($_POST['update'])) {
	if (isset($_SESSION['error']))
		unset ($_SESSION['error']);
	$_SESSION['update'] = TRUE;
	$updateronumber = $_POST['updateronumber'];
	$_SESSION['updateronumber'] = $updateronumber;

	die (header("Location: enterrofoundationupdate_process.php"));
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
    <link rel="stylesheet" href="css/responsive-tables.css" media="screen" />
	<!--<link rel="stylesheet" href="css/tablesort.theme.blue.enterrofoundation.css" />-->
	<link rel="stylesheet" href="css/sticky_footer.css" />
	<link rel="stylesheet" type="text/css" href="css/dataTables.foundation.css" />
	<link rel="stylesheet" href="css/spinner.css" />
	<style>
		@media (min-width: 40.063em) {
			.collapse_select {
				margin-top: .42rem;
				width: 130px;
			}
			.collapse_submit {
				margin-left: 18px;
			}
		}

		.collapse_select {
			height: 2rem;
		}

		.collapse_submit {
			height: 2rem !important;
		}
		.rosurvey_title {
			color: #00008B;
			font-size: 23px;
		}
		.rosurvey_subtitle {
			color: gray;
			font-size: 15px;
		}
		@media (min-width: 40.063em) {
			.total_ro_count {
				text-align: right;
			}
		}
		@media (max-width: 40.063em) {
			.hide_button {
				margin-left: 13px;
			}
		}
		.hide_button {
			font-size: 14px;
			font-weight: bold;
		}
		.error_msg {
			color: red;
			font-weight: bold;
			font-size: 15px;"
		}
		.success_msg {
			color: #228B22;
			font-weight: bold;
			font-size: 15px;"
		}
		.form_error {
			display: none;
		}
		.ro_success {
			display: none;
		}
		.panel input {
			cursor: pointer;
		}
		input.placeholder {
			color: #aaa;
		}
		textarea.placeholder {
			color: #aaa;
		}
		label {
            cursor: default;
            font-size: 17px;
        }
		.service_checkbox {
			display: inline-block;
			cursor: pointer;
		}
		table.original {
			font-family: helvetica;
			font-size: 8pt;
			border-collapse: collapse;
			margin-right: auto;
			margin-left: auto;
		}
		table.original thead tr th {
			font-size: 10pt;
			text-align: center;
			border-left: 1px solid #CCCCCC;
			border-bottom: 1px solid #CCCCCC;
			width: 150px;
			height: 35px;
		}
		table.original tbody td {
			color: #3D3D3D;
			padding: 4px;
			height: 60px;
			text-align: center;
			border-bottom: 1px solid #CCCCCC;
		}
		table.original form {
			padding: 0px;
			margin-bottom: 0px;
		}
		table.original form input {
			margin: 0rem;
		}
		.submit_td {
			border-right: 1px solid #CCCCCC;
		}
		@media (min-width: 40.063em) {
			table.original thead tr {
				background-image: url(css/bg.gif);
			}
		}
		table.original thead tr {
			background-repeat: no-repeat;
			background-position: center right;
		}
	</style>
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
	<!--<script src="js/tablesorter.js"></script>-->
	<script src="js/custom_enterrofoundation.js"></script>
</head>
<body>
<div class="wrapper">

<?php
include('templates/menubar_enterrofoundation.php');
?>
<div class="loader_div"></div> <!-- for the spinner -->
<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-8 large-9 columns">
				<h2>RO Survey
					<span class="rosurvey_title">
					<?php
					if(isset($_SESSION['survey_description']) && $currentyearID > 0) {
						echo ' - '.constant('ENTITY'). ' '.$_SESSION['dealercode'].'&nbsp;<span class="rosurvey_subtitle" > ('.$currentyear.' &nbsp;'.$_SESSION['survey_description']. ')</span>';
					} else {
						echo ' - '.constant('ENTITY'). ' '.$_SESSION['dealercode'].'&nbsp;<span class="rosurvey_subtitle" > ('.$_SESSION['survey_description']. ')</span>';
					}
					?>
					</span>
				</h2>
			</div>
			<div class="small-12 medium-4 large-3 columns">
				<h5 class="total_ro_count"><div id="update_div2"><span class="total_ro_count_span">Total ROs: <?php echo $_SESSION['repairorderrows'];?></span></div><a class="hide_button" id="hide_button" data-text-swap="Show Form">Hide Form</a></h5>
			</div>
		</div>
	</div>
</div>
<?php
	if ($survey_start_test == 0) {
		// Show the 'Select Year' dropdown only if survey_start_test == 0
		echo'
		<form style="margin-bottom: 0px;" method="POST" action="survey_startyear_process.php">
			<div class="row">
				<div class="small-12 medium-3 large-3 columns">
					<div class="row collapse">
						<div class="small-10 medium-10 large-10 columns">
							<select id="survey_start_yearmodelID" name="survey_start_yearmodelID">
								<option value=""> Select Survey Year </option>';
								for ($i = 0; $i < $yearmodelrows-13; $i++) {
									echo'<option value= '.$ymrow[$i]['yearmodelID'].'>' .$ymrow[$i]['modelyear']. '</option><br>';
								}
							echo'
							</select>
						</div>
						<div class="small-2 medium-2 large-2 columns">
							<input type="submit" value="Go" id="survey_start_yearmodelID_submit" name="survey_start_yearmodelID_submit" class="button postfix" >
						</div>
					</div>
				</div>';
				if (isset($_SESSION['survey_start_error'])) {
					echo'
					<div class="medium-9 large-9 columns">
						<h5 class="error_msg">' .$_SESSION['survey_start_error'].  '</h5>
					</div>';
					unset($_SESSION['survey_start_error']);
				} //end if
			echo'
			</div>
		</form>';
	}
?>
<div id="update_div5">
	<div class="row">
		<div class="small-12 medium-7 large-7 columns">
			<?php
			// Echo error messages
			if (isset($_SESSION['error'])) {
				$_SESSION['update'] = FALSE;
				foreach ($_SESSION['error'] as $error) {
					echo '<h5 class="error_msg">' .$error.  '</h5>';
				}
				unset($_SESSION['error']);
			}
			// Echo success messages
			if (isset($_SESSION['success'])) {
				$_SESSION['update'] = FALSE;
				foreach ($_SESSION['success'] as $error) {
					echo '<h5 class="success_msg">' .$error. '</h5>';
				}
				unset($_SESSION['success']);
			}
			?>
		</div>
	</div>
</div>
<div class="small-12 medium-12 large-12 columns">
	<div class="row">
		<p> </p>
	</div>
</div>
<div id="hide">
<form method="POST" id="service_form" action="#">
<input type="hidden" name="submitted" value="true" />
<div class="row">
	<div class="small-12 large-4 columns">
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<p><img src="<?php echo constant('PIC_ENTERRO');?>"></p>
			</div>
			<div class="small-12 medium-12 large-12 columns">
				<div id="update_div1"></div>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<div class="number-field">
					<label>RO Number
							<small class="form_error" style="color: red; font-size: 13px;" id="ro_error">*Enter a valid RO number</small>
							<small class="form_error" style="color: red; font-size: 13px;" id="error_login">*Error: You are no longer logged in!</small>
							<small class="form_error" style="color: red; font-size: 13px;" id="error_query">*Query error!  Please see administrator</small>
							<small class="form_error" style="color: red; font-size: 13px;" id="error_ro_dupe">*Error: Repair order already exists!</small>
							<small class="form_error" style="color: red; font-size: 13px;" id="error_survey_lock">*Survey is locked! Entry denied.</small>
							<small class="form_error" style="color: red; font-size: 13px;" id="error_survey_startyear">*Error: Please select the start year!</small>
							<small class="form_error" style="color: red; font-size: 13px;" id="error_insert">*Error: Unable to enter order!</small>
							<small class="ro_success" style="color: green; font-size: 13px;" id="ro_success">*Repair order was added!</small>
					<input class="text_input_error" type="text" id="ronumber" name="ronumber" placeholder="Enter Repair Order Number" autofocus>
					</label>
				</div>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Year Model <small class="form_error" style="color: red; font-size: 13px;" id="year_error">*Please select a year</small>
					<select id="yearmodelID" name="yearmodelID">
						<option value="">Select...</option>
						<?php
						for ($i = 0; $i < $yearmodelrows-1; $i++) {
							echo'<option value= '.$ymrow[$i]['yearmodelID'].'>' .$ymrow[$i]['modelyear']. '</option><br>';
						}
						?>
					</select>
				</label>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Mileage Spread <small class="form_error" style="color: red; font-size: 13px;" id="mileage_error">*Please select the mileage</small>
					<select id="mileagespreadID" name="mileagespreadID">
						<option value="">Select...</option>
						<?php
						for ($i = 0; $i < $mileagerows; $i++) {
							echo '<option value= '.$msrow[$i]['mileagespreadID'].'>' .$msrow[$i]['carmileage']. '</option><br>';
						}
						?>
					</select>
				</label>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Single Issue? <small class="form_error" style="color: red; font-size: 13px;" id="single_error">*Please select an option</small>
					<select id="singleissue" name="singleissue">
						<option value="">Select...</option>
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</label>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Labor <small class="form_error" style="color: red; font-size: 13px;" id="labor_error">*Enter a valid dollar amount</small>
					<input type="text" id="labor" name="labor" placeholder="Enter Dollar Amount">
				</label>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Parts <small class="form_error" style="color: red; font-size: 13px;" id="parts_error">Enter a valid dollar amount</small>
					<input type="text" id="parts" name="parts" placeholder="Enter Dollar Amount">
				</label>
			</div>
		</div>
	</div>
	<div class="small-12 medium-12 large-8 columns">
	<h5>Basic Services <span style="color: blue; font-size: 15px;"> &nbsp; *Note: Select 'Add' to activate both boxes </span>
					   <small class="form_error" style="color: red; font-size: 14px;" id="service_error">*Please select a service</small></h5>
		<div class="panel" style="padding: 1.45rem 1.5rem .75rem 0rem;">
			<div class="row">
				<div class="small-2 medium-1 large-1 columns">
					<p>   </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
					<?php
					for ($i=0; $i<5; $i++) {
						echo'<label class="service_checkbox"><input type="checkbox" id="servicebox[]" name="servicebox[]" onclick="check_svcboxes('.$i.');" value='.$svcarray[$i]['serviceID']. '> '.$svcarray[$i]['service_nickname']. '</label><br>';
					}
					?>
				</div>
				<div class="small-4 medium-2 large-2 columns">
					<?php
					for ($i=0; $i<5; $i++) {
						echo'<label class="service_checkbox"><input type="checkbox" id="addsvc[]" name="addsvc[]" onclick="check_addboxes('.$i.');" value='.$svcarray[$i]['serviceID'].'> Add </label> <br>';
					}
					?>
				</div>
				<div class="small-2 medium-1 large-1 columns">
					<p>   </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
					<?php
					for ($i=5; $i<9; $i++) {
						echo'<label class="service_checkbox"><input type="checkbox" id="servicebox[]" name="servicebox[]" onclick="check_svcboxes('.$i.');" value='.$svcarray[$i]['serviceID']. '> '.$svcarray[$i]['service_nickname']. '</label> <br>';
					}
					?>
				</div>
				<div class="small-4 medium-2 large-2 columns">
					<?php
					for ($i=5; $i<9; $i++) {
						echo'<label class="service_checkbox"><input type="checkbox" id="addsvc[]" name="addsvc[]" onclick="check_addboxes('.$i.');" value='.$svcarray[$i]['serviceID'].'> Add </label> <br>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="small-12 medium-12 large-8 columns">
	<h5>Other Services</h5>
		<div class="panel" style="padding: 1.45rem 1.5rem .75rem 0rem;">
			<div class="row">
				<div class="small-2 medium-1 large-1 columns">
					<p>  </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
					<?php
					for ($i=9; $i<18; $i++) {
					echo'<label class="service_checkbox"> <input type="checkbox" id="servicebox[]" name="servicebox[]" onclick="check_svcboxes('.$i.');" value='.$svcarray[$i]['serviceID']. '>  '.$svcarray[$i]['service_nickname']. '</label> <br>';
					}
					?>
				</div >
				<div class="small-4 medium-2 large-2 columns">
					<?php
					for ($i=9; $i<18; $i++) {
					echo'<label class="service_checkbox"> <input type="checkbox" id="addsvc[]" name="addsvc[]" onclick="check_addboxes('.$i.');" value='.$svcarray[$i]['serviceID'].'>  Add </label> <br>';
					}
					?>
				</div>
				<div class="small-2 medium-1 large-1 columns">
					<p>   </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
					<?php
					for ($i=18; $i<27; $i++) {
					echo'<label class="service_checkbox"><input type="checkbox" id="servicebox[]" name="servicebox[]" onclick="check_svcboxes('.$i.');" value='.$svcarray[$i]['serviceID']. '>  '.$svcarray[$i]['service_nickname']. '</label> <br>';
					}
					?>
				</div>
				<div class="small-4 medium-2 large-2 columns">
					<?php
					for ($i=18; $i<27; $i++) {
					echo'<label class="service_checkbox"><input type="checkbox" id="addsvc[]" name="addsvc[]" onclick="check_addboxes('.$i.');" value='.$svcarray[$i]['serviceID'].'>  Add </label> <br>';
					}
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<div class="row">
					<div class="small-12 medium-9 large-8 columns">
						<textarea id="comment" name="comment" placeholder="Any Comments?"><?php if(isset($_SESSION['enterro_comment'])) { echo $_SESSION['enterro_comment'];}?></textarea>
					</div>
					<div class="small-12 medium-3 large-4 columns">
						<input id="submit" type="submit" name="submit" value="Submit Repair Order" class="small button radius">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
</div> <!-- end div 'hide' -->
<div class="row">
	<div class="medium-12 large-12 columns">
	<hr>
		<div id="update_div4">
		<div class="row">
			<div class="small-12 medium-10 large-10 columns">
				<div id="update_div4">
					<p>Total Repair Orders: <?php echo $_SESSION['repairorderrows'];?><span style="color: blue; font-size: 13px;"> (Showing last 5 entries)</span><br>
					<a href="viewall_ros.php">View all entries</a></p>
				</div>
			</div>
			<div class="small-12 medium-2 large-2 columns">
				<h6><a href="ro_x.php">Export RO Data</a></h6>
			</div>
		</div>
		</div>
	</div>
</div>

<?php
/*  Read all the repair orders  */
$query = 	"SELECT ronumber, modelyear, carmileage, singleissue, labor, parts, comment FROM repairorder, yearmodel, mileagespread
			WHERE repairorder.mileagespreadID = mileagespread.mileagespreadID AND repairorder.yearmodelID = yearmodel.yearmodelID
			AND repairorder.dealerID = $dealerID AND repairorder.surveyindex_id = $surveyindex_id
			ORDER BY roID DESC
			LIMIT 5";

$result = $mysqli->query($query);

if (!$result) {
	$_SESSION['error'][] = 'Table read error.  See administrator.';
}
$rows = $result->num_rows;

/*  Display each repair order in list allowing DELETE   */
/*  And display all associated services for each order  */
?>
	  <div id="update_div3">
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<table id="enterrotable" class="original responsive">
					<thead>
						<tr>
							<th><a>	Action			</a></th>
							<th><a>	RO #			</a></th>
							<th><a>	Model			</a></th>
							<th><a>	Mileage			</a></th>
							<th><a>	Single Svc		</a></th>
							<th><a>	Labor			</a></th>
							<th><a>	Parts			</a></th>
							<th><a>	Services		</a></th>
							<th><a>	Comments		</a></th>
						</tr>
					</thead>
					<tbody>
<?php
for ($j = 0 ; $j < $rows ; ++$j)
{
	$row = $result->fetch_row();

	/*  Convert 0,1 to No,Yes to display as Single Issue  */
	if ($row[3] == 0) {
		$singleissue = "No";
		}
	else {
		$singleissue = "Yes";
	}
	/*  Display repair order fields  */
?>
		<tr>
			<td class="submit_td">
				<form action="" method="post">
				<input type="hidden" name="update" value="yes" />
				<input type="hidden" name="updateronumber" value="<?php echo $row[0];?>"/>
				<input type="submit" value="Select" class= "tiny button radius"/></form>
			</td>
			<td><?php echo $row[0];?>		</td>
			<td><?php echo $row[1];?> 		</td>
			<td><?php echo $row[2];?> 		</td>
			<td><?php echo $singleissue;?>  </td>
	<?php
	if ($row[4] == NULL) {
	echo	'<td> N/A			   	 </td>';
	} else {
	echo 	'<td>', '$' , $row[4], 	'</td>';
	}
	if ($row[5] == NULL) {
	echo	'<td> N/A			   	 </td>';
	} else {
	echo 	'<td>', '$' , $row[5], 	'</td>';
	}
	/*  Now show services within rightmost data slot */
	/*  Add'l svc indicated by *               */

	$service = array();

	$query2 = 	"SELECT servicedescription, addsvc FROM servicerendered
				NATURAL JOIN services
				WHERE $row[0] = servicerendered.ronumber AND servicerendered.dealerID = $dealerID AND servicerendered.surveyindex_id = $surveyindex_id
				ORDER By services.servicesort";

	$result2 = $mysqli->query($query2);
	if (!$result2) {
		$_SESSION['error'][] = 'Services query error. See administrator.';
	}

	//  Build all services in array for this single order

	$rows2 = $result2->num_rows;
	for ($i = 0; $i < $rows2; ++$i)
	{
		$row2 = $result2->fetch_row();

		//  For Additional Service convert 0 to null, 1 to *

		if ($row2[1] == 0) {
			// This is not an additional service
			$addsvc = '';
			}
		else {
			// This is an additional service
			$addsvc = "*";
		}
		$svc = $row2[0].$addsvc;
		//  Place comma only between services, not after last one
		if ($i != ($rows2-1)) {
			// This is not last service so add comma
			$svc = $svc.', ';
		}
		$service[] = $svc;

	}	// END of Services Rendered loop for this one repair order

	$services = "";
	foreach ($service as $s)
		{
			$services .= $s;
		}
	echo 	'<td>',$services,'</td>
			 <td>',$row[6],  '</td>';

/* End services table display for this service order
   END of Repair Order loop
   End repair order service table display for all repair orders queried */
	echo '</tr>';
}
	echo '</tbody>
		  </table>
	</div>
  </div>
</div>';

//Unset sticky form elements upon page reload
unset($_SESSION['enterro_ronumber']			);
unset($_SESSION['enterro_yearmodelID']		);
unset($_SESSION['enterro_yearmodel']		);
unset($_SESSION['enterro_mileagespreadID']	);
unset($_SESSION['enterro_carmileage']		);
unset($_SESSION['enterro_singleissue']		);
unset($_SESSION['enterro_labor']			);
unset($_SESSION['enterro_parts'] 			);
unset($_SESSION['enterro_comment'] 			);

$result->close();
if ($rows2 > 0) {
	$result2->close();
}
/*  End program  */
?>
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
<script src="js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="js/dataTables.foundation.js"></script>
<script>
	$(document).foundation();


	$(document).ready(function() {
		// Focuses the cursor in the RO input field (if browser does not support html 5 'autofocus' (stupid IE)
		$("#ronumber").first().focus();

		<?php
		if ($month > 7) {
		echo'
		$("#survey_start_yearmodelID_submit").on("click",
		function() {
			if(confirm("Please confirm your selection.  If it is August or later in the year, you should select next year ('.$currentyear.') to allow for next year\'s models.")) {
				return true;
			} else {
				return false;
			}
		}); ';
		}
		?>

		$("#lock_submit").on("click",
		function() {
			if(confirm("Are you sure you want to lock the survey?  The survey may only be re-opened by admin.")) {
				return true;
			} else {
				return false;
			}
		});

		$("#hide_button").click(function(){
			$("#hide").toggle(100);
		});

		$("#hide_button").on("click", function() {
		  var el = $(this);
		  if (el.text() == el.data("text-swap")) {
			el.text(el.data("text-original"));
		  } else {
			el.data("text-original", el.text());
			el.text(el.data("text-swap"));
		  }
		});
	});
</script>
</body>
</html>