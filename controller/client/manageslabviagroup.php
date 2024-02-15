<?php
//prepare for request
session_start();

//include necessary helpers
require_once('../../config/config.php');
require_once(__ROOT__.'/config/dbUtils.php');
require_once(__ROOT__.'/config/errorMap.php');
require_once(__ROOT__.'/config/auth.php');
require_once(__ROOT__.'/config/logs/logsProcessor.php');
require_once(__ROOT__.'/config/logs/logsCoreFunctions.php');
require_once(__ROOT__.'/libphp-phpmailer/autoload.php');

//include necessary models
require_once(__ROOT__.'/model/user/userModel.php');
require_once(__ROOT__.'/model/client/clientModel.php');

//Connection With Database
$conn = createDbConnection($host, $dbUsername, $dbPassword, $dbName);
if (!noError($conn)) {
    //error connecting to DB
    $returnArr["errCode"] = 1;
    $returnArr["errMsg"] = getErrMsg(1) . $conn["errMsg"];
    echo (json_encode($returnArr));
    exit;
}

$conn = $conn["errMsg"];


//accept, sanitize and validate inputs
//need email first to create logs
$username = "";
if (isset($_SESSION["userEmail"])) {
    $username = cleanQueryParameter($conn, cleanXSS($_SESSION["userEmail"]));
}
if (empty($username)) {
    $returnArr["errCode"] = 4;
    $returnArr["errMsg"] = getErrMsg(4) . ": There seems to be noone logged in or the session has timed out. Please login again.";
    echo (json_encode($returnArr));
    exit;
}

//initialize logs
$logsProcessor = new logsProcessor();
$initLogs = initializeJsonLogs($username);
$logFilePath = $logStorePaths["clients"];
$logFileName = "client_slab_percentage.json";


$paramlog['table_name'] = "client_slab_percentage";
$paramlog['file_name'] = '';
$paramlog['status_name'] = "Insert-update";
$paramlog['status_flag'] = "Start";
$paramlog['date_added'] = date("Y-m-d H:is:");
$paramlog['ip_address'] = get_client_ip();
$paramlog['login_user'] = $_SESSION["userEmail"];
$paramlog['log_file'] = $logStorePaths["clients"];
$paramlog['raw_data'] = json_encode($_POST);
activitylogs($paramlog, $conn);


$logMsg = "Add/edit client_slab_percentage controller process start.";
$logData['step1']["data"] = "1. {$logMsg}";

$logMsg = "Database connection successful. Lets validate inputs.";
$logData["step2"]["data"] = "2. {$logMsg}";

$logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

$group_name = "";
if (isset($_POST["group_name"])) {
    // $group_name = cleanQueryParameter($conn, cleanXSS(strtolower($_POST["group_name"])));
    $group_name = cleanQueryParameter($conn, cleanXSS(strtolower($_POST["group_name"])));
}
$group_id = 0;
if (isset($_POST["group_id"])) {
    // $group_id = cleanQueryParameter($conn, cleanXSS(strtolower($_POST["group_id"])));
    $group_id = cleanQueryParameter($conn, cleanXSS(strtolower($_POST["group_id"])));
}
if (empty($group_name)) {
    $logMsg = "group_name Code field empty: " . json_encode($_POST);
    $logData["step2.1"]["data"] = "2.1. {$logMsg}";
    $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

    $returnArr["errCode"] = 4;
    $returnArr["errMsg"] = getErrMsg(4) . " group_name Code cannot be empty";
    echo (json_encode($returnArr));
    exit;
}

//from_amt
//to_amt
//percentage
//print_r($_POST);
$deleteClientInfoQuery = "delete from client_slab_percentage where group_name = '{$group_name}'";
 
$deleteClientInfoQueryResult = runQuery($deleteClientInfoQuery, $conn);

$youtube = (isset($_POST['youtube'])) ? $_POST['youtube'] : [];
//print_r($youtube);
foreach($youtube as $key => $value){

    //percentage
  //  print_r($key);
    if($key=="percentage"){
   //     print_r($value);
        foreach($value as $key2 => $value2){
           
             $percentage = (int)$value2;
            if($percentage > 0){
                $param = [];
                $values = [];
                $keys = [];

                $param['percentage'] = $percentage;
                $param['to_amt'] = $youtube['to_amt'][$key2];
                $param['from_amt'] = $youtube['from_amt'][$key2];
                $param['slab_for'] = $youtube['slab_for'][$key2];
                $param['group_name'] = $group_name;
                $param['group_id'] = $group_id;
                
                foreach ($param as $key=>$val) {
                    $value = mysqli_real_escape_string($conn, $val);
                    $keys[] = "`".$key."`";
                    $values[] = "'{$val}'";
                }
            
                  //echo "<br>\n".
                    $query = "INSERT INTO client_slab_percentage (" . implode(",", $keys) . ") VALUES (" . implode(",", $values) . ")";
                    $queryresult = runQuery($query, $conn);
                   
              //  exit;
            }
        }
    }

   

    /*
         foreach ($param as $key=>$val) {
			$value = mysqli_real_escape_string($conn, $val);
			$keys[] = "`".$key."`";
			$values[] = "'{$val}'";
		}
	
		 	$query = "INSERT INTO {$tableName} (" . implode(",", $keys) . ") VALUES (" . implode(",", $values) . ")";
			$queryresult = runQuery($query, $conn);
    */
}

$logMsg = "client_slab_percentage info saved successfully.";
$logData["step6"]["data"] = "6. {$logMsg}";
$logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
$commit = commitTransaction($conn);
$returnArr["errCode"] = -1;
$returnArr["errMsg"] = getErrMsg(-1) . " Percentage Shares  Info saved successfuly.";
echo (json_encode($returnArr));
exit;