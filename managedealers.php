<?php
/**
 * Program: managedealers.php
 * Purpose: Display and manage dealer data
 * PHP version 5.5.29
 * @author Matthew Holland
 *
 * History:
 * Date				Description												By
 * 06/20/2014		Initial design and coding								Matt Holland
 * 12/11/2014		Updated car picture with php constant					Matt Holland
 * 01/07/2015		Added city field to input								Matt Holland
 * 01/08/2015		Added sticky footer										Matt Holland
 * 02/12/2015		Redesigned form structure and added inputs				Matt Holland
 *					Integrated additional sql table information
 *					Added dataTables functionality
 *					Added hide form functionality
 *					Added webtab icon
 *					Added district, area and state dynamic dropdown
 * 	06/24/16		Revamped original dealer_summary.php (created 2/10/15)		Matt Holland
 *					into this OOP-style page for better functionality and
 *					maintainability
 */

// Include required files
require_once("functions.inc.php");
include ('templates/login_check.php');
include('templates/db_cxn.php');

// Set dealer ID
$dealerID = $_SESSION['dealerID'];

// Set user ID
$userID = $user->userID;

// Set last page for process.inc.php headers
$_SESSION['last_page'] = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

?>
<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - Admin</title>
	<link rel="icon" href="img/sos_logo3.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="css/dataTables.foundation.css" />
    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/responsive-tables.css" media="screen" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
    <link rel="stylesheet" href="css/fluid-layout.css" />
	<style>
		@media(min-width: 40.063em) {
			.hide_parent {
				text-align: right;
		}

		.hide_button {
			font-size: 14px;
			font-weight: bold;
		}

		@media (min-width: 40.063em) {
			.subtitle {
				text-align: right;
			}
		}

		.table_form {
            padding: 0px;
            margin: 0px;
        }

		.require_msg {
			font-size: 12px;
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

        table.display tbody td, table tbody td {
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
          <a href="#"> Welcome, <?php echo $user->firstName; ?> </a>
          <ul class="dropdown">
			<li class="has-dropdown">
			<?php include('templates/menubar_sidecontents.php');?>
          </ul>
        </li>
      </ul>
    </section>
</nav>
</div> <!-- end .fixed -->
<?php

// Test new Dealer class
$obj = new Dealer($mysqli, $pdo);
$error_obj = new Error;

if(isset($_POST['dealerID']) || isset($_SESSION['edit_dealer_error'])) {
	// If user selects a dealer from the dealer table or there was an edit dealer submit error,
	// display dealer edit form with dealer values filled in.
	// Note: $_POST['dealerID'] is a comma-delimitted value (dealerID, dealercode). dealercode will be used to check for dupe
	// If edit dealer error, set $dealerID == false so that getDealerForm does not run getDealerData() method
	if(isset($_SESSION['edit_dealer_error'])) {
		$dealerID = false;
	} else {
		$dlr 		= explode(",", $_POST['dealerID']);
		$dealerID 	= $dlr[0];
		$dealercode = $dlr[1];
	}
	$form 		= $obj->getDealerForm(array('dealerID'=>$dealerID));
	$table 		= null;
	$page_title = 'Update '.MANUF.' '.ENTITY;
	$heading 	= $obj->getPageHeading(array('page_title'=>$page_title, 'side_title'=>'<a href="managedealers.php">Go Back</a>'));
	$feedback 	= $error_obj->displayFeedback();

} elseif (isset($_POST['add_dealer_form']) || isset($_SESSION['add_dealer_error'])) {
	// If user selects 'Add New Dealer' link above dealer table, display Add New Dealer form
	$form 		= $obj->getDealerForm(array('dealerID'=>false));
	$table 		= null;
	$page_title = 'Register New '.ENTITY. '<span class="require_msg"> *All fields are required</span><br>';
	$heading 	= $obj->getPageHeading(array('page_title'=>$page_title, 'side_title'=>'<a href="managedealers.php">Go Back </a>'));
	$feedback 	= $error_obj->displayFeedback();

} else {
	// If page loads with no $_POST, display dealer table by default
	$form 		= null;
	$table 		= $obj->getDealerTable(array('dealerID'=>false));
	$submit 	= ($_SESSION['admin_user'] == 1) ? '<button style="display: inline; background: none; margin: 0; padding: 0; border: none;"><a>Add New Dealer</a> </button>' : null;
	$page_title = 'Manage '.MANUF.' '.ENTITY.'s <form method="POST" action="managedealers.php" style="margin: 0; padding: 0; display: inline;"> <input type="hidden" name="add_dealer_form" /> '.$submit.'  </form>';
	$heading    = $obj->getPageHeading(array('page_title'=>$page_title, 'side_title'=>'Total '.ENTITY.'s: '.$_SESSION['dlr_count']));
	$feedback 	= $error_obj->displayFeedback();
}
echo $heading.$feedback.$form.$table;

// Unset sticky form elements upon page reload
$obj->unsetEditDealerGlobals();

?>

<div class="push"></div>  <!--pushes down footer so does not overlap anything-->
</div> <!--End .wrapper-->

<footer>
	<span class="footer_span"><span class="copyright">&copy; <?php echo date('Y'); ?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>

    <script src="js/foundation.min.js"></script>
	<script src="js/responsive-tables.js"></script>
    <script>
      $(document).foundation();

	  $(document).ready(function() {

		// Re-initialize table functionality
		$("#dealer_table").DataTable({
		   paging: true,
		   searching: true,
		   "order": [1, 'asc']
		});
	});
    </script>
  </body>
</html>