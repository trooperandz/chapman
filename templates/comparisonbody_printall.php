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
	/*-----------------------------------------*/
	if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
		echo ' - '.constant('MANUF').' '.constant('ENTITY').' Comparison ';
	} elseif (isset($_SESSION['compareglobalIDs'])) {
		echo ' - '.constant('MANUF').' Global Comparison ';
	} elseif ((isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) OR (isset($_SESSION['regionvsglobalIDs'])))	{
		echo ' - '.constant('MANUF').' Region Comparison ';
	} else {
		echo ' - '.constant('MANUF').' '.constant('ENTITY').' Comparison ';
	}
	/*-----------------------------------------*/
	if ($_SESSION['comparesurveyindexid_rows'] > 1) {
		echo '<hll id="subtitle">(All Survey Types)</h11>';
	} else {
		echo '<hll id="subtitle">(' .$_SESSION['comparisonsurvey_description']. 's)</h11>';
	}
echo'</h4>';
	/*-----------------------------------------*/
if (isset($_SESSION['comparedealer1IDs']) && isset($_SESSION['comparedealer2IDs'])) {
	if ($_SESSION['dealerarraysize1'] > 1 OR $_SESSION['dealerarraysize2'] > 1) {
		echo'
		<h11 id="subtitle">Group 1 '.constant('ENTITY').'s: ' .$_SESSION['comparedealer1codes']. '</h11><br>
		<h11 id="subtitle">Group 2 '.constant('ENTITY').'s: ' .$_SESSION['comparedealer2codes']. '</h11>';
	} else {
		echo'
		<h11 id="subtitle">'.constant('ENTITY').' ' .$_SESSION['comparedealer1codes']. ' &nbsp; Vs. &nbsp; '.constant('ENTITY').' ' .$_SESSION['comparedealer2codes']. '</h11>';
	}
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1 ) {
	echo'
	<h11 id="subtitle">'.constant('ENTITY').' ' .$_SESSION['compareglobalcodes']. ' &nbsp; Vs. &nbsp; All '.constant('ENTITY').'s </h11>';
	} else {	  
	echo'
	<h11 id="subtitle">'.constant('ENTITY').'s Selected: ' .$_SESSION['compareglobalcodes']. '</h11>';
	}
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'
	<h11 id="subtitle">'.constant('ENTITY').' ' .$_SESSION['comparedealerregion1codes']. ' &nbsp; Vs. &nbsp; ' .$_SESSION['compareregionname1']. ' Region </h11>';
	} else {	  
	echo '
	<h11 id="subtitle">'.constant('ENTITY').'s Selected: ' .$_SESSION['comparedealerregion1codes']. '</h11><br>
	<h11 id="subtitle">Region Selected: ' .$_SESSION['compareregionname1']. '</h11>';
	}
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {	  
	echo'
	<h11 id="subtitle">' .$_SESSION['regionname1']. ' Region Vs. ' .$_SESSION['regionname2']. ' Region </h11>';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {	  
	echo'
	<h11 id="subtitle">' .$_SESSION['regionvsglobalname']. ' Region &nbsp; Vs. &nbsp; All '.constant('ENTITY').'s </h11>';  
} else {
	echo '
	<h11 id="subtitle">'.constant('ENTITY').' '.$dealercode.'&nbsp; Vs. &nbsp; All '.constant('ENTITY').'s</h11>';
}		  
	/*-----------------------------------------*/
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
	/*-----------------------------------------*/
	if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
		echo ' - '.constant('MANUF').' '.constant('ENTITY').' Comparison ';
	} elseif (isset($_SESSION['compareglobalIDs'])) {
		echo ' - '.constant('MANUF').' Global Comparison ';
	} elseif ((isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) OR (isset($_SESSION['regionvsglobalIDs'])))	{
		echo ' - '.constant('MANUF').' Region Comparison ';
	} else {
		echo ' - '.constant('MANUF').' '.constant('ENTITY').' Comparison ';
	}
	/*-----------------------------------------*/
	if ($_SESSION['comparesurveyindexid_rows'] > 1) {
		echo '<hll id="subtitle">(All Survey Types)</h11>';
	} else {
		echo '<hll id="subtitle">(' .$_SESSION['comparisonsurvey_description']. 's)</h11>';
	}
echo'</h4>';
	/*-----------------------------------------*/
if (isset($_SESSION['comparedealer1IDs']) && isset($_SESSION['comparedealer2IDs'])) {
	if ($_SESSION['dealerarraysize1'] > 1 OR $_SESSION['dealerarraysize2'] > 1) {
		echo'
		<h11 id="subtitle">Group 1 '.constant('ENTITY').'s: ' .$_SESSION['comparedealer1codes']. '</h11><br>
		<h11 id="subtitle">Group 2 '.constant('ENTITY').'s: ' .$_SESSION['comparedealer2codes']. '</h11>';
	} else {
		echo'
		<h11 id="subtitle">'.constant('ENTITY').' ' .$_SESSION['comparedealer1codes']. ' &nbsp; Vs. &nbsp; '.constant('ENTITY').' ' .$_SESSION['comparedealer2codes']. '</h11>';
	}
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1 ) {
	echo'
	<h11 id="subtitle">'.constant('ENTITY').' ' .$_SESSION['compareglobalcodes']. ' &nbsp; Vs. &nbsp; All '.constant('ENTITY').'s </h11>';
	} else {	  
	echo'
	<h11 id="subtitle">'.constant('ENTITY').'s Selected: ' .$_SESSION['compareglobalcodes']. '</h11>';
	}
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'
	<h11 id="subtitle">'.constant('ENTITY').' ' .$_SESSION['comparedealerregion1codes']. ' &nbsp; Vs. &nbsp; ' .$_SESSION['compareregionname1']. ' Region </h11>';
	} else {	  
	echo '
	<h11 id="subtitle">'.constant('ENTITY').'s Selected: ' .$_SESSION['comparedealerregion1codes']. '</h11><br>
	<h11 id="subtitle">Region Selected: ' .$_SESSION['compareregionname1']. '</h11>';
	}
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {	  
	echo'
	<h11 id="subtitle">' .$_SESSION['regionname1']. ' Region Vs. ' .$_SESSION['regionname2']. ' Region </h11>';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {	  
	echo'
	<h11 id="subtitle">' .$_SESSION['regionvsglobalname']. ' Region &nbsp; Vs. &nbsp; All '.constant('ENTITY').'s </h11>';		  
} else {
	echo '
	<h11 id="subtitle">'.constant('ENTITY').' '.$dealercode.'&nbsp; Vs. &nbsp; All '.constant('ENTITY').'s</h11>';
}		  
	/*-----------------------------------------*/
echo'
	</div>
	<div id="table">
			<table id='.$tableid.' class="tablesorter">
				<thead>
					<tr id="trmain"> 
						<th>' .$tablehead1. '</th>'; 
						if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
	if ($_SESSION['dealerarraysize1'] == 1 && $_SESSION['dealerarraysize2'] == 1) {
	echo' 					<th>'.constant('ENTITY').' ' .$_SESSION['comparedealer1codes']. '</th>
							<th>'.constant('ENTITY').' ' .$_SESSION['comparedealer2codes']. '</th>';
	} else {
	echo' 					<th>'.constant('ENTITY').' Grp 1</th>
							<th>'.constant('ENTITY').' Grp 2</th>';
	}						
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'					<th>'.constant('ENTITY').' ' .$_SESSION['compareglobalcodes'].'</th>
							<th>All '.constant('ENTITY').'s</th>';
	} else {
	echo'					<th>'.constant('ENTITY').' Set</th>
							<th>All '.constant('ENTITY').'s</th>';
	}						
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'					<th>'.constant('ENTITY').' ' .$_SESSION['comparedealerregion1codes']. '</th>
							<th>' .$_SESSION['compareregionname1']. ' Region</th>';
	} else {
	echo'					<th>'.constant('ENTITY').' Set</th>
							<th>' .$_SESSION['compareregionname1']. ' Region</th>';
	}	
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {
	echo'					<th>' .$_SESSION['regionname1']. ' Region</th>
							<th>' .$_SESSION['regionname2']. ' Region</th>';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {
	echo'					<th>' .$_SESSION['regionvsglobalname']. ' Region </th>
							<th>	All '.constant('ENTITY').'s					 </th>';
} else {	
	echo'					<th>'.constant('ENTITY').' ' .$dealercode. '</th>
							<th>     All '.constant('ENTITY').'s	    </th>';
}		
						
echo'					</tr>
				</thead>
			<tbody>';