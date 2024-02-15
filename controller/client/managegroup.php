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
$logFileName = "client_slab_groups.json";


$paramlog['table_name'] = "client_slab_groups";
$paramlog['file_name'] = '';
$paramlog['status_name'] = "Insert-update";
$paramlog['status_flag'] = "Start";
$paramlog['date_added'] = date("Y-m-d H:is:");
$paramlog['ip_address'] = get_client_ip();
$paramlog['login_user'] = $_SESSION["userEmail"];
$paramlog['log_file'] = $logStorePaths["clients"];
$paramlog['raw_data'] = json_encode($_POST);
activitylogs($paramlog, $conn);


$logMsg = "Add/edit client_slab_groups controller process start.";
$logData['step1']["data"] = "1. {$logMsg}";

$logMsg = "Database connection successful. Lets validate inputs.";
$logData["step2"]["data"] = "2. {$logMsg}";

$logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
$group_name = "";
if (isset($_POST["group_name"])) {
    // $group_name = cleanQueryParameter($conn, cleanXSS(strtolower($_POST["group_name"])));
    $group_name = cleanQueryParameter($conn, cleanXSS(strtolower($_POST["group_name"])));
}

if (empty($group_name)) {
    $logMsg = "Client Code field empty: " . json_encode($_POST);
    $logData["step2.1"]["data"] = "2.1. {$logMsg}";
    $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

    $returnArr["errCode"] = 4;
    $returnArr["errMsg"] = getErrMsg(4) . "content_owner group name cannot be empty";
    echo (json_encode($returnArr));
    exit;
}

//need client resident field
$group_name_old = "";
if (isset($_POST["group_name_old"])) {
    $group_name_old = cleanQueryParameter($conn, cleanXSS($_POST["group_name_old"]));
}
$editMode = true;
if (empty($group_name_old)) {
    $editMode = false;
    $logMsg = "Request is to add a new group name: " . json_encode($_POST);
    $logData["step1.1"]["data"] = "1.1. {$logMsg}";
} else {
    $logMsg = "Request is to edit an existing group name: " . json_encode($_POST);
    $logData["step1.1"]["data"] = "1.1. {$logMsg}";
}


if ($editMode === true) {
    if (strtolower($group_name) != strtolower($group_name_old)) {
        //check if client already exists with this client code
       
        $clientInfo = getClientsGroup($conn,$group_name);
        if (!noError($clientInfo)) {
            //error fetching client info
            $logMsg = "Error fetching client info to check for duplicate: {$clientInfo["errMsg"]}";
            $logData["step5.1"]["data"] = "5.1. {$logMsg}";
            $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
            $rollback = rollbackTransaction($conn);
            $returnArr["errCode"] = 5;
            $returnArr["errMsg"] = getErrMsg(5) . " Error finding client info. Please try again after some time.";
            echo (json_encode($returnArr));
            exit;
        }

        if (!empty($clientInfo["errMsg"])) {
            //client with this client code already exists
            $logMsg = "group name  already exists: {$group_name}";
            $logData["step5.1"]["data"] = "5.1. {$logMsg}";
            $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
            $rollback = rollbackTransaction($conn);
            $returnArr["errCode"] = 9;
            $returnArr["errMsg"] = getErrMsg(9) . " group name already exists with group {$group_name}.";
            echo (json_encode($returnArr));
            exit;
        }
    }
    
    $content_owner = (isset($_POST['to'])) ? $_POST['to'] : [];
    if(1){
       $clientInfo = getClientsGroup($conn,$group_name);
       $clientInfo = $clientInfo['errMsg'];
       
        if(!empty($content_owner)){
            $content_owner_impl =  implode(", ",$content_owner);
        } else {
            $content_owner_impl = "";
        }
        

        $clientSearchArr = array("LOWER(group_name)" => strtolower($group_name));

        $arrToUpdate = array( 
            'content_owner' => $content_owner_impl
           
        );

        $updateClientInfo = updateContent_ownerGroupNameInfo($arrToUpdate, $clientSearchArr,$content_owner,$clientInfo, $conn);

        if (!noError($updateClientInfo)) {
            //client with this client code already exists
            $logMsg = "Error updating client info : {$group_name}";
            $logData["step5.1"]["data"] = "5.1. {$logMsg}";
            $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
            $rollback = rollbackTransaction($conn);
            $returnArr["errCode"] = 9;
            $returnArr["errMsg"] = getErrMsg(9) . " Error updating client info.";
            echo (json_encode($returnArr));
            exit;
        }
      
        //Everything completed successfully
        $logMsg = "Client info saved successfully.";
        $logData["step6"]["data"] = "6. {$logMsg}";
        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
        $commit = commitTransaction($conn);
        $returnArr["errCode"] = -1;
        $returnArr["errMsg"] = getErrMsg(-1) . " Client Info saved successfuly.";
        echo (json_encode($returnArr));exit;
    }
        
} else{

    $clientInfo = getClientsGroup($conn,$group_name);
    if (!noError($clientInfo)) {
        //error fetching client info
        $logMsg = "Error fetching client info to check for duplicate: {$clientInfo["errMsg"]}";
        $logData["step5.1"]["data"] = "5.1. {$logMsg}";
        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
        $rollback = rollbackTransaction($conn);
        $returnArr["errCode"] = 5;
        $returnArr["errMsg"] = getErrMsg(5) . " Error finding client info. Please try again after some time.";
        echo (json_encode($returnArr));
        exit;
    }

    if (!empty($clientInfo["errMsg"])) {
        //client with this client code already exists
        $logMsg = "group name  already exists: {$group_name}";
        $logData["step5.1"]["data"] = "5.1. {$logMsg}";
        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
        $rollback = rollbackTransaction($conn);
        $returnArr["errCode"] = 9;
        $returnArr["errMsg"] = getErrMsg(9) . " group name already exists with group {$group_name}.";
        echo (json_encode($returnArr));
        exit;
    }
    
   
    $content_owner = $_POST['to'];
    $content_owner_impl =  implode(", ",$content_owner);

    $arrToCreate = array(
        $group_name => array(
            'group_name' => "'" . $group_name . "'",
            'content_owner' => "'" . $content_owner_impl . "'",
           
        ),
    );

    $fieldsStr = array_keys($arrToCreate[$group_name]);
    $fieldsStr = implode(",", $fieldsStr);

    $createClient = createContent_ownerGroupNameInfo($group_name, $arrToCreate, $fieldsStr, $content_owner, $conn);

    if (!noError($createClient)) {
        //error creating client
        $logMsg = "Client could not be created: {$createClient["errMsg"]}";
        $logData["step5.1"]["data"] = "5.1. {$logMsg}";
        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
        $rollback = rollbackTransaction($conn);
        $returnArr["errCode"] = 5;
        $returnArr["errMsg"] = getErrMsg(5) . " Error creating client. Please try again after some time.";
        echo (json_encode($returnArr));
        exit;
    }

    $logMsg = "Client created successfully.";
    $logData["step6"]["data"] = "6. {$logMsg}";
    $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
    $commit = commitTransaction($conn);
    $returnArr["errCode"] = -1;
    $returnArr["errMsg"] = getErrMsg(-1) . " Client Info save successful.";
    echo (json_encode($returnArr));
    exit;
}

//from_amt
//to_amt
//percentage
//print_r($_POST);
 
  
 

 