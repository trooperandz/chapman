<?php

/* -------------------------------------------------------------------------*
   Program: enterrofoundationadd_process.php

   Purpose: Add repair orders and associated services.  This is invoked
			from enterrofoundation.php when submit button is hit.

   History:
    Date		Description										by
	09/12/2014	Initial design and coding.						Matt Holland
	09/12/2014	Init services variables for fcns.				Matt Holland
	09/13/2014	serviceallflushes, addsvcallflushes wrong		Matt Holland
	09/16/2014	Rework fcn add_services. Same as update			Matt Holland
	10/15/2014	Add dynamic year processing for year selection	Matt Holland
	10/26/2014	Add surveyindex_id processing					Matt Holland
	10/30/2014	Add survey_year_start INSERT instruction		Matt Holland
	11/02/2014	Convert to use arrays for services and adds.	Matt Holland
	11/18/2014	Adjust yearmodel processing to incorporate
				new yearmodel_strings table						Matt Holland
	11/21/2014	Change valid_services to validate_services.		Matt Holland
				Fix cond no adds or no services causes fault.   Matt Holland
	12/18/2014	Allow for null parts and labor to store in db.	Matt Holland
	12/22/2014	Changed labor & parts !empty instruction to 
				!='' to allow for correct 0 value processing	Matt Holland
	01/16/2015	Added to code:  check to see if dealerID exists
				before inserting into repairorder				Matt Holland
	01/19/2015	Added to code:  check to see if survey is locked
				for $dealerID and $surveyindex_id				Matt Holland
	01/19/2015	Added manufacturer ID query for insert/update	Matt Holland
	02/05/2015	Added sticky form elements (not for checkboxes)	Matt Holland
	03/06/2015	Altered $currentyear processing.				Matt Holland
				Moved sticky form elements up in program so
				that $currentyear error does not prevent sticky
				elements from getting set
	03/13/2015	Changed RO success message SESSION variable to	Matt Holland
				$_SESSION['ro_success'] to work in tandem with
				new message placement on enterrofoundation.php
	06/02/2015  Revamped with AJAX form submit					Matt Holland
 ---------------------------------------------------------------------------*/
 
 // Required system includes
require_once("functions.inc");
include('templates/login_check_ajax.php');

// Database connection
include('templates/db_cxn.php');

// Initialize default variables
include ('templates/init_dlr_vars.php');

// Check to make sure that $dealerID exists (could be the wrong global due to multiple open systems in one browser sharing the $_SESSION['dealerID'] global variable)
$query = "SELECT dealercode FROM dealer WHERE dealerID = $dealerID and dealercode = $dealercode";
$result = $mysqli->query($query);
if (!$result) {
	exit("error_query");	
}
$rows = $result->num_rows;
if ($rows == 0) {
	exit("error_insert");
}

// Survey lock test - check to see if survey is locked for $dealerID and $surveyindex_id.  Deny entry and issue user message if so.
$query = "SELECT locked FROM repairorder WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
$result = $mysqli->query($query);
if (!$result) {
	exit("error_query");
}
$lookup = $result->fetch_assoc();
$survey_lock = $lookup['locked'];
if ($survey_lock == 1) {
	exit("error_survey_lock");
}

// Set manufacturer ID for repairorder and servicerendered INSERT/UPDATE queries
$manufacturer = constant('MANUF');
$query = "SELECT manuf_id FROM manufacturer WHERE manuf_desc = '$manufacturer'";
$result = $mysqli->query($query);
if (!$result) {
	exit("error_query");	
}
$lookup = $result->fetch_assoc();
$manuf_id = $lookup['manuf_id'];


/*  Erase all error messages  */
if (isset($_SESSION['error'])) { 
	unset($_SESSION['error']);
}
$ronumber = $_POST['ronumber'];
if (isset($_POST['ronumber']) && !empty($_POST['ronumber']) &&
	isset($_POST['yearmodelID']) &&
	isset($_POST['mileagespreadID']) &&
	isset($_POST['singleissue']) &&
	isset($_POST['labor']) &&
	isset($_POST['parts']) &&
	isset($_POST['comment'] ))
{
	/*  All repair order primary fields are entered with something  */
	/*  Fill variables with repair order table values entered       */

	$ronumber   	= $mysqli->real_escape_string($_POST['ronumber'])		;
	$yearmodelID   	= $mysqli->real_escape_string($_POST['yearmodelID'])	;
	$mileagespread 	= $mysqli->real_escape_string($_POST['mileagespreadID']);
	$singleissue 	= $mysqli->real_escape_string($_POST['singleissue'])	;
	
	$_SESSION['enterro_ronumber'] 			= $_POST['ronumber'];
	$_SESSION['enterro_yearmodelID'] 		= $_POST['yearmodelID'];
	$_SESSION['enterro_mileagespreadID'] 	= $_POST['mileagespreadID'];
	$_SESSION['enterro_singleissue'] 		= $_POST['singleissue'];
	$_SESSION['enterro_labor'] 				= $_POST['labor'];
	$_SESSION['enterro_parts'] 				= $_POST['parts'];
	$_SESSION['enterro_comment'] 			= $_POST['comment'];
	
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
	$comment		= $mysqli->real_escape_string($_POST['comment'])		;
	
	/* Lookup modelyear from yearmodel table for sticky form and model age processing below */
	$query = "SELECT modelyear from yearmodel WHERE yearmodelID = $yearmodelID";
	$result = $mysqli->query($query);
	if (!$result) {
		exit("error_query");
	}
	$yearmodel_value 		 = $result->fetch_assoc();
	$yearmodel_lookup_value	 = $yearmodel_value['modelyear'];
	
	// Save for sticky form echo
	$_SESSION['enterro_yearmodel'] = $yearmodel_lookup_value;
	
	// Lookup carmileage from mileagespreadID for sticky form input
	$query = "SELECT carmileage from mileagespread WHERE mileagespreadID = $mileagespread";
	$result = $mysqli->query($query);
	if (!$result) {
		exit("error_query");
	}
	$lookup = $result->fetch_assoc();
	// Set variable for sticky form
	$_SESSION['enterro_carmileage'] = $lookup['carmileage'];
	
	// Survey start year test - check to see if survey start year has been set.  If not, deny RO entry and issue error message
	$query = "SELECT survey_start_yearmodelID FROM surveys WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
	$result = $mysqli->query($query);
	if (!$result) {
		exit("error_query");
	}
	$lookup = $result->fetch_assoc();
	$survey_start_check = $lookup['survey_start_yearmodelID'];
	// If survey_start_yearmodelID in surveys table = 0, issue error notice and return back to main program.  Survey year must be set by user before proceeding.  Else user can proceed with adding RO
	if ($survey_start_check == 0) {
		exit("error_survey_startyear");
	} else {
		// Set $currentyearID to lookup value
		$currentyearID = $survey_start_check;
		// Lookup ID to find actual modelyear label
		$query = "SELECT modelyear FROM yearmodel WHERE yearmodelID = $currentyearID";
		$result = $mysqli->query($query);
		if (!$result) {
			exit("error_query");
		}
		// Fetch actual modelyear and set $currentyear to result
		$lookup = $result->fetch_assoc();
		$currentyear = $lookup['modelyear'];
	}

	// Calculate last year for yearmodel string query - for BETWEEN statement
	$firstyear = $currentyear-8;
	
	// Calculate current auto age relative to $currentyear
	$model_age = $currentyear - $yearmodel_lookup_value;
	if ($model_age > 9) {
		$model_age = 9;// Calculate age of auto relative to survey year
	}
	
	/*  First check if duplicate exists for this dealer/ronumber/surveyindex_id */
	$query = "SELECT roID FROM repairorder WHERE (ronumber = $ronumber AND dealerID = $dealerID AND surveyindex_id = $surveyindex_id)";
	$result = $mysqli->query($query);
	if ($result) {
		$rows = $result->num_rows;
		}
	else {
		$rows = 0;	/* query failed, set rows 0 */
	}
		
	if ($rows > 0) {
		/*  ERROR - DUPLICATE repair order, request denied  */
		exit("error_ro_dupe");
	} else {
		/*  No duplicate, everything is clear to proceed with adding record, validate services first  */
		if (validate_services()) {
			if ($parts == NULL and $labor == NULL) {
				$query = "INSERT INTO repairorder
					(ronumber, yearmodelID, model_age, mileagespreadID, singleissue, dealerID, surveyindex_id, manuf_id, create_date, userID, comment)
					VALUES
					('$ronumber', '$yearmodelID', '$model_age', '$mileagespread', '$singleissue', '$dealerID', '$surveyindex_id', '$manuf_id', NOW(), '$userID', '$comment')";
			} elseif ($parts == NULL and $labor != NULL) {
				$query = "INSERT INTO repairorder
					(ronumber, yearmodelID, model_age, mileagespreadID, singleissue, labor, dealerID, surveyindex_id, manuf_id, create_date, userID, comment)
					VALUES
					('$ronumber', '$yearmodelID', '$model_age', '$mileagespread', '$singleissue', '$labor', '$dealerID', '$surveyindex_id', '$manuf_id', NOW(), '$userID', '$comment')";
			} elseif ($parts != NULL and $labor == NULL) {
				$query = "INSERT INTO repairorder
					(ronumber, yearmodelID, model_age, mileagespreadID, singleissue, parts, dealerID, surveyindex_id, manuf_id, create_date, userID, comment)
					VALUES
					('$ronumber', '$yearmodelID', '$model_age', '$mileagespread', '$singleissue', '$parts', '$dealerID', '$surveyindex_id', '$manuf_id', NOW(), '$userID', '$comment')";
			} else {
				$query = "INSERT INTO repairorder
					(ronumber, yearmodelID, model_age, mileagespreadID, singleissue, parts, labor, dealerID, surveyindex_id, manuf_id, create_date, userID, comment)
					VALUES
					('$ronumber', '$yearmodelID', '$model_age', '$mileagespread', '$singleissue', '$parts', '$labor', '$dealerID', '$surveyindex_id', '$manuf_id', NOW(), '$userID', '$comment')";
			}
			/*  Check for completion of insert and issue message if failure  */
			if (!$result = $mysqli->query($query)) {
				/*  ERROR - repair order not inserted  */
				exit("error_insert");
			} else {
				/*  Now insert services into services rendered table  */
				if (add_services($mysqli, $ronumber, $singleissue, $dealerID, $surveyindex_id, $manuf_id, $userID)) {
					echo '<div>
						    <div id="update_div1">
						      <h5 style="color: #228B22; font-weight: bold; font-size: 15px;">Repair order '.$ronumber.' has been added</h5>
						    </div>';
						  
					// Get new ro count for ajax update:
					$query = "SELECT * FROM repairorder WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id";
					$result = $mysqli->query($query);
					if (!$result) {
						exit("error_query");
					}
					$repairorderrows = $result->num_rows;
					echo'<div id="update_div2"><h5>Total ROs: '.$repairorderrows.'</h5></div>';
					echo'<div id="update_div4">
							<div class="row">
								<div class="small-12 medium-10 large-10 columns">
									<div id="update_div4">
										<p>Total Repair Orders: '.$repairorderrows.' <span style="color: blue; font-size: 13px;"> (Showing last 5 entries)</span><br>
										<a href="viewall_ros.php">View all entries</a></p>
									</div>
								</div>
								<div class="small-12 medium-2 large-2 columns">
									<h6><a href="ro_x.php">Export RO Data</a></h6>
								</div>
							</div>
						 </div>';
		
					//Unset sticky form elements if RO addition was successful
					unset($_SESSION['enterro_ronumber']			);			
					unset($_SESSION['enterro_yearmodelID']		);
					unset($_SESSION['enterro_yearmodel']		); 
					unset($_SESSION['enterro_mileagespreadID']	);
					unset($_SESSION['enterro_carmileage']		); 
					unset($_SESSION['enterro_singleissue']		); 		
					unset($_SESSION['enterro_labor']			); 			
					unset($_SESSION['enterro_parts'] 			);		
					unset($_SESSION['enterro_comment'] 			);
					
					// Echo table for ajax update
					/*  Read all the repair orders  */
					$query = 	"SELECT ronumber, modelyear, carmileage, singleissue, labor, parts, comment FROM repairorder, yearmodel, mileagespread
					WHERE repairorder.mileagespreadID = mileagespread.mileagespreadID AND repairorder.yearmodelID = yearmodel.yearmodelID
					AND repairorder.dealerID = $dealerID AND repairorder.surveyindex_id = $surveyindex_id
					ORDER BY roID DESC
					LIMIT 5";
					$result = $mysqli->query($query);
					if (!$result) {
						exit("error_query");
					}
					$rows = $result->num_rows;
					/*  Display each repair order in list allowing DELETE   */
					/*  And display all associated services for each order  */
					echo'  <div id="update_div3">
							 <div class="row">
							  <div class="small-12 medium-12 large-12 columns">
								<table id="enterrotable" class="original responsive">
								  <thead>
									<tr style="width: 150px; height: 35px; background-image: url(css/bg.gif); background-repeat: no-repeat; background-position: center right;">
										<th style="width: 150px; height: 35px;"><a>	Action			</a></th>  
										<th style="width: 150px; height: 35px;"><a>	RO #			</a></th> 
										<th style="width: 150px; height: 35px;"><a>	Model			</a></th> 
										<th style="width: 150px; height: 35px;"><a>	Mileage			</a></th> 
										<th style="width: 150px; height: 35px;"><a>	Single Svc		</a></th> 
										<th style="width: 150px; height: 35px;"><a>	Labor			</a></th> 
										<th style="width: 150px; height: 35px;"><a>	Parts			</a></th>
										<th style="width: 150px; height: 35px;"><a>	Services		</a></th>
										<th style="width: 150px; height: 35px;"><a>	Comments		</a></th>
									</tr>
								  </thead>
								  <tbody>';
											
					for ($j = 0 ; $j < $rows ; ++$j) {
						$row = $result->fetch_row();
						/*  Convert 0,1 to No,Yes to display as Single Issue  */
						if ($row[3] == 0) {
							$singleissue = "No";
						} else {
							$singleissue = "Yes";
						}
						
						/*  Display repair order fields  */
						echo'  <tr>
								 <td class="submit_td">
								 	<form action="" method="post">
								 	<input type="hidden" name="update" value="yes" />
								 	<input type="hidden" name="updateronumber" value="'.$row[0].'"/>
								 	<input type="submit" value="Select" class= "tiny button radius"/></form>
								 </td> 
								 <td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;">'.$row[0].'</td>
								 <td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;">'.$row[1].'</td>
								 <td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;">'.$row[2].'</td>
								 <td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;">'.$singleissue.'</td>';
								 
						if ($row[4] == NULL) {
							echo'<td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;"> N/A			   	 </td>';
						} else {
							echo'<td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;">', '$' , $row[4], 	'</td>';
						}
						
						if ($row[5] == NULL) {
							echo'<td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;"> N/A			   	 </td>';
						} else {
							echo'<td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;">', '$' , $row[5], 	'</td>';
						}
						
						/*  Now show services within rightmost data slot */
						/*  Add'l svc indicated by *   */
					
						$service = array();
						
						$query2 = "SELECT servicedescription, addsvc FROM servicerendered
								   NATURAL JOIN services
								   WHERE $row[0] = servicerendered.ronumber AND servicerendered.dealerID = $dealerID AND servicerendered.surveyindex_id = $surveyindex_id
								   ORDER By services.servicesort";
								
						$result2 = $mysqli->query($query2);
						if (!$result2) {
							exit("query_error");
						}
					
						//  Build all services in array for this single order
						$rows2 = $result2->num_rows;
						for ($i = 0; $i < $rows2; ++$i)
						{
							$row2 = $result2->fetch_row();
							
							//  For Additional Service convert 0 to null, 1 to * 
							if ($row2[1] == 0) {
								// This is not an additional service
								$addsvc = '';
							} else {
								// This is an additional service
								$addsvc = "*";
							}
							
							$svc = $row2[0].$addsvc;
							//  Place comma only between services, not after last one
							if ($i != ($rows2-1)) {
								// This is not last service so add comma
								$svc = $svc.', ';
							}
							$service[] = $svc;
							
						} // END of Services Rendered loop for this one repair order
					
						$services = "";
						foreach ($service as $s) {
							$services .= $s;
						}
						
						echo'  <td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;">',$services,'</td>
							   <td style="color: #3D3D3D; padding: 4px; height: 60px; border-bottom: 1px solid #CCCCCC;">',$row[6],  '</td>
							 </tr>';
					}	
					echo '</tbody>
						  </table>
						 </div>
					    </div>
					   </div>
					  </div>';
					
					// Check to see if yearmodel_string has a record for $dealerID
					$query = "SELECT dealerID FROM yearmodel_strings WHERE dealerID = $dealerID AND surveyindex_id = $surveyindex_id and userID = 0";
					$result = $mysqli->query($query);
					if (!$result) {
						exit("error_query");
					}
					$rows = $result->num_rows;
					// Insert a default yearmodel_string with userID of 0 (zero) into yearmodel_strings if there is not a record in the table for $dealerID, $surveyindex_id and userID = 0
					if ($rows == 0) { 
						// Find associated yearmodelID in yearmodel table that is between $firstyear and $currentyear, create string and save as variable for INSERT query
						$query = "SELECT yearmodelID FROM yearmodel WHERE modelyear BETWEEN $firstyear AND $currentyear
								  ORDER BY modelyear DESC";
						$result = $mysqli->query($query);
						if (!$result) {
							exit("error_query");
						}
						$rows = $result->num_rows;
						if ($rows == 0) {
							exit("error_query");
						}
						
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
						// echo '$yearmodel_string: ' .$yearmodel_string. '<br>';
						// String has been created; now insert it into yearmodel_strings table
						$query = "INSERT INTO yearmodel_strings (dealerID, yearmodel_string, surveyindex_id, create_date, userID)
								  VALUES ('$dealerID', '$yearmodel_string', '$surveyindex_id', NOW(), 0)";
						$result = $mysqli->query($query);
						if (!$result) {
							exit("error_query");
						}
					}
				} else {
					$_SESSION['error'][] = "Repair order ". $ronumber. " has been added, but services are compromised";
				}
			}
		} else {
			$_SESSION[error][] = "You must select a service";
		}	
	}
} else {
	/*  If RO number is blank display error  */
	$_SESSION['error'][] = "RO number cannot be blank";
}
// die(header("Location: enterrofoundation.php"));

// End of Main Program  

/********************************************************/
/*                        FUNCTIONS                     */
/*-------------------------------------------------------*
    Function to validate services before adding records
	returns: FALSE if not valid, TRUE if valid
 *-------------------------------------------------------*/

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
    Function to add services to servicerendered table
	returns TRUE if successful, FALSE if not.
 *------------------------------------------------------*/

function add_services($mysqli, $ronumber, $singleissue, $dealerID, $surveyindex_id, $manuf_id, $userID)
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

}	/*  End of add_service function */
?>