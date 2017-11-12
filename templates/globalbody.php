<?php
echo'
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<div class="panel" style="padding-bottom: 10px;">
			<div class="row">
				<div class="small-5 medium-6 large-6 columns" style="float: left;">
					<h3 style="text-align: left; color: #707070; margin-top: 3px;">RO Survey</h3> 
				</div>
				<div class="small-7 medium-6 large-6 columns" style="float: right;">
					<h4 style="text-align: right; margin-top: 8px; font-size: 15px; color: #707070;">'.(Date("l, F d")).'</h4>  
				</div>
			</div>	
		</div>	
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns" style="color: #FF0000; font-weight: bold;">';
			if (isset($_SESSION['error'])) {
				$num_errors = sizeof($_SESSION['error']);
				for ($i=0; $i < $num_errors; $i++) {
					print $_SESSION['error'][$i]. "<br />\n";
				} //end foreach 
				unset($_SESSION['error']);
			}
			if (isset($_SESSION['success'])) {
				$num_errors = sizeof($_SESSION['success']);
				for ($i=0; $i < $num_errors; $i++) {
					print $_SESSION['success'][$i]. "<br />\n";
				} //end foreach 
				unset($_SESSION['success']);
			}
		echo'
	</div>
</div>
<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-10 large-10 columns">
				<h4>';
					// Process survey type report headings (if all survey types, echo "All survey Types")
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
					echo $chart_title; 
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
					echo '<span style="font-size: 17px; color: #A9A9A9;">' .$globalsurvey_description. '</span></h4>
				</h4>
			</div>
			<div class="small-12 medium-2 large-2 columns">
				<a href='.$printeranchor.'>Printer Friendly </a>
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
echo'
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<div id='.$chart_div.'  style="height: '.$chart_height.'px;"></div>
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
					<h4>' .$tabletitle;
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
					<a href='.$exportanchor. '> Export Data </a>
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
					<table id='.$tableid.' class="tablesorter">
					<thead>
						<tr> 
							<th>' .$tablehead1. '</th> 
							<th>' .$tablehead2.	'</th>
							<th>' .$tablehead3.	'</th>
						</tr>
					</thead>
					<tbody>';