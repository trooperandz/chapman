<?php 
/* Upload this file to your server and then go to the file in your browser (e.g. http://example.com/phpinfo.php). 
   It will autmoatically download Godaddy's PHP5.ini file to your server. */
   
//phpinfo();
 $File = "php5.ini"; 
 $Handle = fopen($File, 'w');
 $Vdata = file_get_contents('/opt/alt/php55/etc/php.ini'); 
 fwrite($Handle, $Vdata); 
 print "File Saved!"; 
 fclose($Handle); 
?>