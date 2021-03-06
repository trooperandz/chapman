<!------------------------------------------------------------------------------------------------*
   Template: header_printreports.php

   Purpose: Provide header template for all print reports (necessary for the css file)
   History:
    Date				Description							by
	11/11/2014			Initial design & coding				Matt Holland
*------------------------------------------------------------------------------------------------->
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - <?php echo constant('MANUF');?></title>

    <link rel="stylesheet" href="css/foundation.css" />
	<!--<link rel="stylesheet" href="css/responsive-tablestest.css" media="screen" /> -->
	<link rel="stylesheet" href="css/tablesort.theme.blue.css" />
	<link rel="stylesheet" href="css/printreports.css" />
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script>
		$(document).ready(function() { 
			$("#ymtable").tablesorter();
			$("#ymsitable").tablesorter();
			$("#mstable").tablesorter();
			$("#mssitable").tablesorter();
			$("#sttable").tablesorter();
			$("#stsitable").tablesorter();
			$("#lofdtable").tablesorter();
			$("#lofbtable").tablesorter();
			$("#sitable").tablesorter();
			$("#sictable").tablesorter();
			$("#sdtable").tablesorter();
			$("#L1table").tablesorter();
			$("#L2table").tablesorter();
		} 
	);
	</script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>