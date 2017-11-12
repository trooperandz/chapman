<?php
echo'
</head>
<body>';
/* ----------------------------------------------------------------------------------------*
   Program: menubar_dealer_modelyear_report.php

   Purpose: To display Model Year report menu bar with year 
			selection capability.

   History:
    Date		Description										by
	11/05/2014	Adapted original dealer menu bar to Model year	Matt Holland
				and added yearmodel_string processing
 
/*--------------------------------Survey type menu processing-----------------------------*/
// Generate modal menu items from table
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
/*--------------------------------Model Year menu years processing-----------------------------*/
// Get current server year for yearmodel query
$currentyear = date('Y');
// If it is after August, introduce next year selection by increasing $currentyear by 1
$month = date('m');
if ($month > 8) {
	$currentyear = $currentyear+1;
}
// echo $currentyear. '<br>';

// Query services table to retrieve values and labels for all checkboxes
$query = "SELECT yearmodelID, modelyear FROM yearmodel WHERE modelyear BETWEEN 1999 AND $currentyear
		  ORDER BY yearmodelID DESC";
$years_result = $mysqli->query($query);
$year_rows = $years_result->num_rows;
if (!$years_result) {
	$_SESSION['error'][] = "yearmodel SELECT query failed. See administrator";
}

$array = array(array());
$index = 0;
while ($checkboxlookup = $years_result->fetch_assoc()) {
	$array[$index]['yearmodelID'] = $checkboxlookup['yearmodelID'];
	$array[$index]['modelyear'] = $checkboxlookup['modelyear'];
	$index += 1;
}
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
		<li><a data-reveal-id="myModal" style="color: #CCCCCC; font-weight: bold;">Select Survey &raquo</a></li>
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
		<li><a data-reveal-id="servicemodal" style="color: #46BCDE; font-weight: bold;">Select Years &raquo</a></li>
		<li class="divider"></li>
		<div id="servicemodal" class="large reveal-modal" style="background-color: #ffffff;" data-reveal>
		<form method="post" action="yearmodelmenu_process.php">
			<div class="row">
				<div class="medium-12 large-12 columns">
				<h5>Select Years for Display:</h5>
					<fieldset style="padding-left: 0rem; padding-right: 2rem; background-color: #f2f2f2;">
						<div class="row">
							<div class="small-1 medium-1 large-1 columns">
								<p> </p>
							</div>
							<div class="small-6 medium-3 large-3 columns">';
								
								for ($i=0; $i<5; $i++) {
								echo'<input type="checkbox" class="yearmodelbox" name="yearmodelbox[]" value=' .$array[$i]['yearmodelID'].'> '.$array[$i]['modelyear']. ' <br>';
								}
								echo'   
							</div>
							<div class="small-5 medium-2 large-2 columns">';
								
								for ($i=5; $i<10; $i++) {
								echo'<input type="checkbox" class="yearmodelbox" name="yearmodelbox[]" value=' .$array[$i]['yearmodelID'].'> '.$array[$i]['modelyear']. ' <br>';
								}
								echo'
							</div>
							<div class="small-1 medium-1 large-1 columns">
								<p> </p>
							</div>
							<div class="small-5 medium-3 large-3 columns">';
								
								for ($i=10; $i<15; $i++) {
								echo'<input type="checkbox" class="yearmodelbox" name="yearmodelbox[]" value=' .$array[$i]['yearmodelID'].'> '.$array[$i]['modelyear']. ' <br>';
								}
								echo'       
							</div>
							<div class="small-5 medium-2 large-2 columns">';
								
								for ($i=15; $i<$year_rows; $i++) {
								echo'<input type="checkbox" class="yearmodelbox" name="yearmodelbox[]" value=' .$array[$i]['yearmodelID'].'> '.$array[$i]['modelyear']. ' <br>';
								}
								echo'
							</div>
						</div>
					</fieldset>
				</div>			
			</div>
			<div class="row">	
				<div class="small-12 medium-12 large-12 columns">
					<input type="checkbox" id="selectall"><label for="selectall"> Select All </label>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<input type="submit" value="Submit" class="tiny button radius">
				</div>
			</div>
		</form>
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