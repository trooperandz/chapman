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

/*--------------------------------Longhorn service menu processing-----------------------------*/
// Query services table to retrieve values and labels for all checkboxes
$query = "SELECT serviceID, service_nickname FROM services
		  ORDER BY servicesort ASC";
$menuresult = $mysqli->query($query);
if (!$menuresult) {
	$_SESSION['error'][] = "services SELECT query failed.";
}

$array = array(array());
$index = 0;
while ($checkboxlookup = $menuresult->fetch_assoc()) {
	$array[$index]['serviceID'] = $checkboxlookup['serviceID'];
	$array[$index]['service_nickname'] = $checkboxlookup['service_nickname'];
	$index += 1;
}
/*------------------------------------Regions menu processing-----------------------------------*/
// Select values from tables for region dropdown menu
$query = "SELECT regionID, region FROM dealerregion";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "dealerregion SELECT query failed.  See administrator.";
}
$regionrows = $result->num_rows;

$regionrow = array(array());
$i = 0;
while ($value = $result->fetch_assoc()) {
	$regionrow[$i]['regionID'] = $value['regionID']	;
	$regionrow[$i]['region']   = $value['region']	;
	$i += 1;
}
/*-------------------------------------------Main menu-------------------------------------------*/
echo'
<div class="fixed">
<nav class="top-bar" data-topbar>
	<ul class="title-area">
		<li class="name"><h1><a>'.constant('MANUF').' Global </a></h1></li>
		<li class="toggle-topbar menu-icon"><a><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
    <ul class="left">
	<li class="divider"></li>
		<li><a data-reveal-id="surveymodal" style="color: #CCCCCC; font-weight: bold;">Select Survey &raquo</a></li>
		<li class="divider"></li>
		<div id="surveymodal" class="small reveal-modal" style="background-color: #ffffff;" data-reveal>
			<div class="row">
				<div class="medium-12 large-12 columns">
					<div class="medium-1 large-1 columns">
						<p> </p>
					</div>
					<div class="medium-10 large-10 columns">
						<form method="post" action="global_selectsurvey_process.php">
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
						<hr style="margin-top: 0px; border-color: #909090;">
						<form method="post" action="global_selectallsurveys_process.php" style="margin: 0px;">
							<h6 style="color: #000000; text-align: center;">Select All Surveys: </h6>
							<input type="submit" name="submitallsurveys" id="submitallsurveys" class="tiny success button radius" value="Submit">
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
		<li><a data-reveal-id="myModal" style="color: #46BCDE; font-weight: bold;">Select '.constant('ENTITY').'s &raquo</a></li>
		<li class="divider"></li>
		<div id="myModal" class="small reveal-modal" style="background-color: #474747;" data-reveal>
			<div class="row">
				<div class="medium-12 large-12 columns">
				<h5 style=" text-align: center; color: #FFFFFF;">Select '.constant('ENTITY').'s</h5>
				<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">
					<div class="medium-1 large-1 columns">
						<p> </p>
					</div>
					<div class="medium-10 large-10 columns">
						<form method="post" action="multidealerglobal_process.php">
							<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
								<h6 style="color: #000000; text-align: center;">Combine '.constant('ENTITY').'s:</h6>
								<input type="text" name="multidealer" id="multidealer" placeholder="Enter dealer codes separated by commas">
								<input type="submit" class="tiny button radius" value="Submit" name="submitmultidealer" id="submitmultidealer">
							</fieldset>
						</form>
						<hr style="margin-top: 0px; border-color: #909090;">
						<form method="post" action="dealerregionglobal_process.php">
							<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
								<h6 style="color: #000000; text-align: center;">View By Region: </h6>
								<select name="dealerregionID" id="dealerregionID">
								<option value="">Select...</option>';

for ($i=0; $i<$regionrows; $i++){
echo									'<option value= '.$regionrow[$i]['regionID'].'>' .$regionrow[$i]['region']. '</option><br>';
}
echo'
								</select>
								<input type="submit" name="dealerregionsubmit" class="tiny success button radius" value="Submit">
							</fieldset>
						</form>
						<hr style="margin-top: 0px; border-color: #909090;">
						<form method="post" action="unsetmultidealer.php">
							<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
							<h6 style="color: #000000; text-align: center">Select All '.constant('ENTITY').'s: </h6>
							<input type="submit" name="viewallsubmit" class="tiny button radius" value="Submit">
							</fieldset>
						</form>
					</div>
					<div class="medium-1 large-1 columns">
						<p>  </p>
					</div>
				</div>
			</div>
		    <a class="close-reveal-modal" style="font-size: 19px;">&#215;</a>
		</div>
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