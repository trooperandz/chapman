<?php
$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(
// Labels for your chart, these represent the column titles
// Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
array('label' => $chartarraytitle1, 'type' => 'string'),
array('label' => $chartarraytitle2, 'type' => 'number')
);