<?php
echo'
</head>
<body>';
/*------------------------------------Surveys menu processing-----------------------------------*/
// Generate surveytype modal menu items from table
$query = "SELECT surveyindex_id, survey_description FROM survey_index";
$surveyresult = $mysqli->query($query);
if (!$surveyresult) {
	$_SESSION['error'][] = "survey_index SELECT query failed. See administrator.";
}

$survey = array(array());
$index = 0;
while ($value = $surveyresult->fetch_assoc()) {
	$survey[$index]['surveyindex_id'] 	  = $value['surveyindex_id'];
	$survey[$index]['survey_description'] = $value['survey_description'];
	$index += 1;
}
/*---------------------------------Dlr Change menu processing-----------------------------------*/
// Generate dealer codes for change dealer dropdown
$query = "SELECT dealerID, dealercode, dealername FROM dealer
	      ORDER BY dealername ASC";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Dealer query failed.  See administrator.';
}
$dlr_rows = $result->num_rows;
$array = array(array());
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$dlr_list[$index]['dealerID'] = $lookup['dealerID'];
	$dlr_list[$index]['dealercode'] = $lookup['dealercode'];
    $dlr_list[$index]['dealername'] = $lookup['dealername'];
	$index += 1;
}
/*-------------------------------------------Main menu-------------------------------------------*/
?>
<div class="fixed">
<nav class="top-bar" data-topbar>
	<ul class="title-area">
		<li class="name"><h1><a><?php echo constant('MANUF').' - '.constant('ENTITY').' '.$_SESSION['dealercode'];?></a></h1></li>
		<li class="toggle-topbar menu-icon"><a><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
    <ul class="left">
	<li class="divider"></li>
		<li><a data-reveal-id="myModal" style="color: #46BCDE; font-weight: bold;">Select Survey &raquo</a></li>
		<li class="divider"></li>
		<div id="myModal" class="small reveal-modal" style="background-color: #ffffff;" data-reveal>
			<div class="row">
				<div class="medium-12 large-12 columns">
					<div class="medium-1 large-1 columns">
						<p> </p>
					</div>
					<div class="medium-10 large-10 columns">
						<form method="post" action="selectsurvey_process.php">
							<h6 style="color: #008cba; text-align: center;">Select <?php echo constant('ENTITY');?> Survey </h6>
							<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">
							<select style="margin-top: 30px; margin-bottom: 20px;" name="survey_selection" id="survey_selection">
								<option value="">Select survey...</option>
								<?php
								for ($i=0; $i<10; $i++) {
									echo'<option value= '.$survey[$i]['surveyindex_id']. '>' .$survey[$i]['survey_description']. '</option>';
								}
								?>
							</select>
							<input type="submit" class="tiny button radius" value="Submit">
						</form>
					</div>
					<div class="medium-1 large-1 columns">
						<p>  </p>
					</div>
				</div>
			</div>
		    <a class="close-reveal-modal" style="font-size: 19px;">&#215;</a>
		</div>
		<li class="divider"></li>
		<li class="has-form">
		<form method="post" action="dealercodeswitch_process.php">
			<div class="row collapse">
				<div class="small-6 medium-8 large-8 columns">
					<select class="collapse_select" id="dealercodechange" name="dealercodechange">
						<option value="">Select Dealer </option>
						<?php
						for ($i=0; $i<$dlr_rows; $i++) {
							echo '<option style="width: auto;" value='.$dlr_list[$i]['dealercode'].'>'.$dlr_list[$i]['dealername'].' ('.$dlr_list[$i]['dealercode'].')</option>';
						}
						?>
					</select>
				</div>
				<div class="small-2 medium-4 large-4 columns">
					<input type="submit" value="Go" class="alert button postfix collapse_submit" id="dealercodesubmit" name="dealercodesubmit" >
				</div>
				<div class="small-4 columns">

				</div>
			</div>
		</form>
		</li>
    </ul>
    <ul class="right">
		<li class="divider"></li>
		<li class="has-dropdown">
			<a>Welcome, <?php echo $user->firstName;?></a>
			<ul class="dropdown">
				<li class="has-dropdown">
				<?php
				include('templates/menubar_sidecontents.php');
				?>
			</ul>
        </li>
      </ul>
    </section>
</nav>
</div>