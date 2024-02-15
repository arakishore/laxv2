<?php

$getClientInfoQuery = "SHOW TABLES LIKE '$table_type_name'";

$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
if (!noError($getClientInfoQueryResult)) {
    return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
}

while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
    //kisho

    foreach ($row as $k => $desinationtable) {
        $updateQuery2 = "UPDATE  {$desinationtable} a
      set a.amt_payable=ROUND((a.shares * a.total_amt_recd)/100,8),
      a.final_payable= ROUND(((a.total_amt_recd - a.witholding) * a.shares / 100),8)
       ";

        @unlink("polo_ACT_update2.txt");
        file_put_contents("polo_ACT_update2.txt", $updateQuery2);
        @chmod("polo_ACT_update2.txt", 0777);

        $updateQueryResult2 = runQuery($updateQuery2, $conn);

        $updateQuery3 = "UPDATE  {$desinationtable} a
                      set a.final_payable_with_gst= ROUND(a.final_payable + (final_payable * gst_percentage / 100),8)
                       ";

        @unlink("polo_ACT_update3.txt");
        file_put_contents("polo_ACT_update3.txt", $updateQuery3);
        @chmod("polo_ACT_update3.txt", 0777);

        $updateQueryResult3 = runQuery($updateQuery3, $conn);
    }
}
