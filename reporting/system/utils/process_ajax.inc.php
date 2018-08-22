<?php
/**
 * File: process_ajax.inc.php
 * Purpose: Process ajax calls, use echo to return output
 * Similar to process.inc.php
 * PHP version 5.5.29
 * @author   Matthew Holland
 *
 * History:
 *   Date			Description									by
 *   09/24/2015		Initial design & coding	    				Matt Holland
 *	 02/12/2016		Adapted to new Creative Tim system			Matt Holland
 *   02/12/2016		Reduced complexity by adding updated		Matt Holland
 *					init.inc.php file and removing the
 *					__autoload function (already in init file)
 */

// Include config file
include_once '../config/init.inc.php';

// Establish classes
$Admin = new Admin($dbo = null);
$Welr = new Welr($dbo = null);
$Metrics = new Metrics($dbo = null);
$Stats = new Stats($dbo = null);
$SurveysSummary = new SurveysSummary($dbo = null);
$DealerInfo = new DealerInfo($dbo = null);
$Documents = new Documents($dbo = null);
$ContactUs = new ContactUs($dbo = null);

/* This is for testing.  Try to put non-session AJAX processes here, before the verifyUserLoginAjax() code */
if(isset($_POST['no_session'])) {
	if (isset($_POST['action'])) {
		if($_POST['action'] == 'forgot_pass_link') {
      $successArr = array(
        'welcome_msg'=>'Please Enter Your Email Address Below:',
        'forgot_pass_link'=>true,
        'a_id'=>'return_loginform_link'
      );

      $errorArr = array(
        'welcome_msg'=>'Please Enter Your Login Details Below:',
        'get_login_form'=>true
      );

      $errorHtml = '<p> There was an error processing your request. Please see the administrator. </p>';

			// If there was no form load error, update content with new form.
      // Else return original login form with error msg at bottom of form
			if($form = $Admin->getLoginForm($successArr)) {
				echo $form;
			} else {
				echo $Admin->getLoginForm($errorArr).$errorHtml;
			}
		}

		if($_POST['action'] == 'get_login_form') {
      $loginArr = array(
        'welcome_msg' => 'Please Enter Your Login Details Below:',
        'a_id' => 'forgot_pass_link',
        'get_login_form' => true
      );

			echo $Admin->getLoginForm($loginArr);
		}

		if($_POST['action'] == 'send_reset_link') {
      $emailArr = array('user_email' => trim($_POST['user_email']));

      $resetSuccessArr = array(
        'email_resetlink_success' => true,
        'a_id' => 'return_loginform_link',
        'reset_msg' => 'Thank you. A reset password link has been emailed to: '.$_POST['user_email']
      );

      $resetFailArr = array(
        'email_resetlink_success' => true,
        'a_id' => 'return_loginform_link',
        'reset_msg' => 'There was an error processing your password reset.  Please see the administrator.'
      );

			// Trim email whitespace to ensure no spaces cause error. If true, return success msg
			if($Admin->emailPassResetLink($emailArr)) {
				echo $Admin->getLoginForm($resetSuccessArr);
			} else {
				echo $Admin->getLoginForm($resetFailArr);
			}
		}

		if($_POST['action'] == 'reset_user_pass') {
			/* If reset password is successful, display success message with 'Return to Login Form' link.
			 * Make sure that Url $_GET['user'] value is removed so that correct form is displayed
			 * Else show error.
			**/
      $postArr = array(
        'pass1'=>$_POST['pass1'],
        'pass2'=>$_POST['pass2'],
        'user_email'=>$_POST['user_email'],
        'hash'=>$_SESSION['hash']
      );

      $successArr = array(
        'email_resetlink_success'=>true,
        'reset_msg'=>'Your password has been successfully reset. A confirmation has been emailed to: '.$_POST['user_email']
      );

      $errorArr = array(
        'email_resetlink_success'=>true,
        'reset_msg'=>'There was an error processing your request.<br>  Please try again or contact the administrator if the problem persists.'
      );

			echo 'hash: '.$_SESSION['hash'];
			if($Admin->validateResetPassData($postArr)) {
				unset($_SESSION['hash']);
				echo $Admin->getLoginForm($successArr);
			} else {
				echo $Admin->getLoginForm($errorArr);
			}
		}
	}
// Make sure that program exists before any further code could be executed accidentally
exit;
}

/* Make sure that user is logged in before any actions occur. If not, return 'error_login'
 * This is needed not only for security, but also for user feedback.  If a user clicks a link to
 * run a process, and they are not logged in, they need to be shown the 'error_login' message so
 * that they know they need to log in again.
**/
if (verifyUserLoginAjax()) {
  // Make sure the requested action exists in the lookup array
  if (isset($_POST['action'])) {
    echo 'action: ', $_POST['action'];
    switch($_POST['action']) {
      case 'ro_entry':
        echo processRoEntry();
        break;
      case 'update_ro_form':
        echo renderRoEditForm();
        break;
      case 'view_ros_month':
        echo viewRosByMonth();
        break;
      case 'view_ros_all':
        echo viewAllRos();
        break;
      case 'enter_ros':
        echo renderRoEnteryForm();
        break;
      case 'ro_search':
        echo getRoSearchResults();
        break;
      case 'metrics_search':
        echo getMetricsSearchResults();
        break;
      case 'metrics_dlr_comp':
        echo getMetricsDealerComparison();
        break;
      case 'view_metrics_all':
        echo getDealerMetricsAllHistory();
        break;
      case 'view_metrics_month':
        echo getDealerMetricsCurrentMonth();
        break;
      case 'dealer_summary_select':
        echo getDealerMetricsCurrentMonth();
        break;
      case 'change_dealer_globals':
        echo getDealerMetricsCurrentMonth();
        break;
      case 'metrics_trend':
        echo getMetricsTrendReport();
        break;
      case 'view_stats_month':
        echo getDealerStatsCurrentMonth();
        break;
      case 'view_stats_all':
        echo getDealerStatsAllHistory();
        break;
      case 'stats_search':
        echo getDealerStatsSearchResults();
        break;
      case 'dealer_summary':
        echo getAllDealersSummaryResults();
        break;
      case 'view_dealer_list_all':
        echo getDealerListingView();
        break;
      case 'get_dealer_add_form':
        echo getAddDealerForm();
        break;
      case 'add_dealer_row':
        echo getAddNewDealerRow();
        break;
      case 'add_dealers':
        echo addNewDealers();
        break;
      case 'get_user_request_form':
        echo getUserRequestForm();
        break;
      case 'add_user_req_row':
        echo addUserRequestRow();
        break;
      case 'add_new_user_table': // left off here
        echo getAddNewUserTable();
        break;
      case 'add_new_user_row':
        echo getAddNewUserRow();
        break;
      case 'add_new_users':
        echo addNewUsers();
        break;
      case 'check_username_dupe':
        echo checkUsernameDupe();
        break;
      case 'get_dealer_info_js':
        echo getDealerInfoJSON();
        break;
      case 'process_user_setup_request':
        echo processUserSetupRequest();
        break;
      case 'view_user_setup_requests':
        echo viewUserSetupRequests();
        break;
      case 'approve_user_setup_requests':
        echo approveUserSetupRequests();
        break;
      case 'view_dealer_users':
        echo viewDealerUsers();
        break;
      case 'view_sos_users':
        echo viewSosUsers();
        break;
      case 'view_manuf_users':
        echo viewManufacturerUsers();
        break;
      case 'table_user_edit_select':
        echo getEditUserView();
        break;
      case 'table_dealer_edit_select':
        echo getEditDealerView();
        break;
      case 'add_doc_link':
        echo getAddDocumentForm();
        break;
      case 'file_submit':
        echo processFileUpload();
        break;
      case 'view_doc_link': // ALL system docs
        echo viewAllDocuments();
        break;
      case 'view_doc_table': // Specific doc types (release forms, sys guides, my docs)
        echo viewSpecificDocumentType();
        break;
      case 'delete_doc':
        echo deleteDocument();
        break;
      case 'edit_doc_form':
        echo getEditDocumentForm();
        break;
      case 'file_update_submit':
        echo processEditDocumentSubmit();
        break;
      case 'contact_us_link':
        echo getContactForm();
        break;
      case 'contact_us_submit':
        echo processContactFormSubmit();
        break;
      case 'change_advisor':
        echo processAdvisorSelect();
        break;
      // case 'table_doc_select': /* This was removed to non-ajax form POST action
      //   echo viewPdfDocument();
      //   break;
      default:
        echo 'Sorry, there was an error processing the $_POST["action"] action.  Please see the system administrator.';
    }
  }
} else {
  echo 'error_login';
}

function processRoEntry() {
  $Welr = new Welr($dbo = null);

  // Prevent undefined index notices from service arrays
  $svc_reg = (isset($_POST['svc_reg'])) ? $_POST['svc_reg'] : null;
  $svc_add = (isset($_POST['svc_add'])) ? $_POST['svc_add'] : null;
  $svc_dec = (isset($_POST['svc_dec'])) ? $_POST['svc_dec'] : null;

  $postArr = array(
    'submit_name'=>$_POST['submit_name'],
    'ronumber'=>$_POST['ronumber'],
    'ro_date'=>$_POST['ro_date'],
    'yearmodel'=>$_POST['yearmodel'],
    'mileage'=>$_POST['mileage'],
    'vehicle'=>$_POST['vehicle'],
    'labor'=>$_POST['labor'],
    'parts'=>$_POST['parts'],
    'dealer_id'=>$_SESSION['dealer_id'],
    'comment'=>$_POST['comment'],
    'svc_reg'=>$svc_reg,
    'svc_add'=>$svc_add,
    'svc_dec'=>$svc_dec,
    'svc_hidden'=>$_POST['svc_hidden'],
    'search_params'=>false
  );

  return $Welr->processRoEntry($postArr);
}

function renderRoEnteryForm() {
  $Welr = new Welr($dbo = null);
  $returnHtml = '';

  $pageArr = array(
    'page_title'=>'Enter Repair Orders',
    'ro_count'=>true,
    'entry_form'=>true,
    'update_form'=>false,
    'dealer_id'=>$_SESSION['dealer_id'],
    'dealer_code'=>$_SESSION['dealer_code'],
    'print-icon'=>false,
    'export-icon'=>false
  );

  $paramsArr = array(
    'entry_form' => true,
    'date_range' => false,
    'search_params' => false
  );

  $returnHtml .= $Welr->getPageHeading($pageArr);

  // Only show the 'Select Advisor' dropdown if user is SOS or Dealer admin type
  if ($_SESSION['user']['user_type_id'] == 1
    || ($_SESSION['user']['user_type_id'] == 3 && $_SESSION['user']['user_admin'] == 1)
  ) {
    $returnHtml .= $Welr->getAdvisorDropdown();
  }

  $returnHtml .= $Welr->getRoEntryForm($update_form = false, $update_ro_id = null, $search_params = false)
    .$Welr->getRoEntryTable($paramArr);

  return $returnHtml;
}

function renderRoEditForm() {
  $Welr = new Welr($dbo = null);

  // Set $_SESSION['update_ronumber'] for future checking of ro update form. Do not forget to unset later
  $_SESSION['update_ronumber'] = $_POST['ro_number'];

  // Set $_SESSION['update_ro_id'] for updating of repairorder_welr record. Do not forget to unset later
  $_SESSION['update_ro_id'] = $_POST['ro_id'];

  $pageArr = array(
    'page_title'=>'Update Order',
    'ro_count'=>true,
    'entry_form'=>true,
    'update_form'=>true,
    'dealer_id'=>$_SESSION['dealer_id'],
    'dealer_code'=>$_SESSION['dealer_code'],
    'print-icon'=>false,
    'export-icon'=>false
  );

  $paramArr = array(
    'entry_form' => true,
    'date_range' => false,
    'search_params' => false
  );

  return $Welr->getPageHeading($pageArr)
    .$Welr->getRoEntryForm($update_form = true, $update_ro_id = $_POST['ro_id'], $search_params = false)
    .$Welr->getRoEntryTable($paramArr);
}

function viewRosByMonth() {
  $Welr = new Welr($dbo = null);

  // Set dates to current month to date
  $_SESSION['ro_date_range1'] = date("Y-m-01");
  $_SESSION['ro_date_range2'] = date("Y-m-d");

  $date1 = date("m/d/y", strtotime(date("Y-m-01")));
  $date2 = date("m/d/y", strtotime(date("Y-m-d")));

  $pageArr = array('page_title'=>'Repair Order Listing ('.$date1.' - '.$date2.')', 'ro_count'=>true, 'entry_form'=>false,
           'update_form'=>false, 'dealer_id'=>$_SESSION['dealer_id'], 'dealer_code'=>$_SESSION['dealer_code'],
           'date_range'=>true, 'print-icon'=>true, 'export-icon'=>true);

  $paramsArr = array(
    'entry_form' => false,
    'date_range' => true,
    'search_params' => false
  );

  return $Welr->getPageHeading($pageArr).$Welr->getRoEntryTable($paramsArr);
}

function viewAllRos() {
  $Welr = new Welr($dbo = null);

  $pageArr = array(
    'page_title'=>'Repair Order Listing (All History)',
    'ro_count'=>true,
    'entry_form'=>false,
    'update_form'=>false,
    'dealer_id'=>$_SESSION['dealer_id'],
    'dealer_code'=>$_SESSION['dealer_code'],
    'date_range'=>false,
    'search_params'=>false,
    'print-icon'=>true,
    'export-icon'=>true
  );

  $paramsArr = array(
    'entry_form' => false,
    'date_range' => false,
    'search_params' => false
  );

  return $Welr->getPageHeading($pageArr).$Welr->getRoEntryTable($paramsArr);
}

function getRoSearchResults() {
  $Welr = new Welr($dbo = null);

  $pageArr = array(
    'page_title'=>'Repair Order Search Results',
    'ro_count'=>false,
    'entry_form'=>false,
    'update_form'=>false,
    'dealer_id'=>$_SESSION['dealer_id'],
    'dealer_code'=>$_SESSION['dealer_code'],
    'print-icon'=>true,
    'export-icon'=>true
  );

  $postArr = array(
    'entry_form' => false,
    'date_range' => false,
    'search_params' => array(
      'ro_params'=>$_POST['ro_params'],
      'svc_reg'=>$_POST['svc_reg'],
      'svc_add'=>$_POST['svc_add'],
      'svc_dec'=>$_POST['svc_dec'],
      'svc_exclude'=>$_POST['svc_exclude']
    )
  );

  return $Welr->getPageHeading($pageArr).$Welr->getRoEntryTable($postArr);
}

/**
* Possible metrics search options include the following:
* dealer, dealer group, area, region, district, all dealers
* Leave these out of the original array, and then use foreach to add search items to array
*/
function getMetricsSearchResults() {
  $Metrics = new Metrics($dbo = null);

  $metrics_params = json_decode($_POST['metrics_params'], true);

  $array = array(
    'page_title'=>'View Metrics - ',
    'title_info'=>'Filtered Results',
    'ro_count'=>true,
    'metrics_table'=>'L1',
    'metrics_month'=>false,
    'metrics_search'=>true,
    'print-icon'=>true,
    'export-icon'=>true,
    'a_id'=>false
  );

  // Now add $metrics_params to $array for submission to class methods
  foreach($metrics_params as $key=>$value) {
    $array[$key] = $value;
  }

  // If dates were entered as search params, set $array['date_range'] = true, create sql-compatible format and add to $array for passing to methods
  if($array['date1_pres']) {
    $array['date_range'] = true;
    $date = new DateTime($array['date1_pres']);
    $array['date1_sql'] = $date->format("Y-m-d");
    $date = new DateTime($array['date2_pres']);
    $array['date2_sql'] = $date->format("Y-m-d");
  }

  // If only dates and/or date fields were entered, add 'Dealer: Name + Code' to search_feedback string so user knows which dealer the info pertains to
  if (($array['date1_pres'] &&  $array['date2_pres'] &&  $array['advisor_id'])
    || ( $array['date1_pres'] &&  $array['date2_pres'] && !$array['advisor_id'])
    || (!$array['date1_pres'] && !$array['date2_pres'] &&  $array['advisor_id'])
  ) {
    if (!$array['region_id']
      && !$array['area_id']
      && !$array['district_id']
      && !$array['dealer_group']
    ) {
      $array['search_feedback'] .= 'Dealer: '.$_SESSION['dealer_name'].' ('.$_SESSION['dealer_code'].')';
    }
  }

  // Create $array copy and change value of 'metrics_table' so that correct L2_3 table data is generated
  $array2 = array();
  foreach($array as $key=>$value) {
    if($key == 'metrics_table') {
      $array2[$key] = 'L2_3';
    } else {
      $array2[$key] = $value;
    }
  }

  /** If dates only were selected (and no region, district, etc):
   * Pass 'dealer_id' param SESSION var as default UNLESS
   * 'View All Dealers' has been checked
   */
  if(!$array['region_id']
    && !$array['area_id']
    && !$array['district_id']
    && !$array['dealer_group']
  ) {
    if (!$array['all_dealers_checkbox']) {
      $array['dealer_id'] = $_SESSION['dealer_id'];
    }
  }

  return $Metrics->getPageHeading($array)
    .$Metrics->getMetricsTable($array)
    .$Metrics->getLaborPartsTable($array)
    .$Metrics->getMetricsTable($array2);
}

/**
 * Possible dealer comp filter options include the following:
 * date range, dealer group
 * Leave these out of the original array, and then use foreach to add search items to array
 */
function getMetricsDealerComparison() {
  $Metrics = new Metrics($dbo = null);

  $params = json_decode($_POST['params'], true);

  $array = array(
    'page_title'=>'View Metrics - ',
    'title_info'=>'Dealer Comparison Data',
    'ro_count'=>false,
    'metrics_search'=>true,
    'print-icon'=>true,
    'export-icon'=>true,
    'a_id'=>false
  );

  // Now add $params to $array for submission to class methods
  foreach($params as $key=>$value) {
    $array[$key] = $value;
  }

  // If dates were entered as params, set $array['date_range'] = true, create sql-compatible format and add to $array for passing to methods
  if($array['date1_pres']) {
    $array['date_range'] = true;
    $date = new DateTime($array['date1_pres']);
    $array['date1_sql'] = $date->format("Y-m-d");
    $date = new DateTime($array['date2_pres']);
    $array['date2_sql'] = $date->format("Y-m-d");
  }

  return $Metrics->getPageHeading($array).$Metrics->getMetricsDlrCompTable($array);
}

getDealerMetricsAllHistory() {
  $Metrics = new Metrics($dbo = null);

  $array = array(
    'page_title'=>'View Metrics (All History) - ',
    'title_info'=>$_SESSION['dealer_name'].' ('.$_SESSION['dealer_code'].')',
    'ro_count'=>true,
    'metrics_table'=>'L1',
    'dealer_group'=>false,
    'dealer_id'=>$_SESSION['dealer_id'],
    'date_range'=>false,
    'metrics_month'=>false,
    'metrics_search'=>false,
    'advisor_id'=>false,
    'district_id'=>false,
    'area_id'=>false,
    'region_id'=>false,
    'search_feedback'=> 'Showing: All History',
    'export_feedback'=> array(
      'Dealer: '.$_SESSION['dealer_code'],
      'Showing: All History'
    ),
    'print-icon'=>true,
    'export-icon'=>true,
    'a_id'=>false
  );

  // Create $array copy and change value of 'metrics_table' so that correct L2_3 table data is generated
  $array2 = array();
  foreach($array as $key=>$value) {
    if($key == 'metrics_table') {
      $array2[$key] = 'L2_3';
    } else {
      $array2[$key] = $value;
    }
  }

  return $Metrics->getPageHeading($array)
    .$Metrics->getMetricsTable($array)
    .$Metrics->getLaborPartsTable($array)
    .$Metrics->getMetricsTable($array2);
}

function getDealerMetricsCurrentMonth() {
  $Metrics = new Metrics($dbo = null);

  // Set dates to month to date
  $_SESSION['metrics_month_date1_sql'] = date("Y-m-01");
  $_SESSION['metrics_month_date2_sql'] = date("Y-m-d");
  $_SESSION['metrics_month_date1_pres']= $date1 = date("m-01-y");
  $_SESSION['metrics_month_date2_pres']= $date2 = date("m-d-y");

  // If action was 'dealer_summary_select' or 'change_dealer_globals', change dealer SESSION vars
  if ($_POST['action'] == 'dealer_summary_select' || $_POST['action'] == 'change_dealer_globals') {
    $_SESSION['dealer_id'] = $_POST['dealer_id'];
    $_SESSION['dealer_code'] = $_POST['dealer_code'];
    $_SESSION['dealer_name'] = $_POST['dealer_name'];
  }

  $array = array(
    'page_title'=>'View Metrics (Month To Date) - ',
    'title_info'=>$_SESSION['dealer_name'].' ('.$_SESSION['dealer_code'].')',
    'ro_count'=>true,
    'metrics_table'=>'L1',
    'dealer_group'=>false,
    'dealer_id'=>$_SESSION['dealer_id'],
    'date_range'=>true,
    'metrics_month'=>true,
    'metrics_search'=>false,
    'advisor_id'=>false,
    'district_id'=>false,
    'area_id'=>false,
    'region_id'=>false,
    'search_feedback'=> 'Date Range: '.$date1.' through '.$date2,
    'export_feedback'=> array(
      'Dealer: '.$_SESSION['dealer_code'],
      'Date Range: '.$date1.' through '.$date2
    ),
    'print-icon'=>true,
    'export-icon'=>true,
    'a_id'=>false
  );

  // Create $array copy and change value of 'metrics_table' so that correct L2_3 table data is generated
  $array2 = array();
  foreach($array as $key=>$value) {
    if($key == 'metrics_table') {
      $array2[$key] = 'L2_3';
    } else {
      $array2[$key] = $value;
    }
  }

  return $Metrics->getPageHeading($array)
    .$Metrics->getMetricsTable($array)
    .$Metrics->getLaborPartsTable($array)
    .$Metrics->getMetricsTable($array2);
}

/**
 * Possible search options include the following:
 * dealer group, area, region, district
 * Leave these out of the original array, and then use foreach to add search items to array
 */
function getMetricsTrendReport() {
  $Metrics = new Metrics($dbo = null);

  // Use json_decode to turn JS params into array
  $params = json_decode($_POST['params'], true);

  // Add the page title and make sure that misc $array params are set for success
  $array = array(
    'page_title'=>'View Metrics - ',
    'title_info'=>'Trending By Month',
    'ro_count'=>false,
    'stats_month'=>false,
    'stats_search'=>false,
    'metrics_trends'=>true,
    'print-icon'=>true,
    'export-icon'=>true,
    'a_id'=>false
  );

  // Now add $params to $array for submission to class methods
  foreach($params as $key=>$value) {
    $array[$key] = $value;
  }

  // If dates were entered as search params, set $array['date_range'] = true, create sql-compatible format and add to $array for passing to methods
  if($array['date1_pres']) {
    $array['date_range'] = true;
    $date = new DateTime($array['date1_pres']);
    $array['date1_sql_user'] = $date->format("Y-m-d");
    $date = new DateTime($array['date2_pres']);
    $array['date2_sql_user'] = $date->format("Y-m-d");
  }

  /**
   * If dates only were selected (and no region, district, dealer group etc):
   * Pass 'dealer_id' param SESSION var as default UNLESS
   * 'View All Dealers' has been checked
   */
  if (!$array['region_id']
    && !$array['area_id']
    && !$array['district_id']
    && !$array['dealer_group']
  ) {
    if (!$array['all_dealers_checkbox']) {
      $array['dealer_id'] = $_SESSION['dealer_id'];
      // Push dealer info string to search feedback message
      $array['search_feedback'] .= "Dealer = ".$_SESSION['dealer_name']." (".$_SESSION['dealer_code'].") | ";
    }
  }

  return $Metrics->getPageHeading($array).$Metrics->getMetricsTrendTable($array);
}

function getDealerStatsCurrentMonth() {
  $Stats = new Stats($dbo = null);

  // Set dates to month to date
  $_SESSION['stats_month_date1_sql'] = date("Y-m-01");
  $_SESSION['stats_month_date2_sql'] = date("Y-m-d");
  $_SESSION['stats_month_date1_pres']= $date1 = date("m-01-y");
  $_SESSION['stats_month_date2_pres']= $date2 = date("m-d-y");

  $array = array(
    'page_title'=>'View Statistics (Month To Date) - ',
    'title_info'=>$_SESSION['dealer_name'].' ('.$_SESSION['dealer_code'].')',
    'ro_count'=>true,
    'dealer_group'=>false,
    'dealer_id'=>$_SESSION['dealer_id'],
    'date_range'=>true,
    'stats_month'=>true,
    'stats_search'=>false,
    'advisor_id'=>false,
    'district_id'=>false,
    'area_id'=>false,
    'region_id'=>false,
    'search_feedback'=> 'Date Range: '.$date1.' through '.$date2,
    'print-icon'=>true,
    'export-icon'=>true,
    'a_id'=>false
  );

  return $Stats->getPageHeading($array)
    .$Stats->getServiceLevelTable($array)
    .$Stats->getLofTable($array)
    .$Stats->getVehicleTable($array)
    .$Stats->getYearModelTable($array)
    .$Stats->getMileageTable($array)
    .$Stats->getRoTrendTable($array);
}

function getDealerStatsAllHistory() {
  $Stats = new Stats($dbo = null);

  $array = array(
    'page_title'=>'View Statistics (All History) - ',
    'title_info'=>$_SESSION['dealer_name'].' ('.$_SESSION['dealer_code'].')',
    'ro_count'=>true,
    'dealer_group'=>false,
    'dealer_id'=>$_SESSION['dealer_id'],
    'date_range'=>false,
    'stats_month'=>false,
    'stats_search'=>false,
    'advisor_id'=>false,
    'district_id'=>false,
    'area_id'=>false,
    'region_id'=>false,
    'search_feedback'=> 'Showing: All History',
    'print-icon'=>true,
    'export-icon'=>true,
    'a_id'=>false
  );

  return $Stats->getPageHeading($array)
    .$Stats->getServiceLevelTable($array)
    .$Stats->getLofTable($array)
    .$Stats->getVehicleTable($array)
    .$Stats->getYearModelTable($array)
    .$Stats->getMileageTable($array)
    .$Stats->getRoTrendTable($array);
}

/**
 * Possible search options include the following:
 * dealer, dealer group, area, region, district
 * Leave these out of the original array, and then use foreach to add search items to array
 */
function getDealerStatsSearchResults() {
  $Stats = new Stats($dbo = null);

  $search_params = json_decode($_POST['search_params'], true);

  /* For testing
  foreach($search_params as $key=>$value) {
    echo '$array: '.$key.'=>'.$value.'<br>';
  }
  */

  $array = array(
    'page_title'=>'View Statistics - ',
    'title_info'=>'Filtered Results',
    'ro_count'=>true,
    'stats_month'=>false,
    'stats_search'=>true,
    'print-icon'=>true,
    'export-icon'=>true,
    'a_id'=>false
  );

  // Now add $search_params to $array for submission to class methods
  foreach($search_params as $key=>$value) {
    $array[$key] = $value;
  }

  // If dates were entered as search params, set $array['date_range'] = true, create sql-compatible format and add to $array for passing to methods
  if($array['date1_pres']) {
    $array['date_range'] = true;
    $date = new DateTime($array['date1_pres']);
    $array['date1_sql'] = $date->format("Y-m-d");
    $date = new DateTime($array['date2_pres']);
    $array['date2_sql'] = $date->format("Y-m-d");
  }

  // If only dates and/or date fields were entered, add 'Dealer: Name + Code' to search_feedback
  // string so user knows which dealer the info pertains to
  if (($array['date1_pres'] &&  $array['date2_pres'] &&  $array['advisor_id'])
    || ( $array['date1_pres'] &&  $array['date2_pres'] && !$array['advisor_id'])
    || (!$array['date1_pres'] && !$array['date2_pres'] &&  $array['advisor_id'])
  ) {
    if (!$array['region_id']
      && !$array['area_id']
      && !$array['district_id']
      && !$array['dealer_group']
    ) {
      $array['search_feedback'] .= 'Dealer: '.$_SESSION['dealer_name'].' ('.$_SESSION['dealer_code'].')';
    }
  }

  /**
   * If dates only were selected (and no region, district, etc):
   * Pass 'dealer_id' param SESSION var as default UNLESS
   * 'View All Dealers' has been checked
   */
  if (!$array['region_id']
    && !$array['area_id']
    && !$array['district_id']
    && !$array['dealer_group']
  ) {
    if (!$array['all_dealers_checkbox']) {
      $array['dealer_id'] = $_SESSION['dealer_id'];
    }
  }

  return $Stats->getPageHeading($array)
    .$Stats->getServiceLevelTable($array)
    .$Stats->getLofTable($array)
    .$Stats->getVehicleTable($array)
    .$Stats->getYearModelTable($array)
    .$Stats->getMileageTable($array)
    .$Stats->getRoTrendTable($array);
}

function getAllDealersSummaryResults() {
  $SurveysSummary = new SurveysSummary($dbo = null);

  $pageArr = array(
    'ro_count'=>true,
    'page_title'=>'Dealer Reporting Summary',
    'export-icon'=>true,
    'print-icon'=>true
  );

  // Run 'method2' first so that dealer count is available as SESSION['dealer_summary_count'] var
  $table = $SurveysSummary->getDealerSummaryTable();

  return $SurveysSummary->getPageHeading().$table;
}

function getDealerListingView() {
  $DealerInfo = new DealerInfo($dbo = null);

  $array = array(
    'page_title'=>'Manage Dealers - ',
    'title_info'=>'All '.MANUF.' Dealers',
    'a_id'=>'add_dealer_link',
    'link_msg'=>'Add New Dealer',
    'dealer_count'=>true,
    'print-icon'=>true,
    'export-icon'=>true
  );

  // Run getDealerListingTable() method first so that $_SESSION['dealer_count'] may be used in title etc.
  $dealerTable = $DealerInfo->getDealerListingTable($array);

  return $DealerInfo->getPageHeading($array).$dealerTable;
}

function getAddDealerForm() {
  $DealerInfo = new DealerInfo($dbo = null);

  $pageArr = array(
    'page_title'=>'Manage Dealers - ',
    'title_info'=>'Add New Dealers',
    'a_id'=>false,
    'link_msg'=>false,
    'dealer_count'=>false
  );

  $tableArr = array(
    'edit_dealer_val'=>false,
    'a_id'=>'add_dealer_row',
    'link_msg'=>'Add Row'
  );

  return $DealerInfo->getPageHeading($pageArr).$DealerInfo->getAddDealerTable($tableArr);
}

function getAddNewDealerRow() {
  $DealerInfo = new DealerInfo($dbo = null);

  $paramsArr = array(
    'add_dealer_row'=>true,
    'edit_dealer_val'=>false
  );

  return $DealerInfo->getAddDealerTable($paramsArr);
}

function addNewDealers() {
  $DealerInfo = new DealerInfo($dbo = null);

  $postArr = array(
    'edit_dealer_val'=>$_POST['edit_dealer_val'],
    'edit_dealer_id'=>$_POST['edit_dealer_id']
  );

  return $DealerInfo->processAddNewDealers($postArr);

  // Instantiate Admin class for access to getSuccessMsg method
  /*
  $admin = new Admin($dbo=null);
  // Execute UPDATE method if edit_dealer_val == 1. Else execute INSERT statement.
  if(true === $result = $obj->$use_array['method1'](array('edit_dealer_val'=>$_POST['edit_dealer_val'], 'edit_dealer_id'=>$_POST['edit_dealer_id']))) {
    if($_POST['edit_dealer_val'] == true) {
      // Set dealer code value to you can echo it with the result
      $dealer_code = $_POST['edit_dealer_code'];
      echo $obj->$use_array['method2'](array('page_title'=>'Manage Dealers - ', 'title_info'=>'Edit '.MANUF.' Dealer')).
           $admin->$use_array['method4'](array('success_msg'=>'*Dealer '.$dealer_code.' has been updated successfully'));
    } else {
      echo $obj->$use_array['method2'](array('page_title'=>'Manage Dealers - ', 'title_info'=>'Add '.MANUF.' Dealers')).
         $obj->$use_array['method3'](array('edit_dealer_val'=>false, 'a_id'=>'add_dealer_row', 'link_msg'=>'Add Row')).
         $admin->$use_array['method4'](array('success_msg'=>'The dealers you submitted have been processed successfully.'));
    }
  } else {
    echo $result;
  }*/
}

function getUserRequestForm() {
  $Admin = new Admin($dbo = null);

  $pageArr = array(
    'page_title'=>'Manage Users - ',
    'title_info'=>'Submit User Setup Request',
    'a_id'=>false,
    'link_msg'=>false
  );

  $paramsArr = array(
    'a_id'=>'add_user_req_row',
    'link_msg'=>'Add Row'
  );

  return $Admin->getPageHeading($pageArr).$Admin->getUserRequestTable($paramsArr);
}

function addUserRequestRow() {
  $Admin = new Admin($dbo = null);

  return $Admin->addUserRequestRow();
}

function getAddNewUserTable() {
  $Admin = new Admin($dbo = null);

  $pageArr = array(
    'page_title'=>'Manage Users - ',
    'title_info'=>'Add New Users',
    'a_id'=>false,
    'link_msg'=>false
  );

  $paramsArr = array(
    'a_id'=>'add_new_user_row',
    'link_msg'=>'Add Row'
  );

  return $Admin->getPageHeading($pageArr).$Admin->getAddUserTable($paramsArr);
}

function getAddNewUserRow() {
  $Admin = new Admin($dbo = null);

  $paramsArr = array('add_user_row'=>true);

  return $Admin->getAddUserTable($paramsArr);
}

function addNewUsers() {
  $Admin = new Admin($dbo = null);

  $returnHtml = '';

  // Execute INSERT instruction; if successful, proceed with table form reload and success msg
  $userParamsArr = array(
    'edit_user_val'=>$_POST['edit_user_val'],
    'edit_user_id'=>$_POST['edit_user_id']
  );

  if($result = $Admin->processAddNewUsers($userParamsArr)) {
    if(substr($result, 0, 10) == "error_dupe") {
      $returnHtml = $result;
      exit;
    }

    if($_POST['edit_user_val'] == 1) {
      // Configure username POST for success message
      $user_name_success = json_decode($_POST['user_uname'], true);
      $user_name_success = $user_name_success[0];

      $pageArr = array(
        'page_title'=>'Manage Users - ',
        'title_info'=>'Edit '.MANUF.' User',
        'success_msg'=>'*User '.$user_name_success.' has been updated successfully!'
      );

      $returnHtml = $Admin->getPageHeading($array).$Admin->getSuccessMsg($pageArr);
    } elseif (substr($result, 0, 10) != "error_dupe") {
      $pageArr = array(
        'page_title'=>'Manage Users - ',
        'title_info'=>'Add New Users',
        'a_id'=>'add_new_user_row',
        'link_msg'=>'Add Row',
        'success_msg'=>'*The users you submitted have been processed successfully.  An email confirmation has been sent to: '.$_SESSION['user']['user_email']
      );

      $returnHtml = $Admin->getPageHeading($pageArr)
        .$Admin->getAddUserTable($pageArr)
        .$Admin->getSuccessMsg($pageArr);
    }
  } else {
    $returnHtml = $result;
  }

  return $returnHtml;
}

function checkUsernameDupe() {
  $Admin = new Admin($dbo = null);

  $returnHtml = '';
  $array = array('user_name'=>$_POST['user_name']);

  // If username duplicate was found, return 'username_dupe'
  if($Admin->checkUsernameDupe($array)) {
    $returnHtml = 'username_dupe';
  } else {
    $returnHtml = null;
  }

  return $returnHtml;
}

function getDealerInfoJSON() {
  $DealerInfo = new DealerInfo($dbo = null);

  return json_encode($DealerInfo->getDealerListing());
}

/**
 * Note that this process contains a js array string for $_POST['dealer_id'] and $_POST['dealer_code']
 * Must use php json_decode($var, true) to convert back to arrays on server side
 */
function processUserSetupRequest() {
  $Admin = new Admin($dbo = null);

  $returnHtml = '';

  // Run the method for db INSERT
  if($Admin->processUserSetupRequest()) {
    // If successful, reload page with new heading and fresh table so user can see something happened
    $pageArr = array(
      'page_title'=>'Manage Users - ',
      'title_info'=>'Approve User Setup Requests',
      'a_id'=>'add_user_req_row',
      'link_msg'=>'Add Row'
    );

    $userParamsArr = array('user_approve'=>false);

    $successArr = array('success_msg'=>'*Your request was successfully submitted. An email confirmation will be sent to '.$_SESSION['user']['user_email']);

    $returnHtml = $Admin->getPageHeading($pageArr)
      .$Admin->getUserRequestTable($userParamsArr)
      .$Admin->getSuccessMsg($successArr);
  } else {
    $returnHtml = '<p>There was an error submitting your request.  Please see the administrator.</p>';
  }

  return $returnHtml;
}

function viewUserSetupRequests() {
  $Admin = new Admin($dbo = null);

  $pageArr = array(
    'page_title'=>'Manage Users - ',
    'title_info'=>'Approve User Setup Requests',
    'a_id'=>false,
    'link_msg'=>false
  );

  $paramsArr = array(
    'user_approve'=>true,
    'a_id'=>'select_all_user_requests',
    'link_msg'=>'Select All'
  );

  return $Admin->getPageHeading($pageArr).$Admin->getUserRequestTable($paramsArr);
}

// If processUserSetupApprovals() returns true, display table and success msg. Else display error
function approveUserSetupRequests() {
  $Admin = new Admin($dbo = null);

  $returnHtml = '';

  if($Admin->processUserSetupApprovals($array = null)) {
    $pageArr = array(
      'page_title'=>'Approve User Setup Requests',
      'a_id'=>'select_all_user_requests',
      'link_msg'=>'Add Row'
    );

    $successArr = array('success_msg'=>'*Your approvals were successfully submitted. An email confirmation will be sent to '.$_SESSION['user']['user_email'].'. <br> &nbsp; All requestors have been notified.');

    $returnHtml = $Admin->getPageHeading($pageArr)
      .$Admin->getUserRequestTable(array('user_approve'=>true))
      .$Admin->getSuccessMsg($successArr);
  } else {
    $returnHtml = '<p>There was an error submitting your request.  Please see the administrator.</p>';
  }

  return $returnHtml;
}

/**
 * Note: if dealer user, should only see table of their dealers (provide SESSION dealer_id)
 * If SOS admin, should see list of all available dealer users
 * If SOS non-admin, should see list of all available dealer users
 * User type reference:  1 == SOS, 2 == Manuf, 3 == Dealer
 */
function viewDealerUsers() {
  $Admin = new Admin($dbo = null);

  // Set page 'title_info' based on the type of user requesting to view Manage Users page
  if($_SESSION['user']['user_type_id'] == 3) {
    $info = $_SESSION['dealer_name'];
  } else {
    $info = MANUF.' Dealer Users';
  }

  $tableParamsArr = array(
    'table_requested'=>'dealer',
    'requested_by'=>$_SESSION['user']['user_type_id'],
    'request_admin_val'=>$_SESSION['user']['user_admin']
  );

  $pageArr = array(
    'page_title'=>'Manage Users - ',
    'title_info'=>$info,
    'link_msg'=>'Add New User',
    'a_id'=>'add_user_link',
    'print-icon'=>true,
    'export-icon'=>true,
    'user_count'=>true
  );

  // Run table generation code first so that dealer count SESSION var is available to getPageHeading() method
  $user_table = $Admin->getUserTable($tableParamsArr);

  return $Admin->getPageHeading($pageArr).$user_table;
}

function viewSosUsers() {
  $Admin = new Admin($dbo = null);

  // Run table generation code first so that dealer count SESSION var is available to getPageHeading() method
  $tableArr = array(
    'table_requested'=>'sos',
    'requested_by'=>$_SESSION['user']['user_type_id'],
    'request_admin_val'=>$_SESSION['user']['user_admin']
  );

  $pageArr = array(
    'page_title'=>'Manage Users - ',
    'title_info'=>MANUF.' SOS Users',
    'link_msg'=>'Add New User',
    'a_id'=>'add_user_link',
    'print-icon'=>true,
    'export-icon'=>true,
    'user_count'=>true
  );

  $userTable = $Admin->getUserTable($tableArr);

  return $Admin->getPageHeading($pageArr).$userTable;
}

function viewManufacturerUsers() {
  $Admin = new Admin($dbo = null);

  // Run table generation code first so that dealer count SESSION var is available to getPageHeading() method
  $tableParamsArr = array(
    'table_requested'=>'manuf',
    'requested_by'=>$_SESSION['user']['user_type_id'],
    'request_admin_val'=>$_SESSION['user']['user_admin']
  );

  $pageArr = array(
    'page_title'=>'Manage Users - ',
    'title_info'=>MANUF.' Manufacturer Users',
    'link_msg'=>'Add New User',
    'a_id'=>'add_user_link',
    'print-icon'=>true,
    'export-icon'=>true,
    'user_count'=>true
  );

  $user_table = $Admin->getUserTable($tableParamsArr);

  return $Admin->getPageHeading($pageArr).$user_table;
}

function getEditUserView() {
  $Admin = new Admin($dbo = null);

  $pageArr = array(
    'page_title'=>'Manage Users - ',
    'title_info'=>'Edit '.MANUF.' User',
    'link_msg'=>null
  );

  $paramsArr = array('edit_user'=>true, 'user_id'=>$_POST['user_id']);

  return $Admin->getPageHeading($pageArr).$Admin->getAddUserTable($paramsArr);
}

function getEditDealerView() {
  $DealerInfo = new DealerInfo($dbo = null);

  $pageArr = array(
    'page_title'=>'Manage Dealers - ',
    'title_info'=>'Edit '.MANUF.' Dealer',
    'link_msg'=>null
  );

  $paramsArr = array(
    'edit_dealer_val'=>true,
    'dealer_id'=>$_POST['dealer_id']
  );

  return $DealerInfo->getPageHeading($pageArr).$DealerInfo->getAddDealerTable($paramsArr);
}

function getAddDocumentForm() {
  $Documents = new Documents($dbo = null);

  $pageArr = array(
    'page_title'=>'System Documents - ',
    'title_info'=>'Add New Document',
    'a_id'=>null,
    'link_msg'=>null
  );

  return $Documents->getPageHeading($pageArr).$Documents->getFileUploadForm();
}

function processFileUpload() {
  $Documents = new Documents($dbo = null);

  // Run the processFileUpload() method. $status will contain uploaded filename if successful. Else false (bool)
  $status = $Documents->processFileUpload();

  // Build feedback msg based on $status value
  if($status) {
    $msg = $status.' has been uploaded successfully!';
  } else {
    $msg = 'There was an error uploading '.$status.'<br>Please try again, and contant the administrator if the problem persists.';
  }

  $pageArr = array(
    'page_title'=>'System Documents - ',
    'title_info'=>'Add New Document',
    'a_id'=>'view_doc_link',
    'link_msg'=>'View System Documents'
  );

  // Send back html results
  return $Documents->getPageHeading($pageArr)
    .$Documents->getSuccessMsg(array('success_msg'=>$msg))
    .$Documents->getFileUploadForm();
}

// Pulls up ALL system docs; for admin users
function viewAllDocuments() {
  $Documents = new Documents($dbo = null);

  $pageArr = array(
    'page_title'=>'System Documents - ',
    'title_info'=>'View Documents',
    'doc_count'=>true,
    'export-icon'=>true,
    'a_id'=>'add_doc_link',
    'link_msg'=>'Add New Document'
  );

  $documentTable = $Documents->getDocTable();

  return $Documents->getPageHeading($pageArr).$documentTable;
}

// View documents of specific type
function viewSpecificDocumentType() {
  $Documents = new Documents($dbo = null);

  // Assign $doc_type variable for db SELECT doc_type field value, and $doc_title for page title_info display
  switch ($_POST['doc_type']) {
    case 'release_docs':
      $doc_type = 1;
      $doc_title= 'Release Forms';
      break;
    case 'sysguide_docs':
      $doc_type = 2;
      $doc_title= 'System Guides';
      break;
    case 'my_docs':
      $doc_type = 3;
      $doc_title= 'My Documents';
      break;
  }

  // Save doc_type and doc_title as SESSION vars so that correct table displays after delete or edit doc action. Will be used in 'delet_doc' and 'file_update_submit' actions.
  $_SESSION['doc_type']  = $doc_type;
  $_SESSION['doc_title'] = $doc_title;

  $pageArr = array(
    'page_title'=>'System Documents - ',
    'title_info'=>$doc_title,
    'doc_count'=>true,
    'export-icon'=>true,
    'a_id'=>'add_doc_link',
    'link_msg'=>'Add New Document'
  );

  $table = $Documents->getDocTable(array('doc_type'=>$doc_type));

  return $Documents->getPageHeading($pageArr).$table;
}

function deleteDocument() {
  $Documents = new Documents($dbo = null);

  $docParamsArr = array(
    'view_doc_id'=>$_POST['view_doc_id'],
    'tmp_name'=>$_POST['tmp_name']
  );

  $result = $Documents->deleteDoc($docParamsArr);

  // Set sucess msg based on $result.
  $msg = $result
    ? "The document was successfully deleted!"
    : "Error: The document could not be deleted. Please see the administrator.";

  $pageArr = array(
    'page_title'=>'System Documents - ',
    'title_info'=>$_SESSION['doc_title'],
    'doc_count'=>true,
    'export-icon'=>true,
    'a_id'=>'add_doc_link',
    'link_msg'=>'Add New Document'
  );

  // Now reload doc table and page heading. Table must be loaded first to acquire doc count SESSION var
  $documentTable = $Documents->getDocTable(array('doc_type'=>$_SESSION['doc_type']));

  return $Documents->getPageHeading($pageArr)
    .$Documents->getSuccessMsg(array('success_msg'=>$msg))
    .$documentTable;
}

function getEditDocumentForm() {
  $Documents = new Documents($dbo = null);

  $pageArr = array(
    'page_title'=>'System Documents - ',
    'title_info'=>'Edit Document',
    'doc_count'=>false,
    'a_id'=>'view_doc_link',
    'export-icon'=>false,
    'link_msg'=>'View System Docs'
  );

  return $Documents->getPageHeading($pageArr).$Documents->getFileUploadForm(array('edit_doc_id'=>$_POST['edit_doc_id']));
}

function processEditDocumentSubmit() {
  $Documents = new Documents($dbo = null);

  $status = $Documents->updateDoc();

  $msg = $status
    ? 'The file was updated successfully!'
    : 'Error: The file was not updated.  Please try again or see the administrator.';

  $pageArr = array(
    'page_title'=>'System Documents - ',
    'title_info'=>$_SESSION['doc_title'],
    'doc_count'=>true,
    'export-icon'=>true,
    'a_id'=>'add_doc_link',
    'link_msg'=>'Add New Document'
  );

  $documentTable = $Documents->getDocTable(array('doc_type'=>$_SESSION['doc_type']));

  return $Documents->getPageHeading($pageArr)
    .$Documents->getSuccessMsg(array('success_msg'=>$msg))
    .$documentTable;
}

function getContactForm() {
  $ContactUs = new ContactUs($dbo = null);

  $pageArr = array(
    'page_title'=>'Contact Us - ',
    'title_info'=>'Comments? Suggestions?'
  );

  return $ContactUs->getPageHeading($pageArr).$ContactUs->getContactForm();
}

function processContactFormSubmit() {
  $ContactUs = new ContactUs($dbo = null);

  $result = $ContactUs->processContactForm();

  // Set feedback based on $result
  $msg = ($result) ? 'Thank you for your inquiry. A copy of your request has been sent to: '.$_SESSION['inquiry_email'].'.  We will contact you as soon as possible.' : 'Sorry! There was an error processing your request.  Please try again or contact the administrator.';

  $pageArr = array(
    'page_title'=>'Contact Us - ',
    'title_info'=>'Comments? Suggestions?'
  );

  unset($_SESSION['inquiry_email']);

  return $ContactUs->getPageHeading($pageArr)
    .$ContactUs->getSuccessMsg(array('success_msg'=>$msg))
    .$ContactUs->getContactForm();
}

// Set $_SESSION['advisor_enterro'] if user selects advisor from enterro advisor dropdown option
function processAdvisorSelect() {
  $Welr = new Welr($dbo = null);

  $result = $Welr->processAdvisorSelection();
}

// This action was moved to a straight-up form POST action bc AJAX wouldn't originally work
// function viewPdfDocument() {
//   $Documents = new Documents($dbo = null);

//   $Documents->viewFile(array('view_doc_id'=>$_POST['view_doc_id']));
// }

exit;
?>
