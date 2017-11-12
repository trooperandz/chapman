<div class="row">
	<div class="small-12 medium-12 large-12 columns" style="color: #FF0000; font-weight: bold;">
		<?php	
				if (isset($_SESSION['error'])) { 
					foreach ($_SESSION['error'] as $error) { 
							print $error . "<br />\n"; 
					} //end foreach 
					unset($_SESSION['error']);
				} //end if 
		?>
	</div>
</div>