<?php
/* -----------------------------------------------------------------------------*
   Program: emailpass.php

   Purpose: Send reset password link to user via user's email address

	History:
    Date			Description										by
	06/20/2014		Initial design and coding						Matt Holland
	12/11/2014		Updated car picture with php constant			Matt Holland
	01/08/2015		Added sticky footer								Matt Holland
 *------------------------------------------------------------------------------*/
require_once("functions.inc");	
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> Reset Password - RO Survey </title>
    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>
<div class="wrapper">  
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
			<h2 style="margin-top: 20px;"> Forgot Password? </h2>
			<p>	 &nbsp;	</p>
	</div>
</div>
<form method="POST" action="email-process.php" >
	<div class="row">
		<div class="small-12 medium-4 large-4 columns">
			<p><img src="<?php echo PIC_MENUS;?>"></p>
		</div>	
	
	<div class="small-12 medium-4 large-4 columns">
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<p> </p>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<div class="email">
					<label> Email Address
						<input type="text" id="email" name="email" placeholder="Enter your email address" autofocus>
					</label>
				</div>
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
					<input type="submit" id="submit" name="submit" value="Send reset request" class="tiny button radius">
			</div>
		</div>
	</div>
	<div class="small-12 medium-4 large-4 columns">
	
	</div>
	</div>
</form>		
<div class="push"></div>  <!--pushes down footer so does not overlap anything-->	
</div> <!--End div 'wrapper'-->
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