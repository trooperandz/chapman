<?php

$query = "SELECT ronumber FROM servicerendered WHERE serviceID = 1 AND dealerID IN ($dealerIDs2) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Demand query failed.  See administrator.".$mysqli->error;
die (header("Location: enterrofoundation.php"));}
$LOFrows2 = $result->num_rows;

// echo '$LOFrows2: '.$LOFrows.'<br>';

$query = "SELECT DISTINCT dealerID,ronumber FROM servicerendered WHERE serviceID IN ($LOF_all_string) AND dealerID IN ($dealerIDs2) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$LOF_all_rows2 = $result->num_rows;

// echo '$LOF_all_rows2: '.$LOF_all_rows.'<br>';

$query = "SELECT DISTINCT dealerID,ronumber FROM servicerendered WHERE serviceID IN ($LOF_string) AND dealerID IN ($dealerIDs2) AND surveyindex_id IN ($surveyindex_id)";
$result = $mysqli->query($query);
if (!$result) { $_SESSION['error'][] = "LOF Demand query failed.  See administrator.";
die (header("Location: enterrofoundation.php"));}
$LOF_other_rows2 = $result->num_rows;

// echo '$LOF_other_rows2: '.$LOF_other_rows2.'<br>';

$SILOFrows2 = $LOF_all_rows2 - $LOF_other_rows2;
// echo '$SILOF_rows2: '.$SILOF_rows2.'<br>';