<?php
/* ---------------------------------------------------------------------------------*
   Program: system_summary.php

   Purpose: Provide user with summary of system information

	History:
    Date			Description											by
	01/23/2015		Initial design and coding							Matt Holland
	01/29/2015		Added user navigation - click dealercode to
					navigate to specific survey (enterrofoundation)		Matt Holland
					Processing file: surveys_summary_goto_dealer.php
	02/10/2015		Prevented php errors and blank web view by			Matt Holland
					checking to see if there are records in
					repairorder.  If not, bypass all queries
					to prevent errors and show empty table
	03/10/2015		Added total survey count above table				Matt Holland
	07/02/2015		Added dealer name to table per Leigh Yates			Matt Holland
    12/05/2016      Added user name to table                            Matt Holland
 *----------------------------------------------------------------------------------*/
require_once("functions.inc");
include('templates/login_check.php');
include('templates/db_cxn.php');
include('templates/lastpagevariable_dealerreports_include.php'); // Set last page variable for form action in case of query errors

/* Set dealer ID */
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;

// Get total dealer count
$query = "SELECT dealerID, dealercode FROM dealer";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Dealer query failed.  See administrator.";
}
$rows_dlr = $result->num_rows;
//echo '$rows_dlr: '.$rows_dlr.' (total dealers in system)<br>';

// Get total count of dealers with surveys (those with repairorder records)
$query = "SELECT DISTINCT dealerID FROM repairorder";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Dealer query failed.  See administrator.";
}
$rows_dlr_surveys = $result->num_rows;  // total count of dealers with surveys

if ($rows_dlr_surveys > 0) {


	//echo '$rows_dlr_surveys: '.$rows_dlr_surveys.' (total dealers in system with surveys)<br>';

	// Get list of dealers with survey type for each dealer (will have duplicate dealerIDs due to survey types)
	$query = "SELECT DISTINCT dealerID, surveyindex_id FROM repairorder";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "Dealer query failed.  See administrator.";
	}
	$rows_dlr_allsurveys = $result->num_rows;  // get count of all dealer surveys (will have dupe dealers due to survey type)

	$dlr_survey_list = array(array());
	$index = 0;
	while ($lookup = $result->fetch_assoc()) {
		$dlr_survey_list[$index]['dealerID'] = $lookup['dealerID'];
		$dlr_survey_list[$index]['surveyindex_id'] = $lookup['surveyindex_id'];
		$index += 1;
	}
	// var_dump($dlr_survey_list);
	$dlr_id_list = "";
	for ($i=0; $i<$rows_dlr_allsurveys; $i++) {
		if ($i == $rows_dlr_allsurveys -1) {
			$dlr_id_list .= $dlr_survey_list[$i]['dealerID'];
		} else {
			$dlr_id_list .=$dlr_survey_list[$i]['dealerID'].',';
		}
	}
	//echo '$dlr_id_list: '.$dlr_id_list.' (list of dealerIDs per survey type)<br>';

	$dlr_survey_list2 = "";
	for ($i=0; $i<$rows_dlr_allsurveys; $i++) {
		if ($i == $rows_dlr_allsurveys -1) {
			$dlr_survey_list2 .= $dlr_survey_list[$i]['surveyindex_id'];
		} else {
			$dlr_survey_list2 .=$dlr_survey_list[$i]['surveyindex_id'].',';
		}
	}
	//echo '$dlr_survey_list2: '.$dlr_survey_list2.' (list of survey type per each dealer)<br>';

	$variable_query1 = "";
	for ($i=0; $i<$rows_dlr_allsurveys; $i++) {
		if ($i == 0 ) {
			$variable_query1 .= 'dealerID = '.$dlr_survey_list[$i]['dealerID'];
		} else {
			$variable_query1 .= ' UNION ALL SELECT dealercode, dealername FROM dealer WHERE dealerID = '.$dlr_survey_list[$i]['dealerID'];
		}
	}
	$query = 'SELECT dealercode, dealername FROM dealer WHERE '.$variable_query1;
	//echo '$query: '.$query.'<br>';
	// Lookup dealercodes from above dealerID comma delimited list.  Ensure that result set is not distinct values only by using JOIN statement

	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "Dealer query failed.  See administrator.";
	}
	$rows = $result->num_rows;

	// Turn dealercode result list into array. This will be echoed in the table using for loop
	$dlr_code_list = array();
	$index = 0;
	while ($lookup = $result->fetch_assoc()) {
		$dlr_code_list[$index]['dealercode'] = $lookup['dealercode'];
		$dlr_code_list[$index]['dealername'] = $lookup['dealername'];
		$index += 1;
	}
	// This is to view result list - for testing purposes only
	$dlr_code_list_check = "";
	for ($i=0; $i<$rows; $i++) {
		if ($i == $rows - 1) {
			$dlr_code_list_check .= $dlr_code_list[$i]['dealercode'];
		} else {
			$dlr_code_list_check .= $dlr_code_list[$i]['dealercode'].',';
		}
	}
	//echo '$dlr_code_list_check: '.$dlr_code_list_check.'<br>';

	// Obtain total RO count and locked status for each survey using above array results (via dynamic query)
	$variable_query2 = "";
	for ($i=0; $i<$rows_dlr_allsurveys; $i++) {
		if ($i == 0 ) {
			$variable_query2 .= 'dealerID = '.$dlr_survey_list[$i]['dealerID'].' AND surveyindex_id = '.$dlr_survey_list[$i]['surveyindex_id'];
		} else {
			$variable_query2 .= ' UNION ALL SELECT COUNT(ronumber), locked, MAX(a.create_date) as create_date, b.user_name
            FROM repairorder a
            LEFT JOIN user b ON (a.userID = b.user_id)
            WHERE dealerID = '.$dlr_survey_list[$i]['dealerID'].' AND surveyindex_id = '.$dlr_survey_list[$i]['surveyindex_id'];
		}
	}

	//$query = 'SELECT COUNT(ronumber), locked, MAX(create_date) as create_date FROM repairorder WHERE '.$variable_query2;
    $query = 'SELECT COUNT(ronumber), locked, MAX(a.create_date) as create_date, b.user_name
            FROM repairorder a
            LEFT JOIN user b ON (a.userID = b.user_id)
            WHERE '.$variable_query2;
	//echo '$query: '.$query.'<br>';
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "RO query failed.  See administrator.".$mysqli->error;
	}
	$rows = $result->num_rows;
	//echo '$rows: '.$rows.'<br>';

	$survey_specifics = array();
	$index = 0;
	while ($lookup = $result->fetch_assoc()) {
		$survey_specifics[$index]['COUNT(ronumber)'] = $lookup['COUNT(ronumber)'];
		$survey_specifics[$index]['locked'] = $lookup['locked'];
		$survey_specifics[$index]['create_date'] = $lookup['create_date'];
        $survey_specifics[$index]['user_name'] = $lookup['user_name'];
		$index += 1;
	}
	for ($i=0; $i<$rows; $i++) {
		if ($survey_specifics[$i]['locked'] == 1) {
			$survey_specifics[$i]['locked'] = 'Closed';
		} else {
			$survey_specifics[$i]['locked'] = 'Open';
		}
		//echo '$survey_specifics: ' .$survey_specifics[$i]['COUNT(ronumber)'].', '.$survey_specifics[$i]['locked'].', '.$survey_specifics[$i]['create_date'].'<br>';
	}

	// Get survey_description from survey_index table using above results
	for ($i=0; $i<$rows_dlr_allsurveys; $i++) {
		if ($i == 0) {
			$variable_query3 .= 'SELECT survey_description FROM survey_index WHERE surveyindex_id = '.$dlr_survey_list[$i]['surveyindex_id'];
		} else {
			$variable_query3 .= ' UNION ALL SELECT survey_description FROM survey_index WHERE surveyindex_id = '.$dlr_survey_list[$i]['surveyindex_id'];
		}
	}
	$query = $variable_query3;
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "Surveys query failed.  See administrator.";
	}
	$rows = $result->num_rows;

	$surveydesc_array = array();
	$index = 0;
	while ($lookup = $result->fetch_assoc()) {
		$surveydesc_array[$index]['survey_description'] = $lookup['survey_description'];
		$index += 1;
	}
	for ($i=0; $i<$rows; $i++) {
		if ($surveydesc_array[$i]['survey_description'] == 'Supplemental Assessment') {
			$surveydesc_array[$i]['survey_description'] = 'Supp. Assessment';
		}
		//echo 'surveydesc_array: '.$surveydesc_array[$i]['survey_description'].'<br>';
	}
} // end $rows_dlr_surveys if.  If there are no ROs, bypass all queries and show empty table
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - <?php echo constant('MANUF');?></title>
	<link rel="icon" href="img/sos_logo3.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="css/dataTables.foundation.css" />
    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/menubar_dealerchange.css" />
	<!--<link rel="stylesheet" href="css/tablesort.theme.blue.css" />-->
	<link rel="stylesheet" href="css/responsive-tables.css" media="screen" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
    <link rel="stylesheet" href="css/fluid-layout.css" />
	<!--<link rel="stylesheet" href="css/jquery.dataTables.css" />-->
	<style>
		@media (min-width: 40.063em) {
			.subtitle {
				text-align: right;
			}
		}

		.title_main {
			margin-top: 20px;
		}

		.subtitle span {
			color: blue;
		}

		.table_form {
			padding: 0px;
			margin: 0px;
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
			vertical-align: middle;
			border-left: 1px solid #CCCCCC;
			height: 44px;
			text-align: center;
			border-bottom: 1px solid #CCCCCC;
		}

		@media (min-width: 40.063em) {
			table.display thead tr th  {
				background-image: url(css/bg.gif);
			}
		}

		table.display thead tr th  {
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
			//$("#rosummary_table").DataTable();

			$("#rosummary_table").DataTable({
				order: [[ 7, "desc" ]]
			});
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
      <h1><a href=""> <?php echo constant('MANUF');?> - Survey Summary</a></h1>
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
			<h2 class="title_main"> <?php echo constant('MANUF');?> Survey Summary<h6 class="subtitle">Total Surveys: <span> <?php echo $rows_dlr_allsurveys; ?> </span></h6> </h2>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 small-centered columns">
		<table class="tablesorter responsive display" id="rosummary_table">
			<thead>
				<tr>
					<th><a> Action			</a></th>
					<th><a> Dealer Name		</a></th>
					<th><a>	Dealer Code		</a></th>
					<th><a>	Survey Type		</a></th>
					<th><a>	Total ROs		</a></th>
					<th><a>	Status			</a></th>
                    <th><a> User            </a></th>
					<th><a>	Date Completed	</a></th>
				</tr>
			</thead>
			<tbody>
			<?php
			if ($rows_dlr_surveys > 0) {
				for ($i=0; $i<$rows_dlr_allsurveys; $i++) {
					echo'<tr>
							<td>
							<form class="table_form" id="goto_dealer_form" method="POST" action="surveys_summary_goto_dealer.php">
								<input type="hidden" value='.$dlr_survey_list[$i]['dealerID'].' id="summary_dealerID" name="summary_dealerID" />
								<input type="hidden" value='.$dlr_survey_list[$i]['surveyindex_id'].' id="summary_surveyindex_id" name="summary_surveyindex_id" />
								<input type="submit" style="margin: 0rem;" class="tiny button radius" value="Select" />

							</form>
							</td>
							<td>'.$dlr_code_list[$i]['dealername'].				'</td>
							<td>'.$dlr_code_list[$i]['dealercode'].				'</td>
							<td>'.$surveydesc_array[$i]['survey_description'].	'</td>
							<td>'.$survey_specifics[$i]['COUNT(ronumber)'].		'</td>
							<td>'.$survey_specifics[$i]['locked'].				'</td>
                            <td>'.$survey_specifics[$i]['user_name'].       '</td>
							<td>'.$survey_specifics[$i]['create_date'].			'</td>
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