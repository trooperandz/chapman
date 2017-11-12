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
		echo ' - Nissan Dealer Comparison ';
	} elseif (isset($_SESSION['compareglobalIDs'])) {
		echo ' - Nissan Global Comparison ';
	} elseif ((isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) OR (isset($_SESSION['regionvsglobalIDs'])))	{
		echo ' - Nissan Region Comparison ';
	} else {
		echo ' - Nissan Dealer ' .$dealercode. ' Comparison ';
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
		<h11 id="subtitle">Group 1 Dealers: ' .$_SESSION['comparedealer1codes']. '</h11><br>
		<h11 id="subtitle">Group 2 Dealers: ' .$_SESSION['comparedealer2codes']. '</h11>';
	} else {
		echo'
		<h11 id="subtitle">Dealer ' .$_SESSION['comparedealer1codes']. ' &nbsp; Vs. &nbsp; Dealer ' .$_SESSION['comparedealer2codes']. '</h11>';
	}
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1 ) {
	echo'
	<h11 id="subtitle">Dealer ' .$_SESSION['compareglobalcodes']. ' &nbsp; Vs. &nbsp; All Dealers </h11>';
	} else {	  
	echo'
	<h11 id="subtitle">Dealers Selected: ' .$_SESSION['compareglobalcodes']. '</h11>';
	}
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'
	<h11 id="subtitle">Dealer ' .$_SESSION['comparedealerregion1codes']. ' &nbsp; Vs. &nbsp; ' .$_SESSION['compareregionname1']. ' Region </h11>';
	} else {	  
	echo '
	<h11 id="subtitle">Dealers Selected: ' .$_SESSION['comparedealerregion1codes']. '</h11><br>
	<h11 id="subtitle">Region Selected: ' .$_SESSION['compareregionname1']. '</h11>';
	}
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {	  
	echo'
	<h11 id="subtitle">' .$_SESSION['regionname1']. ' Region Vs. ' .$_SESSION['regionname2']. ' Region </h11>';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {	  
	echo'
	<h11 id="subtitle">' .$_SESSION['regionvsglobalname']. ' Region &nbsp; Vs. &nbsp; All Dealers </h11>	  
}		  
	/*-----------------------------------------*/
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
		<h4>' .$chart_title;
	/*-----------------------------------------*/
	if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
		echo ' - Nissan Dealer Comparison ';
	} elseif (isset($_SESSION['compareglobalIDs'])) {
		echo ' - Nissan Global Comparison ';
	} elseif ((isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) OR (isset($_SESSION['regionvsglobalIDs'])))	{
		echo ' - Nissan Region Comparison ';
	} else {
		echo ' - Nissan Dealer ' .$dealercode. ' Comparison ';
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
		<h11 id="subtitle">Group 1 Dealers: ' .$_SESSION['comparedealer1codes']. '</h11><br>
		<h11 id="subtitle">Group 2 Dealers: ' .$_SESSION['comparedealer2codes']. '</h11>';
	} else {
		echo'
		<h11 id="subtitle">Dealer ' .$_SESSION['comparedealer1codes']. ' &nbsp; Vs. &nbsp; Dealer ' .$_SESSION['comparedealer2codes']. '</h11>';
	}
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1 ) {
	echo'
	<h11 id="subtitle">Dealer ' .$_SESSION['compareglobalcodes']. ' &nbsp; Vs. &nbsp; All Dealers </h11>';
	} else {	  
	echo'
	<h11 id="subtitle">Dealers Selected: ' .$_SESSION['compareglobalcodes']. '</h11>';
	}
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'
	<h11 id="subtitle">Dealer ' .$_SESSION['comparedealerregion1codes']. ' &nbsp; Vs. &nbsp; ' .$_SESSION['compareregionname1']. ' Region </h11>';
	} else {	  
	echo '
	<h11 id="subtitle">Dealers Selected: ' .$_SESSION['comparedealerregion1codes']. '</h11><br>
	<h11 id="subtitle">Region Selected: ' .$_SESSION['compareregionname1']. '</h11>';
	}
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {	  
	echo'
	<h11 id="subtitle">' .$_SESSION['regionname1']. ' Region Vs. ' .$_SESSION['regionname2']. ' Region </h11>';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {	  
	echo'
	<h11 id="subtitle">' .$_SESSION['regionvsglobalname']. ' Region &nbsp; Vs. &nbsp; All Dealers </h11>';		  
}		  
	/*-----------------------------------------*/
echo'
	</div>
	<div id=table">
			<table id='.$table_id.' class="tablesorter">
				<thead>
					<tr id="trmain"> 
						<th>' .$tablehead1. '</th>'; 
						if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
	if ($_SESSION['dealerarraysize1'] == 1 && $_SESSION['dealerarraysize2'] == 1) {
	echo' 					<th>Dealer ' .$_SESSION['comparedealer1codes']. '</th>
							<th>Dealer ' .$_SESSION['comparedealer2codes']. '</th>';
	} else {
	echo' 					<th>Dealer Grp 1</th>
							<th>Dealer Grp 2</th>';
	}						
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'					<th>Dealer ' .$_SESSION['compareglobalcodes'].'</th>
							<th>All Dealers</th>';
	} else {
	echo'					<th>Dealer Set</th>
							<th>All Dealers</th>';
	}						
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'					<th>Dealer ' .$_SESSION['comparedealerregion1codes']. '</th>
							<th>' .$_SESSION['compareregionname1']. ' Region</th>';
	} else {
	echo'					<th>Dealer Set</th>
							<th>' .$_SESSION['compareregionname1']. ' Region</th>';
	}	
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {
	echo'					<th>' .$_SESSION['regionname1']. ' Region</th>
							<th>' .$_SESSION['regionname2']. ' Region</th>';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {
	echo'					<th>' .$_SESSION['regionvsglobalname']. ' Region </th>
							<th>	All Dealers					 </th>';
} else {	
	echo'					<th>Dealer ' .$dealercode. '</th>
							<th>     All Dealers	    </th>';
}		
						
echo'					</tr>
				</thead>
			<tbody>';
// This is where the file itself builds the main body of the table