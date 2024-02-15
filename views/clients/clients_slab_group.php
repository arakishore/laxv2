<?php
//Manage Clients view page
session_start();

//prepare for request
//include necessary helpers
require_once('../../config/config.php');

//check if session is active
$sessionCheck = checkSession();

//include some more necessary helpers
require_once(__ROOT__.'/config/dbUtils.php');
require_once(__ROOT__.'/config/errorMap.php');
require_once(__ROOT__.'/config/logs/logsProcessor.php');
require_once(__ROOT__.'/config/logs/logsCoreFunctions.php');
require_once(__ROOT__.'/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

//include necessary models
require_once(__ROOT__.'/model/user/userModel.php');
require_once(__ROOT__.'/model/client/clientModel.php');
require_once(__ROOT__.'/model/distributor/distributorModel.php');


//Connection With Database
$conn = createDbConnection($host, $dbUsername, $dbPassword, $dbName);
 

if (!noError($conn)) {
    //error connecting to DB
    $returnArr["errCode"] = 1;
    $returnArr["errMsg"] = getErrMsg(1).$conn["errMsg"];
} else {
    $conn = $conn["errMsg"];

    $returnArr = array();
    //get the user info
    $email = $_SESSION['userEmail'];

    //initialize logs
    $logsProcessor = new logsProcessor();
    $initLogs = initializeJsonLogs($email);
    $logFilePath = $logStorePaths["clients"];
    $logFileName="viewClients.json";

    $logMsg = "View Clients process start.";
    $logData['step1']["data"] = "1. {$logMsg}";

    $logMsg = "Database connection successful.";
    $logData["step2"]["data"] = "2. {$logMsg}";

    $logMsg = "Attempting to get user info.";
    $logData["step3"]["data"] = "3. {$logMsg}";

    $clientStatusMap = array(
        "1" => "Active",
        "0" => "Inactive",
        "2" => "Deleted"
    );
    
    $userSearchArr = array('email'=>$email);
    $fieldsStr = "email, status, image, `groups`, rights, firstname, lastname";
    $userInfo = getUserInfo($userSearchArr, $fieldsStr, $conn);
    if (!noError($userInfo)) {
        //error fetching user info
        $logMsg = "Couldn't fetch user info: {$userInfo["errMsg"]}";
        $logData["step3.1"]["data"] = "3.1. {$logMsg}";
        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

        $returnArr["errCode"] = 5;
        $returnArr["errMsg"] = getErrMsg(5).": Error fetching user details.";
    } else {
        //check if user not found
        $userInfo = $userInfo["errMsg"];
        if (empty($userInfo)) {
            //user not found
            $logMsg = "User not found: {$token}";
            $logData["step3.1"]["data"] = "3.1. {$logMsg}";
            $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

            $returnArr["errCode"] = 5;
            $returnArr["errMsg"] = getErrMsg(5).": This URL is invalid or expired.";
        } else {
            //check if user is active
            //first get the user email
            $email = array_keys($userInfo);
            $email = $email[0];
            if ($userInfo[$email]["status"]!=1) {
                //user not active
                $logMsg = "User not active: {$token}";
                $logData["step3.1"]["data"] = "3.1 {$logMsg}";
                $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

                $returnArr["errCode"] = 5;
                $returnArr["errMsg"] = getErrMsg(5).": This URL is invalid or expired.";
            } else {
                //user is found and is active. Now validate the request parameters
                //pagination parameters
                $page = 1;
                if (isset($_GET['page']) && !empty($_GET["page"])) {
                    $page = preg_replace('#[^0-9]#i', '', $_GET['page']);
                }
                $resultsPerPage = RESULTSPERPAGE;
                $offset = ($page - 1) * $resultsPerPage;
                
                $logMsg = "Attempting to get count of all clients.";
                $logData["step4"]["data"] = "4. {$logMsg}";
                
                //set the search array based on get parameters
                $clientSearchArr = array("1"=>1);
            
                $Keyword = (isset($_GET['Keyword'])) ? $_GET['Keyword'] :'';
                if($Keyword!=""){
                    $clientSearchArr = array("email"=>$Keyword);  
                } else {
                   
                    $clientSearchArr = array("1"=>1);  
                }

                 if (isset($_GET["userName"]) && !empty($_GET["userName"])) {
                     $clientSearchArr["client_username"] = cleanQueryParameter($conn, cleanXSS($_GET["userName"]));
                 }
                
                
               
              
                $fieldsStr = "COUNT(*) as noOfClients";
                $allClientsCount = slabGetClients($clientSearchArr, $fieldsStr, $conn);
            
                if (!noError($allClientsCount)) {
                    //error fetching all clients Count
                    $logMsg = "Couldn't fetch all clients Count: {$allClientsCount["errMsg"]}.".
                                "Search params: ".json_encode($clientSearchArr);
                    $logData["step4.1"]["data"] = "4.1. {$logMsg}";
                    $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
                    
                    $returnArr["errCode"] = 5;
                    $returnArr["errMsg"] = getErrMsg(5)." Error fetching client details.";
                } else {
                     
                    $allClientsCount = $allClientsCount["errMsg"][0]["noOfClients"]; //why anonymous? see function definition
                    // printArr($allClientsCount);
                    //set the last page num
                    $lastPage = ceil($allClientsCount / $resultsPerPage);
                    // printArr($lastPage);

                    if ($page <= 1) {
                        $page = 1;
                    } else if ($page > $lastPage) {
                        $page = $lastPage;
                    }

                    $logMsg = "Got all clients count for page: {$page}. Now getting all clients info";
                    $logData["step5"]["data"] = "5. {$logMsg}";
                    
                    $fieldsStr = "*";
                    //set different getter arguments if it is in export mode
                    $export = false;
                    
                
                    $allClientsInfo = slabGetClients(
                        $clientSearchArr,
                        $fieldsStr,
                       
                        $conn,
                        $offset,
                        $resultsPerPage
                    );
                
                      if (!noError($allClientsInfo)) {
  
                        //error fetching all clients info
                        $logMsg = "Couldn't fetch all clients info: {$allClientsInfo["errMsg"]}";
                        $logData["step5.1"]["data"] = "5.1. {$logMsg}";
                        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
                        
                        $returnArr["errCode"] = 5;
                        $returnArr["errMsg"] = getErrMsg(5)." Error fetching clients details.";
                    } else {
                        $logMsg = "Got all clients data for page: {$page}";
                        $logData["step6"]["data"] = "6. {$logMsg}";
                        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

                       

                        $allClientsInfo = $allClientsInfo["errMsg"];
                       // print_r($allClientsInfo);
                       
                    
                        $returnArr["errCode"] = -1;
                    } //close getting all clients info
                } //close getting all clients count
            } //close checking if user is active
        } // close checking if user is found
    } // close user info
} //close db conn
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?php echo $rootUrl; ?>assets/img/nirvana_favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        <?php echo APPNAME; ?>
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="<?php echo $rootUrl; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $rootUrl; ?>assets/css/material-dashboard.css?v=1.2.0" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Work+Sans:400,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo $rootUrl; ?>assets/css/style.css">
    <script src="<?php echo $rootUrl; ?>assets/js/jquery.min.js" type="text/javascript"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> -->
    <script src="<?php echo $rootUrl; ?>assets/js/jquery.multi-selection.v1.js" type="text/javascript"></script>

    <style>
    #formshare .form-control {
        height: 30px !important;
        padding: 2px 0 !important;
        font-size: 14px !important;
        line-height: 1.42857 !important;
    }

    #formshare .table>thead>tr>th,
    .table>tbody>tr>th,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>td,
    .table>tfoot>tr>td {
        padding: 2px 2px !important;
        vertical-align: middle;
    }

    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow: 0px 0px 0px 0px #000;
        box-shadow: 0px 0px 0px 0px #000;
    }

    .form-group {
        padding-bottom: 10px;
        margin: 1px 0 0 0;
    }

    select.form-control[multiple],
    .form-group.is-focused select.form-control[multiple] {
        height: 185px;
    }

    .jp-multiselect select {
        max-height: 230px;
    }

    .jp-multiselect {
        display: block;
    }

    .jp-multiselect select {
        width: 100%;
    }

    .jp-multiselect .from-panel {
        float: left;
        width: 45%;
    }

    .jp-multiselect .from-panel option {
        width: 100%;
        display: block;
    }

    .jp-multiselect .move-panel {
        float: left;
        width: 10%;
    }

    .jp-multiselect .to-panel {
        float: left;
        width: 45%;
        ;
    }

    .jp-multiselect .to-panel option {
        width: 100%;
        display: block;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php 
            $pageTitle = "Manage Slab";
            require_once(__ROOT__.'/controller/access-control/checkUserAccess.php');
            require_once(__ROOT__."/views/common/sidebar.php");
        ?>
        <div class="main-panel">
            <?php 
                require_once(__ROOT__."/views/common/header.php");
            ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- card header and breadcrumbs -->
                                <div class="card-header">
                                    <h4 class="title">
                                        <?php echo cleanXSS($pageTitle); ?>
                                    </h4>

                                </div> <!-- end card header -->
                                <div class="card-content">
                                    <!-- success/error messages -->
                                    <?php
                                    $alertMsg = "";
                                    $alertClass = "alert-success";
                                    if (!noError($returnArr)) {
                                        $alertClass = "alert-danger";
                                        $alertMsg = $returnArr["errMsg"];
                                    ?>
                                    <div class="alert <?php echo $alertClass; ?>">
                                        <span>
                                            <?php echo $alertMsg; ?>
                                        </span>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <!-- end success/error messages -->

                                    <!-- Search row -->
                                    <div class="col-md-6">
                                        <form enctype="multipart/form-data" class="form-inline searchForm"
                                            action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                                            <!-- client search drop down -->
                                            <div class="form-group">
                                                <?php
                                                $clientsSearchArr = array("status"=>1);
                                                $fieldsStr = "email, client_username, client_firstname";
                                                $allClients = getClientsInfo($clientsSearchArr, $fieldsStr, null, $conn);
                                                if (!noError($allClients)) {
                                                    printArr("Error fetching all clients");
                                                    exit;
                                                }
                                                $allClients = $allClients["errMsg"];
                                               
                                                ?>

                                                <select name="userName" id="userName" class="form-control">
                                                    <option value="">Select Client</option>
                                                    <?php
                                                    foreach ($allClients as $clientEmail => $clientDetails) {
                                                        $selected = "";
                                                        if (isset($clientSearchArr["client_username"]) && ($clientDetails['client_username']==$clientSearchArr["client_username"])) {
                                                            $selected = "selected='selected'";
                                                        }
                                                    ?>
                                                    <option <?php echo $selected; ?>
                                                        value="<?php echo $clientDetails['client_username']; ?>">
                                                        <?php echo $clientDetails['client_username']."-".$clientDetails['client_firstname']; ?>
                                                    </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <!-- search button -->
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success fa fa-search">
                                                </button>
                                            </div>
                                            <!-- end search button -->

                                        </form>
                                    </div>
                                    <div class="col-md-4">

                                    </div>

                                    <!-- Search row -->


                                    <!-- Clients table -->
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Group Name</th>
                                                <th>Content Owners</th>

                                                <th>Share Youtube Structure</th>

                                                <?php
                                                //if user has write access, show Actions col
                                                if ($userHighestPermOnPage == 2) {
                                                ?>
                                                <th>Actions</th>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                             
                                            foreach($allClientsInfo as $key => $clientDetails){ 

                                                 
                                                //$getClientsSlabInfo =  explode(",",$clientDetails['content_owner']);
                                                $content_owner_assigned =  str_replace(","," , ",$clientDetails['content_owner']);

                                                $getClientsSlabInfo = getClientsSlabViaGroupName(
                                                    $conn,
                                                    $clientDetails["group_name"]
                                                );  

                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $clientDetails["group_name"]; ?></td>

                                                <td><?php echo $content_owner_assigned; ?></td>
                                                <td> <?php
                                                    if(!empty($getClientsSlabInfo["errMsg"])){
                                                        $getClientsSlabInfo = $getClientsSlabInfo["errMsg"];

                                                     
                                                ?>
                                                    <table class="table">

                                                        <tbody>
                                                            <?php
                                                            foreach($getClientsSlabInfo as $key => $shareInfo){ 
                                                            // print_r($shareInfo);
                                                            ?>
                                                            <tr>


                                                                <td>From
                                                                    <?php echo (isset($shareInfo['from_amt'])) ? $shareInfo['from_amt'] : '';?>
                                                                </td>
                                                                <td>Upto
                                                                    <?php echo (isset($shareInfo['to_amt'])) ? $shareInfo['to_amt'] : '';?>
                                                                </td>
                                                                <td><?php echo (isset($shareInfo['percentage'])) ? $shareInfo['percentage'] : '';?>%
                                                                </td>
                                                            </tr>
                                                            <?php }?>
                                                        </tbody>
                                                    </table>
                                                    <?php }?>
                                                </td>

                                                <td>
                                                <a href="javascript:void(0);"    class="ls-modal btn btn-xs btn-success" onclick="showAddClientForm('<?php echo htmlentities(trim($clientDetails['group_name'])); ?>','<?php echo htmlentities(trim($clientDetails['id'])); ?>', 'Percentage Share for <?php echo $clientDetails['group_name']?>');"
                                                        >
                                                            <span class="fa fa-edit"></span>
                                                        </a> 
                                                </td>

                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <!-- end Clients table -->
                                    <!-- pagination -->
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            <?php
                                            
                                            if($page>1){
                                            ?>
                                            <li class="page-item"><a class="page-link" href="?page=1">&laquo;</a></li>
                                            <li class="page-item"><a class="page-link"
                                                    href="?page=<?php echo ($page-1); ?>">Prev</a></li>
                                            <?php
                                            }
                                            //loop through the pagination range after setting it to display page numbers
                                            if ($page == 1) {
                                                $startLoop = 1;
                                                $endLoop = ($lastPage < PAGINATIONRANGE) ? $lastPage : PAGINATIONRANGE;
                                            } else if ($page == $lastPage) {
                                                    $startLoop = (($lastPage - PAGINATIONRANGE) < 1) ? 1 : ($lastPage - PAGINATIONRANGE);
                                                    $endLoop = $lastPage;
                                            } else {
                                                    $startLoop = (($page - PAGINATIONRANGE) < 1) ? 1 : ($page - PAGINATIONRANGE);
                                                    $endLoop = (($page + PAGINATIONRANGE) > $lastPage) ? $lastPage : ($page + PAGINATIONRANGE);
                                            }
                                        
                                            for ($i = $startLoop; $i <= $endLoop; $i++) {
                                                $activeClass = ($i==$page)?"active":"";                                                        
                                            ?>
                                            <li class="page-item <?php echo $activeClass; ?>"><a class="page-link"
                                                    href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if($page<$lastPage){	
                                            ?>
                                            <li class="page-item"><a class="page-link"
                                                    href="?page=<?php echo ($page+1); ?>">Next</a></li>
                                            <li class="page-item"><a class="page-link"
                                                    href="?page=<?php echo ($lastPage); ?>">&raquo;</a></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </nav>
                                    <!-- end pagination -->
                                </div> <!-- end card content -->
                            </div> <!-- end card -->
                        </div> <!-- end col md 12 -->
                    </div> <!-- end row -->
                </div> <!-- end container fluid -->
            </div> <!-- end content -->
        </div> <!-- end main panel -->
    </div> <!-- end wrapper -->
    <?php
    //if user has write access, keep add+delete modal and related scripts
    if ($userHighestPermOnPage == 2) {
    ?>
    <!-- delete Client modal -->
    
    <!-- end delete client modal -->

    <!-- add client modal -->
    <div class="modal fade modal-lg" id="addClientModal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span></span> Group Name</h4>
            </div>
            <div class="modal-body">
                <div class="alert" style="display: none">
                    <span></span>
                </div>
                <div class="modal-body-content"></div>
            </div>
        </div>
    </div>
    <!-- end add client modal -->

    <?php
    }
    //include the loader
    require_once(__ROOT__."/views/common/loader.php");
    ?>
    <!--   Core JS Files   -->
    <script src="<?php echo $rootUrl; ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo $rootUrl; ?>assets/js/material.min.js" type="text/javascript"></script>
    <script src="<?php echo $rootUrl; ?>assets/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="<?php echo $rootUrl; ?>assets/js/parsley.js"></script>
    <!--<script src="<?php echo $rootUrl; ?>assets/js/material-dashboard.js"></script>-->


    <script>
        function showAddClientForm(group_name,group_id, actionType)
            {
                $("#addClientModal .modal-title span").html(actionType);
                $("#addClientModal .modal-body-content").load(
                    "<?php echo $rootUrl; ?>views/clients/manage/manageslabviagroup.php?group_name="+encodeURIComponent(group_name)+"&group_id="+encodeURIComponent(group_id)
                );
                $("#addClientModal").modal();
            }
    </script>


</body>

</html>