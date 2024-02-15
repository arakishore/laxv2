<?php
//////////////////////////////Prepare for request/////////////////////////////////
session_start();

//require helpers
require_once('../../../config/config.php');    
require_once(__ROOT__.'/config/dbUtils.php');
require_once(__ROOT__.'/config/errorMap.php');
require_once(__ROOT__.'/config/auth.php');

//include necessary models
require_once(__ROOT__.'/model/reports/reportsModel.php');
 
require_once(__ROOT__.'/model/activate/activateModel.php');
 
//TO DO: Logs

$returnArr = array();
$fileLocation = '';
$controller = '';
$conn = createDbConnection($host, $dbUsername, $dbPassword, $dbName);
if (!noError($conn)) {
    $returnArr["errCode"] = 4;
    $returnArr["errMsg"] = "Error Connecting to DB";
    echo(json_encode($returnArr));
    exit;
} else {
    //db connection successful
    $conn = $conn["errMsg"];
    // printArr($_POST); exit;
    $selectedDate = cleanQueryParameter($conn, cleanXSS($_POST["reportMonthYear"]));
    
    $year     = date("Y", strtotime($selectedDate));
    $month    = date("m", strtotime($selectedDate));


     
  

    if ($_POST["report"] == "youtube_video_claim_activation_report") {
        $table_type_name = 'youtube_video_claim_activation_report_%'.'_'.$year.'_'.$month;
        $tableArr = updatePercentage($table_type_name, $conn);
      //  print_r($tableArr);
        if ($tableArr['errCode'] != '-1') {                
            $returnArr["errCode"] = 4;
            $returnArr["errMsg"] = "Cannot update Percentage";
            echo(json_encode($returnArr));
            exit;
        }else{
            $returnArr["errCode"] = -1;
            $returnArr["errMsg"] = "Updated Percentage";
            echo(json_encode($returnArr));
            exit;
        }
    }
        
 
    exit;
}
?>