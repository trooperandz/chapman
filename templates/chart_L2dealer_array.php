<?php
$table['cols'] = array(
array('label' => $chartarraytitle1, 'type' => 'string'),
array('label' => '% '.constant('ENTITY').' ' .$dealercode. '', 'type' => 'number'),
array('label' => '% L2 Metric', 'type' => 'number'));

// Reset $resultL2 pointer	
$resultL2->data_seek(0);

// Convert L2_value string into array
$L2vals  = $resultL2->fetch_assoc();
$L2val   = explode(',', $L2vals['L2_value']);
$L2value = array();
$index = 0;
foreach ($L2val as $num) {
	$L2value[$index] = (int)$num;
	$index += 1;
}

/*  For single rows and all rows, hold in arrays for later to eliminate another set of queries  */
$hold1 = array(array());
$hold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($stvalue = $resultst2->fetch_row()) {
	
	$hold1[$row][0] = $stvalue[0];
	$hold1[$row][2] = $stvalue[2];
	$hold2[$row]    = $L2value[$row];
	
	$temp = array();
	$temp[] = array('v' => $stvalue[0]);
	$temp[] = array('v' => number_format($stvalue[2],0));
	$temp[] = array('v' => $L2value[$row]);
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);