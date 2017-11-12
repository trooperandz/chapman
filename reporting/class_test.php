<?php
/* Program:	 class_test.php
 * Created:  02/26/2016 by Matt Holland
 * Purpose:  Test integration of OOP restructuring
 * Methods:   		
 * Updates:	 
 *
**/

// Require the initialization file
require_once('system/config/init.inc.php');
	
include_once('system/templates/header.php');
/*
// Test prepared statements
$obj = new Welr($dbo);
echo $obj->testStmt();


// Test services class
$svc_obj = new ServicesInfo($dbo);
$svc = $svc_obj->getServiceInfo();
echo '$svc: ',var_dump($svc);

$l1_count = 0;
$l2_count = 0;
$l3_count = 0;
foreach($svc['svc_level'] as $level) {
	if($level == 1) {
		$l1_count += 1;
	}
	if($level == 2) {
		$l2_count += 1;
	}
	if($level == 3) {
		$l3_count += 1;
	}
}
echo '$l1_2_3_count: ',$l1_count,'<br>',$l2_count,'<br>',$l3_count,'<br>';

/*
$obj = new UserInfo($dbo);
$stuff = $obj->getAdvisors($_SESSION['dealer_id']);
echo var_dump($stuff['user_name']);

echo 'SESSION[user]: ',$_SESSION['user'];

unset($_SESSION['error']);

$welr_obj = new Welr($dbo);
$array = array("ronumber"=>12345, "ro_date"=>'03/06/2016', 
					  "yearmodel"=>'16 2016', "mileagespreadID"=>'2,0 - 15k',
					  "vehicle_make_id"=>'3,Acura', "labor"=>NULL, "parts"=>NULL,
					  "dealer_id"=>$_SESSION['dealer_id'], "comment"=>'testing', "svc_reg"=>array(1),
					  "svc_add"=>array(1), "svc_dec"=>array(1), "svc_hidden"=>array(1));
echo $welr_obj->processRoEntry($array);
//echo '$ym: ',print_r($stuff),'<br>';
/*
echo 'modulus L1: ',(L1_SVC_COUNT+L2_SVC_COUNT);

$welr = new Welr($dbo);

$svcs = $welr->getSvcrenderedData($_SESSION['dealer_id'], $ronumber = 11111);
echo '$svcs: ',var_dump($svcs),'<br><br>';


// Test servicerendered_welr query search results
$svc = new Welr($dbo);
echo $svc->test();
*/
/*
// Test pdo extention
$C = array();

// The database host URL
$C['DB_HOST'] = 'localhost';

// The database username
$C['DB_USER'] = 'sosfirm_acurates';

// The database password
$C['DB_PASS'] = 'Trooper4#';

// The database name to work with
$C['DB_NAME'] = 'sosfirm_acuratest';

foreach($C as $name=>$val) {
	define($name, $val);
}

try {
	$dbo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
	//$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo 'Connection failed: '.$e->getMessage();
}

// Set search array items
$array = array('dealer_id'=>false, 'date_range'=>false, 'region_id'=> 1, 'advisor_id'=>false);

// Initialize $stmt and $params and build query dynamically
$stmt 	= array();
$params = array();
$stmt[] = "SELECT COUNT(ronumber) FROM repairorder_welr a
		  LEFT JOIN dealer b ON(a.dealerID = b.dealerID) ";

if($array['dealer_id']) {
	$dealer_id = $array['dealer_id'];
	$params[] = $dealer_id;
	$stmt[] = "a.dealerID = ? ";
}

// If date_range == true, add BETWEEN statement for count
if($array['date_range']) {
	$metrics_date_range1 = '2016-03-01';
	$metrics_date_range2 = '2016-03-17';
	$params[] = $metrics_date_range1;
	$params[] = $metrics_date_range2;
	$stmt[] = "a.ro_date BETWEEN ? AND ? ";
}

if($array['region_id']) {
	$region_id = $array['region_id'];
	$params[] = $region_id;
	$stmt[] = "b.regionID = ? ";
}

if($array['advisor_id']) {
	$advisor_id = $array['advisor_id'];
	$params[] = $advisor_id;
	$stmt[] = "a.userID = ? ";
}

// Build statement dynamically based on size of array
$query = "";
for($i=0; $i<count($stmt); $i++) {
	if(count($stmt) >= 1 && count($stmt) < 3) {
		if($i == 0) {
			$query .= $stmt[$i];
		} else {
			$query .= " WHERE ".$stmt[$i];
		}
	} elseif(count($stmt) > 1 && count($stmt) > 2) {
		if($i == 0) {
			$query .= $stmt[$i];
		} elseif ($i == 1) {
			$query .= " WHERE ".$stmt[$i];
		} else {
			$query .= " AND ".$stmt[$i];
		}
	}
}
//echo '$query: '.$query.'<br>';

// Prepare and execute statement

if(!($stmt = $dbo->prepare($query))) {
	sendErrorNew($dbo->errorInfo(), __LINE__, __FILE__);
}

if(!($stmt->execute($params))) {
	sendErrorNew($stmt->errorInfo(), __LINE__, __FILE__);
} else {
	$result = (int)$stmt->fetchColumn();
	echo '$stmt: '.var_dump($stmt).'<br>';
	echo '$result: '.$result.'<br>';
}

// Test mail function
$subject = "Mail Test";
$error = "Is it working?";
mail(CONTACT_EMAIL, $subject, $error);

if ( function_exists( 'mail' ) )
{
    echo 'mail() is available';
}
else
{
    echo 'mail() has been disabled';
} 

// Test getLaborPartsData($array)
$obj = new Metrics($dbo=null);
$array = array('dealer_group'=>false, 'dealer_id'=>271, 
			   'date_range'=>false, 'metrics_month'=>false, 'metrics_search'=>false, 'advisor_id'=>195, 
			   'district_id'=>10, 'area_id'=>1, 'region_id'=>1
			  );
echo var_dump($obj->getLaborPartsData($array));
*/
/*
// Test Stats class
$obj = new Stats($dbo=null);
$array = array('dealer_id'=>271, 'date_range'=>true, 'date1_sql'=>'2016-01-01', 'date2_sql'=>'2016-03-11',
			   'advisor_id'=>false, 'district_id'=>10, 'area_id'=>1, 'region_id'=>1, 'stats_search'=>true
		      );
$result = $obj->getVehicleTable($array);
echo '$result: '.$result.'<br>';

$result = $obj->getLofTable($array);
echo '$result: '.$result.'<br>';

$result = $obj->getYearModelTable($array);
echo '$result" '.$result.'<br>';

$result = $obj->getMileageTable($array);
echo '$result: '.$result.'<br>';

$result = $obj->getServiceLevelTable($array);
echo '$result: '.var_dump($result).'<br>';

//$result = $obj->getServiceLevelStats($array);
//echo '$result: '.$result.'<br>';

//$result = $obj->getRoEntryStats($array);
//echo '$result: '.var_dump($result).'<br>';

$result = $obj->getRoEntryStatsTable($array);
echo '$result: '.$result.'<br>';
/*
// Test getMetricsData dynamic query
$metrics = new Metrics($dbo=null);
echo $metrics->getMetricsTable(array('L1_svcs'=>true, 'L2_3_svcs'=>false, 'dealer_id'=>271));
// Test Metrics class
/*
$metrics = new Metrics($dbo=null);
//echo $svc->getMetricsData();
echo $metrics->getMetricsTable(array("L1_svcs"=>true, "L2_3_svcs"=>false, "dealer_id"=>$_SESSION['dealer_id']));
echo $metrics->getLaborPartsTable(array('dealer_id'=>$_SESSION['dealer_id'], 'date_range'=>false));
echo $metrics->getMetricsTable(array("L1_svcs"=>false, "L2_3_svcs"=>true, "dealer_id"=>$_SESSION['dealer_id']));
/*
$welr = new Welr($dbo);
echo $welr->getPageHeading($page_title = 'Test', $entry_form = true, $update_form=false).
     $welr->getRoEntryTable($entry_form = false, $date_range = false, $search_params = '{"ro_num1":11111,"ro_num2":30965}');
/*

$stuff = $welr->getRoEntryForm($_SESSION['dealer_id'], $update_form=true, $ronumber = 11111);
echo 'entry_form: ',$stuff,'<br><br>';

/*
$stuff = $welr_obj->getVehicleOpts();
echo '$vehicle: ',print_r($stuff),'<br>';

// Test trending of dates
$obj = new Metrics($dbo=null);
//echo var_dump($obj->getMetricsTrendData($array = null));
//echo var_dump($obj->getMetricsTrendTable($array = null));
echo $obj->getMetricsTrendTable($array=null);
//echo $obj->getMetricsTrendTable(array('table_title'=>'Opportunity By Month', 'table_id'=>'trend_opp_table', 'metric_type'=>'frequency'));
//echo $obj->getMetricsTrendTable(array('table_title'=>'Close Rate By Month', 'table_id'=>'trend_close_table', 'metric_type'=>'close_rate'));
/*
// Test user info fetch
$adm_obj = new Admin($dbo);
//$user = $adm_obj->_processLoginForm($user_name = 'bsanta', $login_password = 'Bsanta!725', $login_dealercode = '251514');
$user = $adm_obj->processLoginForm($user_name = 'shamby', $login_password = 'Sham603#', $login_dealercode = '251002');
//$user = $adm_obj->_processLoginForm($user_name = 'mholland', $login_password = 'Trooper2#', $login_dealercode = '251002');

// Test return data
echo '$user: ',var_dump($user),'<br>';
echo '$_SESSION[dealer_id}: ',$_SESSION['dealer_id'],'<br>';
echo '$_SESSION[dealer_code]: ',$_SESSION['dealer_code'],'<br>';
echo '$_SESSION[Acura]: ',$_SESSION['Acura'];


// Test SurveysSummary class
$obj = new SurveysSummary($dbo=null);
echo $obj->getDealerSummaryTable($array = null);
//echo var_dump($obj->getDealerSummaryData($array = null));


// Test getMetricsDlrCompData($array) 
$obj = new Metrics($dbo=null);
$array = ();
$info = $obj->getMetricsDlrCompData($array);
echo var_dump($info);

// Test DateTimeCalc class
$obj = new DateTimeCalc;
$array = array('date1_sql'=>'2016-01-09', 'date2_sql'=>'2016-07-31');
echo var_dump($obj->getMonthRanges($array));

*/
// Test dealerInfo PDO revamp
$obj = new DealerInfo($dbo=null);
echo var_dump($obj->getDealerListing());

//unset($_SESSION['error']);

include_once('system/templates/footer.php');
?>