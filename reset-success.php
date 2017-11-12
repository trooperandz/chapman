<?php
	require_once("functions.inc");
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
<form>
	<div class="row">
		<div class="small-12 medium-4 large-4 columns">
			<p><img src="<?php echo PIC_MENUS;?>" alt=""></p>
		</div>	
	
	<div class="small-12 medium-4 large-4 columns">
		<div class="row">
			<div class="small-12 large-12 columns">
				<p> </p>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<div class="email">
					<p>Your password has been reset.</p>
					<p>Click <a href="index.php">here </a>to login.</p>
				</div>
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