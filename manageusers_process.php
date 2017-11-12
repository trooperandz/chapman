<?php
/* -----------------------------------------------------------------------------*
   Program: manageusers_process.php

   Purpose: Update users - editing users

	History:
    Date			Description										by
	06/20/2014		Initial design and coding						Matt Holland
	12/11/2014		Updated car picture with php constant			Matt Holland
	01/08/2015		Added sticky footer								Matt Holland
 *------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

// Set email requirement
$validPassword = '/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,}$/';

// Edit Requested; read user record only if first page load
if ($_SESSION['edit']) {
	$edit_email = $_SESSION['edit_orig_email'];
    $edit_user = $_SESSION['edit_orig_user'];
	$query = "SELECT user_id, user_name, user_fname, user_lname, user_email, user_active, user_admin FROM user WHERE user_email = '$edit_email'";
	$result = $mysqli->query($query);
	if (!$result) die ("Database access failed: " . mysqli_error($dbcon));
	$user = $result->fetch_assoc();
	if (isset($user['user_email']) && $user['user_email'] != "") {
        $_SESSION['edit_username'] = $user['user_name'];
		$_SESSION['edit_email'] = $user['user_email'];
		$_SESSION['edit_last_name'] = $user['user_lname'];
		$_SESSION['edit_first_name'] = $user['user_fname'];
        $_SESSION['edit_active'] = $user['user_active'];
		$_SESSION['edit_admin'] = $user['user_admin'];
        // Save for update
		$user_id = $user['user_id'];
		$_SESSION['formAttempt'] = true;
	} else {
		$_SESSION['error'][] = $edit_email." not read after edit!";
		$_SESSION['formAttempt'] = true;
	}
}

// If user hits register then Update user
if	(isset($_POST['submit'])) {
    // Set to false so that query does not run again
    $_SESSION['edit'] = false;

    // Register all form posts
    $username = $mysqli->real_escape_string($_POST['username']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $first_name = $mysqli->real_escape_string($_POST['first_name']);
    $last_name = $mysqli->real_escape_string($_POST['last_name']);
    $active = $_POST['active'];
    $admin = $_POST['admin'];
    $password1 = $mysqli->real_escape_string($_POST['password1']);
    $password2 = $mysqli->real_escape_string($_POST['password2']);

    // Save inputs for form placeholders
    $_SESSION['edit_username'] = $username;
    $_SESSION['edit_email'] = $email;
    $_SESSION['edit_first_name'] = $first_name;
    $_SESSION['edit_last_name'] = $last_name;
    $_SESSION['edit_active'] = $active;
    $_SESSION['edit_admin'] = $admin;
    $_SESSION['edit_password1'] = $password1;
    $_SESSION['edit_password2'] = $password2;

    // Validate inputs.  Admin does not need validating.  Will never be null
    if (!$username) {
        $_SESSION['error'][] = 'Please enter a valid username!';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'][] = 'Please enter a valid email address!';
    }
    if (!$first_name) {
        $_SESSION['error'][] = 'Please enter a valid first name!';
    }
    if (!$last_name) {
        $_SESSION['error'][] = 'Please enter a valid last name!';
    }
    // If there was a password entered, process.  Otherwise skip over it.
    if ($password1) {
        if (!preg_match($validPassword, $password1)) {
            $_SESSION['error'][] = 'Please enter a valid password!';
        }
        if ($password1 != $password2) {
            $_SESSION['error'][] = 'Your passwords did not match!';
        }
        // Encrypt password
        $cryptedPassword = crypt($password1);
    }

    // Check to make sure username/email is not already taken if changed
    if ($_SESSION['edit_orig_user'] != $_SESSION['edit_username']) {
        $query = "SELECT user_name FROM user WHERE user_name = '$username'";
        $result = $mysqli->query($query);
        if (!$result) {
            die ("Database error: " .$mysqli->error);
        } else {
            if ($result->num_rows > 0) {
                $_SESSION['error'][] = 'That username is already taken!';
            }
        }
    }

    if ($_SESSION['edit_orig_email'] != $_SESSION['edit_email']) {
        $query = "SELECT user_email FROM user WHERE user_email = '$email'";
        $result = $mysqli->query($query);
        if (!$result) {
            die ("Database error: " .$mysqli->error);
        } else {
            if ($result->num_rows > 0) {
                $_SESSION['error'][] = 'That email address is already taken!';
            }
        }
    }

    // If there were errors, stop processing and show error feedback
    if (!isset($_SESSION['error'])) {

	   $query = "UPDATE user SET user_name = '$username',
            user_email = '$email',
	   		user_lname = '$last_name',
	   		user_fname = '$first_name',
            user_active = $active,
	   		user_admin = $admin";

        // If password was entered, also update the password
        if ($cryptedPassword) {
            $query .= ", user_pass = '$cryptedPassword' ";
        }

        $query .= " WHERE user_id = $user_id";

	   // Check for completion of Update and issue message if failure
	   if (!$mysqli->query($query)) {
	   	   // ERROR - user not inserted
	   	   $_SESSION['error'][] = "User ". $email. " was not updated";
	   	   $_SESSION['error'][] = $mysqli->error;
	   	   $_SESSION['formAttempt'] = true;
	   	} else {
	   		$_SESSION['error'][] = "User ". $email. " updated";
	   		// Unset all sticky form elements
            unset($_SESSION['edit_username'], $_SESSION['edit_email'], $_SESSION['edit_first_name'], $_SESSION['edit_last_name'], $_SESSION['edit_admin'], $_SESSION['edit_password1'], $_SESSION['edit_password2']);
	   		die (header("Location: manageusers.php"));
	   }
    } else {
        // There were errors. Reload page
        die (header("Location: manageusers_process.php"));
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
          <a href="#"> Welcome, <?php echo $_SESSION['firstName']; ?> </a>
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
	<div class="large-12 columns">
		<h2 style="margin-top: 20px;"> Edit User </h2>
	</div>
</div>
<form data-abide method="POST" action="manageusers_process.php" >
		<div class="row">
			<div class="large-12 columns">
				<label>Username
					<input type="text" id="username" name="username" value="<?php echo $_SESSION['edit_username']; ?>" autofocus>
				</label>
				<small class="error">Please enter a username</small>
			</div>
            <div class="large-12 columns">
                <label>Email Address
                    <input type="text" id="email" name="email" placeholder="Email address" value="<?php echo $_SESSION['edit_email']; ?>" autofocus>
                </label>
                <small class="error">Please enter an email address</small>
            </div>
			<div class="large-12 columns">
				<label>First Name
					<input required type="text" id="first_name" name="first_name" pattern="alpha" value="<?php echo $_SESSION['edit_first_name']; ?>">
					<small class="error">Please enter a valid first name</small>
				</label>
			</div>
			<div class="large-12 columns">
				<label>Last Name
					<input required type="text" id="last_name" name="last_name" pattern="alpha" value="<?php echo $_SESSION['edit_last_name']; ?>">
				</label>
				<small class="error">Please enter a valid last name</small>
			</div>
            <div class="large-12 columns">
                <label>Active?
                    <?php
                        if ($_SESSION['edit_active']) {
                            echo'<select id="active" name="active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>';
                        } else {
                            echo'<select id="active" name="active">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>';
                        }
                    ?>
                </label>
                <small class="error">Please enter an active status</small>
            </div>
			<div class="large-12 columns">
				<label>Admin?
					<?php
						if ($_SESSION['edit_admin']) {
							echo'<select id="admin" name="admin">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>';
						} else {
							echo'<select id="admin" name="admin">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select>';
						}
					?>
				</label>
				<small class="error">Please enter an admin status</small>
			</div>
            <div class="large-12 columns">
                <label>Password <small style="color: blue; font-size: 8px;"> &nbsp; 8 characters:&nbsp; number + upper & lower case letter + special character</small>
                    <input type="password" id="password1" name="password1" value="<?php echo $_SESSION['edit_password1']; ?>">
                </label>
                <small class="error">Please enter a valid password</small>
            </div>
            <div class="large-12 columns">
                <label>Verify Password
                    <input required type="password" id="password2" name="password2" placeholder="Please enter a matching password" data-equalto="password1" value="<?php echo $_SESSION['edit_password2']; ?>">
                </label>
                <small class="error">Password does not match</small>
            </div>
			<div class="large-12 columns">
                <p style="color: blue; font-size: 12px">&nbsp; **Only use password field if changing user's password!</p>
				<div id="errorDiv" style="color: #FF0000;">
            <?php
				if (isset($_SESSION['error'])) {
					foreach ($_SESSION['error'] as $error) {
						echo "<p>".$error."</p>";
					}
                    // Flush out all errors
                    unset($_SESSION['error']);
				}
            ?>  </div>
	        </div>
			<div class="small-12 medium-9 large-9 columns">
				<input type="submit" id="submit" name="submit" value="Save Changes &raquo;" class="tiny button radius">
			</div>
			<div class="small-12 medium-3 large-3 columns">
				<h6><a href="manageusers.php">Cancel</a></h6>
			</div>
	   </div> <!-- /.row -->
</form>
<div class="push"></div>  <!--pushes down footer so does not overlap anything-->
</div> <!-- /.wrapper -->

<footer>
	<span class="footer_span"><span class="copyright">&copy; <?php echo date('Y'); ?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
