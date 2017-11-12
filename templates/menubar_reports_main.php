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
/*-------------------------------------------Main menu-------------------------------------------*/
echo'
<div class="fixed">
<nav class="top-bar" data-topbar>
	<ul class="title-area">
		<li class="name"><h1><a>'.constant('MANUF').' - '.constant('ENTITY').' '.$_SESSION['dealercode'].'</a></h1></li>
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
							<h6 style="color: #008cba; text-align: center;">Select '.constant('ENTITY').' Survey </h6>
							<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">
							<select style="margin-top: 30px; margin-bottom: 20px;" name="survey_selection" id="survey_selection">
								<option value="">Select survey...</option>';

for ($i=0; $i<3; $i++) {
echo'							<option value= '.$survey[$i]['surveyindex_id']. '>' .$survey[$i]['survey_description']. '</option>';
}
echo'							
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
				<div class="large-8 small-9 columns">
					<input style="height: 1.8rem;" type="text" id="dealercodechange" name="dealercodechange" placeholder="Change '.constant('ENTITY').'">
				</div>
				<div class="large-4 small-3 columns">
					<input type="submit" id="dealercodesubmit" name="dealercodesubmit" value="Go" class="alert button expand">
				</div>
			</div>
		</form>
		</li>
    </ul>
    <ul class="right">
		<li class="divider"></li>
		<li class="has-dropdown">
			<a>Welcome, '.$user->firstName.'</a>
			<ul class="dropdown">
				<li class="has-dropdown">';
				include('templates/menubar_sidecontents.php');
				echo'
			</ul>
        </li>
      </ul>
    </section>
</nav> 
</div>';