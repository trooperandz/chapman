<?php 

require_once("classPage.php"); 

$page = new Page();
 
print $page->getTop();
 
echo  
'<div id="mainContent"> 
	<p> This is where content would go, should there be any. </p> 
</div> <!-- end main content -->'; 
 

print $page->getBottom();

?>

