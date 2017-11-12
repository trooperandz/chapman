<?php
if (isset($_SESSION['comparedealer1IDs']) && isset($_SESSION['comparedealer2IDs'])) {
	if ($dealerarraysize1 > 1 OR $dealerarraysize2 > 1) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">Group 1 '.constant('ENTITY').'s: ' .$comparedealer1codes. '</h11><br><br>
				<h11 style="color: #A9A9A9;">Group 2 '.constant('ENTITY').'s: ' .$comparedealer2codes. '<br></h11>
			</div>
		  </div>';
	} else {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' ' .$comparedealer1codes. ' &nbsp; Vs. &nbsp; '.constant('ENTITY').' ' .$comparedealer2codes. '</h11><br><br>
			</div>
		  </div>';
	}		
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($dealerarraysize == 1 ) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' Selected: ' .$compareglobalcodes. ' &nbsp; | &nbsp; Total active '.constant('ENTITYLCASE').'s: ' .$totalactivedealers. '</h11><br><br>
			</div>
		  </div>';
	} else {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').'s Selected: ' .$compareglobalcodes. ' &nbsp; | &nbsp; Total active '.constant('ENTITYLCASE').'s: ' .$totalactivedealers. '</h11><br><br>
			</div>
		  </div>';
	}	  
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($dealerarraysize == 1) {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').' Selected: ' .$comparedealerregion1codes. '<br><br></h11>
				<h11 style="color: #A9A9A9;">Region Selected: ' .$compareregionname1. ' ('.$comparisondealerIDrocount. ' of ' .$totalcompareregiondealers1. ' total '.constant('ENTITYLCASE').'s have records)<br></h11>
			</div>
		  </div>';
	} else {	  
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">'.constant('ENTITY').'s Selected: ' .$comparedealerregion1codes. '<br><br></h11>
				<h11 style="color: #A9A9A9;">Region Selected: ' .$compareregionname1. ' ('.$comparisondealerIDrocount. ' of ' .$totalcompareregiondealers1. ' total '.constant('ENTITYLCASE').'s have records)<br></h11>
			</div>
		  </div>';
	}	  
} else {
	echo '<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<h11 style="color: #A9A9A9;">Total active '.constant('ENTITYLCASE').'s: ' .$totalactivedealers. '</h11>
			</div>
		  </div>';
}
?>