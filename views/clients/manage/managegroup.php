<?php
//Add/edit client view page
session_start();

//prepare for request
//include necessary helpers
require_once('../../../config/config.php');

//check if session is active
$sessionCheck = checkSession();

//include some more necessary helpers
require_once(__ROOT__.'/config/dbUtils.php');
require_once(__ROOT__.'/config/errorMap.php');
require_once(__ROOT__.'/config/logs/logsProcessor.php');
require_once(__ROOT__.'/config/logs/logsCoreFunctions.php');

//include necessary models
require_once(__ROOT__.'/model/client/clientModel.php');
require_once(__ROOT__.'/model/distributor/distributorModel.php');

$conn = createDbConnection($host, $dbUsername, $dbPassword, $dbName);
if (!noError($conn)) {
    //error connecting to DB
    $returnArr["errCode"] = 1;
    $returnArr["errMsg"] = getErrMsg(1) . $conn["errMsg"];
} else {
    $email = $_SESSION['userEmail'];
    //initialize logs
    $logsProcessor = new logsProcessor();
    $initLogs = initializeJsonLogs($email);
    $logFilePath = $logStorePaths["clients"];
    $logFileName = "manageClient.json";

    $logMsg = "Manage client process start.";
    $logData['step1']["data"] = "1. {$logMsg}";

    $logMsg = "Database connection successful.";
    $logData["step2"]["data"] = "2. {$logMsg}";

    $conn = $conn["errMsg"];
    $returnArr = array();

    $clientStatusMap = array(
        "1" => "Active",
        "0" => "Inactive",
        "2" => "Deleted",
    );

    $logMsg = "Attempting to get distributor info.";
    $logData["step3"]["data"] = "3. {$logMsg}";

 

    $logMsg = "Distributor info fetched successfully.";
    $logData["step4"]["data"] = "4. {$logMsg}";


    $client_youtube_shares_detail = array();
    $group_name = "";
    
    if (isset($_GET["group_name"])) {
        $group_name = $_GET["group_name"];
    }
    $getClientsSlabInfo = []; 
    if (!empty($group_name)) {
        
        $getClientsSlabInfo = getClientsGroup(
            $conn,
            $group_name
        );
    
          if (!noError($getClientsSlabInfo)) {

            //error fetching all clients info
            $logMsg = "Couldn't fetch all getClientsSlab info: {$getClientsSlabInfo["errMsg"]}";
            $logData["step5.1"]["data"] = "5.1. {$logMsg}";
            $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
            
            $returnArr["errCode"] = 5;
            $returnArr["errMsg"] = getErrMsg(5)." Error fetching clients details.";
        } else {
            $getClientsSlabInfo = $getClientsSlabInfo["errMsg"];
        }
 
    } else {
        
        $group_name = "";
        
    }
    $fieldsStr = "client_username, client_firstname, client_lastname, address, email, mobile_number, pan,source, status, client_type_details,client_youtube_shares";
    //set different getter arguments if it is in export mode
    $clientSearchArr = array("1"=>1);
    $dateField = null;
    $offset = null;
    $resultsPerPage = 999999999999999999;
    $allClientsInfo = getClientsInfo(
        $clientSearchArr,
        $fieldsStr,
        $dateField,
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
        $logMsg = "Got all clients data for page: managegroup.php";
        $logData["step6"]["data"] = "6. {$logMsg}";
        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
        $allClientsInfo = $allClientsInfo["errMsg"];
    }
   // print_r($allClientsInfo);
 
}
?>
 
<div class="row">
    <form id="addEditClientForm" name="addEditClientForm" action="javascript:;" data-parsley-validate="">
        <input type="hidden" class="form-control" id="group_name_old" name="group_name_old"
            value="<?php echo $group_name; ?>">
       
        <!--  success/error messages -->
        <?php
$alertMsg = "";
$alertClass = "";

if (!noError($returnArr)) {
    $alertClass = "alert-danger";
    $alertMsg = isset($returnArr["errMsg"]);
}
?>
        <div class="alert <?php echo $alertClass; ?>" style="display: none">
            <span>
                <?php echo $alertMsg; ?>
            </span>
        </div>
        <!-- end success/error messages -->
        <!-- hidden old client code fielld to define add/edit mode -->
        <div class="col-md-12 companyTypeDetails">
            <!-- Client group_name -->
            <div class="col-md-12 form-group">
                <label class="control-label">Group Name</label>
                <input type="text" class="form-control" required="" id="group_name" name="group_name"
                    value="<?php echo $group_name; ?>">
            </div>
            <div class="col-md-12">
                <div class="jp-multiselect">
                <div class="to-panel">
                <label class="control-label">Assigned Content Owner</label>
                        <select name="to[]" id="tocontentowner" class="form-control" size="8" multiple="multiple">
                        <?php
                            if(!empty($getClientsSlabInfo)){

                            
                          $content_owner_assigned =  explode(",",$getClientsSlabInfo['content_owner']);
                            
                            ?>
                             <?php
                             $j = 0;
                            foreach($content_owner_assigned as $key => $value){
                                $content_owner_assigned[$j] = strtolower(trim($value));
                                $j++;
                            ?>
                            <option value="<?php echo trim($value)?>" selected><?php echo $value?></option>
                            <?php }?>
                        <?php }?>   
                        </select>
                    </div>
                    <div class="move-panel text-center">
                    <!-- <button type="button" class="btn btn-default    btn-move-all-left"><< </button>  -->
                    <button type="button" class="btn btn-default  btn-move-selected-left"><</button>
                      <!-- <button type="button" class="btn btn-default    btn-move-all-right">>></button> -->
                    <button type="button" class="btn btn-default btn btn-default btn-move-selected-right">></button>
        
                    </div>
                    <div class="from-panel">
                        <?php
                       // print_r($content_owner_assigned);
                        ?>
                    <label class="control-label">List Of Content Owner</label>
                        <select name="from[]" class="form-control" size="8" multiple="multiple">
                           
                           <?php
                           
                            foreach($allClientsInfo as $key => $value){
                                if(!in_array($value['client_username'],$content_owner_assigned)){
                            ?>
                            <option value="<?php echo $value['client_username']?>"><?php echo $value['client_username']?></option>
                            <?php 
                                }
                        }?>
                        </select>
                    </div>
                    
                     
                </div>
            </div>
            <!-- End Client group_name -->

            <!-- submit button -->
            <div class="col-md-12 form-group">
                <button class="btn" type="submit">Save</button>
            </div>
        </div>
    </form>
</div>
<script>
$(function() {  
 $(".jp-multiselect").jQueryMultiSelection();
});
</script>
<script>
//managing the floating labels behaviour
// $("form#addEditClientForm :input").each(function() {
//     var input = $(this).val();
//     if ($.trim(input) != "") {
//         $(this).parent().removeClass("is-empty");
//     }
//     $(this).on("focus", function() {
//         $(this).parent().removeClass("is-empty");
//     })
//     $(this).on("blur", function() {
//         var input = $(this).val();
//         if (input && $.trim(input) != "") {
//             $(this).parent().removeClass("is-empty");
//         } else {
//             $(this).parent().addClass("is-empty");
//         }
//     })
// });




//handle form submit
$('form#addEditClientForm').parsley().on('field:validated', function() {
        var ok = $('.parsley-error').length === 0;
        $('.bs-callout-info').toggleClass('hidden', !ok);
        $('.bs-callout-warning').toggleClass('hidden', ok);
    })
    .on('form:submit', function() {

         //tocontentowner
         var multi = document.getElementById('tocontentowner');

        multi.value = null; // Reset pre-selected options (just in case)
        var multiLen = multi.options.length;
        for (var i = 0; i < multiLen; i++) {
            multi.options[i].selected = true;
        }

        var formData = new FormData($('#addEditClientForm')[0]);

        //resetting the error message
        $("#addEditClientForm .alert").
        removeClass("alert-success").
        removeClass("alert-danger").
        fadeOut().
        find("span").html("");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo $rootUrl; ?>controller/client/managegroup.php",
            data: formData,

            contentType: false,
            cache: false,
            processData: false,
            success: function(user) {
                console.log("user_edit-add", user);
                if (user["errCode"]) {
                    if (user["errCode"] != "-1") { //there is some error
                        $("#addEditClientForm .alert").
                        removeClass("alert-success").
                        addClass("alert-danger").
                        fadeIn().
                        find("span").
                        html(user["errMsg"]);
                    } else {
                        $("#addEditClientForm .alert").
                        removeClass("alert-danger").
                        addClass("alert-success").
                        fadeIn().
                        find("span").
                        html(user["errMsg"]);
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    }
                }
            },
            error: function(msg) {
                console.log("error", msg);
                $("#addEditClientForm .alert").
                removeClass("alert-success").
                addClass("alert-danger").
                fadeIn().
                find("span").
                html("500 internal server error");
            }

        });

    });
</script>