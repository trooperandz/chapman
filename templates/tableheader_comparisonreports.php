
<?php
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
	echo'					<th>' .$_SESION['regionvsglobalname']. ' Region </th>
							<th>	All '.constant('ENTITY').'s					 </th>';
} else {	
	echo'					<th>'.constant('ENTITY').' ' .$dealercode. '</th>
							<th>     All '.constant('ENTITY').'s	    </th>';
}
?>