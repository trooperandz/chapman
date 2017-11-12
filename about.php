<?php
require_once("classPage.php"); 
 
$page = new Page();
 
$page->titleExtra = "About";

print $page->getTop(); 

echo
	"<div id='mainContent'> 
		<p> It's all about me. </p> 
	</div> <!-- end main content -->";

print $page->getBottom();

?>
