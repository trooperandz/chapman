<?php
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
?>	

<div class="fixed">
<nav class="top-bar" data-topbar>
  <!-- Title -->
	<ul class="title-area">
		<li class="name"><h1><a href="#">Nissan Global </a></h1></li>
		<!-- Mobile Menu Toggle -->
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
  <!-- Top Bar Section -->
	<section class="top-bar-section">
    <!-- Top Bar Left Nav Elements -->
    <ul class="left">
	<li class="divider"></li>
		<li><a data-reveal-id="surveymodal" style="color: #CCCCCC; font-weight: bold;">Select Survey &raquo</a></li>
		<li class="divider"></li>
		<div id="surveymodal" class="small reveal-modal" style="background-color: #ffffff;" data-reveal>
			<div class="row">
				<div class="medium-12 large-12 columns">
				<!--<h5 style=" text-align: center; color: #000000;">Select Survey</h5>
				<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">-->
					<div class="medium-1 large-1 columns">
						<p> </p>
					</div>
					<div class="medium-10 large-10 columns">
						<form method="post" action="global_selectsurvey_process.php">
							<!--<fieldset style="padding-bottom: 2px; background-color: #333333;">-->
							<h6 style="color: #008cba; text-align: center;">Select Dealer Survey </h6>
							<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">
							<select style="margin-top: 30px; margin-bottom: 20px;" name="survey_selection" id="survey_selection">
								<option value="">Select survey...</option>
<?php
for ($i=0; $i<3; $i++) {
echo'							<option value= '.$survey[$i]['surveyindex_id']. '>' .$survey[$i]['survey_description']. '</option>';
}
?>								
							</select>
							<input type="submit" class="tiny button radius" value="Submit">
							<!--</fieldset>-->
						</form>
					</div>
					<div class="medium-1 large-1 columns">
						<p>  </p>
					</div>
				</div>
			</div>
		    <a class="close-reveal-modal" style="font-size: 19px;">&#215;</a>
		</div>
<!---------------------------------------------------------------------------->	
		<li class="divider"></li>
		<li><a data-reveal-id="myModal" style="color: #46BCDE; font-weight: bold;">Select Dealers &raquo</a></li>
		<li class="divider"></li>
		<div id="myModal" class="small reveal-modal" style="background-color: #474747;" data-reveal>
			<div class="row">
				<div class="medium-12 large-12 columns">
				<h5 style=" text-align: center; color: #FFFFFF;">Select Dealers</h5>
				<hr style="margin-top: 0px; margin-bottom: 0px; border-color: #909090;">
					<div class="medium-1 large-1 columns">
						<p> </p>
					</div>
					<div class="medium-10 large-10 columns">
						<form method="post" action="multidealerglobal_process.php">
							<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
							<h6 style="color: #000000; text-align: center;">Combine Dealers:</h6>
							
							<input type="text" name="multidealer" id="multidealer" placeholder="Enter dealer codes separated by commas">
							<input type="submit" class="tiny button radius" value="Submit" name="submitmultidealer" id="submitmultidealer">
							</fieldset>
						</form>
						<hr style="margin-top: 0px; border-color: #909090;">
						<form method="post" action="dealerregionglobal_process.php">
							<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
							<h6 style="color: #000000; text-align: center;">View By Region: </h6>
							<select name="dealerregionID" id="dealerregionID">
								<option value="">Select region...</option>
								<option value="1">Central</option>
								<option value="2">Midwest</option>
								<option value="3">Northeast</option>
								<option value="4">Southeast</option>
								<option value="5">West</option>
							</select>
							<input type="submit" name="dealerregionsubmit" class="tiny success button radius" value="Submit">
							</fieldset>
						</form>
						<hr style="margin-top: 0px; border-color: #909090;">
						<form method="post" action="unsetmultidealer.php">
							<fieldset style="padding-bottom: 2px; background-color: #E0E0E0;">
							<h6 style="color: #000000; text-align: center">Select All Dealers: </h6>
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
    <!-- Top Bar Right Nav Elements -->
    <ul class="right">
		<!-- Divider -->
		<li class="divider"></li>
		<!-- Dropdown -->
		<li class="has-dropdown">
			<a><?php echo "Welcome, {$user->firstName}"; ?></a>
			<ul class="dropdown">
				<li class="has-dropdown">
				<a>Dealer Reports</a>
					<ul class="dropdown">
					<li><label>Dealer Reports</label></li>
					<li><a href="yearmodelqueryandchart.php">Model Year</a></li>
					<li><a href="mileagespreadqueryandchart.php">Mileage Spread</a></li>
					<li><a href="servicetypequeryandchart.php">Longhorn</a></li>
					<li><a href="lofdemandquery.php">LOF Demand</a></li>
					<li><a href="lofbaselinequery_column.php">LOF Baseline</a></li>
					<li><a href="singleissuequery.php">Single Issue %</a></li>
					<li><a href="singleissuecategory.php">Single Issue Cat</a></li>
					<li><a href="demand1and2query.php">Service Demand</a></li>
					<li><a style="color: #46BCDE;" href="viewalldealerqueryandchart.php">View All Reports</a></li>
					<li class="divider"></li>
					<li><a style="color: #A9A9A9;" href="dealer_printall.php">Print All Reports &raquo</a></li>
					<li><a style="color: #D34836;" href="csvexportall.php">Export All Data &raquo</a></li>
					</ul>
				</li>
				<li class="has-dropdown">
				<a>Global Reports</a>
					<ul class="dropdown">
					<li><label>All Nissan Dealers</label></li>
					<li><a href="yearmodelqueryandchartglobal.php">Model Year</a></li>
					<li><a href="mileagespreadqueryandchartglobal.php">Mileage Spread</a></li>
					<li><a href="servicetypequeryandchartglobal.php">Longhorn</a></li>
					<li><a href="lofdemandqueryglobal.php">LOF Demand</a></li>
					<li><a href="lofbaselinequery_columnglobal.php">LOF Baseline</a></li>
					<li><a href="singleissuequeryglobal.php">Single Issue %</a></li>
					<li><a href="singleissuecategoryglobal.php">Single Issue Cat</a></li>
					<li><a href="demand1and2queryglobal.php">Service Demand</a></li>
					<li><a style="color: #46BCDE;" href="viewallglobalqueryandchart.php">View All Reports</a></li>
					<li class="divider"></li>
					<li><a style="color: #A9A9A9;" href="global_printall.php">Print All Reports &raquo</a></li>
					<li><a style="color: #D34836;" href="csvexportallglobal.php">Export All Data &raquo</a></li>
					</ul>
				</li>
				<li class="has-dropdown">
				<a>Comparison Reports</a>
					<ul class="dropdown">
					<li><label>Comparison Reports</label></li>
					<li><a href="yearmodelqueryandchartcomparison.php">Model Year</a></li>
					<li><a href="mileagespreadqueryandchartcomparison.php">Mileage Spread</a></li>
					<li><a href="servicetypequeryandchartcomparison.php">Longhorn</a></li>
					<li><a href="lofdemandquerycomparison.php">LOF Demand</a></li>
					<li><a href="lofbaselinequery_columncomparison.php">LOF Baseline</a></li>
					<li><a href="singleissuequerycomparison.php">Single Issue %</a></li>
					<li><a href="singleissuecategorycomparison.php">Single Issue Cat</a></li>
					<li><a href="demand1and2querycomparison.php">Service Demand</a></li>
					<li><a style="color: #46BCDE;" href="viewallcomparisonqueryandchart.php">View All Reports</a></li>
					<li class="divider"></li>
					<li><a style="color: #A9A9A9;" href="comparison_printall.php">Print All Reports &raquo</a></li>
					<li><a style="color: #D34836;" href="csvexportallcomparison.php">Export All Data &raquo</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="enterrofoundation.php">Enter ROs</a></li>
				<li class="divider"></li>
				<li><a href="roimpact.php">Express Impact</a></li>
				<li class="divider"></li>
				<li><a href="filemanager.php">File Bin</a></li>
				<li class="divider"></li>
				<li><a href="admin-process.php">Admin</a></li>
				<li class="divider"></li>
				<li><a href="logout.php">Logout</a></li>
				<li class="divider"></li>
			</ul>
        </li>
      </ul>
    </section>
</nav> 
</div>