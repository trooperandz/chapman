<?php
/*------------------------------------------------------------Top Panel-------------------------------------------------------*/
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
</div>';
/*-----------------------------------------------------------Error Reporting--------------------------------------------------*/
echo'
<div class="row">
	<div class="small-12 medium-12 large-12 columns" style="color: #FF0000; font-weight: bold;">';
			if (isset($_SESSION['error'])) {
				$num_errors = sizeof($_SESSION['error']);
				for ($i=0; $i < $num_errors; $i++) {
					print $_SESSION['error'][$i]. "<br />\n";
				} // End for loop
				unset($_SESSION['error']);
			}
		echo'
	</div>
</div>';
/*---------------------------------------------------------Main title 1-------------------------------------------------------*/
echo'
<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-10 large-10 columns">
			<h4>';
				// Process survey type report headings (if all survey types, echo "All survey Types")
				if (isset($_SESSION['comparisonsurvey_description'])) {
					if (isset($_SESSION['comparisonsurveyindexid_rows'])) {
						$comparisonsurvey_description = '(' .$_SESSION['comparisonsurvey_description']. 's)';
						if ($_SESSION['comparisonsurveyindexid_rows'] > 1) {
							$comparisonsurvey_description = '(All Survey Types)';
						} else {
							$comparisonsurvey_description = '(' .$_SESSION['comparisonsurvey_description']. 's)';
						}
					}
				}
				echo $chart_title;
				if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
				echo ' - '.constant('MANUF').' '.constant('ENTITY').' Comparison ';
				/*----------------------------------------------------------------------------------------------------------*/	
				} elseif (isset($_SESSION['compareglobalIDs'])) {
					echo ' - '.constant('MANUF').' Global Comparison ';
				/*----------------------------------------------------------------------------------------------------------*/	
				} elseif ((isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) OR (isset($_SESSION['regionvsglobalIDs'])))	{
					echo ' - '.constant('MANUF').' Region Comparison ';
				/*----------------------------------------------------------------------------------------------------------*/	
				} else {
					echo ' - '.constant('MANUF').' '.constant('ENTITY').' Comparison ';
				/*----------------------------------------------------------------------------------------------------------*/
				}
				echo '<span style="font-size: 17px; color: #A9A9A9;">' .$comparisonsurvey_description. '</span>
				</h4>	
			</div>
			<div class="small-12 medium-2 large-2 columns">
				<a href='.$printeranchor.'>Printer Friendly </a><br><br>
			</div>
		</div>
	</div>
</div>';
/*---------------------------------------------------------Subtitle 1------------------------------------------------------*/
if (isset($_SESSION['comparedealer1IDs']) && isset($_SESSION['comparedealer2IDs'])) {
	if ($_SESSION['dealerarraysize1'] > 1 OR $_SESSION['dealerarraysize2'] > 1) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">Group 1 '.constant('ENTITY').'s: ' .$_SESSION['comparedealer1codes']. '</h11><br><br>
				<h11 style="color: #A9A9A9;">Group 2 '.constant('ENTITY').'s: ' .$_SESSION['comparedealer2codes']. '<br></h11>
			</div>
		  </div>';
	} else {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' ' .$_SESSION['comparedealer1codes']. ' &nbsp; Vs. &nbsp; '.constant('ENTITY').' ' .$_SESSION['comparedealer2codes']. '</h11><br><br>
			</div>
		  </div>';
	}		
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1 ) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' Selected: ' .$_SESSION['compareglobalcodes']. ' &nbsp; | &nbsp; Total active '.constant('ENTITYLCASE').'s: ' .$totaldealers_persurvey. '</h11><br><br>
			</div>
		  </div>';
	} else {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').'s Selected: ' .$_SESSION['compareglobalcodes']. ' &nbsp; | &nbsp; Total active '.constant('ENTITYLCASE').'s: ' .$totaldealers_persurvey. '</h11><br><br>
			</div>
		  </div>';
	}
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' Selected: ' .$_SESSION['comparedealerregion1codes']. '<br><br></h11>
				<h11 style="color: #A9A9A9;">Region Selected: ' .$_SESSION['compareregionname1']. ' ('.$_SESSION['comparisondealerIDrocount']. ' of ' .$_SESSION['totalcompareregiondealers1']. ' total '.constant('ENTITYLCASE').'s have records)<br></h11>
			</div>
		  </div>';
	} else {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">Dealers Selected: ' .$_SESSION['comparedealerregion1codes']. '<br><br></h11>
				<h11 style="color: #A9A9A9;">Region Selected: ' .$_SESSION['compareregionname1']. ' ('.$_SESSION['comparisondealerIDrocount']. ' of ' .$_SESSION['totalcompareregiondealers1']. ' total '.constant('ENTITYLCASE').'s have records)<br></h11>
			</div>
		  </div>';
	}
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">Region 1 Selection: ' .$_SESSION['regionname1']. ' (' .$_SESSION['regionrocount1']. ' of ' .$_SESSION['totalregiondealers1']. ' total '.constant('ENTITYLCASE').'s have records)<br><br></h11>
				<h11 style="color: #A9A9A9;">Region 2 Selection: ' .$_SESSION['regionname2']. ' (' .$_SESSION['regionrocount2']. ' of ' .$_SESSION['totalregiondealers1']. ' total '.constant('ENTITYLCASE').'s have records)<br></h11>
			</div>
		  </div>';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">' .$_SESSION['regionvsglobalname']. ' Region vs. All '.constant('ENTITY').'s &nbsp; | &nbsp; ' .$totaldealers_persurvey. ' total active '.constant('ENTITYLCASE').'s<br><br></h11>
				<h11 style="color: #A9A9A9;">(' .$_SESSION['regionvsglobalrocount']. ' of ' .$_SESSION['totalregionvsglobaldealers']. ' total '.constant('ENTITYLCASE').'s in the region have records)</h11>
			</div>
		  </div>';		  
} else {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' '.$dealercode.'&nbsp;vs.&nbsp;All '.constant('ENTITY').'s (Total active '.constant('ENTITYLCASE').'s: ' .$totaldealers_persurvey. ')</h11>
			</div>
		  </div>';
}		  
/*---------------------------------------------------------Chart div--------------------------------------------------------*/
echo'
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<div id='.$chart_div.' style="height: '.$chart_height.'px;"></div>
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
</div>';
/*---------------------------------------------------------Main title 2------------------------------------------------------*/
echo'
	<div class="row">
		<div class="medium-12 large-12 columns">
			<div class="row">
				<div class="small-12 medium-10 large-10 columns">
					<h4>'.$tabletitle;
						if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {
							echo ' - '.constant('MANUF').' '.constant('ENTITY').' Comparison ';
						/*----------------------------------------------------------------------------------------------------------*/	
						} elseif (isset($_SESSION['compareglobalIDs'])) {
							echo ' - '.constant('MANUF').' Global Comparison ';
						/*----------------------------------------------------------------------------------------------------------*/	
						} elseif ((isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) OR (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) OR (isset($_SESSION['regionvsglobalIDs'])))	{
							echo ' - '.constant('MANUF').' Region Comparison ';
						/*----------------------------------------------------------------------------------------------------------*/	
						} else {
							echo ' - '.constant('MANUF').' '.constant('ENTITY').' Comparison ';
						/*----------------------------------------------------------------------------------------------------------*/
						}
						echo '<span style="font-size: 17px; color: #A9A9A9;">' .$comparisonsurvey_description. '</span>
					</h4>
				</div>
				<div class="small-12 medium-2 large-2 columns">
					<a href=' .$exportanchor. '> Export Data </a><br><br>
				</div>
			</div>
		</div>
	 </div>';
/*---------------------------------------------------------Subtitle 2-----------------------------------------------------*/
if (isset($_SESSION['comparedealer1IDs']) && isset($_SESSION['comparedealer2IDs'])) {
	if ($_SESSION['dealerarraysize1'] > 1 OR $_SESSION['dealerarraysize2'] > 1) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">Group 1 '.constant('ENTITY').'s: ' .$_SESSION['comparedealer1codes']. '</h11><br><br>
				<h11 style="color: #A9A9A9;">Group 2 '.constant('ENTITY').'s: ' .$_SESSION['comparedealer2codes']. '<br></h11>
			</div>
		  </div>';
	} else {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' ' .$_SESSION['comparedealer1codes']. ' &nbsp; Vs. &nbsp; Dealer ' .$_SESSION['comparedealer2codes']. '</h11><br><br>
			</div>
		  </div>';
	}		
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1 ) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' Selected: ' .$_SESSION['compareglobalcodes']. ' &nbsp; | &nbsp; Total active '.constant('ENTITYLCASE').'s: ' .$totaldealers_persurvey. '</h11><br><br>
			</div>
		  </div>';
	} else {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').'s Selected: ' .$_SESSION['compareglobalcodes']. ' &nbsp; | &nbsp; Total active '.constant('ENTITYLCASE').'s: ' .$totaldealers_persurvey. '</h11><br><br>
			</div>
		  </div>';
	}
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' Selected: ' .$_SESSION['comparedealerregion1codes']. '<br><br></h11>
				<h11 style="color: #A9A9A9;">Region Selected: ' .$_SESSION['compareregionname1']. ' ('.$_SESSION['comparisondealerIDrocount']. ' of ' .$_SESSION['totalcompareregiondealers1']. ' total '.constant('ENTITYLCASE').'s have records)<br></h11>
			</div>
		  </div>';
	} else {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').'s Selected: ' .$_SESSION['comparedealerregion1codes']. '<br><br></h11>
				<h11 style="color: #A9A9A9;">Region Selected: ' .$_SESSION['compareregionname1']. ' ('.$_SESSION['comparisondealerIDrocount']. ' of ' .$_SESSION['totalcompareregiondealers1']. ' total '.constant('ENTITYLCASE').'s have records)<br></h11>
			</div>
		  </div>';
	}
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">Region 1 Selection: ' .$_SESSION['regionname1']. ' (' .$_SESSION['regionrocount1']. ' of ' .$_SESSION['totalregiondealers1']. ' total '.constant('ENTITYLCASE').'s have records)<br><br></h11>
				<h11 style="color: #A9A9A9;">Region 2 Selection: ' .$_SESSION['regionname2']. ' (' .$_SESSION['regionrocount2']. ' of ' .$_SESSION['totalregiondealers1']. ' total '.constant('ENTITYLCASE').'s have records)<br></h11>
			</div>
		  </div>';
} elseif (isset($_SESSION['regionvsglobalIDs'])) {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">' .$_SESSION['regionvsglobalname']. ' Region vs. All '.constant('ENTITY').'s &nbsp; | &nbsp; ' .$totaldealers_persurvey. ' total active '.constant('ENTITYLCASE').'s<br><br></h11>
				<h11 style="color: #A9A9A9;">(' .$_SESSION['regionvsglobalrocount']. ' of ' .$_SESSION['totalregionvsglobaldealers']. ' total '.constant('ENTITYLCASE').'s in the region have records)</h11>
			</div>
		  </div>';		  
} else {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' '.$dealercode.'&nbsp;vs.&nbsp;All '.constant('ENTITY').'s (Total active '.constant('ENTITYLCASE').'s: ' .$totaldealers_persurvey. ')</h11>
			</div>
		  </div>';
}		  
/*---------------------------------------------------------Report table-----------------------------------------------------*/
echo '<div class="row">
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
					<table style="margin-left: auto; margin-right: auto; border-collapse: collapse;">
					<table id='.$tableid.' class="tablesorter">
					<thead>
						<tr>
							<th>' .$tablehead1. '</th>';	
/*--------------------------------------------------------Table headings-----------------------------------------------------*/													
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
	echo'					<th>'.constant('ENTITY').' Group</th>
							<th>All '.constant('ENTITY').'s</th>';
	}						
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	echo'					<th>'.constant('ENTITY').' ' .$_SESSION['comparedealerregion1codes']. '</th>
							<th>' .$_SESSION['compareregionname1']. ' Region</th>';
	} else {
	echo'					<th>Dealer Group</th>
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