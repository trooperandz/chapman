<?php
/**
 * File: ClassDealer.php
 * Purpose:  Display and manage dealer data
 * PHP version 5.5.29
 * @author Matthew Holland
 *
 *History:
 *	Date			Description													By
 * 	06/24/16		Revamped original dealer_summary.php (created 2/10/15)		Matt Holland
 *					into this OOP-style page for better functionality and
 *					maintainability
 */

class Dealer {

	protected $mysqli;
	protected $pdo;

	/**
	 * Assigns db connection
	 * @param array 'mysqli' or 'pdo'
	 * @return object db connection
	 */
	public function __construct($mysqli, $pdo) {
		$this->mysqli = $mysqli;
		$this->pdo = $pdo;
	}

	/**
	 * Create page heading for page display
	 * @param array 'page_title', 'side_title'
	 * @return string html for page heading
	 */
	public function getPageHeading($array) {
		$html ='
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<div class="small-12 medium-9 large-9 columns">
					<h2> '.$array['page_title'].' </h2>
				</div>';
			if ($array['side_title']) {
				$html .='
				<div class="small-12 medium-3 large-3 columns">
					<h6 class="subtitle">'.$array['side_title'].'</h6>
				</div>';
			}
			$html .='
			</div>
		</div>';

		return $html;
	}

	/**
	 * Create table of dealer data
	 * @param N/A
	 * @return string html for dealer table
	 */
	public function getDealerTable() {
		$html = '
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<table id="dealer_table" class="tablesorter responsive display">
					<thead>
						<tr>';
						if($_SESSION['admin_user'] == 1) {
							$html .='
							<th><a>	Action			</a></th>';
						}
							$html .='
							<th><a>	'.ENTITY.' Name	</a></th>
							<th><a>	Code			</a></th>
							<th><a>	Address			</a></th>
							<th><a>	City			</a></th>
							<th><a>	State			</a></th>
							<th><a>	Zip				</a></th>
							<th><a>	Phone			</a></th>
							<th><a>	Region			</a></th>
						</tr>
					</thead>
					<tbody>';

		$data = $this->getDealerData(array('dealerID'=>false));

		// Set dealer count as global var for display above dealer table
		$_SESSION['dlr_count'] = count($data);

		for ($i = 0; $i < count($data); $i++) {

					// Display each dealer in table, allowing EDIT form if user is admin user
					echo'<tr>';
					if($_SESSION['admin_user'] == 1) {
							$html .='
							<td>
								<form class="table_form" action="managedealers.php" method="POST" >
									<input type="hidden" name="dealerID" value='.$data[$i]['dealerID'].','.$data[$i]['dealercode'].' />
									<input type="submit" style="margin: 0rem;" value="Select" class="tiny button radius" />
								</form>
							</td>';
					}
							$html .='
							<td>' .$data[$i]['dealername'].    '</td>
							<td>' .$data[$i]['dealercode'].    '</td>
							<td>' .$data[$i]['dealeraddress']. '</td>
							<td>' .$data[$i]['dealercity'].    '</td>
							<td>' .$data[$i]['state_name'].    '</td>
							<td>' .$data[$i]['dealerzip']. 	   '</td>
							<td>' .$data[$i]['dealerphone'].   '</td>
							<td>' .$data[$i]['region']. 	   '</td>
						</tr>';
			}
					$html .='
					</tbody>
				</table>
			</div>
		</div>';
		return $html;
	}

	/**
	 * Create add/edit dealer form
	 * @param array 'dealerID'
	 * @return string html for dealer management form
	 * Notes: If $array['edit_dealer'] == true, set all input values to $array values. Otherwise set to null.
	 */
	public function getDealerForm($array) {
		/* If user has requested to edit a dealer, get dealer info and load form with form values filled in.
		 * Check for input error $_SESSION vars first, in case user already submitted form and there were form errors.
		 * Note: $submit_type is included in form hidden input & dictates UPDATE or INSERT db action via process.inc.php file
		**/
		if(isset($_SESSION['edit_dealername'])) {
			$edit_dealerID  = $_SESSION['edit_dealerID']		;
			$dealername 	= $_SESSION['edit_dealername']		;
			$dealercode 	= $_SESSION['edit_dealercode']		;
			$dealeraddress 	= $_SESSION['edit_dealeraddress']	;
			$dealercity 	= $_SESSION['edit_dealercity']		;
			$state_ID 		= $_SESSION['edit_state_ID']		;
			$state_name 	= $_SESSION['edit_state_name']		;
			$dealerzip 		= $_SESSION['edit_dealerzip']		;
			$dealerphone 	= $_SESSION['edit_dealerphone']		;
			$regionID 		= $_SESSION['edit_regionID']		;
			$region_name 	= $_SESSION['edit_region_name']	    ;
			$area_ID 		= $_SESSION['edit_area_ID']			;
			$area_name 		= $_SESSION['edit_area_name']	    ;
			$district_ID 	= $_SESSION['edit_district_ID']		;
			$district_name 	= $_SESSION['edit_district_name']   ;
			$submit_type    = ($_SESSION['submit_type'] == 'add_dealer') ? 'add_dealer' : 'edit_dealer';
			$submit_value   = 'Save Changes';
		} elseif($array['dealerID']) {
			$array = $this->getDealerData(array('dealerID'=>$array['dealerID']));
			$edit_dealerID  = $array[0]['dealerID']		;
			$dealername 	= $array[0]['dealername']	;
			$dealercode 	= $array[0]['dealercode']	;
			// Set global var for ensuring user does not try to enter a duplicate dealer code
			$_SESSION['orig_dealercode'] = $dealercode  ;
			$dealeraddress 	= $array[0]['dealeraddress'];
			$dealercity 	= $array[0]['dealercity']	;
			$state_ID 		= $array[0]['state_ID']		;
			$state_name 	= $array[0]['state_name']	;
			$dealerzip 		= $array[0]['dealerzip']	;
			$dealerphone 	= $array[0]['dealerphone']	;
			$regionID 		= $array[0]['regionID']		;
			$region_name 	= $array[0]['region']	    ;
			$area_ID 		= $array[0]['area_ID']		;
			$area_name 		= $array[0]['area']	        ;
			$district_ID 	= $array[0]['district_ID']	;
			$district_name 	= $array[0]['district']     ;
			$submit_type    = 'edit_dealer';
			$submit_value   = 'Save Changes';
		} else {
			$edit_dealerID  = null;
			$dealername 	= null;
		    $dealercode 	= null;
		    $dealeraddress 	= null;
		    $dealercity 	= null;
		    $state_ID 		= null;
		    $state_name 	= 'Select...';
		    $dealerzip 		= null;
		    $dealerphone 	= null;
		    $regionID 		= null;
		    $region_name 	= 'Select...';
		    $area_ID 		= null;
		    $area_name 		= 'Select...';
		    $district_ID 	= null;
		    $district_name 	= 'Select...';
		    $submit_type    = 'add_dealer';
		    $_SESSION['submit_type'] = 'add_dealer';
		    $submit_value   = 'Register '.ENTITY.' &raquo';
		}
		$html ='
		<form method="post" action="system/utils/process.inc.php" class="dealer_form">
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' Name
							<input type="text" value="'.$dealername.'" id="dealername" name="dealername" autofocus>
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' Code
							<input type="text" value="'.$dealercode.'" id="dealercode" name="dealercode">
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' Address
							<input type="text" value="'.$dealeraddress.'" id="dealeraddress" name="dealeraddress">
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' City
							<input type="text" value="'.$dealercity.'" id="dealercity" name="dealercity">
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' State
							<select id="state" name="state">
								<option value="'.$state_ID.','.$state_name.'">'.$state_name.'</option>';
								$data = $this->getStateData();
								for ($i=0; $i<count($data); $i++) {
									$html .='<option value="'.$data[$i]['state_ID'].', '.$data[$i]['state_name'].'">'.$data[$i]['state_name'].'</option>';
								}
							$html .='
							</select>
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' Zip Code
							<input type="text" value="'.$dealerzip.'" id="dealerzip" name="dealerzip">
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>Phone Number
							<input type="text" value="'.$dealerphone.'" id="dealerphone" name="dealerphone">
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' Region
							<select id="region" name="region">
								<option value="'.$regionID.','.$region_name.'">'.$region_name.'</option>';
								$data = $this->getRegionData();
								for ($i=0; $i<count($data); $i++) {
									$html .='<option value="'.$data[$i]['regionID'].', '.$data[$i]['region'].'">'.$data[$i]['region'].'</option>';
								}
							$html .='
							</select>
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' District
							<select id="district" name="district">
								<option value="'.$district_ID.','.$district_name.'">'.$district_name.'</option>';
								$data = $this->getDistrictData();
								for ($i=0; $i<count($data); $i++) {
									$html .='<option value="'.$data[$i]['district_ID'].', '.$data[$i]['district'].'">'.$data[$i]['district'].'</option>';
								}
							$html .='
							</select>
						</label>
					</div>
					<div class="small-12 medium-6 large-4 columns">
						<label>'.ENTITY.' Area
							<select id="area" name="area">
								<option value="'.$area_ID.','.$area_name.'">'.$area_name.'</option>';
								$data = $this->getAreaData();
								for ($i=0; $i<count($data); $i++) {
									$html .='<option value="'.$data[$i]['area_ID'].', '.$data[$i]['area'].'">'.$data[$i]['area'].'</option>';
								}
							$html .='
							</select>
						</label>
					</div>
					<div class="small-12 medium-12 large-12 columns">
						<input type="hidden" id="action" name="action" value="'.$submit_type.'" />
						<input type="hidden" id="edit_dealerID" name="edit_dealerID" value="'.$edit_dealerID.'" />
						<input type="submit" id="submit" name="submit" value="'.$submit_value.'" class="tiny button radius">
					</div>
				</div>
			</div>
		</form>

		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<hr>
			</div>
		</div>';
		return $html;
	}

	/**
	 * Generate dealer data for dealer table or dealer edit form
	 * @param array 'dealerID'
	 * @return array array of dealer data
	 * Notes:
	 *		If $array['dealerID'] is passed, include SQL WHERE clause and return dealer-specific data
	 * 		Otherwise return a list of all dealers in db
	 */
	public function getDealerData($array) {
		$stmt = "SELECT a.dealerID, a.dealername, a.dealercode, a.dealeraddress, a.dealercity,
					  	b.state_ID, b.state_name, b.state_abbrev, a.dealerzip, a.dealerphone,
					  	c.regionID, c.region, d.area_ID, d.area, e.district_ID, e.district
				  FROM  dealer a
				  LEFT JOIN us_state_list b   ON(a.state_ID = b.state_ID)
				  LEFT JOIN dealerregion c 	  ON(a.regionID = c.regionID)
				  LEFT JOIN dealer_area d 	  ON(a.area_ID = d.area_ID)
				  LEFT JOIN dealer_district e ON(a.district_ID = e.district_ID) ";

		// If dealerID was passed as param, include WHERE clause
		if($array['dealerID']) {
			$dealerID = $array['dealerID'];
			$stmt .= " WHERE a.dealerID = :dealerID ";
		}

		$stmt .= " ORDER BY a.dealername ASC ";

		try {
			$stmt = $this->pdo->prepare($stmt);

			if($array['dealerID']) {
				$stmt->bindParam(':dealerID', $dealerID);
			}

			$stmt->execute();
			$array = $stmt->fetchAll();
			$execute = true;
		} catch (PDOException $e) {
			$_SESSION['error'][] = "*Error!  We are sorry, but a processing error has occurred.  Please contact the administrator.";
			SysFeedback::emailError(__LINE__, __FILE__, $e);
			$execute = false;
		}

		return ($execute) ? $array : $execute;
	}

	/**
	 * Receive dealer form input, validate input & run relevant db action based on hidden input name="submit_type"
	 * @param N/A
	 * @return N/A
	 */
	public function processDealerEntry() {
		//echo 'POST[edit_dealerID]: '.$_POST['edit_dealerID'].'<br>'; exit;
		// Receive form inputs
		$submit_type	= $_POST['action']  	 			;
		$edit_dealerID  = $_POST['edit_dealerID']			;
		$dealername 	= $_POST['dealername']	 			;
		$dealercode 	= $_POST['dealercode']	 			;
		// Set global var for feedback messages
		$_SESSION['process_dealercode'] = $dealercode		;
		$dealeraddress 	= $_POST['dealeraddress']			;
		$dealercity 	= $_POST['dealercity']	 			;
		$state 			= explode(",", $_POST['state'])		;
		$state_ID		= $state[0]				 			;
		$state_name		= $state[1]							;
		$dealerzip		= $_POST['dealerzip']	 			;
		$dealerphone	= $_POST['dealerphone']  			;
		$region			= explode(",", $_POST['region'])	;
		$regionID		= $region[0]		  	 			;
		$region			= $region[1]						;
		$area			= explode(",", $_POST['area'])		;
		$area_ID		= $area[0]			     			;
		$area			= $area[1]							;
		$district		= explode(",", $_POST['district'])	;
		$district_ID	= $district[0]			  			;
		$district		= $district[1]						;

		// Set input SESSION vars in case of form submit error
		$_SESSION['edit_dealerID']			= $edit_dealerID;
		$_SESSION['edit_dealername']		= $dealername	;
		$_SESSION['edit_dealercode']		= $dealercode	;
		$_SESSION['edit_dealeraddress']		= $dealeraddress;
		$_SESSION['edit_dealercity']		= $dealercity	;
		$_SESSION['edit_state_ID']			= $state_ID		;
		$_SESSION['edit_state_name'] 		= $state_name	;
		$_SESSION['edit_dealerzip']			= $dealerzip	;
		$_SESSION['edit_dealerphone']		= $dealerphone  ;
		$_SESSION['edit_regionID']			= $regionID		;
		$_SESSION['edit_region_name']		= $region		;
		$_SESSION['edit_area_ID']			= $area_ID	    ;
		$_SESSION['edit_area_name'] 		= $area			;
		$_SESSION['edit_district_ID']		= $district_ID	;
		$_SESSION['edit_district_name']  	= $district		;

		// Initialize empty array for errors
		$_SESSION['error'] = array();

		// Validate form inputs
		if($dealername == '') {
			$_SESSION['error'][] = '*Please enter a dealer name!';
		}
		if($dealercode == '') {
			$_SESSION['error'][] = '*Please enter a dealer code!';
		}
		if($dealeraddress == '') {
			$_SESSION['error'][] = '*Please enter a dealer address!';
		}
		if($dealercity == '') {
			$_SESSION['error'][] = '*Please enter a deaer city!';
		}
		if($state_ID == '') {
			$_SESSION['error'][] = '*Please select a dealer state!';
		}
		if($dealerzip == '') {
			$_SESSION['error'][] = '*Please enter a dealer zip code!';
		}
		if($dealerphone == '') {
			$_SESSION['error'][] = '*Please enter a dealer phone number!';
		}
		if($regionID == '') {
			$_SESSION['error'][] = '*Please enter a dealer region!';
		}
		if($area_ID == '') {
			$_SESSION['error'][] = '*Please enter a dealer area!';
		}
		if($district_ID == '') {
			$_SESSION['error'][] = '*Please enter a dealer district!';
		}

		/* If no errors, proceed with db action based on $submit_type.
		 * Check for dealer dupe for both actions if dealercode was changed.
		 * Return true if db action successful, false otherwise
		**/
		if(!$_SESSION['error']) {
			if($submit_type == 'add_dealer') {
				if($this->checkDealercodeDupe(array('dealercode'=>$dealercode))) {
					// Note: $_SESSION['add_dealer_error'] is used for correct re-entry to add dealer form
					$_SESSION['add_dealer_error'] = true;
					$_SESSION['error'][] = "*Warning: The dealer code you tried to enter already exists.  Please select another!";
					return false;
				}
				// Note: dealerID is not needed for INSERT function
				if(!$this->insertDealer(array('state_ID'=>$state_ID, 'regionID'=>$regionID, 'area_ID'=>$area_ID, 'district_ID'=>$district_ID))) {
					$_SESSION['error'][] = "*Error: The dealer was not added. There was an error with the insert function. Please contact the administrator.";
					return false;
				} else {
					// Make sure to unset SESSION['submit_type'] so that form error rebuild inserts correct form action type
					if(isset($_SESSION['submit_type'])) { unset($_SESSION['submit_type']); }
					return true;
				}
			} elseif ($submit_type == 'edit_dealer') {
				//echo 'orig_dealercode: '.$_SESSION['orig_dealercode'].'<br>';
				if($dealercode != $_SESSION['orig_dealercode']) {
					if($this->checkDealercodeDupe(array('dealercode'=>$dealercode))) {
						// Note: $_SESSION['edit_dealer_error'] is used for correct re-entry to edit dealer form
						$_SESSION['edit_dealer_error'] = true;
						$_SESSION['error'][] = "*Warning: The dealer code you tried to enter already exists.  Please select another!";
						return false;
					}
				}
				// Do not forget to pass dealerID for UPDATE instruction. Also pass state_ID, regionID, area_ID and district_ID since orig $_POST vars are arrays
				if(!$this->updateDealer(array('dealerID'=>$_SESSION['edit_dealerID'], 'state_ID'=>$state_ID, 'regionID'=>$regionID, 'area_ID'=>$area_ID, 'district_ID'=>$district_ID)))  {
					$_SESSION['error'][] = "*Error: The dealer was not updated.  There was an error with the update function.  Please contact the administrator.";
					return false;
				} else {
					return true;
				}
			}
		// If there were form errors, set SESSION vars so that program enters correct block upon re-entry
		} else {
			if($submit_type == 'add_dealer') {
				$_SESSION['add_dealer_error'] = true;
			}
			if($submit_type == 'edit_dealer') {
				$_SESSION['edit_dealer_error'] = true;
			}
			return false;
		}
	}

	/**
	 * Check for duplicate dealercode when user tries to update a dealer record. Return TRUE if duplicate found.
	 * @param array 'dealercode'
	 * @return boolean true if dupe found, false if dupe not found
	 */
	public function checkDealercodeDupe($array) {
		$stmt = "SELECT dealercode FROM dealer WHERE dealercode = :dealercode"/*.$array['dealercode']*/;
		try {
			$stmt = $this->pdo->prepare($stmt);
			$stmt->bindParam(':dealercode', $array['dealercode'], PDO::PARAM_STR);
			$stmt->execute();
			$rows = $stmt->rowCount();
			$result = ($rows > 0 ) ? true : false;
		} catch(PDOException $e) {
			$_SESSION['error'][] = "*Error!  We are sorry, but a processing error has occurred.  Please contact the administrator.";
			SysFeedback::emailError(__LINE__, __FILE__, $e);
			$result = false;
		}
		return $result;
	}

	/**
	 * UPDATE dealer record if user submitted the edit dealer form
	 * @param array 'dealerID', 'state_ID', 'district_ID', 'area_ID', 'regionID'
	 * @return boolean true if UPDATE successful, false if UPDATE failed
	 */
	public function updateDealer($array) {

		$stmt = "UPDATE dealer SET dealercode = ?, dealername = ?, dealeraddress = ?, dealercity = ?, state_ID = ?, dealerzip = ?,
					    dealerphone = ?, district_ID = ?, area_ID = ?, regionID = ?, updated_by = ?, update_date = ?
				 WHERE  dealerID = ?";

		$params = array($_POST['dealercode'], $_POST['dealername'], $_POST['dealeraddress'], $_POST['dealercity'], $array['state_ID'], $_POST['dealerzip'], $_POST['dealerphone'], $array['district_ID'], $array['area_ID'], $array['regionID'], $_SESSION['userID'], date('Y-m-d h:i:s'), $array['dealerID']);

		try {
			$stmt = $this->pdo->prepare($stmt);
			$stmt->execute($params);
			$result = true;
		} catch (PDOException $e) {
			$_SESSION['error'][] = "*Error!  We are sorry, but a processing error has occurred.  Please contact the administrator.";
			SysFeedback::emailError(__LINE__, __FILE__, $e);
			$result = false;
		}
		return $result;
	}

	/**
	 * INSERT new dealer record if user submitted the add dealer form
	 * @param array 'state_ID', 'district_ID', 'area_ID', 'regionID'
	 * @return boolean true if INSERT successful, false if INSERT failed
	 */
	public function insertDealer($array) {
		$stmt = "INSERT INTO dealer (dealercode, dealername, dealeraddress, dealercity, state_ID, dealerzip, dealerphone, district_ID,
				 area_ID, regionID, userID, create_date)
				 VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
		$params = array($_POST['dealercode'], $_POST['dealername'], $_POST['dealeraddress'], $_POST['dealercity'], $array['state_ID'], $_POST['dealerzip'], $_POST['dealerphone'], $array['district_ID'], $array['area_ID'], $array['regionID'], $_SESSION['userID'], date('Y-m-d h:i:s'));

		try {
			$stmt = $this->pdo->prepare($stmt);
			$stmt->execute($params);
			$rows = $stmt->rowCount();
			$result = ($rows > 0) ? true : false;
		} catch (PDOException $e) {
			$_SESSION['error'][] = "*Error!  We are sorry, but a processing error has occurred.  Please contact the administrator.";
			SysFeedback::emailError(__LINE__, __FILE__, $e);
			$result = false;
		}
		return $result;
	}

	/**
	 * Unset dealer global vars for edit/add dealer form on managedealers page
	 * @param N/A
	 * @return N/A
	 */
	public function unsetEditDealerGlobals() {
		// Unset sticky form elements upon page reload
		unset(
			$_SESSION['edit_dealer_error']	,
			$_SESSION['add_dealer_error']	,
			$_SESSION['edit_dealerID']		,
			$_SESSION['edit_dealername']	,
			$_SESSION['edit_dealercode']	,
			$_SESSION['edit_dealeraddress']	,
			$_SESSION['edit_dealercity']	,
			$_SESSION['edit_state_ID']		,
			$_SESSION['edit_state_name']	,
			$_SESSION['edit_dealerzip']		,
			$_SESSION['edit_dealerphone']	,
			$_SESSION['edit_regionID']		,
			$_SESSION['edit_region_name']	,
			$_SESSION['edit_district_ID']	,
			$_SESSION['edit_district_name']	,
			$_SESSION['edit_area_ID']		,
			$_SESSION['edit_area_name']		,
			$_SESSION['process_dealercode']
		);
	}

	/**
	 * Get state data from us_state_list table
	 * @param N/A
	 * @return array array of state data
	 */
	public function getStateData() {
		if(!isset($_SESSION['state_data'])) {
			$stmt = "SELECT state_ID, state_name, state_abbrev FROM us_state_list ORDER BY state_name ASC";

			try {
				$stmt = $this->pdo->prepare($stmt);
				$stmt->execute();
				$array = $stmt->fetchAll();
				$_SESSION['state_data'] = $array;
			} catch (PDOException $e) {
				$_SESSION['error'][] = "*Error!  We are sorry, but a processing error has occurred.  Please contact the administrator.";
				SysFeedback::emailError(__LINE__, __FILE__, $e);
				return false;
			}
		}
		return $_SESSION['state_data'];
	}

	/**
	 * Get region data from dealerregion table
	 * @param N/A
	 * @return array array of region data
	 */
	public function getRegionData() {
		unset($_SESSION['region_data']);
		if(!isset($_SESSION['region_data'])) {
			$stmt = "SELECT regionID, region from dealerregion ORDER BY region ASC";

			try {
				$stmt = $this->pdo->prepare($stmt);
				$stmt->execute();
				$array = $stmt->fetchAll();
				$_SESSION['region_data'] = $array;
			} catch (PDOException $e) {
				$_SESSION['error'][] = "*Error!  We are sorry, but a processing error has occurred.  Please contact the administrator.";
				SysFeedback::emailError(__LINE__, __FILE__, $e);
				return false;
			}
		}
		return $_SESSION['region_data'];
	}

	/**
	 * Get district data from dealer_district table
	 * @param N/A
	 * @return array array of district data
	 */
	public function getDistrictData() {
		unset($_SESSION['district_data']);
		if(!isset($_SESSION['district_data'])) {
			$stmt = "SELECT district_ID, district FROM dealer_district ORDER BY district ASC";

			try {
				$stmt = $this->pdo->prepare($stmt);
				$stmt->execute();
				$array = $stmt->fetchAll();
				$_SESSION['district_data'] = $array;
			} catch (PDOException $e) {
				$_SESSION['error'][] = "*Error!  We are sorry, but a processing error has occurred.  Please contact the administrator.";
				SysFeedback::emailError(__LINE__, __FILE__, $e);
				return false;
			}
		}
		return $_SESSION['district_data'];
	}

	/**
	 * Get area data from dealer_area table
	 * @param N/A
	 * @return array array of area data
	 */
	public function getAreaData() {
		if(!isset($_SESSION['area_data'])) {
			$stmt = "SELECT area_ID, area FROM dealer_area ORDER BY area ASC";

			try {
				$stmt = $this->pdo->prepare($stmt);
				$stmt->execute();
				$array = $stmt->fetchAll();
				$_SESSION['area_data'] = $array;
			} catch (PDOException $e) {
				$_SESSION['error'][] = "*Error!  We are sorry, but a processing error has occurred.  Please contact the administrator.";
				SysFeedback::emailError(__LINE__, __FILE__, $e);
				return false;
			}
		}
		return $_SESSION['area_data'];
	}
} // end class Dealer