<?php
require_once("functions.inc");
include ('templates/login_check.php');
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey Login</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>
<div class="fixed">
 <nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href=""> Nissan - <?php echo $_SESSION['dealercode']?></a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
	<!-- Right Nav Section -->
	<ul class="right">
        <li class="divider"></li>
        <li class="has-dropdown">
          <a href="#"><?php echo "Welcome, {$user->firstName}"; ?></a>
          <ul class="dropdown">
			<li class="divider"></li>
			<li><a href="enterrofoundation.php">Manage Users</a></li>
			<li class="divider"></li>
			<li><a href="">Manage Dealers</a></li>
			<li class="divider"></li>
            <li><a href="logout.php">Logout</a></li>
            <li class="divider"></li>
          </ul>
        </li>
      </ul>
    </section>
</nav> 
</div>

<div class="row">
	<div class="large-12 columns">
			<h2 style="margin-top: 20px;"> Register User </h2>
			<p>	 &nbsp;	</p>
	</div>
</div>
<form data-abide method="POST" action="login-process1.php" >
	<div class="row">
		<div class="small-12 large-4 columns">
			<p><img src="zwhitespace.jpg" alt=""></p>
		</div>	
	
	<div class="small-12 large-4 columns">
		<div class="row">
			<div class="small-12 large-12 columns">
				<div class="email-field">
					<label>Username
						<input required type="email" id="email" name="email" placeholder="Enter your email address" autofocus>
					</label>
					<small class="error">Email address is required</small>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-12 columns">
				<label>First Name
					<input required type="text" id="fname" name="fname">
				</label>
				<small class="error">First Name is required</small>
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-12 columns">
				<label>Last Name
					<input required type="text" id="lname" name="lname">
				</label>
				<small class="error">Last Name is required</small>
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-12 columns">
				<label>Email Address
					<input required type="text" id="email" name="email">
				</label>
				<small class="error">Email address is required</small>
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-12 columns">
				<label>Password
					<input required type="password" id="password1" name="password1">
				</label>
				<small class="error">Please enter a password</small>
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-12 columns">
				<label>Verify Password
					<input required type="password" id="password2" name="password2">
				</label>
				<small class="error">Password does not match</small>
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-12 columns">
				<div id="errorDiv" style="color: #FF0000;"> 
					<?php 
						if (isset($_SESSION['error']) && isset($_SESSION['formAttempt'])) { 
							unset($_SESSION['formAttempt']); 
							foreach ($_SESSION['error'] as $error) { 
								print $error . "<br />\n"; 
							} //end foreach 
						} //end if 
					?> 
				</div>
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-12 columns">
					<p> </p>
					<input type="submit" id="submit" name="submit" value="Register User &raquo;" class="tiny button radius">
			</div>
		</div>
	</div>
	<div class="small-12 large-4 columns">
	
	</div>
	</div>
</form>	

		
		
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
