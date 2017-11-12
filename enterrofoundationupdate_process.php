<?php
require_once("functions.inc");
include ('templates/login_check.php');

/* --------------------------------------------------------------------------*
   Program: enterrofoundationupdate_process.php

   Purpose: Update repair orders and associated services

   History:
    Date		Description									    by
	07/10/2014	Initial design and coding.					    Matt Holland
	07/14/2014	Finalize update process.					    Matt Holland
	07/15/2014	Reset global error array when appropriate.	    Matt Holland
	07/21/2014	Add delete function.						    Matt Holland
	07/23/2014	Clean up messages.							    Matt Holland
	07/24/2014	Add services validation.					    Matt Holland
	07/25/2014	Enhance error checking in check_ro.			    Matt Holland
	07/28/2014	Remove formAttempt and update globals.		    Matt Holland
	09/12/2014	Init services variables for check box init.     Matt Holland
	09/13/2014	Update services fcns for non-init variables.    Matt Holland
	09/13/2014	Correct $addsvctirebalance mispellings.		    Matt Holland
	09/13/2014	Correct $serviceallflushes mispellings.		    Matt Holland
	09/16/2014	Rework fcn add_services.					    Matt Holland
	10/15/2014	Add dynamic yearmodel menu functionality	    Matt Holland
	10/16/2014	Add model_age functionality					    Matt Holland
	10/26/2014	Add survey table processing (surveyindex_id)    Matt Holland
	11/18/2014	Add dynamic form checkbox array generation	    Matt Holland
	11/21/2014	Finalize dynamic form checkbox array.		    Matt Holland
				Fix cond no adds or no services causes fault.   Matt Holland
	11/23/2014	Add dynamic mileage spreads and year models.	Matt Holland
	12/14/2014	Reorder mysql update WHERE ronumber dealerID...	Matt Holland
	12/18/2014	Allow for null parts and labor to store in db.	Matt Holland
	12/22/2014	Changed labor & parts !empty instruction to
				!='' to allow for correct 0 value processing	Matt Holland
	01/09/2015	Added sticky footer								Matt Holland
	01/14/2015	Edited $i rows for checkbox display to make
				room for new service 'Recall' and removed space Matt Holland
				between checkbox divs
	01/19/2015	Added to code:  check to see if survey is locked
				for $dealerID and $surveyindex_id				Matt Holland
	01/19/2015	Added manufacturer ID query for insert/update	Matt Holland
	02/26/2015	Altered $i increment to make room for addt'l	Matt Holland
				'differential' service in basic services list.
				Also altered 'Trans/Diff' in 'Other Services'
				list to 'Transmission'
	03/05/2015	Added error logic -> if the only service		Matt Holland
				selected is an add service then return error.
				Need at least one service selected that is not
				an addsvc
	03/09/2015	Altered Model Year selection dropdown to read	Matt Holland
				from survey_start_yearmodelID in surveys table
				instead of server year.  This ensures that user
				can only select the highest year of the year
				of the survey to ensure data accuracy
	03/23/2015	Adjusted <label> tags for checkboxes to allow	Matt Holland
				for clicking label to mark the checkbox.
				Altered $i increments to make room for
				'Other Svc 1' and 'Other Svc 2' additional items.
				Altered main page title to be blue instead of
				gray.
	04/22/2015	Edited RO table to only show last 5 entries.
				(for better site performance)
				Removed search bar and paginatino from RO table Matt Holland
	05/13/2015	Added javascript instructions to check/uncheck	Matt Holland
				both checkboxes with 'Add' is checked/unchecked
 ---------------------------------------------------------------------------*/

// Required system includes
require_once("functions.inc");
include ('templates/login_check.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

/*---------------------------------------------------------------------------------------*/
// Survey lock test - check to see if survey is locked for $dealerID and $surveyindex_id.  Deny page entry and issue user message if so.
$query = "SELECT locked FROM repairorder WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Lock query failed.  See administrator.';
}
$lookup = $result->fetch_assoc();
$survey_lock = $lookup['locked'];
if ($survey_lock == 1) {
	$_SESSION['error'][] = "No RO edits are allowed.";
	die(header('Location: '.$_SESSION['lastpagedealerreports']));
}
/*---------------------------------------------------------------------------------------*/
// Set manufacturer ID for repairorder and servicerendered INSERT/UPDATE queries
$manufacturer = constant('MANUF');
$query = "SELECT manuf_id FROM manufacturer WHERE manuf_desc = '$manufacturer'";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Manufacturer query failed.  See administrator.';
}
$lookup = $result->fetch_assoc();
$manuf_id = $lookup['manuf_id'];

/*---------------------------------------------------------------------------------------*/
// yearmodel selection menu processing

// Get survey start year from 'surveys' table - model year menu selection will be driven from this
$query = "SELECT survey_start_yearmodelID FROM surveys WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = 'Survey search error.  Please see administrator.';
	die(header('Location: enterrofoundation.php'));
}

$lookup = $result->fetch_assoc();
$currentyear = $lookup['survey_start_yearmodelID'];
//echo'$currentyear: '.$currentyear.'<br>';

// Find associated yearmodelID and modelyear in yearmodel table
$query = "SELECT yearmodelID, modelyear  FROM yearmodel WHERE yearmodelID <= $currentyear
		  ORDER BY yearmodelID DESC";

$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "Year processing failed.  See administrator.";
}
$yearmodelrows = $result->num_rows;
$ymrow = array();
$i = 0;
while ($lookup = $result->fetch_assoc()) {
	$ymrow[$i]['yearmodelID'] = $lookup['yearmodelID']	;
	$ymrow[$i]['modelyear'] 	= $lookup['modelyear']	;
	// echo $ymrow[$i]['yearmodelID'].','.$ymrow[$i]['modelyear'].'<br>';
	$i += 1;
}

/*---------------------------------------------------------------------------------------*/
// Mileage Spread selection menu processing*

// Retrieve id's and labels from table
$query = "SELECT mileagespreadID, carmileage FROM mileagespread";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "mileagespread SELECT query failed.  See administrator.";
}
$mileagerows   = $result->num_rows;

$msrow = array(array());
$i = 0;
while ($mileagevalues = $result->fetch_assoc()) {
	$msrow[$i]['mileagespreadID'] = $mileagevalues['mileagespreadID']	;
	$msrow[$i]['carmileage']	    = $mileagevalues['carmileage']		;
	$i += 1;
}
/*---------------------------------------------------------------------------------------*/
// Update or Delete Processing
// If Update Requested, read repair order record

	/*  Erase all error messages  */
	if (isset($_SESSION['error'])) {
		unset($_SESSION['error']);
	}
	$updateronumber = $_SESSION['updateronumber'];
	$query = "SELECT roID, ronumber, yearmodelID, model_age, mileagespreadID, singleissue, labor, parts, comment FROM repairorder
			WHERE ronumber = '{$updateronumber}' AND dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
	$result = $mysqli->query($query);

	/*  Make sure database is accessible  */
	if (!$result) {
		$_SESSION['error'][] = "Database access failed for repair order ". $ronumber;
		$_SESSION['error'][] = $mysqli->error;
		die (header("Location: enterrofoundation.php"));
	}

	/*  Make sure repair order was read successfully  */
	$row_cnt = $result->num_rows;
	if ($row_cnt == 0) {
		$_SESSION['error'][] = "Repair order ".$updateronumber." NOT found";
		die (header("Location: enterrofoundation.php"));
	}

	/*  Access all the repair order fields  */
	$findRow = $result->fetch_assoc();
	if (isset($findRow['ronumber']) && $findRow['ronumber'] != "") {
		$ronumber 			= $findRow['ronumber'];
		$yearmodelID		= $findRow['yearmodelID'];
		$model_age			= $findRow['model_age'];
		$mileagespreadID	= $findRow['mileagespreadID'];
		$singleissue		= $findRow['singleissue'];
		$labor 				= $findRow['labor'];
		$parts 				= $findRow['parts'];
		$comment 			= $findRow['comment'];
		$roID 				= $findRow['roID'];  /* save for update */

		// Find associated modelyear from $yearmodelID
		$query = "SELECT modelyear FROM yearmodel WHERE yearmodelID = $yearmodelID";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "yearmodel SELECT query failed.  See administrator.";
		}
		$modelyearvalue = $result->fetch_assoc();
		$modelyear		= $modelyearvalue['modelyear'];

		// Find associated carmileage label from $mileagespreadID
		$query = "SELECT carmileage FROM mileagespread WHERE mileagespreadID = $mileagespreadID";
		$result = $mysqli->query($query);
		if (!$result) {
			$_SESSION['error'][] = "Mileage query failed.  See administrator.";
		}
		$mileagespreadvalue = $result->fetch_assoc();
		$carmileage		= $mileagespreadvalue['carmileage'];

		// Select RO's services
		$servicequery = "SELECT *
						FROM servicerendered
						WHERE dealerID = $dealerID AND ronumber = '{$updateronumber}' AND surveyindex_id = $surveyindex_id ";
		$result = $mysqli->query($servicequery);
		if (!$result) {
			$_SESSION['error'][] = "query failed for services rendered table";
			$_SESSION['error'][] = $mysqli->error;
			die (header("Location: enterrofoundation.php"));
		}
		/*  Create arrays for services rendered and corresponding add settings/service# of this order to use in generating form later  */
		$services = array();
		$addvalue = array();
		$services_cnt = $result->num_rows;
		$indx = 1;
		if (isset($findRow['ronumber']) && $findRow['ronumber'] != "") {
			while ($service = $result->fetch_assoc()) {
				$services[$indx] = $service['serviceID'];
				$addvalue[$indx] = $service['addsvc'];
				$indx += 1;
			}
		}
	else {
		$_SESSION['error'][] = "Repair order ".$updateronumber." NOT read after update request";
		}
	}

/*------------------------------UPDATE---------------------------*
						  UPDATE REQUEST ??
	Update repair order, delete all associated services,
	and add all associated services using new values. But
	first make sure if user has changed the ronumber that
	ronumber does not exist already or duplicate ronumbers
	will result.
 *---------------------------------------------------------------*/
if (isset($_POST['submit'])) {
	$updateronumber = $_SESSION['updateronumber'];
	$ronumber = $mysqli->real_escape_string($_POST['ronumber']);
	$rostatus = check_ro($updateronumber, $ronumber, $dealerID, $surveyindex_id, $mysqli);
	switch ($rostatus) {
		case 0:
			/*  RO number entered was blank  */
			$_SESSION['error'][] = "RO number cannot be blank";
			break;
		case 1:
			/*  Attempt to overwrite existing RO  */
			$_SESSION['error'][] = "Repair order already exists";
			break;
		case 2:
			/*  Did not find that RO number  */
			$_SESSION['error'][] = "RO number does not exist";
			break;
		case 3:
			/*  Everything is OK, proceed with update  */
			if (!validate_services()) {
				/*  ERROR in service selections  */
				$_SESSION['error'][] = "You must select a service";
				}
			else {
				/*  Services and RO passed mustard, proceed with update  */
				$yearmodelID = $mysqli->real_escape_string($_POST['yearmodelID']);
				$mileagespreadID = $mysqli->real_escape_string($_POST['mileagespreadID']);
				$singleissue = $mysqli->real_escape_string($_POST['singleissue']);
	            if ($_POST['labor'] !='') {
	            	$labor     	= $mysqli->real_escape_string($_POST['labor'])			;
	            } else {
	            	$labor = NULL;
	            }
	            if ($_POST['parts'] !='') {
	            	$parts     	= $mysqli->real_escape_string($_POST['parts'])			;
	            } else {
	            	$parts = NULL;
	            }
				$comment = $mysqli->real_escape_string($_POST['comment']);

				// Find associated modelyear from $yearmodelID
				$query = "SELECT modelyear FROM yearmodel WHERE yearmodelID = $yearmodelID";
				$result = $mysqli->query($query);
				if (!$result) {
					$_SESSION['error'][] = "yearmodel SELECT query failed.  See administrator.";
				}
				$modelyearvalue = $result->fetch_assoc();
				$modelyear		= $modelyearvalue['modelyear'];
				//echo '$modelyear: ' .$modelyear. '<br>';
				// Calculate model_age for repairorder model_age insert
				$model_age = ($currentyear-1) - $modelyear;
				if ($model_age > 9) {
					$model_age = 9;// Calculate age of auto relative to survey year
				}
			    if ($parts == NULL and $labor == NULL) {
				    $query = "UPDATE repairorder SET	ronumber 			='{$ronumber}',
				    									yearmodelID 		='{$yearmodelID}',
				    									model_age			='{$model_age}',
				    									mileagespreadID 	='{$mileagespreadID}',
				    									singleissue 		='{$singleissue}',
				    									labor		    	= NULL,
				    									parts 				= NULL,
				    									comment		    	='{$comment}',
				    									create_date			= NOW()
				    									WHERE ronumber	 	= '{$updateronumber}'
				    									AND dealerID  		='{$dealerID}'
				    									AND surveyindex_id 	='{$surveyindex_id}'";
			    } elseif ($parts == NULL and $labor != NULL) {
				    $query = "UPDATE repairorder SET	ronumber 			='{$ronumber}',
				    									yearmodelID 		='{$yearmodelID}',
				    									model_age			='{$model_age}',
				    									mileagespreadID 	='{$mileagespreadID}',
				    									singleissue 		='{$singleissue}',
				    									labor		    	='{$labor}',
				    									parts 				= NULL,
				    									comment		    	='{$comment}',
				    									create_date			= NOW()
				    									WHERE ronumber	 	= '{$updateronumber}'
				    									AND dealerID  		='{$dealerID}'
				    									AND surveyindex_id 	='{$surveyindex_id}'";
			    } elseif ($parts != NULL and $labor == NULL) {
				    $query = "UPDATE repairorder SET	ronumber 			='{$ronumber}',
				    									yearmodelID 		='{$yearmodelID}',
				    									model_age			='{$model_age}',
				    									mileagespreadID 	='{$mileagespreadID}',
				    									singleissue 		='{$singleissue}',
				    									labor		    	= NULL,
				    									parts 				='{$parts}',
				    									comment		    	='{$comment}',
				    									create_date			= NOW()
				    									WHERE ronumber	 	= '{$updateronumber}'
				    									AND dealerID  		='{$dealerID}'
				    									AND surveyindex_id 	='{$surveyindex_id}'";
			    } else {
				    $query = "UPDATE repairorder SET	ronumber 			='{$ronumber}',
				    									yearmodelID 		='{$yearmodelID}',
				    									model_age			='{$model_age}',
				    									mileagespreadID 	='{$mileagespreadID}',
				    									singleissue 		='{$singleissue}',
				    									labor		    	='{$labor}',
				    									parts 				='{$parts}',
				    									comment		    	='{$comment}',
				    									create_date			= NOW()
				    									WHERE ronumber	 	= '{$updateronumber}'
				    									AND dealerID  		='{$dealerID}'
				    									AND surveyindex_id 	='{$surveyindex_id}'";
				}
				/* Check for completion of Update and issue message if failure */
				if (!$mysqli->query($query)) {
					/* ERROR - repair order not inserted */
					$_SESSION['error'][] = "Repair order ". $ronumber. " was not updated";
					$_SESSION['error'][] = $mysqli->error;
					$_SESSION['error'][] = $query;
					}
				else {
					/* Delete all associated services */
					$query = "DELETE FROM servicerendered WHERE (ronumber=$updateronumber AND dealerID = $dealerID AND surveyindex_id = $surveyindex_id)";
					if (!$mysqli->query($query)) {
						$_SESSION['error'][] = "*Delete of Services in Order ". $ronumber. " failed";
						$_SESSION['error'][] = $mysqli->error;
						$_SESSION['error'][] = $query;
						}
					else {
						if (add_services($mysqli, $ronumber, $singleissue, $dealerID, $surveyindex_id, $userID)) {
							$_SESSION['success'][] = "*Repair order ". $ronumber. " has been updated";
							}
						else {
							$_SESSION['error'][] = "*Repair order ". $ronumber. " has been updated, but services had write errors";
						}
					}
				}
				die (header("Location: ".$_SESSION['lastpagedealerreports']));
			}
			break;
	}  /*  end switch  */
}	/*  end UPDATE process  */

/*----------------------DELETE-------------------------*
				   DELETE REQUEST ??

 *-----------------------------------------------------*/

if (isset($_POST['delete']))
{
	$ronumber = $_SESSION['updateronumber'];
	$querydelete = "DELETE FROM repairorder WHERE (ronumber=$ronumber AND dealerID=$dealerID AND surveyindex_id = $surveyindex_id)";

	/*  Delete the repair order specified  */

	$resultdelete = $mysqli->query($querydelete);
	if (!$resultdelete) {
		$_SESSION['error'][] = "Delete of Repair Order ". $ronumber. " failed";
		$_SESSION['error'][] = $mysqli->error;
		}
	else {
		/*  Now delete all services rendered associated with order  */
		$_SESSION['repairordercount'] -= 1;      /* decrement repair order count for display */

		$querydelete = "DELETE FROM servicerendered WHERE (ronumber=$ronumber AND dealerID = $dealerID and surveyindex_id = $surveyindex_id)";
		$resultdelete = $mysqli->query($querydelete);
		if (!$resultdelete) {
			$_SESSION['error'][] = "DELETE of Services failed for Repair Order ". $ronumber;
			$_SESSION['error'][] = $mysqli->error;
			}
		else {
			$_SESSION['error'][] = "Repair Order ". $ronumber. " deleted";
		}
	}
	die (header("Location: enterrofoundation.php"));    /*  return to enterrofoundation  */

}	// End Update Processing

/*---------------------------------------------- End of Main Program ------------------------------------*/

/*********************************************************************************************************/
/*                                              FUNCTIONS                                                */
/*********************************************************************************************************/

/*------------------------------------------------------*
    Function:	check_ro
	Purpose:
		check for validity of ronumber
	Returns:
		0		blank RO number entered
		1		user changed RO number to existing RO
		2		query failed on RO number
		3		everything is OK
 *------------------------------------------------------*/
function check_ro ($updateronumber, $ronumber, $dealerID, $surveyindex_id, $mysqli)
{
	if ($ronumber == "") {
		return 0;   /*  Signify blank RO number entered  */
	}
	if ($updateronumber != $ronumber) {
		/*  See if ronumber already exists in table  */
		$query = "SELECT * FROM repairorder WHERE ronumber = $ronumber AND dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
		$findresult = $mysqli->query($query);
		if (!$findresult) {
			$_SESSION['error'][] = "Read error on repair order ".$ronumber;
			$findresult->free();	/*  free result set resources */
			return 2;
			}
		$findrow = $findresult->fetch_assoc();
		if (isset($findrow['ronumber'])) {
			$findresult->free();	/*  free result set resources */
			return 1;				/*  Error - overlaying existing RO  */
			}
		else {
			$findresult->free();	/*  free result set resources  */
			return 3;
			}
		}
	else {
		/*  repair orders are the same number, no duplicates  */
		return 3;
	}
}

/*********************************************************/
/*********************************************************/
/*                        FUNCTIONS                      */
/*--------------------------------------------------------*
    Function:	validate_services
	Purpose:
		validate services before adding records
	Returns:
		FALSE	valid
		TRUE 	invalid
 *--------------------------------------------------------*/

function validate_services() {
	/*  Check for any add checked without corresponding service checked  */
	if (isset($_POST['servicebox'])) {
		$servicebox = $_POST['servicebox'];
	} else {
		$servicebox = array();
	}
	if (isset($_POST['addsvc'])) {
		$addbox = $_POST['addsvc'];
	} else {
		$addbox = array();
	}
	/*  Must be one or more services checked  */
	if (count($servicebox) == 0) {
		return FALSE;		/* no services keyed - not valid */
	}
	if (count($addbox) == 0) {
		return TRUE;		/* services but no adds keyed - OK */
	}
	/* There cannot only be a service with an addservice checked.  Must at least have one service that is not an added service */
	if (count($servicebox) ==  count($addbox)) {
		$_SESSION['error'][] = 'You must at least have one service that is not an added service.';
		return FALSE;
	}
	/*  Are any of the Add's unmatched with services?  If so not valid  */
	foreach ($addbox as $add) {
		$unmatched = TRUE;
		foreach ($servicebox as $service) {
			if ($service == $add) {
				$unmatched = FALSE;
			}
		}
		/*  Add has no matching service - not valid  */
		if ($unmatched) {
			return FALSE;
		}
	}
	return TRUE;
}	/*  End of valid_service function */


/*------------------------------------------------------*
    Function:	add_services
	Purpose:
		add services to servicerendered table.
	Returns:
		TRUE	successful
		FALSE	unsuccessful
 *------------------------------------------------------*/

function add_services($mysqli, $ronumber, $singleissue, $dealerID, $surveyindex_id, $userID)
{
	/*  Initialize to no errors for services insertions  */
	$services_error = FALSE;
	/*  build services and add services arrays from form input  */
	if (isset($_POST['servicebox'])) {
		$servicebox = $_POST['servicebox'];
	} else {
		$servicebox = array();
	}
	if (isset($_POST['addsvc'])) {
		$addbox = $_POST['addsvc'];
	} else {
		$addbox = array();
	}

	/*  Are any of the Add's unmatched with services?  If so not valid  */
	foreach ($servicebox as $service) {
		$addsvc = 0;
		if (count($addbox) > 0) {
		    foreach ($addbox as $add) {
		    	if ($service == $add) {
		    		$addsvc = 1;
		    	}
		    }
		}
		/*  Insert service entered into servicerendered table */
		$sqlinsert = "INSERT INTO servicerendered (ronumber, singleissue, serviceID, addsvc, dealerID, surveyindex_id, manuf_id, create_date, userID)
			VALUES ($ronumber, $singleissue, $service, $addsvc, '$dealerID', '$surveyindex_id', '$manuf_id', NOW(), $userID)";
		if (!$mysqli->query($sqlinsert)) {
			$services_error = TRUE;
		}

	}
	if ($services_error) {
		$_SESSION['error'][] = "Services for Repair order ". $ronumber. " were not added";
	}
	return !$services_error;
}

/*----------------------------------------------------------*
    Function:	echoservicebox
	Purpose:
		echo services input box.  If service is
		in services rendered table then show box as checked
	Inputs:
		$servicevalue:	 	numeric associated with service
		$servicenickname: 	description of service
		$services:			array of services for order
 *----------------------------------------------------------*/
function echoservicebox($servicevalue, $servicenickname, &$services, &$i) {
	$key = array_search($servicevalue, $services);	/* see if this service box is in services rendered table */
	if ($key == FALSE) {
echo'			<label class="service_checkbox"><input type="checkbox" id="servicebox[]" name="servicebox[]" onclick="check_svcboxes('.$i.');" value='.$servicevalue.'> '.$servicenickname.'</label><br>';
	} else {
echo'			<label class="service_checkbox"><input type="checkbox" id="servicebox[]" name="servicebox[]" onclick="check_svcboxes('.$i.');" value='.$servicevalue.' checked> '.$servicenickname.'</label><br>';
	}
}
/*------------------------------------------------------------*
    Function:	echoaddbox
	Purpose:
		echo add input box.  If corresponding service is in
		services rendered table then reflect add field value.
	Inputs:
		$servicevalue:	 	numeric associated with service
		$addvalue: 			add service option = 0,1
		$services:			array of services for order
 *------------------------------------------------------------*/
function echoaddbox($servicevalue, &$addvalue, &$services, &$i) {
	$key = array_search($servicevalue, $services);		/* see if this add box is associated with row in services rendered table */
	if ($key == FALSE) {
echo'			<label class="service_checkbox"><input type="checkbox" id="addsvc[]" name="addsvc[]" onclick="check_addboxes('.$i.');" value='.$servicevalue.'> Add </label><br>';
	} else {
		if ($addvalue[$key] == 0) {
echo'			<label class="service_checkbox"><input type="checkbox" id="addsvc[]" name="addsvc[]" onclick="check_addboxes('.$i.');" value='.$servicevalue.'> Add </label><br>';
		} else {
echo'			<label class="service_checkbox"><input type="checkbox" id="addsvc[]" name="addsvc[]" onclick="check_addboxes('.$i.');" value='.$servicevalue.' checked> Add </label><br>';
		}
	}
}
/*--------------------------------------------------------Checkboxes processing--------------------------------------------*/
// Query services table to retrieve values and labels for all checkboxes
$query = "SELECT serviceID, service_nickname FROM services
		  WHERE rosurvey_svc = 1
		  ORDER BY servicesort ASC";
$result = $mysqli->query($query);
if (!$result) {
	$_SESSION['error'][] = "services SELECT query failed.";
}

$svcarray = array(array());
$index = 0;
while ($checkboxlookup = $result->fetch_assoc()) {
	$svcarray[$index]['serviceID'] = $checkboxlookup['serviceID'];
	$svcarray[$index]['service_nickname'] = $checkboxlookup['service_nickname'];
	$index += 1;
}


?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - <?php echo constant('MANUF');?></title>

    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
	<style>
		@media (min-width: 40.063em) {
			.collapse_select {
				margin-top: .42rem;
				width: 130px;
			}
			.collapse_submit {
				margin-left: 18px;
			}
		}

		.collapse_select {
			height: 2rem;
		}

		.collapse_submit {
			height: 2rem !important;
		}

        label {
            cursor: default;
            font-size: 17px;
        }

		.service_checkbox {
			display: inline-block;
			cursor: pointer;
		}
	</style>
	<script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/modernizr.js"></script>
	<script>
	$(document).ready(function() {
		$("#delete").on("click",
		function() {
			if(confirm("Delete Repair Order?")) {
				return true;
			} else {
				return false;
			}
		});
	});

	function check_addboxes(i) {
		var svcbox_array = document.getElementById('service_form')['servicebox[]'];
		var addbox_array = document.getElementById('service_form')['addsvc[]'];
		if (addbox_array[i].checked == true) {
			svcbox_array[i].checked = true;
		} else if (addbox_array[i].checked == false) {
			svcbox_array[i].checked = false;
		}
	}

	function check_svcboxes(i) {
		var svcbox_array = document.getElementById('service_form')['servicebox[]'];
		var addbox_array = document.getElementById('service_form')['addsvc[]'];
		if (svcbox_array[i].checked == false) {
			addbox_array[i].checked = false;
		}
	}
	</script>

  </head>
  <body>
<div class="wrapper">

<?php
include ('templates/menubar_enterrofoundation.php');
?>

<div class="row">
	<div class="medium-12 large-12 columns">
		<div class="row">
			<div class="small-12 medium-10 large-10 columns">
				<h2>Update RO <span style="color: #00008B; font-size: 23px;">
				<?php
					if(isset($_SESSION['survey_description'])) {
						echo ' - '.constant('ENTITY'). ' '.$_SESSION['dealercode'].'&nbsp;<span style="font-size: 15px; color: gray;"> ('.$_SESSION['survey_description']. ')</span>';
					}
				?>
				</span></h2>
			</div>
			<div class="small-12 medium-2 large-2 columns">
				<h5><a href="<?php echo $_SESSION['lastpagedealerreports'];?>"> Cancel</a></h5>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<div style="color: red;" class="small-12 medium-7 large-7 columns">
			<h5 style="color: red;">
				<?php
					if (isset($_SESSION['error'])) {
						foreach ($_SESSION['error'] as $error) {
							print $error . "<br />\n";
						} //end foreach
						unset($_SESSION['error']);
					} //end if
				?>
			</h5>
		</div>
	</div>
</div>
<div class="small-12 medium-12 large-12 columns">
	<div class="row">
		<p> </p>
	</div>
</div>
<form data-abide method="post" id="service_form" action="enterrofoundationupdate_process.php">
<input type="hidden" name="submitted" value="true" />
<div class="row">
	<div class="small-12 large-4 columns">
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<p><img src="<?php echo constant('PIC_ENTERRO');?>"></p>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<div class="number-field">
					<label>RO Number
<!--					<input required type="text" id="ronumber" name="ronumber" placeholder="Enter Repair Order Number" pattern="integer" autofocus>  -->
						<input type="text" id="ronumber" name="ronumber" value="<?php echo $ronumber; ?>" pattern="integer" autofocus>
					</label>
					<small class="error">RO Number is required</small>
				</div>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Year Model
					<select required id="yearmodelID" name="yearmodelID">
						<option value="<?php echo $yearmodelID; ?>"><?php echo $modelyear; ?></option>
<?php
for ($i = 0; $i < $yearmodelrows-1; $i++) {
echo 					'<option value= '.$ymrow[$i]['yearmodelID'].'>' .$ymrow[$i]['modelyear']. '</option><br>';
}
?>
					</select>
				</label>
				<small class="error">Please enter the model year</small>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Mileage Spread
					<select required id="mileagespreadID" name="mileagespreadID">
						<option value="<?php echo $mileagespreadID; ?>"><?php echo $carmileage; ?></option>
=<?php
for ($i = 0; $i < $mileagerows; $i++) {
echo 					'<option value= '.$msrow[$i]['mileagespreadID'].'>' .$msrow[$i]['carmileage']. '</option><br>';
}
?>
					</select>
				</label>
				<small class="error">Please enter the mileage spread</small>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Single Issue?
					<select required id="singleissue" name="singleissue">
						<?php
						if ($singleissue == 0) {
							echo '
						<option value="0">No</option>
						<option value="0">No</option>
						<option value="1">Yes</option>';
						} elseif ($singleissue == 1) {
							echo '
						<option value="1">Yes</option>
						<option value="0">No</option>
						<option value="1">Yes</option>';
						}
						?>
					</select>
					<small class="error">You must select an option</small>
				</label>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Labor
					<input type="text" name="labor" value="<?php echo $labor; ?>" pattern="number">
				</label>
				<small class="error">Labor must be a number</small>
			</div>
			<div class="small-12 medium-6 large-12 columns">
				<label>Parts
					<input type="text" name="parts" value="<?php echo $parts; ?>" pattern="number">
				</label>
				<small class="error">Parts must be a number</small>
			</div>
		</div>
	</div>
	<div class="small-12 medium-12 large-8 columns">
	<h5>Basic Services<span style="color: blue; font-size: 15px;"> &nbsp; *Note: Select 'Add' to activate both boxes </span></h5>
		<div class="panel" style="padding: 1.45rem 1.5rem .75rem 0rem;">
			<div class="row">
				<div class="small-2 medium-1 large-1 columns">
					<p>   </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
<?php
for ($i=0; $i<5; $i++) {
	echoservicebox($svcarray[$i]['serviceID'], $svcarray[$i]['service_nickname'], $services, $i);
}
?>
				</div>
				<div class="small-4 medium-2 large-2 columns">
<?php
for ($i=0; $i<5; $i++) {
	echoaddbox($svcarray[$i]['serviceID'], $addvalue, $services, $i);
}
?>
				</div>
				<div class="small-2 medium-1 large-1 columns">
					<p>   </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
<?php
for ($i=5; $i<9; $i++) {
	echoservicebox($svcarray[$i]['serviceID'], $svcarray[$i]['service_nickname'], $services, $i);
}
?>
				</div>
				<div class="small-4 medium-2 large-2 columns">
<?php
for ($i=5; $i<9; $i++) {
	echoaddbox($svcarray[$i]['serviceID'], $addvalue, $services, $i);
}
?>
				</div>
			</div>
		</div>
	</div>
	<div class="small-12 medium-12 large-8 columns">
	<h5>Other Services</h5>
		<div class="panel" style="padding: 1.45rem 1.5rem .75rem 0rem;">
			<div class="row">
				<div class="small-2 medium-1 large-1 columns">
					<p>  </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
<?php
for ($i=9; $i<18; $i++) {
	echoservicebox($svcarray[$i]['serviceID'], $svcarray[$i]['service_nickname'], $services, $i);
}
?>
				</div >
				<div class="small-4 medium-2 large-2 columns">
<?php
for ($i=9; $i<18; $i++) {
	echoaddbox($svcarray[$i]['serviceID'], $addvalue, $services, $i);
}
?>
				</div>
				<div class="small-2 medium-1 large-1 columns">
					<p>   </p>
				</div>
				<div class="small-6 medium-3 large-3 columns">
<?php
for ($i=18; $i<27; $i++) {
	echoservicebox($svcarray[$i]['serviceID'], $svcarray[$i]['service_nickname'], $services, $i);
}
?>
				</div>
				<div class="small-4 medium-2 large-2 columns">
<?php
for ($i=18; $i<27; $i++) {
	echoaddbox($svcarray[$i]['serviceID'], $addvalue, $services, $i);
}
?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<div class="row">
					<div class="small-12 medium-12 large-6 columns">
						<textarea id="comment" name="comment" ><?php echo $comment; ?></textarea>
					</div>
					<div class="small-12 medium-2 large-3 columns">
						<input id="submit" type="submit" name="submit" value="Save Changes" class="small button radius">
					</div>
					<div class="small-12 medium-3 large-3 columns">
						<input id="delete" type="submit" name="delete" value="Delete Order" class="small alert button radius" style="padding-right: 2.12rem; padding-left: 2.13rem ">
					</div>
					<div class="medium-7 columns">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
</div>

<div class="push"></div>  <!--pushes down footer so does not overlap anything-->
</div> <!--End div 'wrapper'-->

<footer>
	<span class="footer_span"><span class="copyright">&copy; <?php echo date('Y'); ?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>

<script src="js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>

</body>
</html>