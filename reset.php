<?php 
	require_once("functions.inc");
	$invalidAccess = true; 
if (isset($_GET['user']) && $_GET['user'] != "") { 
	$invalidAccess = false; 
	$hash = $_GET['user']; 
} //if they've attempted the form but had a problem, we need to allow them in. 

if (isset($_SESSION['formAttempt']) && $_SESSION['formAttempt'] == true) {
	$invalidAccess = false; 
	$hash = $_SESSION['hash']; 
} 

if ($invalidAccess) { 
	die(header("Location: index.php")); 
} 
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> Reset Password - RO Survey </title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>
<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1 style="color: #FFFFFF; margin-left: 10px;"> RO Survey - <?php echo MANUF; ?> </h1>
    </li>
  </ul>
  <section class="top-bar-section">
    <ul class="right">
      <li class="name">
        <h1 style="color: #FFFFFF; margin-right: 10px;"> Hello </h1>
      </li>
    </ul>
  </section>
</nav>  

<div class="row">
	<div class="large-12 columns">
			<h2 style="margin-top: 20px;"> Reset Password </h2>
			<p>	 &nbsp;	</p>
	</div>
</div>
<form method="POST" action="reset-process.php" >
	<div class="row">
		<div class="small-12 medium-4 large-4 columns">
			<p><img src="<?php echo PIC_MENUS;?>" alt=""></p>
		</div>	
	
	<div class="small-12 medium-4 large-4 columns">
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<div class="email">
					<label>Username
						<input type="text" id="email" name="email" placeholder="Enter your email address" autofocus>
					</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<label> New Password:
					<input type="password" id="password1" name="password1" placeholder="Enter a password of your choice">
				</label>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<label> Confirm Password:
					<input type="password" id="password2" name="password2" placeholder="Re-enter the same password" data-equalto="password1">
				</label>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
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
			<div class="small-12 medium-12 large-12 columns">
					<p> </p>
					<?php print "<input type =\"hidden\" name=\"hash\" value=\"{$hash}\">\n"; ?>
					<input type="submit" id="submit" name="submit" value="Reset &raquo;" class="small button radius">
			</div>
		</div>
	</div>
	<div class="small-12 medium-4 large-4 columns">
	
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