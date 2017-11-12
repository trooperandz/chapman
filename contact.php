<?php

require_once("classPage.php");

$page = new Page();

$page->type = "contact"; 

$page->titleExtra = "Contact Me"; 

print $page->getTop(); 

echo
	'<div id="mainContent"> 
		<h1> Contacting me is easy </h1> 
		<p class="contactMethod"> suehring@ braingia.com</p> 
		<p class="contactMethod"> Twitter: @stevesuehring </p>
	</div> <!-- end main content -->'; 

print $page->getBottom(); 

?>


