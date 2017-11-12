<?php
$rows = array();
$table = array();

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
	
if (isset($_SESSION['comparedealer1IDs']) &&  isset($_SESSION['comparedealer2IDs'])) {	
	if ($_SESSION['dealerarraysize1'] == 1 && $_SESSION['dealerarraysize2'] == 1) {
	$table['cols'] = array(
	array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => constant('ENTITY').' ' .$_SESSION['comparedealer1codes'].'', 'type' => 'number'),
	array('label' => constant('ENTITY').' ' .$_SESSION['comparedealer2codes'].'', 'type' => 'number'));
	} else {
	$table['cols'] = array(
	array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => 'Group 1', 'type' => 'number'),
	array('label' => 'Group 2', 'type' => 'number'));
	}	
} elseif (isset($_SESSION['compareglobalIDs'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	$table['cols'] = array(
	array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => constant('ENTITY').' ' .$_SESSION['compareglobalcodes']. '', 'type' => 'number'),
	array('label' => 'All '.constant('ENTITY').'s', 'type' => 'number'));
	} else {
	$table['cols'] = array(
	array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => constant('ENTITY').' Group', 'type' => 'number'),
	array('label' => 'All '.constant('ENTITY').'s', 'type' => 'number'));
	}	
} elseif (isset($_SESSION['comparedealerregion1IDs']) && isset($_SESSION['compareregiondealerIDs1'])) {
	if ($_SESSION['dealerarraysize'] == 1) {
	$table['cols'] = array(
	array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => constant('ENTITY').' '.$_SESSION['comparedealerregion1codes'].'', 'type' => 'number'),
	array('label' => $_SESSION['compareregionname1']. ' Region', 'type' => 'number'));
	} else {
	$table['cols'] = array(
	array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => constant('ENTITY').' Group', 'type' => 'number'),
	array('label' => $_SESSION['compareregionname1']. ' Region', 'type' => 'number'));
	}
} elseif (isset($_SESSION['compareregionIDs1']) && isset($_SESSION['compareregionIDs2'])) {
	$table['cols'] = array(
	array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => $_SESSION['regionname1']. ' Region', 'type' => 'number'),
	array('label' => $_SESSION['regionname2']. ' Region', 'type' => 'number'));
} elseif (isset($_SESSION['regionvsglobalIDs'])) {
	$table['cols'] = array(
	array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => $_SESSION['regionvsglobalname']. ' Region', 'type' => 'number'),
	array('label' => 'All '.constant('ENTITY').'s', 'type' => 'number'));	
} else {
	$table['cols'] = array(
    array('label' => $chartarraytitle1, 'type' => 'string'),
	array('label' => constant('ENTITY').' '.$dealercode.'', 'type' => 'number'),
	array('label' => 'All '.constant('ENTITY').'s', 'type' => 'number'));
}