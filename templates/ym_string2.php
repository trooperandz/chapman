<?php
/* ------------------------------------------------------------------------*
	Program: ym_string2.php
	
	Purpose: Create yearmodel string for $bucket
			 
	History:
    Date		Description									by
	03/06/2015	Initial design and coding					M.T.Holland

 *-------------------------------------------------------------------------*/
 
// Create Year Model string for second year model query (all remaining yearmodel IDs that are greater than 8 year previous)
$query = "SELECT yearmodelID FROM yearmodel WHERE modelyear BETWEEN 2000 AND $firstyear - 1
		  ORDER BY modelyear DESC";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Year processing failed.  See administrator.';
	die(header('Location: enterrofoundation.php'));
}
$rows = $result->num_rows;
// echo '$rows: '.$rows.'<br>';
$ym_string_test = array();
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$ym_string_test[$index]['yearmodelID'] = $lookup['yearmodelID'];
	$index += 1;
}
// Print results
$yearmodel_string2 = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows - 1) {
		$yearmodel_string2 .= $ym_string_test[$i]['yearmodelID'];
	} else {
		$yearmodel_string2 .= $ym_string_test[$i]['yearmodelID'].',';
	}
}
// echo '$yearmodel_string2: '.$yearmodel_string2.'<br>';
// die();
?>