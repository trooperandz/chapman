<?php
/* -----------------------------------------------------------------------------*
   Program: manageusers.php

   Purpose: Manage users - adding users and managing user active status

	History:
    Date			Description										by
	06/20/2014		Initial design and coding						Matt Holland
	12/11/2014		Updated car picture with php constant			Matt Holland
	01/08/2015		Added sticky footer								Matt Holland
 *------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include('templates/db_cxn.php');

/* Set dealer ID */
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;

/*  Is Edit Requested?  Save email in global and transfer control  */
if (isset($_POST['edit'])) {
	$_SESSION['edit'] = true;
	$_SESSION['edit_orig_email'] = $_POST['edit_email'];
    $_SESSION['edit_orig_user'] = $_POST['edit_user'];

	die (header("Location: manageusers_process.php"));
}

/*  If user hits register then INSERT new user */
if	(isset($_POST['username'])  &&
     isset($_POST['email'])     &&
	 isset($_POST['password1']) &&
	 isset($_POST['password2']) &&
	 isset($_POST['last_name']) &&
	 isset($_POST['first_name'])&&
     isset($_POST['admin'])) {

    $user_name = $mysqli->real_escape_string($_POST['username']);
    $user_type_id = USER_TYPE;
    $user_team_id = USER_TEAM;
    $user_dealer_id = USER_DEALER;
	$email = $mysqli->real_escape_string($_POST['email']);
	$cryptedPassword = crypt($_POST['password1']);
	$password = $mysqli->real_escape_string($cryptedPassword);
	$last_name = $mysqli->real_escape_string($_POST['last_name']);
	$first_name = $mysqli->real_escape_string($_POST['first_name']);
    $admin = $_POST['admin'];
    $registered_by = $_SESSION['userID'];
    $active = 1;

/* Check if duplicate exists for user email */
	$query = "SELECT user_name, user_email FROM user WHERE user_email = '$email'";
	$result = $mysqli->query($query);
	$user = $result->fetch_assoc();
	if (isset($user['user_email']) && $user['user_email'] != "") {
        if ($user['user_name'] == $user_name) {
            $_SESSION['error'][] = 'Username '.$user_name.' already exists!';
        }
        if ($user['user_email'] == $email) {
		      $_SESSION['error'][] = 'Email address '.$email.' already exists!';
        }
		$_SESSION['formAttempt'] = true;
	} else {
		$activeTrue = true;
		$query = "INSERT INTO user (user_name, user_type_id, user_team_id, user_dealer_id, user_pass, user_fname, user_lname, user_email, user_active, user_admin, registered_by, create_date)
		VALUES ('$user_name', $user_type_id, $user_team_id, $user_dealer_id, '$password', '$first_name', '$last_name', '$email', $active, $admin, $registered_by, NOW())";
		/* Check for completion of insert and issue message if failure */
		if (!$mysqli->query($query)) {
			/* ERROR - user not inserted */
			$_SESSION['error'][] = "User ". $email. " was not registered";
			$_SESSION['error'][] = $mysqli->error;
			$_SESSION['formAttempt'] = true;
			} else {
				$_SESSION['error'][] = "User ". $email. " registered successfully!";
				$_SESSION['formAttempt'] = true;
		}
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
	<link rel="stylesheet" href="css/responsive-tables.css" media="screen" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
    <style>
        table {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
        }

        table thead {
            height: 35px;
            padding: 0px;
        }

        table thead tr {
            background-color: #e5e5e5;
            height: 35px;
            padding: 0px;
        }

        table thead tr th {
            text-align: center;
            font-size: 10pt;
            border-left: 1px solid #CCCCCC;
            width: 150px;
            height: 35px;
            padding: 0px;
        }

        table tbody tr {
            padding: 0px;
        }

        table tbody tr td {
            text-align: center;
            border-bottom: 1px solid #CCCCCC;
            border-left: 1px solid #CCCCCC;
            padding: 0px;
            height: 60px;"
        }

        table form {
            padding: 0px;
            margin-bottom: 0px;
        }

        table input.button {
            margin: 0;
        }
    </style>
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
    <script>
		$(document).ready(function() {
			$("#usertable").tablesorter();
			}
		);
	</script>
  </head>
  <body>
<div class="wrapper">
<div class="fixed">
 <nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="">Manage Users</a></h1>
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

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<h2 style="margin-top: 20px;"> Register New User </h2>
	</div>
</div>

<form data-abide method="POST" action="manageusers.php" >
	<div class="row">
        <div class="large-12 columns">
            <label>Username
                <input required type="text" id="username" name="username" placeholder="Enter Username" autofocus>
            </label>
            <small class="error">Please enter a valid username</small>
        </div>
        <div class="large-12 columns">
			<div class="email-field">
				<label>Email Address
					<input required type="email" id="email" name="email" placeholder="Email address" autofocus>
				</label>
				<small class="error">Please enter an email address</small>
			</div>
		</div>
		<div class="large-12 columns">
			<label>First Name
				<input required type="text" id="first_name" name="first_name" pattern="alpha">
			</label>
            <small class="error">Please enter a valid first name</small>
		</div>
		<div class="large-12 columns">
			<label>Last Name
				<input required type="text" id="last_name" name="last_name" pattern="alpha">
			</label>
			<small class="error">Please enter a valid last name</small>
		</div>
        <div class="large-12 columns">
            <label>Admin?
                <select required id="admin" name="admin">
                    <option value="">Select...</option>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </label>
            <small class="error">Please select an admin designation</small>
        </div>
		<div class="large-12 columns">
			<label>Password <small style="color: blue; font-size: 8px;"> &nbsp; 8 characters:&nbsp; number + upper & lower case letter + special character</small>
				<input required type="password" id="password1" name="password1">
			</label>
            <small class="error">Please enter a valid password</small>
		</div>
		<div class="large-12 columns">
			<label>Verify Password
				<input required type="password" id="password2" name="password2" placeholder="Please enter a matching password" data-equalto="password1">
			</label>
			<small class="error">Password does not match</small>
		</div>
        <div class="large-12 columns">
            <div id="errorDiv" style="color: #FF0000;">
                <?php
                    if (isset($_SESSION['error']) && isset($_SESSION['formAttempt'])){
                        unset($_SESSION['formAttempt']);
                        foreach ($_SESSION['error'] as $error) {
                            print $error . "<br />\n";
                        } //end foreach
                    } //end if
                    $_SESSION['error'] = array();   // flush errors
                ?>
            </div>
        </div>
		<div class="small-12 medium-12 large-12 columns">
			<p> </p>
			<input type="submit" id="submit" name="submit" value="Register User &raquo;" class="tiny button radius">
		</div>
	</div> <!-- ./row -->
</form>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<h2> Manage Users </h2>
	</div>
</div>

<?php

$mysqli = new mysqli (DBHOST,DBUSER,DBPASS,DB);
if ($mysqli->connect_errno) {
	$_SESSION['error'][] = "Database access failed";
	$_SESSION['error'][] = $mysqli->connect_error;
	die (header("Location: enterrofoundation.php"));
}

/* Set dealer ID */
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;

$user_type_id = USER_TYPE;

/*  Read all user records  */
$query = "SELECT user_name, user_fname, user_lname, user_email, user_active, user_admin
          FROM user
          WHERE user_type_id = $user_type_id
          ORDER BY user_lname ASC";

$result = $mysqli->query($query);

if ($result) {
    $rows = $result->num_rows;
} else {
    $rows = 0;
}

echo    '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h6>Total Users: ' .$rows. '</h6>
    		</div>
			<div class="large-12 columns">
				<table id="usertable" class="tablesorter responsive">
					<thead>
					    <tr>
							<th><a>Action</a></th>
							<th><a>First Name</a></th>
							<th><a>Last Name</a></th>
                            <th><a>Username</a></th>
							<th><a>Email</a></th>
							<th><a>Active?</a></th>
                            <th><a>Admin?</a></th>
						</tr>
					</thead>
					<tbody>';
for ($j = 0 ; $j < $rows ; ++$j) {
	$row = $result->fetch_row();

/*  Display each user in list allowing EDIT   */
echo '<tr>
		  <td>
		  	   <form action="" method="post">
		  	       <input type="hidden" name="edit" value="yes" />
		  	       <input type="hidden" name="edit_email" value='.$row[3].' />
                   <input type="hidden" name="edit_user" value='.$row[0].' />
		  	       <input type="submit" value="Select" class="tiny button radius" />
		  	   </form>
		  </td>
		  <td>' .$row[1]. '</td>
		  <td>' .$row[2]. '</td>
          <td>' .$row[0]. '</td>
		  <td>' .$row[3]. '</td>';

        // Display active status
        if	($row[4]) {
        	echo '<td>','Yes','</td>';
        } else {
        	echo '<td>','No','</td>';
        }

        // Display admin status
        if ($row[5]) {
            echo '<td>','Yes','</td>';
        } else {
            echo '<td>','No','</td>';
        }
            echo '</tr>';
        } // end for loop

	       echo '</tbody>
			</table>
		</div>
			<div class="medium-2 large-2 columns">
				<p> </p>
			</div>
		</div> <!-- /.row -->';
?>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
</div>
<div class="push"></div>  <!--pushes down footer so does not overlap anything-->
</div> <!-- /.wrapper -->

<footer>
	<span class="footer_span"><span class="copyright">&copy; <?php echo date('Y'); ?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>

    <script src="js/foundation.min.js"></script>
	<script src="js/responsive-tables.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
