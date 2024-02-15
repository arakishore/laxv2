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

    //attempting to get all distributors
    $distributorSearchArr = array("status" => 1);
    $fieldsStr = "distributor_id, distributor_name, email";
    $allDistributors = getDistributorsInfo($distributorSearchArr, $fieldsStr, null, $conn);
    if (!noError($allDistributors)) {
        //error getting all distributors
        $logMsg = "Error fetching distributor info: {$allDistributors["errMsg"]}";
        $logData["step3.1"]["data"] = "3.1. {$logMsg}";
        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

        $returnArr["errCode"] = 5;
        $returnArr["errMsg"] = getErrMsg(5) . $allDistributors["errMsg"];
    }
    $allDistributors = $allDistributors["errMsg"];

    $logMsg = "Distributor info fetched successfully.";
    $logData["step4"]["data"] = "4. {$logMsg}";


    $client_youtube_shares_detail = array();
    $userName = "";
    
    if (isset($_GET["userName"])) {
        $userName = $_GET["userName"];
    }
    
    if (!empty($userName)) {
        
        $getClientsSlabInfo = getClientsSlab(
            $conn,
            $userName
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
        $returnArr["errCode"] = -1;
        $userName = "";
        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
    }

    
}
?>
<div class="row">
    <form id="addEditClientForm" name="addEditClientForm" action="javascript:;" data-parsley-validate="">
    <input type="hidden" class="form-control" data-parsley-trigger="keyup"  id="userName"
                    name="userName" value="<?php echo $userName; ?>">
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
        
        <!-- Client name, pan, code row -->
        <div class="col-md-12" id="formshare">
        <fieldset class="scheduler-border">
            <legend>Share Youtube</legend>
            <?php 
            //print_r($getClientsSlabInfo);
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">From Amt</th>
                        <th scope="col">To Amt</th>
                        <th scope="col">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for($i=1;$i<6;$i++){
                    ?>
                    <tr>
                        <th scope="row"><?php echo $i?></th>
                        <td>
                        <input type="hidden" class="form-control revenueShareYoutube" id="slab_for<?php echo $i?>" name="youtube[slab_for][]" value="<?php echo (isset($getClientsSlabInfo[$i]['slab_for'])) ? $getClientsSlabInfo[$i]['slab_for'] : 'Share Youtube';?>">    
                        <input type="text" class="form-control revenueShareYoutube" id="from_amt<?php echo $i?>" name="youtube[from_amt][]" value="<?php echo (isset($getClientsSlabInfo[$i]['from_amt'])) ? $getClientsSlabInfo[$i]['from_amt'] : '';?>">
                        
                        </td>
                        <td><input type="text" class="form-control revenueShareYoutube" id="to_amt<?php echo $i?>" name="youtube[to_amt][]" value="<?php echo (isset($getClientsSlabInfo[$i]['to_amt'])) ? $getClientsSlabInfo[$i]['to_amt'] : '';?>"></td>
                        <td><input type="text" class="form-control revenueShareYoutube" id="percentage<?php echo $i?>" name="youtube[percentage][]" value="<?php echo (isset($getClientsSlabInfo[$i]['percentage'])) ? $getClientsSlabInfo[$i]['percentage'] : '';?>"></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </fieldset>
        </div>


        <!-- submit button -->
        <div class="col-md-12 form-group">
            <button class="btn" type="submit">Save</button>
        </div>
    </form>
</div>

<script>
//These will allow user to enter not more than one decimal point
$('.revenueShareYoutube')
    .keypress(function(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 8 || charCode == 37) {
            return true;
        } else if (charCode == 46 && $(this).val().indexOf('.') != -1) {
            return false;
        } else if (charCode > 31 && charCode != 46 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    });

//These will not allow user to enter alphabets in phone number fields
function restrictAlphabets(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57) || x == 8 ||
        (x >= 35 && x <= 40) || x == 46)
        return true;
    else
        return false;
}

//These will not allow to enter more than two number after decimal point
$(document).on('keydown', 'input[pattern]', function(e) {
    var input = $(this);
    var oldVal = input.val();
    var regex = new RegExp(input.attr('pattern'), 'g');

    setTimeout(function() {
        var newVal = input.val();
        if (!regex.test(newVal)) {
            input.val(oldVal);
        }
    }, 0);
});

//These return 100 if user enter more than 100
function minmax(value, min, max) {
    if (parseInt(value) < min || isNaN(parseInt(value)))
        return "";
    else if (parseInt(value) > max)
        return 100;
    else return value;
}
 

//managing the floating labels behaviour
$("form#addEditClientForm :input").each(function() {
    var input = $(this).val();
    if ($.trim(input) != "") {
        $(this).parent().removeClass("is-empty");
    }
    $(this).on("focus", function() {
        $(this).parent().removeClass("is-empty");
    })
    $(this).on("blur", function() {
        var input = $(this).val();
        if (input && $.trim(input) != "") {
            $(this).parent().removeClass("is-empty");
        } else {
            $(this).parent().addClass("is-empty");
        }
    })
});

function changeClientType(dropDownElem) {
    $(".clientTypeDetails").addClass("hidden");
    var selectedClientType = $(dropDownElem).val();
    $("#" + selectedClientType + "ClientTypeDetails").removeClass("hidden");
}

//handle form submit
$('form#addEditClientForm').parsley().on('field:validated', function() {
        var ok = $('.parsley-error').length === 0;
        $('.bs-callout-info').toggleClass('hidden', !ok);
        $('.bs-callout-warning').toggleClass('hidden', ok);
    })
    .on('form:submit', function() {
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
            url: "<?php echo $rootUrl; ?>controller/client/manageslab.php",
            data: formData,
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