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
					<h4 style="text-align: right; margin-top: 8px; font-size: 15px; color: #707070;">' .(Date("l, F d")). '</h4>  
				</div>
			</div>	
		</div>	
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns" style="color: #FF0000; font-weight: bold;">';
		if (isset($_SESSION['error'])) { 
			foreach ($_SESSION['error'] as $error) { 
				echo $error . '<br>'; 
			} //end foreach 
			unset($_SESSION['error']);
		} //end if 
echo'
    </div>
</div>
<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-10 large-10 columns">
				<h4>' .$chart_title. ' - '.constant('MANUF').' '.constant('ENTITY').' ' .$dealercode. '</h4>
			</div>
			<div class="small-12 medium-2 large-2 columns">
				<a href=' .$printeranchor. '>Printer Friendly </a><br><br>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<h11 style="color: #A9A9A9;">Survey Type: ' .$_SESSION['survey_description']. '</h11><br>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<div id="chart_reflow_size">
			<div id='.$chart_div.' style="height: '.$chart_height.'px;"></div>
		</div>
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
					<h4>'.$tabletitle. ' - '.constant('MANUF').' '.constant('ENTITY').' ' .$dealercode. '</h4>
				</div>
				<div class="small-12 medium-2 large-2 columns">
					<a href='.$exportanchor. '> Export Data </a><br><br>
				</div>
			</div>
		</div>
	 </div>
	 <div class="row">
		<div class="small-12 medium-12 large-12 columns">
			<h11 style="color: #A9A9A9;">Survey Type: ' .$_SESSION['survey_description']. '</h11><br>
		</div>
	</div> 
	<div class="row">
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
							<th>' .$tablehead2. '</th> 
							<th>' .$tablehead3. '</th> 
						</tr>
					</thead>';