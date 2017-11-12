<?php
/* -------------------------------------------------------------------------*
   Program: ro_x.php

   Purpose: Export all repair order recordes for $dealerID and $surveyindex_id

   History:
    Date		Description										by
	07/12/2014	Initial design and coding.						Matt Holland
	12/23/2014	Add $surveyindex_id and $survey_description		Matt Holland					
----------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include ('templates/db_cxn.php');

// Set dealer variables
$dealercode 	= $_SESSION['dealercode'];
$dealerID 		= $_SESSION['dealerID'];

// Initialize survey globals
$surveyindex_id 	= $_SESSION['surveyindex_id'];
$survey_description	= $_SESSION['survey_description'];
	
$output= "";
$table= "";

/*  Read all the repair orders  */
$query = "SELECT ronumber, modelyear, carmileage, singleissue, labor, parts, comment, create_date, userID FROM repairorder, yearmodel, mileagespread
WHERE repairorder.mileagespreadID = mileagespread.mileagespreadID AND repairorder.yearmodelID = yearmodel.yearmodelID
AND repairorder.dealerID = $dealerID AND repairorder.surveyindex_id = $surveyindex_id
ORDER BY roID ASC";
	
$roresult = $mysqli->query($query);
if (!$roresult) die ("Database access failed: " .$mysqli->error);
$rows = $roresult->num_rows;
$repairordercolumns_total = $roresult->field_count;

// Generate heading names for export function
$output .= (Date("l F d Y"));
$output .= "\n";
$output .= "\n";
$output .= constant('MANUF')." - ".constant('ENTITY'). " ".$_SESSION['dealercode']." (".$_SESSION['survey_description'].")";
$output .= "\n";
$output .= "All Repair Orders - Total: ".$rows; 
$output .= "\n";
$output .= "\n";
$output .= "RO Number,";
$output .= "Model Year,";
$output .= "Mileage Spread,";
$output .= "Single Issue?,";
$output .= "Labor,";
$output .= "Parts,";
$output .= "Services,";
$output .= "Comments,";
$output .= "Date Entered,";
$output .= "User ID";
$output .= "\n";

//Get records from repairorder table
while ($row = $roresult->fetch_row()) {
	for ($i=0; $i < $repairordercolumns_total; $i++){
		switch ($i) {
			case 3:
				/* single issue */
				if ($row[3] == 0) {
					$output .= "NO,";
					}
				else {
					$output .= "YES,";
				}
				break;
			case 4:
				/* labor */
				if ($row[4] == NULL) {
					$output .='N/A,';
				} else {
					$output .='"$'.$row["$i"].'",';
				}
				break;
			case 5:
				/* parts */
				if ($row[5] == NULL) {
					$output .='N/A,';
				} else {
					$output .='"$'.$row["$i"].'",';
				}
				break;
			case 6:
				/*  Services  */
				$output .= get_services_string($mysqli, $row[0], $dealerID, $surveyindex_id);
			default:
				/* all others */
				$output .='"'.$row["$i"].'",';
				break;
		}
	}
	$output .="\n";
}

//Download the file
$filename = "RepairOrdersDealerExport.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;
/********************************************************************
                             FUNCTIONS
********************************************************************/						
/*------------------------------------------------------------------*
	Function: 	get_services_string(ronumber);
	
 Description:	Get string representing all services for an order
 
	 Returns:	string of services separated by blanks only enclosed
				in double quotes.
	 
 *-------------------------------------------------------------------*/
function get_services_string($mysqli, $ronumber, $dealerID, $surveyindex_id) {
	$servicesquery = "SELECT servicedescription, addsvc FROM servicerendered
				 NATURAL JOIN services
				 WHERE $ronumber =  servicerendered.ronumber AND servicerendered.dealerID = $dealerID AND servicerendered.surveyindex_id = $surveyindex_id
				 ORDER By services.servicesort";
				
	$servicesresult = $mysqli->query($servicesquery);
	$servicerows = $servicesresult->num_rows;
	if (!$servicesresult) {
		$output = "services query failed";
		}
	else {
		/* initialize services string at quote */
		$output = '"';
	}

	//Get records from servicerendered table and build output string of services
	while ($row = $servicesresult->fetch_assoc()) {
		$output .= $row['servicedescription'];
		if ($row['addsvc'] == 1) {
			$output .= "*";
		}
		if ($servicerows > 1) {
			$output .= ", ";
		}
	}
	/*  wrap up services string with ",  */
	$output .='",';
 
	/*  return services string as value of function  */
	return $output;
 }
?>