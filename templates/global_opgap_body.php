<?php
echo'
<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-10 large-10 columns">
				<h4>';
				if (isset($_SESSION['globalsurvey_description'])) {
					if (isset($_SESSION['globalsurveyindexid_rows'])) {
						$globalsurvey_description = $_SESSION['globalsurvey_description'];
						if ($_SESSION['globalsurveyindexid_rows'] > 1) {
							$globalsurvey_description = ' (All Survey Types)';
						} else {
							$globalsurvey_description = ' (' .$_SESSION['globalsurvey_description']. 's)';
						}
					}
				}
// Main headings
echo			$chart_title;
				if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] != "") {
						echo ' - '.constant('MANUF').' Global ';
					/*----------------------------------------------------------------------------------------------------------*/
					} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] != "") {
						echo ' - '.constant('MANUF').' ' .$_SESSION['regionname']. ' Region';
					/*----------------------------------------------------------------------------------------------------------*/
					} else {
						echo ' - All '.constant('MANUF').' '.constant('ENTITY').'s ';
					/*----------------------------------------------------------------------------------------------------------*/
					}
					echo '<span style="font-size: 17px; color: #A9A9A9;">' .$globalsurvey_description. '</span>
				</h4>
			</div>
			<div class="small-12 medium-2 large-2 columns">
				<a href=' .$printeranchor. '>Printer Friendly </a><br><br>
			</div>
		</div>
	</div>
</div>';

// Subheadings
if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] !="") {
  echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').'s Selected: ' .$_SESSION['multidealercodes']. '</h11>
			</div>
		</div>';
} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] !="") {
  echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">' .$_SESSION['dealerIDrocount']. ' of ' .$_SESSION['totalregiondealers']. ' total '.constant('ENTITYLCASE').'s in the region have repair orders. </h11>
			</div>
		</div>';
} else {
  echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;"> Total Active '.constant('ENTITY').'s: ' .$totaldealers_persurvey. '</h11>
			</div>
		</div>';
}

// Chart div
echo'
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<div id=' .$chart_div. '></div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<hr>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p>  </p>
	</div>
</div>
<div class="row">
		<div class="medium-12 large-12 columns">
			<div class="row">
				<div class="small-12 medium-10 large-10 columns">
					<h4>'.$tabletitle;
					if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] != "") {
						echo ' - '.constant('MANUF').' Global ';
					/*----------------------------------------------------------------------------------------------------------*/
					} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] != "") {
						echo ' - '.constant('MANUF').' ' .$_SESSION['regionname']. ' Region';
					/*----------------------------------------------------------------------------------------------------------*/
					} else {
						echo ' - All '.constant('MANUF').' '.constant('ENTITY').'s ';
					/*----------------------------------------------------------------------------------------------------------*/
					}
					echo '<span style="font-size: 17px; color: #A9A9A9;">' .$globalsurvey_description. '</span>
					</h4>
				</div>
				<div class="small-12 medium-2 large-2 columns">
					<a href='.$exportanchor. '> Export Data </a><br><br>
				</div>
			</div>
		</div>
	 </div>';
	 
	 if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] !="") {
	  echo '<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<h11 style="color: #A9A9A9;">'.constant('ENTITY').'s Selected: ' .$_SESSION['multidealercodes']. '</h11>
				</div>
			</div>';
	} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] !="") {
	  echo '<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<h11 style="color: #A9A9A9;">' .$_SESSION['dealerIDrocount']. ' of ' .$_SESSION['totalregiondealers']. ' total '.constant('ENTITYLCASE').'s in the region have repair orders. </h11>
				</div>
			</div>';
	} else {
	  echo '<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<h11 style="color: #A9A9A9;"> Total Active '.constant('ENTITY').'s: ' .$totaldealers_persurvey. '</h11>
				</div>
			</div>';
	}
	
// Build table	
echo'<div class="row">
		<div class="small-12 large-12 medium-12 columns">
			<p> &nbsp; </p>
		</div>
	</div>
	 <div class="row">
		<div class="medium-12 large-12 columns">
			<div class="row">
				<div class="medium-3 large-3 columns">
					<p> </p>
				</div>
				<div class="small-12 large-6 medium-6 columns">
					<table id=' .$tableid. ' class="tablesorter">
					<thead>
						<tr> 
							<th>' .$tablehead1. '</th>'; 
							if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] != "") {
								echo '<th>'.constant('ENTITY').' Grp</th>';
							} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] != "") {
								echo '<th>' .$_SESSION['regionname']. ' Region</th>';
							} else {
								echo '<th>All '.constant('ENTITY').'s</th>';
							}
							echo'
							<th>' .$tablehead3. '</th> 
							<th>' .$tablehead4. '</th>
						</tr>
					</thead>
					<tbody>';