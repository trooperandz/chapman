<?php
	require_once("functions.inc");
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey Login</title>
	<link rel="icon" href="img/sos_logo3.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>
<div class="wrapper">  
<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1 style="color: #FFFFFF; margin-left: 10px;">RO Survey - <?php echo constant('MANUF'); ?></h1>
    </li>
  </ul>
  <section class="top-bar-section">
    <ul class="right">
      <li class="name">
        <h1 style="color: #FFFFFF; margin-right: 10px;">Hello</h1>
      </li>
    </ul>
  </section>
</nav>  

<?php
// Set sticky form values if errors exist
$login_email = (isset($_SESSION['login_email'])) ? $_SESSION['login_email'] : null;
$login_pass  = (isset($_SESSION['login_pass']))  ? $_SESSION['login_pass']  : null;
$login_code  = (isset($_SESSION['login_code']))  ? $_SESSION['login_code']  : null;
?>

<div class="row">
	<div class="large-12 columns">
			<h2 style="margin-top: 20px;">RO Survey Login </h2>
			<p>	 &nbsp;	</p>
	</div>
</div>
<form method="POST" action="login-process1.php">
	<div class="row">
		<div class="small-12 medium-4 large-4 columns">
			<p><img src="<?php echo constant('PIC_MENUS');?>"></p>
		</div>	
	
	<div class="small-12 medium-4 large-4 columns">
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<div class="email-field">
					<label>Username
						<input type="email" id="email" name="email" placeholder="Enter your email address" value="<?php echo $login_email; ?>" autofocus>
					</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<label>Password <small><a href="emailpass.php">Forget?</a></small>
					<input type="password" id="password" name="password" value="<?php echo $login_pass; ?>">
				</label>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<label><?php echo constant('ENTITY');?> Code
					<input type="text" id="dealercode" name="dealercode" placeholder="Enter a <?php echo MANUF." ".ENTITYLCASE; ?> code" value="<?php echo $login_code; ?>">
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
					<input type="submit" id="submit" name="submit" value="Drive &raquo;" class="small button radius">
			</div>
		</div>
	</div>
	<div class="small-12 medium-4 large-4 columns">
	
	</div>
	</div>
</form>	
<div class="push"></div>  <!--pushes down footer so does not overlap anything-->	
</div> <!--End div 'wrapper'-->

<?php
// Unset sticky form globals so they are removed after page load
unset($_SESSION['login_email'], $_SESSION['login_pass'], $_SESSION['login_code']);
?>

<footer>
	<span class="footer_span"><span class="copyright">&copy; <?php echo date('Y'); ?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>
	
<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
   $(document).foundation();
   $(document).foundation({
  tooltip: {
    selector : '.has-tip',
    additional_inheritable_classes : [],
    tooltip_class : '.tooltip',
    touch_close_text: 'tap to close',
    disable_for_touch: false,
    tip_template : function (selector, content) {
      return '<span data-selector="' + selector + '" class="'
        + Foundation.libs.tooltip.settings.tooltip_class.substring(1)
        + '">' + content + '<span class="nub"></span></span>';
    }
  }
});
</script>

</body>
</html>