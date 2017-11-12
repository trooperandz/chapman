<?php
/*-----------------------------------------------------------------------------*
Program (template): ym_string.php

Purpose:  Provide a starting point for each dealer year report.
		  Creates the string of dealer IDs for the queries to
		  read in the WHERE clause.  Otherwise the queries would
		  read from the current server year.  Does not give good
		  data if a survey has been completed several years
		  previously and therefore when the current year was not
		  even a possible selection in enterrofoundation.php.  If
		  a yearmodel string was not previously inserted, program
		  will create the yearmodelID string on the fly.  If it was
		  previously inserted, it will look up the string from the
		  yearmodel_strings table and set the variable
		  $yearmodel_string to the string of yearmodelID's.

Date				Description										By
06/10/2014 (est.)	Initial design and coding						Matt Holland
02/25/2015			Altered for year model pie chart
					Created $firstyear and $bucket					Matt Holland
03/08/2015			Altered to work with new survey start year		Matt Holland
					processing.  Instead of taking the server
					current year and creating a string of years
					based on this, it takes the start year from
					the surveys table and creates the year string
					from this.  If there is no start year in the
					surveys table (would be zero in that case)
					then it creates the string based on the 
					server year. This ensures that reports with
					no ROs still have current years listed in 
					the report.
					
*------------------------------------------------------------------------------*/

// First check to see if yearmodel_string for $dealerID, $surveyindex_id and $userID has been entered
$query = "SELECT yearmodel_string FROM yearmodel_strings WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id and userID = $userID";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Line 55: yearmodel_strings SELECT query failed.  See administrator.";
}
$rows = $result->num_rows;

if ($rows == 0) {
	// Now check to see if default string has been entered (would occur if an RO for particular $dealerID and $surveyindex_id has been previously entered)
	$query = "SELECT yearmodel_string FROM yearmodel_strings WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id and userID = 0";
	$result = $mysqli->query($query);
	if (!$result) {
		$_SESSION['error'][] = "String query failed.  See administrator.";
	}
	$rows = $result->num_rows;
	if ($rows > 0) {
		// yearmodel_string has been inserted into yearmodel_strings table from enterrofoundationadd_process.php.  Get result.
		$yearmodel_stringvalue = $result->fetch_assoc();
		$yearmodel_string	   = $yearmodel_stringvalue['yearmodel_string'];
		
		// Get survey_start_yearmodelID from surveys table for calculation of bucket year label
		$query = "SELECT survey_start_yearmodelID FROM surveys WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = 'Survey query failed.  See administrator.';
			die(header('Location: enterrofoundation.php'));
		}
		$lookup = $result->fetch_assoc();
		$survey_start_check = $lookup['survey_start_yearmodelID'];
		
		// Subtract nine from $survey_start_check and find associated modelyear for creation of bucket_year label
		$bucket_year_ID = $survey_start_check - 9;
		
		// Query $bucket_year_ID to find associated modelyear label
		$query = "SELECT modelyear from yearmodel WHERE yearmodelID = $bucket_year_ID";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = 'Year model query failed.  See administrator.';
			die(header('Location: enterrofoundation.php'));
		}
		// Get modelyear label, create $firstyear variable and $bucket_year label ( with concatenated + sign)
		$lookup = $result->fetch_assoc();
		
		// For ym_string2 query
		$firstyear = $lookup['modelyear'] + 1;
		
		// For report label
		$bucket_year = $lookup['modelyear'].'+';
			
	} else {
		// If no rows from above query, report must generate its own string based on current year
		
		// Set $currentyear from server timestamp
		$currentyear = date('Y');
		$month = date('m');
		if ($month > 8) {
			$currentyear = date('Y')+1;
		}

		// Calculate last year for first query - for BETWEEN statement
		$firstyear = $currentyear-8;
		
		// Create default 'bucket' year label (in case of no set yearmodel string)
		$bucket_year = ($firstyear - 1).'+';
		// echo '$bucket_year: '.$bucket_year.'<br>';
		
		$query = "SELECT yearmodelID, modelyear FROM yearmodel WHERE modelyear BETWEEN $firstyear AND $currentyear
		  ORDER BY modelyear DESC";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "String query failed.  See administrator.";
		}
		$rows = $result->num_rows;
		// If no rows, alert user that value is not in table
		if ($rows == 0) {
			$_SESSION['error'][] = "The current year does not exist in the yearmodel table.  See administrator.";
			die(header("Location: enterrofoundation.php"));
		} else {
			// If rows > 0 , create default string for page
			$yearmodel_stringvalue = array();
			$index = 0;
			while ($lookup = $result->fetch_assoc()) {
				$yearmodel_stringvalue[$index]['yearmodelID'] = $lookup['yearmodelID'];
				$index += 1;
			}
			$yearmodel_string = "";
			for ($i=0; $i<$rows; $i++) {
				if ($i == $rows-1) {
					$yearmodel_string .= $yearmodel_stringvalue[$i]['yearmodelID'];
				} else {
					$yearmodel_string .= $yearmodel_stringvalue[$i]['yearmodelID']. ', ';
				}
			}
		}
	}		
// If yearmodel_string has been set for current $dealerID, $survey type & $userID, obtain yearmodel_string from query
} else {
	$yearmodel_stringvalue = $result->fetch_assoc();
	$yearmodel_string = $yearmodel_stringvalue['yearmodel_string'];
}
 //echo '$yearmodel_string: ' .$yearmodel_string. '<br>';