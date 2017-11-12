<?php
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
?>