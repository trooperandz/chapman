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
	<h4>'.$chart_title.' - '.constant('MANUF').' '.constant('ENTITY').' ' .$dealercode.'<span style="color: #CCCCCC; font-size: 13px;">&nbsp;('.$_SESSION['survey_description'].') </span> </h4>
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
		<h4>' .$tabletitle. ' - '.constant('MANUF').' '.constant('ENTITY').' ' .$dealercode. ' <span style="color: #CCCCCC; font-size: 13px;">(' .$_SESSION['survey_description']. ') </span> </h4>
	</div>
	
	<div id="table">
		<table id='.$tableid.' class="tablesorter">
			<thead>
				<tr id="trmain"> 
					<th>' .$tablehead1. '</th> 
					<th>' .$tablehead2. '</th>
					<th>' .$tablehead3. '</th>
					<th>' .$tablehead4. '</th>
				</tr>
			</thead>
			<tbody>';
// This is where the file itself builds the main body of the table