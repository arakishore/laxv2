<?php
//Manage Clients view page
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
require_once(__ROOT__.'/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php');
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

//include necessary models
require_once(__ROOT__.'/model/user/userModel.php');
require_once(__ROOT__.'/model/activate/activateModel.php'); 
require_once(__ROOT__.'/model/distributor/distributorModel.php');
require_once(__ROOT__.'/model/validate/validateModel.php');
require_once(__ROOT__.'/model/client/clientDashboardModel.php');
require_once(__ROOT__.'/model/client/clientModel.php');
//Connection With Database
$conn = createDbConnection($host, $dbUsername, $dbPassword, $dbName);
if (!noError($conn)) {
    //error connecting to DB
    $returnArr["errCode"] = 1;
    $returnArr["errMsg"] = getErrMsg(1).$conn["errMsg"];
} else {
    $conn = $conn["errMsg"];
    $returnArr = array();
    $email = $_SESSION['userEmail'];
     //initialize logs
     $logsProcessor = new logsProcessor();
     $initLogs = initializeJsonLogs($email);
     $logFilePath = $logStorePaths["clients"];
     $logFileName="viewClients.json";


     //check current month data avaialbe for client 
     $dataavaiableforthismonth= false;
     $clientSearchArr = array('email'=>$email);
     $fieldsStr = "client_username, email";
     $clientInfo = getClientsInfo($clientSearchArr, $fieldsStr, null, $conn);
     
     if (!noError($clientInfo)) {
         //error fetching latest client info
         $logMsg = "Error Fetching client info: ".$clientInfo["errMsg"];
         $logData["step5.1"]["data"] = "5.1. {$logMsg}";
         $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

         $returnArr["errCode"] = 5;
         $returnArr["errMsg"] = getErrMsg(5)." Could not get client Info for {$email}.";
     } else {
        $clientname = $clientInfo['errMsg'][$email]['client_username'];
        $allfantable = getAvilableActivateReports('amazon_video_activation', $clientname ,$conn);
        //print_r($allfantable);
        if (!noError($allfantable)) {
         //error fetching latest client info
         $logMsg = "Error Fetching client info: ".$allfantable["errMsg"];
         $logData["step5.1"]["data"] = "5.1. {$logMsg}";
         $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);

         $returnArr["errCode"] = 5;
         $returnArr["errMsg"] = getErrMsg(5)." Could not get client Info for {$email}.";
        }else{
         $allfantable = $allfantable['errMsg']; 
        // print_r($allfantable);
         if(!empty($allfantable)){
            if (in_array($_GET['reportMonthYear'], $allfantable)){
                $dataavaiableforthismonth= true;
             }
         }
        }
     }

    
     if(!$dataavaiableforthismonth){
        header('Location:'.$rootUrl.'views/dashboard/client');
     }
    

     //end check current month data 

    $selectedDate = $_GET["reportMonthYear"];  
    $year     = date("Y", strtotime($selectedDate));
    $month    = date("m", strtotime($selectedDate)); 
    $finance_report_table = 'amazon_video_report_'.$year.'_'.$month;
    $youtube_report_table = 'amazon_video_report_'.$year.'_'.$month;
    $activatetableName  = 'amazon_video_activation_report_'.$year.'_'.$month;
 

    //get current client 
    $myclient = getClientsInfo(
        ['email'=>$_SESSION['userEmail']],
        'email,client_username',
        null,
        $conn
    );

      if (!noError($myclient)) {
        
        $returnArr["errCode"] = 5;
        $returnArr["errMsg"] = getErrMsg(5)." Error fetching clients details.";
        echo json_encode($returnArr);exit;
    }
    $myclientname =current($myclient['errMsg']);
    $myclientname =$myclientname['client_username'];
    $_SESSION['client'] =  $myclientname;


    //get all chennelid of this report 

    $getchennels = getChannelsofthereportAmazon($finance_report_table, $conn ,'', $_SESSION['client']);
    
    
    if (!noError($getchennels)) {

    //error fetching all clients info
    $logMsg = "Couldn't fetch all clients info: {$getchennels["errMsg"]}";
    $logData["step5.1"]["data"] = "5.1. {$logMsg}";
    $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
    
    $returnArr["errCode"] = 5;
    $returnArr["errMsg"] = getErrMsg(5)." Error fetching get chennels details.";
    
    }
    $getallchennels = $getchennels['errMsg']; 
 
                     //set different getter arguments if it is in export mode
                $export = false;
                if (isset($_GET["export"])) {
                        $export = true;
                        $offset = 0;
                        $resultsPerPage = 1;
                        $fieldsStr = "*";
                    
                
                  
                     $allClientsInfocount = getClientsAmazonReportCount($finance_report_table, $youtube_report_table ,$conn);
            
                      if (!noError($allClientsInfocount)) {
  
                        //error fetching all clients info
                        $logMsg = "Couldn't fetch all clients info: {$allClientsInfocount["errMsg"]}";
                        $logData["step5.1"]["data"] = "5.1. {$logMsg}";
                        $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
                        
                        $returnArr["errCode"] = 5;
                        $returnArr["errMsg"] = getErrMsg(5)." Error fetching clients details.";
                    } else {  
                         $allClientsInfocount = $allClientsInfocount["errMsg"][0];
                         $allClientsInfo =$allClientsInfo["errMsg"];
                          
                          
                         $total=$allClientsInfocount['total']; 
                       
                         if ($export) {

                            $logMsg = "Request is to export all clients data to excel.";
                            $logData["step7"]["data"] = "7. {$logMsg}";
                            ob_clean();ob_flush();
                            $xcelfiles = [];
                           // $headtitle = ['Video title','seasonID','Amt Received','Final Payable'];
                            $headtitle = ['Session Id','Video title','Royalty Amt','Exchange Rate','Rev With Holding','With Holding','Client Share','Final Payable'];
                            $noofexcels = $total>100000 ? ceil($total/100000) : 1;
                            
                            for($i=0;$i<$noofexcels;$i++){
                                $spreadsheet = new Spreadsheet();
                                $spreadsheet->setActiveSheetIndex(0);
                                $activeSheet = $spreadsheet->getActiveSheet();
                                
                                //add header to spreadsheet
                                $header = array_keys($allClientsInfo);
                                
                                $header = $header[0];
                                $header = $headtitle;
                                $header = array_values($header);
                                $activeSheet->fromArray([$header], NULL, 'A1');
    
                                //add each client to the spreadsheet
                                $clients = array();
                                $startCell = 2; //starting from A2

                                $offset=$i==0 ? $i : $i*100000;
                                $resultsPerPage = $noofexcels>1 ? 100000 : $total;
                                $allClientsInfo  = getClientsAmazonReport($finance_report_table, $youtube_report_table ,$conn,$offset,$resultsPerPage,$searchdata);
                                $allClientsInfo = $allClientsInfo["errMsg"];
                                
                                foreach($allClientsInfo as $clientEmail=>$clientDetails) {
                                    $client = array_values($clientDetails);
                                    $activeSheet->fromArray([$client], NULL, 'A'.$startCell);
                                    $startCell++;
                                }
                                

                                //auto width on each column
                                $highestColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn();

                                foreach (range('A', $highestColumn) as $col) {
                                    $spreadsheet->getActiveSheet()
                                            ->getColumnDimension($col)
                                            ->setAutoSize(true);
                                }

                                //style the header and totals rows
                                $styleArray = [
                                    'font' => [
                                        'bold' => true,
                                        'color'=>array('argb' => 'FFC5392A'),
                                    ],
                                    'alignment' => [
                                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                                    ],
                                    'borders' => [
                                        'top' => [
                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                                        ]
                                    ]
                                ];
                                $spreadsheet->getActiveSheet()->getStyle('A1:'.$highestColumn.'1')->applyFromArray($styleArray);
                                
                                // //download the file
                                $filename = "Amazon_Client_data_".$year.'_'.$month.'_'.$i;
                                $xcelfiles[]=$filename;

                                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                                ob_clean();
                                $writer->save($filename.'.xlsx');
                            }
                            //save to zip
                            $zip_file_tmp="testzip";
                            $zip = new ZipArchive();
                            $zip->open($zip_file_tmp, ZipArchive::CREATE);
                            foreach ($xcelfiles as $file) {
                               $zip->addFile($file.'.xlsx');
                            }
                            $zip->close();
                                        
                            $download_filename = 'Client_Dashboard_Amazon_'.$year.'_'.$month.'.zip'; 
                            header("Content-Type: application/zip"); 
                            header("Content-Length: " . filesize($zip_file_tmp));
                            header('Content-disposition: attachment; filename='.$download_filename);
                            readfile($zip_file_tmp);
                            unlink($excel_file_tmp);
                            unlink($zip_file_tmp);
                            foreach ($xcelfiles as $file) {
                                  unlink($file.'.xlsx');
                             }
                            $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
                            exit;
                        }
                    
                        $returnArr["errCode"] = -1;
                    }
                }
   }

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.7/css/dataTables.checkboxes.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.21/datatables.min.css" />

    <script src="<?php echo $rootUrl; ?>assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.7/js/dataTables.checkboxes.min.js"
        type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css"
        rel="stylesheet" />


</head>
<style>
.nav-pills>li.active>a,
.nav-pills>li.active>a:focus,
.nav-pills>li.active>a:hover {
    color: white;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: rgba(255, 255, 255, .8) url('http://i.stack.imgur.com/FhHRx.gif') 50% 50% no-repeat;
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading .modal {
    overflow: hidden;
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal {
    display: block;
}
</style>

<body>


    <?php /*
                                    $alertMsg = "";
                                    $alertClass = "";
                                    if (!noError($returnArr)) {
                                        $alertClass = "alert-danger";
                                        $alertMsg = $returnArr["errMsg"];
                                    ?>
    <div class="alert <?php echo $alertClass; ?>" style="display: none">
        <span>
            <?php echo $alertMsg; ?>
        </span>
    </div>
    <?php
                                    } */
                                    ?>

    <!--Loading new page-->
    <div class="header" id="youtube1">


        <div class="row">
            <div class="col-lg-1">
                <div class="form-group" style="margin:10px; ">
                    <button type="button" data-dismiss="modal" style="float:left; padding:5px; font-size:15px;">
                        <a style="color:white;" href="../client/"><i style="font-size:20px;"
                                class="fa fa-arrow-left"></i>
                        </a></button>
                </div>
            </div>
            <div class="col-lg-3">


                <h4 class="modal2-title">Amazon</h4>


            </div>

        </div>
    </div>


    <!-- choose field drowpdoun-->
    <div class="col-md-12">




        <!-- <form enctype="multipart/form-data" class="form-inline searchForm" action="<?php echo $_SERVER['PHP_SELF']; ?>"
            method="GET">
            <input type="hidden" name="reportMonthYear"
                value="<?php echo htmlspecialchars($_GET['reportMonthYear']);?>">

            <div class="form-group">
                <button type="submit" name="export" class="btn btn-warning fa fa-file-excel-o">
                </button>
            </div>

        </form> -->
        <div id="alert" class="alert alert-default" style="display: none;">

        </div>
        <button type="submit" name="export" id="export" class="btn btn-warning fa fa-file-excel-o">
        </button>

        <?php
        $fileis= $_SESSION['client'].'_Amazon_dashboard_'.$year.'_'.$month.'.zip';
        
            if(file_exists('../../../'.$fileis)){?>
        <a href='../../../<?=$fileis?>'>Download zip</a>
        <?php }
        ?>

    </div>
    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



    <div class="col-md-12">


        <select name="channelId" id="channelId">
            <option value="" selected>All Channels</option>
            <?php foreach ($getallchennels as $key => $value) { ?>
            <option value="<?=$value['seasonID'];?>"><?=$value['seasonID'];?> <?=$value['titleName'];?></option>
            <?php } ?>
        </select>
        <input type="submit" value="search" id="search" name="submit">

        <ul class="nav nav-pills">
            <li class="active"><a data-toggle="pill" href="#home">Revenue</a></li>
            <li><a data-toggle="pill" href="#menu1">Views</a></li>

        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <h4>Revenue of the month <?=date('F', mktime(0, 0, 0, $month, 10)).'-'.$year?></h4>
                <p>

                <div id="revenue_chart_div"></div>
                </p>
            </div>
            <div id="menu1" class="tab-pane fade">
                <h4>Views of the month <?=date('F', mktime(0, 0, 0, $month, 10)).'-'.$year?></h4>
                <p>

                <div id="views_chart_div" style="width: 900px; height: 300px;"></div>
                </p>
            </div>

        </div>

    </div>
    <!--main table page-->
    <div class="post-search-panel">
        <input type="hidden" id="searchInput" placeholder="Type keywords..." />

    </div>
    <?php 
  
  $sumoftotalpayout = 0;
  $sumoftotalfinalpayable = 0;
  $clientSearchArr = array("content_owner"=>$_SESSION['client']);
  $allClientsInfo = getActivationReport(
     $activatetableName,
     $clientSearchArr,
     '*',
     null,
     $conn 
 );

   if (!noError($allClientsInfo)) {

     //error fetching all clients info
     $logMsg = "Couldn't fetch all activation info: {$allClientsInfo["errMsg"]}";
     $logData["step5.1"]["data"] = "5.1. {$logMsg}";
     $logsProcessor->writeJSON($logFileName, $logFilePath, $logData, $initLogs["activity"]);
     
     $returnArr["errCode"] = 5;
     $returnArr["errMsg"] = getErrMsg(5)." Error fetching activation details.";
 }else{
     if(!empty($allClientsInfo)){
        $activationdata = $allClientsInfo['errMsg'][0];
     }
     
 }
 //seasonID,ASIN,royaltyAmount, royaltyCurrency, exchangeRate, revINRwithoutHolding, withHolding, amountReceived,clientShare,payable
  ?>
    <table id="example" class="table table-striped table-hover" style="width:100%">
        <thead>
            <tr>
                <th>seasonID</th>

                <th>titleName</th>
                <th>royaltyAmount</th>
                <th>royaltyCurrency</th>
                <th>exchangeRate</th>
                <th>revINRwithoutHolding</th>
                <th>withHolding</th>
                <th>amountReceived</th>
                <th>clientShare</th>
                <th>Final Payable</th>
            </tr>
        </thead>
        <tfoot>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>Total Amaon Payout : <?=$activationdata['total_amt_recd']?> INR</th>
            <th>Final Payable : <?=$activationdata['final_payable']?> INR</th>
        </tfoot>

    </table>

    <?php 
 
  ?>
    <div class="col-md-12">
        <div class="alert alert-default">
            <h5>total_amt_recd : <?=$activationdata['total_amt_recd']?> </h5>
            <h5>witholding : <?=$activationdata['witholding']?> </h5>
            <h5>amt_recd : <?=$activationdata['amt_recd']?> </h5>
            <h5>shares : <?=$activationdata['shares']?> </h5>
            <h5>amt_payable : <?=$activationdata['amt_payable']?> </h5>
            <h5>final_payable : <?=$activationdata['final_payable']?> </h5>

        </div>
    </div>



</body>

<script>
$(document).ready(function() {

    ///chart code start
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(ViewsdrawChart);

    var jsonData = $.ajax({
        url: "../../../controller/client/dashboard/chart/getAmazonChartData.php",
        dataType: "json",
        data: {
            selected_date: '<?php echo $_GET["reportMonthYear"];?> '
        },
        async: false
    }).responseText;
    jsonData = JSON.parse(jsonData);

    function drawChart() {

        
        var revenuedata = JSON.stringify( jsonData.revenue);
        console.log("jsonData",revenuedata);
       // var data = new google.visualization.DataTable(jsonData.revenue);
        
       

      // var revchart = new google.visualization.ColumnChart(document.getElementById('revenue_chart_div'));
        // var viewchart = new google.visualization.LineChart(document.getElementById('views_chart_div'));
        var data = google.visualization.arrayToDataTable(jsonData.revenue);

        
        var chart = new google.visualization.ColumnChart(document.getElementById('revenue_chart_div'));
        chart.draw(data);

        //   viewchart.draw(data2, options);

       /*  var button = document.getElementById('change');

        button.onclick = function() {

            // If the format option matches, change it to the new option,
            // if not, reset it to the original format.
            options.hAxis.format === 'M/d/yy' ?
                options.hAxis.format = 'MMM dd, yyyy' :
                options.hAxis.format = 'M/d/yy';

            revchart.draw(data, options);
            //viewchart.draw(data, options);
        }; */
    }

    function ViewsdrawChart() { 
        var revenuedata = JSON.stringify( jsonData.views);
        console.log("jsonData",revenuedata);
       // var data = new google.visualization.DataTable(jsonData.revenue);
        
       

      // var revchart = new google.visualization.ColumnChart(document.getElementById('revenue_chart_div'));
        // var viewchart = new google.visualization.LineChart(document.getElementById('views_chart_div'));
        var data = google.visualization.arrayToDataTable(jsonData.views);

        
        var chart = new google.visualization.ColumnChart(document.getElementById('views_chart_div'));
        chart.draw(data);
    }

    ///end chart code

    ///end chart code

    var table = $('#example').DataTable({
        "processing": true,
        "searching": true,
        "orderCellsTop": true,
        "fixedHeader": true,
        "serverSide": true,
        "ajax": {
            "url": "../../../controller/client/dashboard/amazon.php",
            "data": function(d) {
                return $.extend({}, d, {
                    "reportMonthYear": '<?php echo $_GET["reportMonthYear"]?>'
                });
            },

        },
        "scrollY": "350px",
        "scrollCollapse": true,
        "lengthMenu": [
            [100, 200, 500],
            [100, 200, 500]
        ],


    });



    $('#export').on('click', function(e) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo $rootUrl; ?>controller/client/dashboard/export/",
            data: {
                selected_date: '<?php echo $_GET["reportMonthYear"];?> ',
                type: 'amazon_video_report_co_dashboard'
            },
            success: function(response) {
                console.log(response);
                //handle error in response
                if (response["errCode"]) {
                    if (response["errCode"] != "-1") {

                        $("#alert").css("display", "block");
                        //there was an error, alert the error and hide the form.
                        $("#alert").
                        removeClass("alert-success").
                        addClass("alert-danger").
                        fadeIn().
                        html(response["errMsg"]);
                        // setTimeout(function(){
                        //     window.location.reload();
                        // }, 3000);
                        // $("#uploadMISFilesContainer").hide();
                    } else {
                        $("#alert").css("display", "block");
                        $("#alert").
                        removeClass("alert-danger").
                        addClass("alert-success").
                        fadeIn().
                        html(response["errMsg"]);
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);

                    }
                }
            },
            error: function() {
                $(".alert").
                removeClass("alert-success").
                addClass("alert-danger").
                fadeIn().
                find("span").
                html("500 Internal Server Error");
            }
        });
    });

    $('#search').on('click', function(e) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "../../../controller/client/dashboard/chart/getAmazonChartData.php",
            data: {
                selected_date: '<?php echo $_GET["reportMonthYear"];?>',
                channelId: $('#channelId').val()
            },
            success: function(response) {

                jsonData = response;
                google.charts.setOnLoadCallback(drawChart);
                google.charts.setOnLoadCallback(ViewsdrawChart);
                //handle error in response
                if (response["errCode"]) {
                    if (response["errCode"] != "-1") {

                        $("#alert").css("display", "block");
                        //there was an error, alert the error and hide the form.
                        $("#alert").
                        removeClass("alert-success").
                        addClass("alert-danger").
                        fadeIn().
                        html(response["errMsg"]);
                        // setTimeout(function(){
                        //     window.location.reload();
                        // }, 3000);
                        // $("#uploadMISFilesContainer").hide();
                    } else {
                        $("#alert").css("display", "block");
                        $("#alert").
                        removeClass("alert-danger").
                        addClass("alert-success").
                        fadeIn().
                        html(response["errMsg"]);
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);

                    }
                }
            },
            error: function() {
                $(".alert").
                removeClass("alert-success").
                addClass("alert-danger").
                fadeIn().
                find("span").
                html("500 Internal Server Error");
            }
        });
    });


});
$body = $("body");

$(document).on({
    ajaxStart: function() {
        $body.addClass("loading");
    },
    ajaxStop: function() {
        $body.removeClass("loading");
    }
});
</script>
<script src="<?php echo $rootUrl; ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<div class="modal">
    <!-- Place at bottom of page -->
</div>

</html>