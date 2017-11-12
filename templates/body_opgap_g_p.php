<?php
echo'
</head>
<body>

<div id="rotitle">
	<h4>RO Survey</h4>	
</div>
	
<div id="date">
	'.(Date("l F d, Y")).'
</div>
<hr>
<div id="page1height">
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
	echo $chart_title;
	if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] != "") {
		echo ' - '.constant('MANUF').' Global ';
	} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] != "") {
		echo ' - '.constant('MANUF').' ' .$_SESSION['regionname']. ' Region ';
	} else {
		echo ' - All '.constant('MANUF').' '.constant('ENTITY').'s ';
	}
	if ($_SESSION['globalsurveyindexid_rows'] > 1) {
		echo '<h11 id="subtitle">(All Survey Types)</h11>';
	} else {
		echo '<h11 id="subtitle">(' .$_SESSION['globalsurvey_description']. 's)</h11>';
	}
echo'</h4>';

if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] !="") {
echo'<h11 id="subtitle">'.constant('ENTITY').'s: ' .$_SESSION['multidealercodes']. '</h11>';
}

echo'
	<div class="row">
		<div class="small-12 medium-12 large-12 columns">
			<div id='.$chart_div.'></div>
		</div>
	</div>
</div>

<hr>

<footer id="footer1">
	&copy;&nbsp; Service Operations Specialists Inc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12211 West Markham&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Little Rock, AR 72211&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;866-406-9707
</footer>

<div id="rotitle">
	<h4>RO Survey</h4>	
</div>
	
<div id="date">
	'.(Date("l F d, Y")).'
</div>  
<hr>

<div id="page2height">
	<div id="tabletitle">
		<h4>' .$tabletitle; 
		if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] != "") {
			echo ' - '.constant('MANUF').' Global ';
		} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] != "") {
			echo ' - '.constant('MANUF').' ' .$_SESSION['regionname']. ' Region ';
		} else {
			echo ' - All '.constant('MANUF').' '.constant('ENTITY').'s ';
		}
		if ($_SESSION['globalsurveyindexid_rows'] > 1) {
			echo '<h11 id="subtitle">(All Survey Types)</h11>';
		} else {
			echo '<h11 id="subtitle">(' .$_SESSION['globalsurvey_description']. 's)</h11>';
		}
echo'</h4>';
if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] !="") {
echo   '<h11 id="subtitle">'.constant('ENTITY').'s: ' .$_SESSION['multidealercodes']. '</h11>';
}
echo'
	</div>
	<div id="table">
		<table id='.$tableid.' class="tablesorter">
			<thead>
				<tr id="trmain"> 
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
// This is where the file itself builds the main body of the table