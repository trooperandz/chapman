<?php
/*------------------------------------Surveys menu processing-----------------------------------*/
// Generate select survey menu items from table
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
/*-------------------------------------------Main menu-------------------------------------------*/
echo'
<div class="fixed">
<nav class="top-bar" data-topbar>
	<ul class="title-area">
		<li class="name"><h1><a href="#">'.constant('MANUF').' Comparison </a></h1></li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
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
						<form method="post" action="comparison_selectsurvey_process.php">
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
						<form method="post" action="comparison_selectallsurveys_process.php" style="margin: 0px;">
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
		<li class="has-dropdown">
			<a style="color: #46BCDE; font-weight: bold;"> Select '.constant('ENTITY').'s </a>
			<ul class="dropdown">
				<li class="divider"></li>
				<li><a data-reveal-id="dealersmodal" style="color: #FFFFFF;">Compare '.constant('ENTITY').'s</a></li>
					<div id="dealersmodal" class="small reveal-modal" style="background-color: #474747;" data-reveal>
						<div class="row">
						<h5 style=" text-align: center; color: #FFFFFF;">Compare '.constant('ENTITY').'s</h5>
						<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">
							<div class="medium-12 large-12 columns">
								<div class="medium-1 large-1 columns">
									<p> </p>
								</div>
								<div class="medium-10 large-10 columns">
									<form method="post" action="multidealercomparison_process.php" style="margin: 0px;">
										<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
										<h6 style="color: #000000; text-align: center;">'.constant('ENTITY').' vs. '.constant('ENTITY').':</h6>
										<input type="text" name="comparedealer1" id="comparedealer1" placeholder="I.e. 11111, 22222" style="margin: 0px;">
										<h6 style="color: #008cba; text-align: center; margin: 0px;">Vs.</h6>
										<input type="text" name="comparedealer2" id="comparedealer2" placeholder="I.e. 55555, 77777, 33333">
										<input type="submit" class="tiny button radius" value="Submit" name="submitdealercomparison" id="submitdealercomparison">
										</fieldset>
									</form>
							    	<hr style="margin-top: 0px; border-color: #909090;">
									<form method="post" action="multidealercomparisonglobal_process.php" style="margin: 0px;">
										<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
										<h6 style="color: #000000; text-align: center;">'.constant('ENTITY').' vs. All '.constant('ENTITY').'s: </h6>
										<input type="text" name="comparedealerall" id="comparedealerall" placeholder="Enter any '.constant('ENTITY').' combination">
										<input type="submit" name="viewallsubmit" class="tiny success button radius" value="Submit">
										</fieldset>	
									</form>
								</div>	
								<div class="medium-1 large-1 columns">
									<p> </p>
								</div>
							</div>
						</div>
						<a class="close-reveal-modal" style="font-size: 19px;">&#215;</a>
					</div>
				<li class="divider"></li>
				<li><a data-reveal-id="regionsmodal" style="color: #FFFFFF;">Compare Regions</a></li>
					<div id="regionsmodal" class="small reveal-modal" style="background-color: #474747;" data-reveal>
					<div class="row">
					<h5 style=" text-align: center; color: #FFFFFF;">Compare Regions</h5>
					<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">
						<div class="medium-12 large-12 columns">
							<div class="medium-1 large-1 columns">
								<p> </p>
							</div>
							<div class="medium-10 large-10 columns">
								<form method="post" action="dealerregioncomparison_process.php" style="margin: 0px;">
								<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
									<h6 style="color: #000000; text-align: center;">'.constant('ENTITY').'(s) vs. Region: </h6>
									<input type="text" name="comparedealerregion1" id="comparedealerregion1" placeholder="I.e. 11111, 22222" style="margin: 0px;">
									<h6 style="color: #008cba; text-align: center; margin: 0px;">Vs.</h6>
									<select name="compareregionID1" id="compareregionID1">
										<option value="">Select...</option>';

for ($i=0; $i<$regionrows; $i++){
echo									'<option value= '.$regionrow[$i]['regionID'].'>' .$regionrow[$i]['region']. '</option><br>';
}
echo'										
									</select>
									<input type="submit" class="tiny button radius" value="Submit">
								</fieldset>
								</form>
								<hr style="margin-top: 0px; border-color: #909090;">
								<form method="post" action="regioncomparison_process.php" style="margin: 0px;">
								<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
									<h6 style="color: #000000; text-align: center;">Region vs. Region: </h6>
									<select name="compareregion1" id="compareregion1" style="margin: 0px;">
										<option value="">Select...</option>';

for ($i=0; $i<$regionrows; $i++){
echo									'<option value= '.$regionrow[$i]['regionID'].'>' .$regionrow[$i]['region']. '</option><br>';
}
echo'										
									</select>
									<h6 style="color: #008cba; text-align: center; margin: 0px;">Vs.</h6>
									<select name="compareregion2" id="compareregion2">
										<option value="">Select...</option>';

for ($i=0; $i<$regionrows; $i++){
echo									'<option value= '.$regionrow[$i]['regionID'].'>' .$regionrow[$i]['region']. '</option><br>';
}
echo'									
									</select>
									<input type="submit" class="tiny success button radius" value="Submit">
								</fieldset>	
								</form>
								<hr style="margin-top: 0px; border-color: #909090;">
								<form method="post" action="regionglobalcomparison_process.php" style="margin: 0px;">
								<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
									<h6 style="color: #000000; text-align: center;">Region vs. All '.constant('ENTITY').'s: </h6>
									<select name="regionvsglobal" id="regionvsglobal">
										<option value="">Select...</option>';

for ($i=0; $i<$regionrows; $i++){
echo									'<option value= '.$regionrow[$i]['regionID'].'>' .$regionrow[$i]['region']. '</option><br>';
}
echo'									
									</select>
									<input type="submit" class="tiny button radius" value="Submit">
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
				<li class="divider"></li>
			</ul>
        </li>
		<li class="divider"></li>
		<li><a data-reveal-id="servicemodal" style="color: #D34836; font-weight: bold;">Select Services &raquo</a></li>
		<li class="divider"></li>
		<div id="servicemodal" class="large reveal-modal" style="background-color: #ffffff;" data-reveal>
		<form method="post" action="longhornsvcs_process.php">
			<div class="row">
				<div class="medium-12 large-12 columns">
				<h5>Select Services for Display:</h5>
					<fieldset style="padding-left: 0rem; padding-right: 2rem; background-color: #f2f2f2;">
						<div class="row">
							<div class="small-1 medium-1 large-1 columns">
								<p> </p>
							</div>
							<div class="small-6 medium-3 large-3 columns">';
								
								for ($i=0; $i<7; $i++) {
								echo'<input type="checkbox" class="longhornbox" name="longhornbox[]" value=' .$array[$i]['serviceID'].'> '.$array[$i]['service_nickname']. ' <br>';
								}
							echo'    
							</div>
							<div class="small-5 medium-2 large-2 columns">';
								
								for ($i=7; $i<14; $i++) {
								echo'<input type="checkbox" class="longhornbox" name="longhornbox[]" value=' .$array[$i]['serviceID'].'> '.$array[$i]['service_nickname']. ' <br>';
								}
							echo'
							</div>
							<div class="small-1 medium-1 large-1 columns">
								<p> </p>
							</div>
							<div class="small-5 medium-3 large-3 columns">';
								
								for ($i=14; $i<21; $i++) {
								echo'<input type="checkbox" class="longhornbox" name="longhornbox[]" value=' .$array[$i]['serviceID'].'> '.$array[$i]['service_nickname']. ' <br>';
								}
							echo'        
							</div>
							<div class="small-5 medium-2 large-2 columns">';
								
								for ($i=21; $i<26; $i++) {
								echo'<input type="checkbox" class="longhornbox" name="longhornbox[]" value=' .$array[$i]['serviceID'].'> '.$array[$i]['service_nickname']. ' <br>';
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
    </ul>
    <ul class="right">
		<li class="divider"></li>
		<li class="has-dropdown">
			<a>Welcome, ' .$user->firstName. '</a>
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