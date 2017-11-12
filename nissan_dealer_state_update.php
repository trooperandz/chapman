<?php

require_once("functions.inc");
include ('templates/login_check.php');
include ('templates/db_cxn.php');

$query = 'Select state_ID, state_abbrev FROM us_state_list';
$result = $mysqli->query($query);
$rows = $result->num_rows;

// Get string of state abbreviations for IN clause
$state_abbrev = array();
$index = 0;
while ($lookup = $result->fetch_assoc()) {
	$state_abbrev[$index]['state_ID'] = $lookup['state_ID'];
	$state_abbrev[$index]['state_abbrev'] = '"'.$lookup['state_abbrev'].'"';
	$index += 1;
}
$statelist = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == $rows-1) {
		$statelist .= $state_abbrev[$i]['state_abbrev'];
	} else {
		$statelist .= $state_abbrev[$i]['state_abbrev'].',';
	}
}
// Create WHEN... THEN... statements using loop
$query_words = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == 0) {
		$query_words .= 'UPDATE dealer SET state_ID = CASE state_ID WHEN '.$state_abbrev[$i]['state_abbrev'].' THEN '.$state_abbrev[$i]['state_ID'].' ';
	} elseif ($i == $rows-1){
		$query_words .= 'WHEN '.$state_abbrev[$i]['state_abbrev'].' THEN '.$state_abbrev[$i]['state_ID'].' END WHERE state_ID IN ('.$statelist.');';
	} else  {
		$query_words .= 'WHEN '.$state_abbrev[$i]['state_abbrev'].' THEN '.$state_abbrev[$i]['state_ID'].' ';
	}
}

//echo $query_words;
//die();

// Run query
/*
$query = 'UPDATE dealer
			SET state_ID = CASE state_ID 
				WHEN "AK" THEN 1 
				WHEN "AL" THEN 2 
				WHEN "AR" THEN 3 
				WHEN "AZ" THEN 4 
				WHEN "CA" THEN 5 
				WHEN "CO" THEN 6 
				WHEN "CT" THEN 7 
				WHEN "DC" THEN 8 
				WHEN "DE" THEN 9 
				WHEN "FL" THEN 10 
				WHEN "GA" THEN 11 
				WHEN "HI" THEN 12 
				WHEN "IA" THEN 13 
				WHEN "ID" THEN 14 
				WHEN "IL" THEN 15 
				WHEN "IN" THEN 16 
				WHEN "KS" THEN 17 
				WHEN "KY" THEN 18 
				WHEN "LA" THEN 19 
				WHEN "MA" THEN 20 
				WHEN "MD" THEN 21 
				WHEN "ME" THEN 22 
				WHEN "MI" THEN 23 
				WHEN "MN" THEN 24 
				WHEN "MO" THEN 25 
				WHEN "MS" THEN 26 
				WHEN "MT" THEN 27 
				WHEN "NC" THEN 28 
				WHEN "ND" THEN 29 
				WHEN "NE" THEN 30 
				WHEN "NH" THEN 31 
				WHEN "NJ" THEN 32 
				WHEN "NM" THEN 33 
				WHEN "NV" THEN 34 
				WHEN "NY" THEN 35 
				WHEN "OH" THEN 36 
				WHEN "OK" THEN 37 
				WHEN "OR" THEN 38 
				WHEN "PA" THEN 39 
				WHEN "RI" THEN 40 
				WHEN "SC" THEN 41 
				WHEN "SD" THEN 42 
				WHEN "TN" THEN 43 
				WHEN "TX" THEN 44 
				WHEN "UT" THEN 45 
				WHEN "VA" THEN 46 
				WHEN "VT" THEN 47 
				WHEN "WA" THEN 48 
				WHEN "WI" THEN 49 
				WHEN "WV" THEN 50 
				WHEN "WY" THEN 51 
			END';
*/
$query = $query_words;
$result = $mysqli->query($query);
if (!$result) {
	echo'The query didnt work!';
} else {
	echo'It worked!';
}

/********************Readable Result*****************
$query_words = "";
for ($i=0; $i<$rows; $i++) {
	if ($i == 0) {
		$query_words .= 'UPDATE DEALER SET state_ID = CASE state_ID <br> WHEN '.$state_abbrev[$i]['state_abbrev'].' THEN '.$state_abbrev[$i]['state_ID'].' <br>';
	} elseif ($i == $rows-1){
		$query_words .= 'WHEN '.$state_abbrev[$i]['state_abbrev'].' THEN '.$state_abbrev[$i]['state_ID'].' <br>END WHERE state_ID IN ('.$statelist.')';
	} else  {
		$query_words .= 'WHEN '.$state_abbrev[$i]['state_abbrev'].' THEN '.$state_abbrev[$i]['state_ID'].' <br>';
	}
}
// echo '$statelist: '.$statelist.'<br>';
//echo $query_words.'<br>';
/*****************************************************/

/******Example of UPDATE statement using CASE*********
$query = 'UPDATE dealer 
			SET state_ID = CASE state_ID
				WHEN "AK" THEN 1
				WHEN "AL" THEN 2
				WHEN "AR" THEN 3
			END
		  WHERE state_ID IN ("AK", "AL", "AR")';
******************************************************/
?>