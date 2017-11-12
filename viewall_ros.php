<?php
/*-----------------------------------------------------------------------------------------*
   Program: viewall_ros.php

   Purpose: Show user ALL repair orders for $dealerID and $surveyindex_id
			Created to reduce load on main entry form (enterrofoundation.php)

	History:
    Date			Description											by
	04/22/2015		Initial design and coding							Matt Holland

*-----------------------------------------------------------------------------------------*/

// Required system includes
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

/* Set last page global variable */
include ('templates/lastpagevariable_dealerreports_include.php');

/*-----------------------------------------------------------------------------------------*/
// Get total RO count for $dealerID, $surveyindex_id
$query = "SELECT * FROM repairorder WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "repairorder SELECT query failed.  See administrator.";
}
$repairorderrows = $result->num_rows;
$_SESSION['repairorderrows'] = $repairorderrows;

/*-----------------------------------------------------------------------------------------*/
// Current year display processing

// Get survey start year from 'surveys' table - If set to zero (which happens if and when the above survey processing inserts entry into surveys table), set $currentyear to server year
$query = "SELECT survey_start_yearmodelID FROM surveys WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Survey search error.  Please see administrator.';
	die(header('Location: enterrofoundation.php'));
}
$lookup = $result->fetch_assoc();
$survey_start_test = $lookup['survey_start_yearmodelID'];
$currentyearID = $lookup['survey_start_yearmodelID'];
if ($currentyearID != 0) {
	// Use $year_test to determine if display of year in title should show (should not show if $year_test = true)
	$year_test = true;
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
	// Use $year_test to determine if display of year in title should show (should not show if $year_test = false)
	$year_test = false;
	$_SESSION['error'][] = '*Dealer '.$dealercode.' has no repair orders for a '.$survey_description;
}

/*-----------------------------------------------*
	UPDATE REQUEST ??
    Save ronumber in global and transfer control
 *-----------------------------------------------*/
if (isset($_POST['update'])) {
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
    <link rel="stylesheet" href="css/fluid-layout.css" />
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
				text-align: right
			}
			.total_ro_count_span {
				margin-right: 10px;
			}
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
		table.original {
			font-family: helvetica;
			font-size: 8pt;
			border-collapse: collapse;
			margin-right: auto;
			margin-left: auto;
		}
		table.original thead tr th {
			background-color: whitesmoke;
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
			table.original thead tr th {
				background-image: url(css/bg.gif);
			}
		}
		table.original thead tr th {
			background-repeat: no-repeat;
			background-position: center right;
            cursor: pointer;
		}
	</style>
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
	<!--<script src="js/tablesorter.js"></script>-->
	<script>
	$(document).ready(function() {
		$("#enterrotable").DataTable();
	});
	</script>
</head>
<body>
<div class="wrapper">

<?php
include('templates/menubar_dealer_reports.php');
?>

<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-8 large-9 columns">
				<h2 style="line-height: .8; margin-top: .4rem;">All Repair Orders
					<span class="rosurvey_title">
						<?php
						if(isset($_SESSION['survey_description']) && $year_test == true) {
							echo ' - '.constant('ENTITY'). ' '.$_SESSION['dealercode'].'&nbsp;<span class="rosurvey_subtitle" > ('.$currentyear.' &nbsp;'.$_SESSION['survey_description']. ')</span>';
						} else {
							echo ' - '.constant('ENTITY'). ' '.$_SESSION['dealercode'].'&nbsp;<span class="rosurvey_subtitle" > ('.$_SESSION['survey_description']. ')</span>';
						}
						?>
					</span>
					<br>
					<span style="font-size: 13px;"><a href="enterrofoundation.php">Go back</a> to entry form</span>
				</h2>
			</div>
			<div class="small-12 medium-4 large-3 columns">
				<h5 class="total_ro_count"><span class="total_ro_count_span">Total ROs: <?php echo $_SESSION['repairorderrows'];?></span><br>
				<a href="ro_x.php">Export RO Data</a></h5>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-7 large-7 columns">
		<?php
		if (isset($_SESSION['error'])) {
		$_SESSION['update'] = FALSE;
			foreach ($_SESSION['error'] as $error) {
				echo '<h5 class="error_msg">' .$error.  '</h5>';
			} //end foreach
			unset($_SESSION['error']);
		} //end if

		if (isset($_SESSION['success'])) {
		$_SESSION['update'] = FALSE;
			foreach ($_SESSION['success'] as $error) {
				echo '<h5 class="success_msg">' .$error. '</h5>';
			} //end foreach
			unset($_SESSION['success']);
		} //end if
		?>
	</div>
</div>

<div class="small-12 medium-12 large-12 columns">
	<div class="row">
		<p> </p>
	</div>
</div>

<?php
/*  Read all the repair orders  */
$query = 	"SELECT ronumber, modelyear, carmileage, singleissue, labor, parts, comment, welr_username FROM repairorder, yearmodel, mileagespread, Customer
			WHERE repairorder.mileagespreadID = mileagespread.mileagespreadID AND repairorder.yearmodelID = yearmodel.yearmodelID
			AND repairorder.dealerID = $dealerID AND repairorder.surveyindex_id = $surveyindex_id AND repairorder.userID = Customer.userID
			ORDER BY roID DESC";

$result = $mysqli->query($query);

if (!$result) {
	$_SESSION['error'][] = "Could not read repair orders";
	$_SESSION['error'][] = $mysql_error;
	die (header("Location: enterrofoundation.php"));
}
$rows = $result->num_rows;

/*  Display each repair order in list allowing DELETE   */
/*  And display all associated services for each order  */
?>
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
							<th><a>	User    		</a></th>
                            <th><a> Comments        </a></th>
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
		$_SESSION['error'][] = "Could not read services";
		$_SESSION['error'][] = $mysql_error;
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
			 <td>',$row[7],  '</td>
             <td>',$row[6],  '</td>';

/* End services table display for this service order
   END of Repair Order loop
   End repair order service table display for all repair orders queried */
	echo '</tr>';
}
	echo '</tbody>
		  </table>
	</div>
</div>';

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
</script>
</body>
</html>
