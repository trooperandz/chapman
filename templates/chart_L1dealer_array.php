<?php
$table['cols'] = array(
array('label' => $chartarraytitle1, 'type' => 'string'),
array('label' => '% '.constant('ENTITY').' ' .$dealercode. '', 'type' => 'number'),
array('label' => '% L1 Metric', 'type' => 'number'));

// Reset $resultL1 pointer	
$resultL1->data_seek(0);

// Convert L1_value string into array
$L1vals  = $resultL1->fetch_assoc();
$L1val   = explode(',', $L1vals['L1_value']);
$L1value = array();
$index = 0;
foreach ($L1val as $num) {
	$L1value[$index] = (int)$num;
	$index += 1;
}

/*  For single rows and all rows, hold in arrays for later to eliminate another set of queries  */
$hold1 = array(array());
$hold2 = array(array());
$row = 0;	/* counter for row index  */

$rows = array();
while($stvalue = $resultst1->fetch_row()) {
	
	$hold1[$row][0] = $stvalue[0];
	$hold1[$row][2] = $stvalue[2];
	$hold2[$row]    = $L1value[$row];
	
	$temp = array();
	$temp[] = array('v' => $stvalue[0]);
	$temp[] = array('v' => number_format($stvalue[2],0));
	$temp[] = array('v' => $L1value[$row]);
	$rows[] = array('c' => $temp);
	$row = $row + 1;
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);