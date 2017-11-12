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
<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1 style="color: #FFFFFF; margin-left: 10px;">Nissan - Admin</h1>
    </li>
  </ul>
  <section class="top-bar-section">
    <ul class="right">
      <li class="name">
        <h1 style="color: #FFFFFF; margin-right: 10px;">No Go</h1>
      </li>
    </ul>
  </section>
</nav>  

<div class="row">
	<div class="large-12 columns">
			<h2 style="margin-top: 20px;">Access Denied </h2>
			<p>	 &nbsp;	</p>
	</div>
</div>
<form data-abide method="POST" action="login-process1.php" >
	<div class="row">
		<div class="small-12 large-4 columns">
			<p><img src="<?php echo constant('PIC_AUTH'); ?>" alt=""></p>
		</div>	
	
	<div class="small-12 large-4 columns">
		<div class="row">
			<div class="small-12 large-12 columns">
				<h5>Sorry, you are not an authorized administrator.</h5>
				<h5><a href="enterrofoundation.php">Return</a> to main.</h5>
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