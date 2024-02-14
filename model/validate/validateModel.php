<?php

function getContentOwner($conn)
{
    $res = array();
    $returnArr = array();
    $extraArg = array();
    $getCOInfoQuery = "SELECT client_username FROM crep_cms_clients where   status = 1 ORDER BY client_username ASC";

    $getCOInfoQueryResult = runQuery($getCOInfoQuery, $conn);

    if (!noError($getCOInfoQueryResult)) {
        return setErrorStack($returnArr, 3, $getCOInfoQueryResult["errMsg"], null);
    }
    while ($row = mysqli_fetch_assoc($getCOInfoQueryResult["dbResource"])) {
        array_push($res, $row['client_username']);
    }
    $errMsg = $res;
    $returnArr = setErrorStack($returnArr, -1, $errMsg, $extraArg);

    return setErrorStack($returnArr, -1, $res, null);
}

function updateContentOwner($table, $contentowner, $ids, $conn)
{
    $res = array();
    $returnArr = array();
    $updateQuery = "UPDATE {$table} SET content_owner = '{$contentowner}' WHERE id IN({$ids})";
    @unlink("polo_update.txt");
    file_put_contents("polo_update.txt", $updateQuery);
    @chmod("polo_update.txt", 0777);

    $updateQueryResult = runQuery($updateQuery, $conn);
    if (noError($updateQueryResult)) {
        /* code 2024-01-18  */
        // updating holding percentage
        $result_des = array_map('strrev', explode('_', strrev($table)));
        $nd_type = getNDTYPESFORCOMAPPING($result_des[2]);

        $client_youtube_sharesQuery = "UPDATE {$table} inner join channel_co_maping on {$table}.assetChannelID = channel_co_maping.assetChannelID  SET {$table}.holding_percentage = channel_co_maping.client_youtube_shares   where  channel_co_maping.CMS ='{$nd_type}' and country='US' and {$table}.id IN({$ids}) ";
		
		@unlink('manual_assign_content_owner_shares_' . $table . date("ymd") . '.txt');
        file_put_contents('manual_assign_content_owner_shares_' . $table . date("ymd") . '.txt', print_r($client_youtube_sharesQuery,true), FILE_APPEND);
		@chmod('manual_assign_content_owner_shares_' . $table . date("ymd") . '.txt', 0777);

        $client_youtube_sharesQueryResult = runQuery($client_youtube_sharesQuery, $conn);
		$table_name_like = $result_des[6]."_".$result_des[5]."_".$result_des[4]."_".$result_des[3];
        if ($table_name_like == 'youtube_video_claim_report') {
			
            $activatetableName = "youtube_video_claim_activation_report_" . $nd_type . "_" . $result_des[1] . "_" . $result_des[0];

            $updateQuery1 = "UPDATE {$activatetableName}  m JOIN(     SELECT         content_owner,
            AVG(holding_percentage) AS holding_percentage     FROM     {$table}  r where r.country='US'  and content_owner='{$contentowner}'  GROUP BY         content_owner ) r ON     m.content_owner = r.content_owner SET     m.holding_percentage = r.holding_percentage";
            $updateQueryResult1 = runQuery($updateQuery1, $conn);

			@unlink("updateQuery1.txt");
			file_put_contents("updateQuery1.txt", $updateQuery1);
			@chmod("updateQuery1.txt", 0777);

            $updateQuery2 = "UPDATE  {$activatetableName} a,crep_cms_clients b
			set
			a.gst_percentage = b.gst_per,
			a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube')),
			a.witholding = ROUND(((a.us_payout) * a.holding_percentage/100),8),
			a.amt_payable=ROUND((a.shares * a.total_amt_recd)/100,8),
			a.final_payable= ROUND(((a.total_amt_recd - a.witholding) * a.shares / 100),8),
			a.final_payable_with_gst= ROUND(a.final_payable + (final_payable * gst_percentage / 100),8)
			where b.client_username =a.content_owner   and b.`status` =1 and a.content_owner='{$contentowner}' ";

            @unlink("updateQuery2.txt");
            file_put_contents("updateQuery2.txt", $updateQuery2);
            @chmod("updateQuery2.txt", 0777);

            $updateQueryResult2 = runQuery($updateQuery2, $conn);

        }

        //end

        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
    }
}
