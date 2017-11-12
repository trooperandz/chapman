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
	<h4>' .$chart_title;
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
echo'<div class="row">
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
						<th>' .$tablehead1. '</th> 
						<th>' .$tablehead2. '</th>
						<th>' .$tablehead3. '</th>
					</tr>
				</thead>
				<tbody>';	
// This is where the file itself builds the main body of the table