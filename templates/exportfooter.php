<?php
//Download the file
$filename = $filename1;
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;