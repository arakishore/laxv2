<?php

function getActivationReport(
    $table,
    $fieldSearchArr = null,
    $fieldsStr = "",
    $dateField = null,
    $conn,
    $offset = null,
    $resultsPerPage = 100,
    $orderBy = "content_owner"
) {
    $res = array();
    $returnArr = array();
    $whereClause = "";

    //looping through array passed to create another array of where clauses

    foreach ($fieldSearchArr as $colName => $searchVal) {
        if (!empty($whereClause)) {
            $whereClause .= " AND ";
        }

        $whereClause .= "{$colName} = '{$searchVal}'";
    }

    if (empty($fieldsStr)) {
        $fieldsStr = "*";
    }

    $getClientInfoQuery = "SELECT {$fieldsStr} FROM {$table}";
    if (!empty($whereClause)) {
        $getClientInfoQuery .= " WHERE {$whereClause}";
    }

    $getClientInfoQuery .= " ORDER BY " . $orderBy;
    if ($offset !== null) {
        $getClientInfoQuery .= " LIMIT {$offset}, {$resultsPerPage}";
    }
 
    $getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
    if (!noError($getClientInfoQueryResult)) {
        return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
    }

    /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
     *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
     */
    while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {

        $res[] = $row;
    }

    return setErrorStack($returnArr, -1, $res, null);
}

function generateActicationReport($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO
					{$desinationtable} (content_owner, total_amt_recd,us_payout,witholding)
					SELECT
						content_owner,
						ROUND(SUM(partnerRevenue),2),
						ROUND(SUM(CASE WHEN country='US' THEN partnerRevenue END),2),
						ROUND((SUM(CASE WHEN country='US' THEN partnerRevenue END)*30/100),2)
					FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";

 

    $updateQueryResult = runQuery($updateQuery, $conn);
    if (noError($updateQueryResult)) {
        $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
		                set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube')),
						a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))*a.total_amt_recd)/100,2),
						a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))/100),2)
                        where b.client_username =a.content_owner";
                        
                      
                        @unlink("polo_ACT_update.txt");
                        file_put_contents("polo_ACT_update.txt", $updateQuery);
                        @chmod("polo_ACT_update.txt", 0777);
                        
        $updateQueryResult = runQuery($updateQuery, $conn);
        if (noError($updateQueryResult)) {
            return setErrorStack($returnArr, -1, $res, null);
        } else {
            return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
        }

        //Update Content owner in claim report
        // $tb = explode('youtube_finance_report_',$sourcetable);
        // $claimtable= 'youtube_video_report_'.$tb[1];

        // $autoAssignChannelCOMapQuery = "UPDATE {$claimtable} t1, {$sourcetable} t2 SET t1.content_owner =t2.content_owner WHERE t1.video_id=t2.videoID";
        // $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
        //  if (noError($autoAssignChannelCOMapQueryResult)) {
        //     return setErrorStack($returnArr, -1, $res, null);
        // }else{
        //     return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult["errMsg"], null);
        // }

        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
    }
}
function generateActicationReportRed($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO
					{$desinationtable} (content_owner, total_amt_recd,us_payout,witholding)
					SELECT
						content_owner,
						ROUND(SUM(partnerRevenue),2),
						ROUND(SUM(CASE WHEN country='US' THEN partnerRevenue END),2),
						ROUND((SUM(CASE WHEN country='US' THEN partnerRevenue END)*30/100),2)
					FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";
    $updateQueryResult = runQuery($updateQuery, $conn);
    if (noError($updateQueryResult)) {
        // $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
        //                 set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeRed')),
        //                 a.amt_payable=(JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))*a.total_amt_recd)/100,
        //                 a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*a.shares/100),2)
        //                 where b.client_username =a.content_owner";
        $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
		                set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube')),
						a.amt_payable=(JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))*a.total_amt_recd)/100,
						a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))/100),2)
						where b.client_username =a.content_owner";
        $updateQueryResult = runQuery($updateQuery, $conn);
        if (noError($updateQueryResult)) {
            return setErrorStack($returnArr, -1, $res, null);
        } else {
            return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
        }
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
    }
}

function generateAudioActicationReport($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO
					{$desinationtable} (content_owner, total_amt_recd,us_payout,witholding)
					SELECT
						content_owner,
						ROUND(SUM(partnerRevenue),2),
						ROUND(SUM(CASE WHEN country='US' THEN partnerRevenue END),2),
						ROUND((SUM(CASE WHEN country='US' THEN partnerRevenue END)*30/100),2)
					FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";

@unlink("polo_ACT.txt");
file_put_contents("polo_ACT.txt", $updateQuery);
@chmod("polo_ACT.txt", 0777);


    $updateQueryResult = runQuery($updateQuery, $conn);
    if (noError($updateQueryResult)) {
        $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
		                set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube')),
						a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))*a.total_amt_recd)/100,2),
						a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))/100),2)
						where b.client_username =a.content_owner";
        $updateQueryResult = runQuery($updateQuery, $conn);
        if (noError($updateQueryResult)) {
            return setErrorStack($returnArr, -1, $res, null);
        } else {
            return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
        }

        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
    }
}
function updateStatus($table, $status, $ids, $conn)
{
    $res = array();
    $returnArr = array();
    $updateQuery = "UPDATE {$table} SET status = '{$status}' WHERE id IN({$ids})";
    $updateQueryResult = runQuery($updateQuery, $conn);
    if (noError($updateQueryResult)) {
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
    }
}



//amazon
function createActivationAmazonReportTable($tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
	$createYoutubeTableQuery = "CREATE TABLE {$tableName}  (
								`id` int(11) NOT NULL AUTO_INCREMENT,
								`content_owner` varchar(150)  NOT NULL,
								`total_amt_recd` varchar(50)  NOT NULL,
								`witholding` varchar(50)  DEFAULT NULL,
								`amt_recd` varchar(50)  DEFAULT NULL,
								`shares` varchar(50)  DEFAULT NULL,
								`amt_payable` varchar(50)  DEFAULT NULL,
								`final_payable` varchar(50)  DEFAULT NULL,
								`status` ENUM('active', 'inactive') DEFAULT 'inactive',
								PRIMARY KEY (id),
								INDEX i (content_owner)
							)";



    $createYoutubeTableQueryResult = runQuery($createYoutubeTableQuery, $conn);

    if (noError($createYoutubeTableQueryResult)) {
        $returnArr = setErrorStack($returnArr, -1, $createYoutubeTableQueryResult);
    } else {
        $returnArr = setErrorStack($returnArr, 3, null);
    }

    return $returnArr;
}

function generateActicationReportAmazon($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO
					{$desinationtable} (content_owner, total_amt_recd,witholding,amt_recd,amt_payable)
					SELECT
						content_owner,
						ROUND(SUM(revINRwithoutHolding),2),
						ROUND(SUM(withHolding),2),
						ROUND(SUM(amountReceived),2),
						ROUND(SUM(payable),2)
                    FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";
                    
                               
    $updateQueryResult = runQuery($updateQuery, $conn);
    if (noError($updateQueryResult)) {
      
        $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
		                set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueAmazon')),
						 
						a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueAmazon'))/100),2)
						where b.client_username =a.content_owner";
        $updateQueryResult = runQuery($updateQuery, $conn);
        if (noError($updateQueryResult)) {
            return setErrorStack($returnArr, -1, $res, null);
        } else {
            return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
        }
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $updateQueryResult["errMsg"], null);
    }
}
function getActivationReportSummaryv2_backup_before_coding_dynamic_gst_holding($type,$clientSearchArr,$client,$conn)
{
	$res = array();
	$returnArr = array();

	 
	$getClientInfoQuery = "SHOW TABLES LIKE '$type'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

	/* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
	*  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
	*/

    $getshare = "SELECT    gst_per  FROM crep_cms_clients where client_username = '" . $client . "'";
    $getshareres = runQuery($getshare, $conn);
    $getshareresdata = mysqli_fetch_assoc($getshareres["dbResource"]);
    
    $gst_per = 0;
    if (!empty($getshareresdata)) {
        $gst_per = $getshareresdata['gst_per'];
    }


	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
   	$check = "SELECT sum(total_amt_recd) as total_amt_recd,sum(us_payout) as us_payout,sum(witholding) as witholding,sum(final_payable) as final_payable , sum(amt_payable) as amt_payable , sum(shares) as shares  from $v WHERE content_owner='{$client}' and `status`='Active'";
				 
				$checkresult = runQuery($check, $conn);
				 
				$test = mysqli_fetch_assoc($checkresult["dbResource"]);
                $res[] = $test;
				 
		}  
		
    }
    $res_final['total_amt_recd'] = 0;
    $res_final['us_payout'] = 0;
    $res_final['witholding'] = 0;
    $res_final['final_payable'] = 0;
    $res_final['amt_payable'] = 0;
    $res_final['shares'] = 0;
    $res_final['gst_per'] = $gst_per;
    $shares = 0;
    foreach($res as $k=>$value){
        
        if($value['shares'] > 0){
            $shares = $value['shares'];
        }
        $res_final['total_amt_recd'] =   $res_final['total_amt_recd'] + $value['total_amt_recd'];
        $res_final['us_payout'] =   $res_final['us_payout']  + $value['us_payout'];
        $res_final['witholding'] =   $res_final['witholding']  + $value['witholding'];
        $res_final['final_payable'] =   $res_final['final_payable'] + $value['final_payable'];
        $res_final['shares'] =     (int) $shares;
        $res_final['amt_payable'] =  $res_final['amt_payable'] + $value['amt_payable'];
    }
 

    
	return setErrorStack($returnArr, -1, $res_final, null);
}

function getActivationReportSummaryv2($type,$clientSearchArr,$client,$conn)
{
	$res = array();
	$returnArr = array();

	 
	$getClientInfoQuery = "SHOW TABLES LIKE '$type'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

	/* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
	*  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
	*/

 

	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
           	$check = "SELECT total_amt_recd as total_amt_recd,us_payout as us_payout,witholding as witholding,final_payable as final_payable , amt_payable as amt_payable , shares as shares,holding_percentage as holding_percentage ,gst_percentage as gst_percentage ,final_payable_with_gst as final_payable_with_gst ,'".$v."' as tablename from $v WHERE content_owner='{$client}' and `status`='Active'";
				 
				$checkresult = runQuery($check, $conn);
             
               $resultQyeryscheck = mysqli_num_rows($checkresult["dbResource"]);
                if($resultQyeryscheck > 0){
				$test = mysqli_fetch_assoc($checkresult["dbResource"]);
                $res[] = $test;
                }
				 
		}  
		
    }
    $res_final['total_amt_recd'] = 0;
    $res_final['us_payout'] = 0;
    $res_final['witholding'] = 0;
    $res_final['witholding_tablename'] = array();
    $res_final['final_payable'] = 0;
    $res_final['amt_payable'] = 0;
    $res_final['shares'] = 0;
    $res_final['gst_percentage'] = 0;
    $res_final['holding_percentage'] = 0;
    $res_final['final_payable_with_gst'] = 0;
   $counter=0;
    foreach($res as $k=>$value){
        $counter = $counter+1;
     //   $holding_percentage = (!empty($value['holding_percentage'])) ? $value['holding_percentage'] : 0;
    //    $gst_percentage = (!empty($value['gst_percentage'])) ? $value['gst_percentage'] : 0;
    //    $shares = (!empty($value['shares'])) ? $value['shares'] : 0;
        
        $res_final['total_amt_recd'] =   $res_final['total_amt_recd'] + $value['total_amt_recd'];
        $res_final['us_payout'] =   $res_final['us_payout']  + $value['us_payout'];
        $res_final['witholding'] =   $res_final['witholding']  + $value['witholding'];
        $res_final['witholding_tablename'][$value['tablename']] = $value['witholding'];
        $res_final['final_payable'] =   $res_final['final_payable'] + $value['final_payable'];
         $res_final['amt_payable'] =  $res_final['amt_payable'] + $value['amt_payable'];
        $res_final['final_payable_with_gst'] =  $res_final['final_payable_with_gst'] + $value['final_payable_with_gst'];
        $res_final['holding_percentage'] =  $res_final['holding_percentage'] + $value['holding_percentage'];
        $res_final['gst_percentage'] =  $res_final['gst_percentage'] + $value['gst_percentage'];
        $res_final['shares'] =  $res_final['shares'] + $value['shares'];
        
    }
     
    if($counter > 0){
        $res_final['gst_percentage'] =      $res_final['gst_percentage'] / $counter ;
        $res_final['shares'] =      $res_final['shares'] / $counter ;
        $res_final['holding_percentage'] =      $res_final['holding_percentage'] / $counter ;
        
    }
   
	return setErrorStack($returnArr, -1, $res_final, null);
}
function getActivationReportv2(
    $table,
    $fieldSearchArr = null,
    $fieldsStr = "",
    $dateField = null,
    $conn,
    $offset = null,
    $resultsPerPage = 100,
    $orderBy = "content_owner"
) {
    $res = array();
    $returnArr = array();
    $whereClause = "";

    //looping through array passed to create another array of where clauses

    foreach ($fieldSearchArr as $colName => $searchVal) {
        if (!empty($whereClause)) {
            $whereClause .= " AND ";
        }

        $whereClause .= "{$colName} = '{$searchVal}'";
    }

    if (empty($fieldsStr)) {
        $fieldsStr = "*";
    }

    $getClientInfoQuery = "SELECT {$fieldsStr} FROM {$table} as main";
    if (!empty($whereClause)) {
        $getClientInfoQuery .= " WHERE {$whereClause}";
    }

    $getClientInfoQuery .= " ORDER BY " . $orderBy;
    if ($offset !== null) {
        $getClientInfoQuery .= " LIMIT {$offset}, {$resultsPerPage}";
    }
    //echo $getClientInfoQuery;
    $getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
    if (!noError($getClientInfoQueryResult)) {
        return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
    }

    /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
     *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
     */
    while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {

        $res[] = $row;
    }

    return setErrorStack($returnArr, -1, $res, null);
}


////////////////youtube red music finance --

function getyoutube_red_music_video_finance_activationSummaryv2($type,$clientSearchArr,$client,$conn)
{
	$res = array();
	$returnArr = array();

	 
	   $getClientInfoQuery = "SHOW TABLES LIKE '$type%'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

	/* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
	*  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
	*/
	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
	  	$check = "SELECT sum(total_amt_recd) as total_amt_recd,sum(us_payout) as us_payout,sum(witholding) as witholding,sum(final_payable) as final_payable ,sum(shares) as shares ,sum(amt_payable) as amt_payable   from $v WHERE content_owner='{$client}' and `status`='Active'";
				 
				$checkresult = runQuery($check, $conn);
				 
				$test = mysqli_fetch_assoc($checkresult["dbResource"]);
                $res[] = $test;
				 
		}  
		
    }
  

    $res_final['total_amt_recd'] = 0;
    $res_final['us_payout'] = 0;
    $res_final['witholding'] = 0;
    $res_final['final_payable'] = 0;
    $res_final['amt_payable'] = 0;
    $res_final['shares'] = 0;

    foreach($res as $k=>$value){
        $res_final['total_amt_recd'] =   $res_final['total_amt_recd'] + $value['total_amt_recd'];
        $res_final['us_payout'] =   $res_final['us_payout']  + $value['us_payout'];
        $res_final['witholding'] =   $res_final['witholding']  + $value['witholding'];
        $res_final['final_payable'] =   $res_final['final_payable'] + $value['final_payable'];
        $res_final['shares'] =  $res_final['shares'] + $value['shares'];
        $res_final['amt_payable'] =  $res_final['amt_payable'] + $value['amt_payable'];
    }
 
	return setErrorStack($returnArr, -1, $res_final, null);
}


//-----youtube ecom paid features 

function get_youtube_ecommerce_paid_features_activation_Summarryv2($type,$clientSearchArr,$client,$conn)
{
	$res = array();
	$returnArr = array();

	 
	   $getClientInfoQuery = "SHOW TABLES LIKE '$type%'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

	/* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
	*  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
	*/
	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
	  	$check = "SELECT sum(total_amt_recd) as total_amt_recd,sum(us_payout) as us_payout,sum(witholding) as witholding,sum(final_payable) as final_payable ,sum(shares) as shares ,sum(amt_payable) as amt_payable   from $v WHERE content_owner='{$client}' and `status`='Active'";
				 
				$checkresult = runQuery($check, $conn);
				 
				$test = mysqli_fetch_assoc($checkresult["dbResource"]);
                $res[] = $test;
				 
		}  
		
    }
    $res_final['total_amt_recd'] = 0;
    $res_final['us_payout'] = 0;
    $res_final['witholding'] = 0;
    $res_final['final_payable'] = 0;
    $res_final['amt_payable'] = 0;
    $res_final['shares'] = 0;

    foreach($res as $k=>$value){
        $res_final['total_amt_recd'] =   $res_final['total_amt_recd'] + $value['total_amt_recd'];
        $res_final['us_payout'] =   $res_final['us_payout']  + $value['us_payout'];
        $res_final['witholding'] =   $res_final['witholding']  + $value['witholding'];
        $res_final['final_payable'] =   $res_final['final_payable'] + $value['final_payable'];
        $res_final['shares'] =  $res_final['shares'] + $value['shares'];
        $res_final['amt_payable'] =  $res_final['amt_payable'] + $value['amt_payable'];
    }
 
	return setErrorStack($returnArr, -1, $res_final, null);
}


function getActivationReportwhm_v10($conn,$type='',$client='',$uspayout=0,$uspayment_holding_amount=0)
{
	$res = array();
	$returnArr = array();

	 

	$getClientInfoQuery = "SHOW TABLES LIKE '$type'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

	/* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
	*  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
	*/
    $client = strtolower($client);

	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
           	$check = "select SUM(old_amt) as old_amt, SUM(new_amt) as new_amt from ( SELECT SUM(partnerRevenue), holding_percentage_actual, ROUND((SUM(partnerRevenue) * holding_percentage)/100,8) as old_amt ,ROUND((SUM(partnerRevenue) * holding_percentage_actual)/100,8) as new_amt FROM $v where LOWER(content_owner)='{$client}' and country='US' GROUP by channelID ) as new_table";
				 
				$checkresult = runQuery($check, $conn);
             
               $resultQyeryscheck = mysqli_num_rows($checkresult["dbResource"]);
                if($resultQyeryscheck > 0){
				$test = mysqli_fetch_assoc($checkresult["dbResource"]);
                $res[] = $test;
                }
				 
		}  
		
    }
    $res_final['new_amt'] = 0;
    $res_final['old_amt'] = 0;
   $counter=0;
    foreach($res as $k=>$value){
        $res_final['old_amt'] =   $res_final['old_amt'] + $value['old_amt'];
        $res_final['new_amt'] =   $res_final['new_amt'] + $value['new_amt'];
        
    }
     
    
   
	return setErrorStack($returnArr, -1, $res_final, null);
}

function getClientWhpReport_v10($conn,$tablename='',$client='',$uspayout=0,$uspayment_holding_amount=0)
{
	$res = array();
	$returnArr = array();

	 

     $getClientInfoQuery = "SHOW TABLES LIKE '$tablename'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

	/* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
	*  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
	*/
    $client = strtolower($client);

	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
         	$check = "select SUM(US_Sourced_Revenue) as US_Sourced_Revenue, avg(Tax_Withholding_Rate) as Tax_Withholding_Rate,sum(Tax_Withheld_Amount)  as Tax_Withheld_Amount ,'{$v}' as  WHM_TableName_TWA    FROM $v where LOWER(content_owner)='{$client}' ";
				 
				$checkresult = runQuery($check, $conn);
             
               $resultQyeryscheck = mysqli_num_rows($checkresult["dbResource"]);
                if($resultQyeryscheck > 0){
				$test = mysqli_fetch_assoc($checkresult["dbResource"]);
                $res[] = $test;
                }
				 
		}  
		
    }
    $res_final['US_Sourced_Revenue'] = 0;
    $res_final['Tax_Withholding_Rate'] = 0;
    $res_final['Tax_Withheld_Amount'] = 0;
    $res_final['WHM_TableName_TWA'] = array();
 

    foreach($res as $k=>$value){
        $res_final['US_Sourced_Revenue'] =   $res_final['US_Sourced_Revenue'] + $value['US_Sourced_Revenue'];
        $res_final['Tax_Withholding_Rate'] =   $res_final['Tax_Withholding_Rate']  + $value['Tax_Withholding_Rate'];
        $res_final['Tax_Withheld_Amount'] =   $res_final['Tax_Withheld_Amount']  + $value['Tax_Withheld_Amount'];
        $res_final['WHM_TableName_TWA'][$value['WHM_TableName_TWA']]  = $value['Tax_Withheld_Amount'];
       
    }
     
    
   
	return setErrorStack($returnArr, -1, $res_final, null);
 
}

function getClientWhpReportPrev_v10($conn,$activationTable,$tablename='',$client='',$uspayout=0,$uspayment_holding_amount=0)
{
	$res = array();
	$returnArr = array();

	 

     $getClientInfoQuery = "SHOW TABLES LIKE '$tablename'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

	/* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
	*  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
	*/
    $client = strtolower($client);

 
    $clientSearchArr["content_owner"] = $client;
    $fieldsStr="*";
    $allClientsInfo = getActivationReportSummaryv2(
        $activationTable,
        $clientSearchArr ,
        $client,  
        $conn 
    );

    $allClientsInfo = $allClientsInfo["errMsg"];
         
    $witholding = (!empty($allClientsInfo['witholding'])) ? $allClientsInfo['witholding'] : 0;

    //witholding
	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
         	$check = "select SUM(US_Sourced_Revenue) as US_Sourced_Revenue, avg(Tax_Withholding_Rate) as Tax_Withholding_Rate,sum(Tax_Withheld_Amount)  as Tax_Withheld_Amount      FROM $v where LOWER(content_owner)='{$client}' ";
				 
				$checkresult = runQuery($check, $conn);
             
               $resultQyeryscheck = mysqli_num_rows($checkresult["dbResource"]);
                if($resultQyeryscheck > 0){
				$test = mysqli_fetch_assoc($checkresult["dbResource"]);
                $res[] = $test;
                }
				 
		}  
		
    }
    $res_final['US_Sourced_Revenue'] = 0;
    $res_final['Tax_Withholding_Rate'] = 0;
    $res_final['Tax_Withheld_Amount'] = 0;
    $res_final['prev_month_adjust'] = 0;

    foreach($res as $k=>$value){
      //  $res_final['US_Sourced_Revenue'] =   $res_final['US_Sourced_Revenue'] + $value['US_Sourced_Revenue'];
      //  $res_final['Tax_Withholding_Rate'] =   $res_final['Tax_Withholding_Rate']  + $value['Tax_Withholding_Rate'];
        $res_final['Tax_Withheld_Amount'] =   $res_final['Tax_Withheld_Amount']  + $value['Tax_Withheld_Amount'];
       
    }
    if($res_final['Tax_Withheld_Amount'] != 0){
        $res_final['prev_month_adjust'] = $witholding - $res_final['Tax_Withheld_Amount'];
    }

    //$res_final['prev_month_adjust'] = $witholding - $res_final['Tax_Withheld_Amount'];
   
	return setErrorStack($returnArr, -1, $res_final, null);
 
}
function getWhpReport_v10($conn,$tablename='',$client='',$uspayout=0,$uspayment_holding_amount=0)
{
	$res = array();
	$returnArr = array();

	 
    $client = strtolower($client);

    $check = "select SUM(US_Sourced_Revenue) as US_Sourced_Revenue, avg(Tax_Withholding_Rate) as Tax_Withholding_Rate,sum(Tax_Withheld_Amount)  as Tax_Withheld_Amount    FROM $tablename where LOWER(content_owner)='{$client}'  ";
				 
    $getClientInfoQueryResult = runQuery($check, $conn);
     if (!noError($getClientInfoQueryResult)) {
         return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
     }

     /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
     *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
     */
     while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {

         $res[] = $row;
     }

    $res_final['US_Sourced_Revenue'] = 0;
    $res_final['Tax_Withholding_Rate'] = 0;
    $res_final['Tax_Withheld_Amount'] = 0;
 

    foreach($res as $k=>$value){
        $res_final['US_Sourced_Revenue'] =   $res_final['US_Sourced_Revenue'] + $value['US_Sourced_Revenue'];
        $res_final['Tax_Withholding_Rate'] =   $res_final['Tax_Withholding_Rate']  + $value['Tax_Withholding_Rate'];
        $res_final['Tax_Withheld_Amount'] =   $res_final['Tax_Withheld_Amount']  + $value['Tax_Withheld_Amount'];
       
    }

     return setErrorStack($returnArr, -1, $res_final, null);
                 
    
 
}


function getWhpReportPrev_v10($conn,$activationTable,$tablename='',$client='',$uspayout=0,$uspayment_holding_amount=0)
{
	$res = array();
	$returnArr = array();

	 
    $client = strtolower($client);

    $clientSearchArr["content_owner"] = $client;
    $fieldsStr="*";
    $allClientsInfo = getActivationReportv2(
       $activationTable,
       $clientSearchArr,
       $fieldsStr,
       null,
       $conn,
       0,
       1
    );


    $res_final['US_Sourced_Revenue'] = 0;
    $res_final['Tax_Withholding_Rate'] = 0;
    $res_final['Tax_Withheld_Amount'] = 0;
    $res_final['prev_month_adjust'] = 0;


    if (!noError($allClientsInfo)) {
    } else {
         
        $allClientsInfo = $allClientsInfo["errMsg"];
        
         $witholding = (!empty($allClientsInfo[0]['witholding'])) ? $allClientsInfo[0]['witholding'] : 0;
        
   //echo "</br>".
      $check = "select SUM(US_Sourced_Revenue) as US_Sourced_Revenue, avg(Tax_Withholding_Rate) as Tax_Withholding_Rate,sum(Tax_Withheld_Amount)  as Tax_Withheld_Amount    FROM $tablename where LOWER(content_owner)='{$client}'  ";
				 
    $getClientInfoQueryResult = runQuery($check, $conn);
     if (!noError($getClientInfoQueryResult)) {
         return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
     }

     /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
     *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
     */
     while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        
       $res_final['Tax_Withheld_Amount'] =   $res_final['Tax_Withheld_Amount']  + $row['Tax_Withheld_Amount'];
     }

    if($res_final['Tax_Withheld_Amount'] != 0){
        $res_final['prev_month_adjust'] = $witholding - $res_final['Tax_Withheld_Amount'];
    }
     

        

    }
    // echo "==========";
    // echo $client;
    // print_r($res_final);
    // echo "==========";
     return setErrorStack($returnArr, -1, $res_final, null);
                 
    
 
}
//end

//slab = count no of records only youtube claim table
function getActivationCountContentOwner_old($type,$conn,$client=null){
    $res = array();
	$returnArr = array();
    $union_final_query  = [];
	 
	$getClientInfoQuery = "SHOW TABLES LIKE '$type'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

 

 

	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
            if (!empty($client)) {
                 
                $union_final_query[] = "  SELECT count(content_owner) as cnt FROM $v  where  content_owner = '{$client}' ";
            } else {

               
                $union_final_query[] = "  SELECT count(content_owner) as cnt FROM $v ";

                
            }
           	 
				 
		}  
		
    }
   
    $union_final_query_sql = "select   sum(cnt)  as noOfClients from 	 ";


    $check_query_list_new = implode(" UNION ALL   ", $union_final_query);
     $youtubereport = $union_final_query_sql . " ( " . $check_query_list_new . " )  AS tbl_common";

    $youtubereportresult = runQuery($youtubereport, $conn);
   
    if (!noError($youtubereportresult)) {
        return setErrorStack($returnArr, 3, $youtubereportresult["errMsg"], null);
    }
 
    while ($row = mysqli_fetch_assoc($youtubereportresult["dbResource"])) {

        $res[] = $row;
    }

    return setErrorStack($returnArr, -1, $res, null);
}

//slab = count no of records it includes all cms and tables 
function getActivationCountContentOwner($type,$conn,$client=null){ 
    
}


function getActivationContentOwnerecords($type,$conn, $fieldSearchArr = null,$offset = null,$resultsPerPage = 100){
    $res = array();
	$returnArr = array();

    $union_final_query  = [];

	$getClientInfoQuery = "SHOW TABLES LIKE '$type'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

 

 

	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
            $result = array_map('strrev', explode('_', strrev($v)));
            $cmstype = $result[2];
            if (!empty($fieldSearchArr)) {
                 
                $union_final_query[] = "  SELECT content_owner, total_amt_recd,shares, us_payout ,witholding ,final_payable,gst_percentage,holding_percentage,final_payable_with_gst,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v   where  content_owner = '{$fieldSearchArr}' ";
            } else {

               
                $union_final_query[] = "   SELECT content_owner, total_amt_recd , shares, us_payout ,witholding ,final_payable,gst_percentage,holding_percentage,final_payable_with_gst ,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v ";

                
            }
           	 
				 
		}  
		
    }
   
    $union_final_query_sql = "select    content_owner,    SUM(total_amt_recd) AS total_amt_recd , GROUP_CONCAT(concat('{$cmstype} : ',total_amt_recd) separator ', '  ) as total_amt_recd_grp ,  AVG(shares) as shares,   SUM(us_payout) as us_payout,   SUM(witholding) as witholding,   SUM(final_payable) as final_payable,   AVG(gst_percentage) as gst_percentage,   AVG(holding_percentage) as holding_percentage,   SUM(final_payable_with_gst) as final_payable_with_gst, cmstype from 	 ";


    $check_query_list_new = implode(" UNION ALL   ", $union_final_query);
    $youtubereport = $union_final_query_sql . " ( " . $check_query_list_new . " )  AS tbl_common GROUP BY content_owner ORDER BY `tbl_common`.`content_owner` ASC ";

    
   
    if ($offset !== null) {
        $youtubereport .= " LIMIT {$offset}, {$resultsPerPage}";
    }
    //echo $youtubereport;
    $youtubereportresult = runQuery($youtubereport, $conn);
   
	  /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
     *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
     */
    while ($row = mysqli_fetch_assoc($youtubereportresult["dbResource"])) {

        $res[] = $row;
    }

    return setErrorStack($returnArr, -1, $res, null);
}

//it works only for youtube_video_claim_activation_report_nd1_ , youtube_video_claim_activation_report_nd2_ , youtube_video_claim_activation_report_ndkids_2023_01
function getActivationContentOwnerecordsGroup_old($type,$conn, $fieldSearchArr = null,$offset = null,$resultsPerPage = 100){
    $res = array();
	$returnArr = array();

    $union_final_query  = [];

	$getClientInfoQuery = "SHOW TABLES LIKE '$type'";

	$getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	if (!noError($getClientInfoQueryResult)) {
		return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	}

	while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
        //kham8888
        
		foreach($row as $k=>$v){
				 
            $result = array_map('strrev', explode('_', strrev($v)));
            $cmstype = $result[2];
            if (!empty($fieldSearchArr)) {
                 
               // $union_final_query[] = "  SELECT content_owner, total_amt_recd,shares, us_payout ,witholding ,final_payable,gst_percentage,holding_percentage,final_payable_with_gst,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v   where  content_owner = '{$fieldSearchArr}' ";

               $union_final_query[] = "   SELECT b.group_name as group_name ,SUM(acttble.total_amt_recd) as total_amt_recd,  AVG(shares) as shares, SUM(witholding) as witholding,  GROUP_CONCAT(b.content_owner) as content_owner ,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v acttble , client_slab_group_content_owner b  WHERE  acttble.content_owner=b.content_owner group by b.group_name";

            } else {

               
                $union_final_query[] = "   SELECT b.group_name as group_name ,SUM(acttble.total_amt_recd) as total_amt_recd,  AVG(shares) as shares,SUM(witholding) as witholding, GROUP_CONCAT(b.content_owner) as content_owner ,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v acttble ,client_slab_group_content_owner b  WHERE  acttble.content_owner=b.content_owner group by b.group_name";

                
            }
           	 
				 
		}  
		
    }
   
    $union_final_query_sql = "select  group_name , content_owner,    SUM(total_amt_recd) AS total_amt_recd ,  AVG(shares) as shares,  SUM(witholding) as witholding  , cmstype from 	 ";


    $check_query_list_new = implode(" UNION ALL   ", $union_final_query);
    $youtubereport = $union_final_query_sql . " ( " . $check_query_list_new . " )  AS tbl_common GROUP BY group_name ORDER BY `tbl_common`.`group_name` ASC ";

    
   
    if ($offset !== null) {
        $youtubereport .= " LIMIT {$offset}, {$resultsPerPage}";
    }
   // echo $youtubereport;
    $youtubereportresult = runQuery($youtubereport, $conn);
   
	  /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
     *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
     */
    while ($row = mysqli_fetch_assoc($youtubereportresult["dbResource"])) {

        $res[] = $row;
    }

    return setErrorStack($returnArr, -1, $res, null);
}


//it works only for all table and cms nd1,nd2,ndkids,ndmusic
/*
youtube_ecom_paid_features_activation_report_nd1_2023_01
youtube_ecom_paid_features_activation_report_nd2_2023_01
youtube_ecom_paid_features_activation_report_ndkids_2023_01
youtube_ecom_paid_features_activation_report_redmusic_2023_01
youtube_labelengine_activation_report_redmusic_2023_01
youtube_redmusic_activation_report_redmusic_2023_01
youtube_red_music_finance_activation_report_nd1_2023_01
youtube_red_music_finance_activation_report_nd2_2023_01
youtube_red_music_finance_activation_report_ndkids_2023_01
youtube_red_music_finance_activation_report_redmusic_2023_01
youtube_video_claim_activation_report_nd1_2023_01
youtube_video_claim_activation_report_nd2_2023_01
youtube_video_claim_activation_report_ndkids_2023_01
*/
function checkTableExistNew($tableName,$conn){
    $returnArr = array();
    $query = "SHOW TABLES LIKE '{$tableName}'";  	 
    $res["query"] = $query;
    $result = runQuery($query, $conn);
    if (noError($result)) {			
        return true;
         
    } else {			
        return false;
    }

    return $returnArr;		
}
function getActivationContentOwnerecordsGroup1($type,$conn, $fieldSearchArr = null,$offset = null,$resultsPerPage = 100){
    $res = array();
	$returnArr = array();
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_ndkids_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_labelengine_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_redmusic_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_ndkids_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_ndkids_".$type;
    $table_list = [];
    foreach($table_list_temp as $key => $value){
      $rt_status =   checkTableExistNew($value,$conn);
      if($rt_status){
        $table_list[] = $value;
      }
    }

    $union_final_query  = [];

 
    foreach($table_list as $k=>$v){
				 
        $result = array_map('strrev', explode('_', strrev($v)));
        $cmstype = $result[2];
        if (!empty($fieldSearchArr)) {
             
           // $union_final_query[] = "  SELECT content_owner, total_amt_recd,shares, us_payout ,witholding ,final_payable,gst_percentage,holding_percentage,final_payable_with_gst,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v   where  content_owner = '{$fieldSearchArr}' ";

           $union_final_query[] = "   SELECT b.group_name as group_name ,SUM(acttble.total_amt_recd) as total_amt_recd,  shares as shares, SUM(witholding) as witholding,  GROUP_CONCAT(b.content_owner) as content_owner ,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v acttble , client_slab_group_content_owner b  WHERE  acttble.content_owner=b.content_owner and    b.content_owner = '{$fieldSearchArr}' group by b.group_name";

        } else {

           
            $union_final_query[] = "   SELECT b.group_name as group_name ,SUM(acttble.total_amt_recd) as total_amt_recd,  shares as shares,SUM(witholding) as witholding, GROUP_CONCAT(b.content_owner) as content_owner ,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v acttble ,client_slab_group_content_owner b  WHERE  acttble.content_owner=b.content_owner group by b.group_name";

            
        }
            
             
    }  
 
   
    $union_final_query_sql = "select  group_name ,GROUP_CONCAT(content_owner  SEPARATOR', ')   as content_owner  ,    SUM(total_amt_recd) AS total_amt_recd ,  GROUP_CONCAT(shares  SEPARATOR', ')   as shares,  SUM(witholding) as witholding  , GROUP_CONCAT(tablename  SEPARATOR', ') as tablename from 	 ";


    $check_query_list_new = implode(" UNION ALL   ", $union_final_query);
    $youtubereport = $union_final_query_sql . " ( " . $check_query_list_new . " )  AS tbl_common GROUP BY group_name ORDER BY `tbl_common`.`group_name` ASC ";

    
   
    if ($offset !== null) {
        $youtubereport .= " LIMIT {$offset}, {$resultsPerPage}";
    }
   // echo $youtubereport;

    @unlink("polo_slab1.txt");
    file_put_contents("polo_slab1.txt", $youtubereport);
    @chmod("polo_slab1.txt", 0777);
    

    $youtubereportresult = runQuery($youtubereport, $conn);
   
	  /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
     *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
     */
    while ($row = mysqli_fetch_assoc($youtubereportresult["dbResource"])) {

        $res[] = $row;
    }

    return setErrorStack($returnArr, -1, $res, null);
}
function getActivationContentOwnerecordsGroup($type,$conn, $fieldSearchArr = null,$offset = null,$resultsPerPage = 100){
    $res = array();
	$returnArr = array();
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_ndkids_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_labelengine_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_redmusic_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_ndkids_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_ndkids_".$type;
    $table_list = [];
    foreach($table_list_temp as $key => $value){
      $rt_status =   checkTableExistNew($value,$conn);
      if($rt_status){
        $table_list[] = $value;
      }
    }

    $union_final_query  = [];

 
    foreach($table_list as $k=>$v){
				 
        $result = array_map('strrev', explode('_', strrev($v)));
        $cmstype = $result[2];
        if (!empty($fieldSearchArr)) {
             
           // $union_final_query[] = "  SELECT content_owner, total_amt_recd,shares, us_payout ,witholding ,final_payable,gst_percentage,holding_percentage,final_payable_with_gst,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v   where  content_owner = '{$fieldSearchArr}' ";

           $union_final_query[] = "   SELECT b.group_name as group_name ,SUM(acttble.total_amt_recd) as total_amt_recd,  shares as shares, SUM(witholding) as witholding,  GROUP_CONCAT(b.content_owner) as content_owner ,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v acttble , client_slab_group_content_owner b  WHERE  acttble.content_owner=b.content_owner and    b.content_owner = '{$fieldSearchArr}' group by b.group_name";

        } else {

           
            $union_final_query[] = "   SELECT b.group_name as group_name ,SUM(acttble.total_amt_recd) as total_amt_recd,  shares as shares,SUM(witholding) as witholding, GROUP_CONCAT(b.content_owner) as content_owner ,'{$cmstype}' as cmstype ,'{$v}' as tablename FROM $v acttble ,client_slab_group_content_owner b  WHERE  acttble.content_owner=b.content_owner group by b.group_name";

            
        }
            
             
    }  
 
   
    $union_final_query_sql = "select  group_name , content_owner,    SUM(total_amt_recd) AS total_amt_recd ,  GROUP_CONCAT(shares  SEPARATOR', ')   as shares,  SUM(witholding) as witholding  , GROUP_CONCAT(tablename  SEPARATOR', ') as tablename from 	 ";


    $check_query_list_new = implode(" UNION ALL   ", $union_final_query);
    $youtubereport = $union_final_query_sql . " ( " . $check_query_list_new . " )  AS tbl_common GROUP BY group_name ORDER BY `tbl_common`.`group_name` ASC ";

    
   
    if ($offset !== null) {
        $youtubereport .= " LIMIT {$offset}, {$resultsPerPage}";
    }
   // echo $youtubereport;

    @unlink("polo_slab.txt");
    file_put_contents("polo_slab.txt", $youtubereport);
    @chmod("polo_slab.txt", 0777);
    

    $youtubereportresult = runQuery($youtubereport, $conn);
   
	  /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
     *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
     */
    while ($row = mysqli_fetch_assoc($youtubereportresult["dbResource"])) {

        $res[] = $row;
    }

    return setErrorStack($returnArr, -1, $res, null);
}

function get_sharespercentage($conn, $total_amt_recd=0,$witholding=0,$content_owner='',$percentage=0){
    $returnArr = array();
    
    
  

    $total_amt_recd = $total_amt_recd;
    
    $total_payable_amt = ceil($total_amt_recd - $witholding);

    $sql = "select * from client_slab_percentage where client_username = '".$content_owner."' and from_amt <= ".$total_payable_amt." and to_amt > ".$total_payable_amt ;


   // file_put_contents("polo_client_slab_percentage.txt", $sql,FILE_APPEND);
   // @chmod("polo_client_slab_percentage.txt", 0777);
    $client_slab_percentageResult = runQuery($sql, $conn);
    $resultQyeryscheck = mysqli_num_rows($client_slab_percentageResult["dbResource"]);
    if ($resultQyeryscheck > 0) {
        $resultQyerydata = mysqli_fetch_assoc($client_slab_percentageResult["dbResource"]);
        $percentage =$resultQyerydata['percentage'];

    }
   
    return $percentage;

   
}

function get_sharespercentage_groupcontentowner($conn, $total_amt_recd=0,$witholding=0,$group_name='',$percentage=0){
    $returnArr = array();
    
   $total_amt_recd = trim($total_amt_recd);
  if($total_amt_recd<=0){
    //echo $group_name."::".$total_amt_recd;
    return $percentage;
  }

    $total_payable_amt = $total_amt_recd;
    
    //$total_payable_amt = ceil($total_amt_recd - $witholding);

    $sql = "select * from client_slab_percentage where group_name = '".$group_name."' and from_amt <= ".$total_payable_amt." and to_amt > ".$total_payable_amt ;

    if($group_name=='kadapati'){
      //  echo $sql;
    }

   // file_put_contents("polo_client_slab_percentage.txt", $sql,FILE_APPEND);
   // @chmod("polo_client_slab_percentage.txt", 0777);
    $client_slab_percentageResult = runQuery($sql, $conn);
    $resultQyeryscheck = mysqli_num_rows($client_slab_percentageResult["dbResource"]);
    if ($resultQyeryscheck > 0) {
        $resultQyerydata = mysqli_fetch_assoc($client_slab_percentageResult["dbResource"]);
        $percentage =$resultQyerydata['percentage'];

    }
   
    return $percentage;

   
}
function getAmountRecived($conn,$type='',$content_owner=''){
    
   
    // $res = array();
	// $returnArr = array();

    // $union_final_query  = [];

	// $getClientInfoQuery = "SHOW TABLES LIKE '$type'";

	// $getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
	// if (!noError($getClientInfoQueryResult)) {
	// 	return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
	// }

 

 

	// while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
    //     //kham8888
        
	// 	foreach($row as $k=>$v){
				 
    //         $result = array_map('strrev', explode('_', strrev($v)));
    //         $cmstype = $result[2];

    //         $union_final_query[] = "  SELECT content_owner, total_amt_recd ,GROUP_CONCAT(concat('{$cmstype} : ',total_amt_recd) separator ', '  ) as total_amt_recd_grp ,shares, us_payout ,witholding ,final_payable,gst_percentage,holding_percentage,final_payable_with_gst,'{$cmstype}' as cmstype FROM $v   where  content_owner = '{$content_owner}' ";
           	 
				 
	// 	}  
		
    // }
   
    // $union_final_query_sql = "select    content_owner,    SUM(total_amt_recd) AS total_amt_recd,  AVG(shares) as shares,   SUM(us_payout) as us_payout,   SUM(witholding) as witholding,   SUM(final_payable) as final_payable,   AVG(gst_percentage) as gst_percentage,   AVG(holding_percentage) as holding_percentage,   SUM(final_payable_with_gst) as final_payable_with_gst, cmstype from 	 ";


    // $check_query_list_new = implode(" UNION ALL   ", $union_final_query);
    // $youtubereport = $union_final_query_sql . " ( " . $check_query_list_new . " )  AS tbl_common GROUP BY content_owner ORDER BY `tbl_common`.`content_owner` ASC ";

    
    // $youtubereportresult = runQuery($youtubereport, $conn);
   
	//   /* This function negotiates that an email must be fetched from the database. All client info is keyed by the client's email
    //  *  However, in case an email is not desired, like in the case of fetching counts, a default email of "anonymous" will be used
    //  */
    // while ($row = mysqli_fetch_assoc($youtubereportresult["dbResource"])) {

    //     $res[] = $row;
    // }
   
}

function updatePercentage($table_type_name,  $conn)
{
    $res = array();
    $returnArr = array();
    $allClientsInfo = getActivationContentOwnerecords(
        $table_type_name,
        $conn,
        null,
        NUll,
        null
     );

     $flag_update_shares = 0;
     if (!noError($allClientsInfo)) { } else {
        $allClientsInfo = $allClientsInfo["errMsg"];
       // print_r($allClientsInfo);
        foreach($allClientsInfo as $clientEmail=>$clientDetails){ 
            $total_amt_recd = $clientDetails["total_amt_recd"];
            $witholding = $clientDetails["witholding"];
             $shares = $clientDetails["shares"];
            
            //$shares = $clientDetails["shares"];
            $content_owner = $clientDetails["content_owner"];
              $slab_percentage = get_sharespercentage($conn,$total_amt_recd,$witholding,$content_owner,$shares);
            if($slab_percentage!=$shares){

                $getClientInfoQuery = "SHOW TABLES LIKE '$table_type_name'";

                $getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
                if (!noError($getClientInfoQueryResult)) {
                    return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
                }
              
            
                while ($row = mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
                    //kisho
                    
                    foreach($row as $k=>$v){
                        $sql = "update {$v} set shares = '{$slab_percentage}' where content_owner='{$content_owner}'";
                        $getClientInfoQueryResult = runQuery($sql, $conn);
                        $flag_update_shares = 1;
                    }
                }

            }
           
        }

        //if shares is changed than 
        if($flag_update_shares==1){
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
                    $updateQueryResult2 = runQuery($updateQuery2, $conn);

                        $updateQuery3 = "UPDATE  {$desinationtable} a
                                set a.final_payable_with_gst= ROUND(a.final_payable + (final_payable * gst_percentage / 100),8)
                                ";
                    $updateQueryResult3 = runQuery($updateQuery3, $conn);
                    

                }
            }

            return setErrorStack($returnArr, -1, $res, null);
              
             
        }

     }
   
}

//slab = count no of records only youtube claim table
function updatePercentageViaGroup_old($table_type_name,  $conn)
{
    $res = array();
    $returnArr = array();
    $allClientsInfo = getActivationContentOwnerecordsGroup(
        $table_type_name,
        $conn,
        null,
        NUll,
        null
     );

     $flag_update_shares = 0;
     $table_name = [];
     if (!noError($allClientsInfo)) { } else {

          $getClientInfoQuery = "SHOW TABLES LIKE '$table_type_name'";
    
        $getClientInfoQueryResult = runQuery($getClientInfoQuery, $conn);
        if (!noError($getClientInfoQueryResult)) {
            return setErrorStack($returnArr, 3, $getClientInfoQueryResult["errMsg"], null);
        }
      
    
        while ($row = @mysqli_fetch_assoc($getClientInfoQueryResult["dbResource"])) {
            //kishore
           
            foreach($row as $k=>$v){
                $table_name[] =  $v;
            }
        }

        $allClientsInfo = $allClientsInfo["errMsg"];
       // print_r($allClientsInfo);exit;
        foreach($allClientsInfo as $clientEmail=>$clientDetails){ 
            $total_amt_recd = $clientDetails["total_amt_recd"];
            $witholding = $clientDetails["witholding"];
             $shares = $clientDetails["shares"];
            
            //$shares = $clientDetails["shares"];
            $content_owner = $clientDetails["content_owner"];

            if(!empty($content_owner)){
                $content_owner_expl = explode(",",$content_owner);
                $content_owner_expl  = make_array_unique($content_owner_expl);
                $content_owner_arr=[];
                foreach($content_owner_expl as $keyc => $valuec){
                    $content_owner_arr[] = " ( LOWER(content_owner) = '". strtolower(trim($valuec))."' )";
                }
                $content_owner_arr_final = implode(" or ", $content_owner_arr);
                $group_name = $clientDetails["group_name"];
                $slab_percentage = get_sharespercentage_groupcontentowner($conn,$total_amt_recd,$witholding,$group_name,$shares);
                //$slab_percentage!=$shares
                if(1){
                    foreach($table_name as $key2 => $tablename){
                          $sql = "update {$tablename} set shares = '{$slab_percentage}' where ".$content_owner_arr_final;
                          
                        $getClientInfoQueryResult = runQuery($sql, $conn);
                        $flag_update_shares = 1;
                    }
    
                }
            }
          
           
        }
       // exit;
        //if shares is changed than 
        if($flag_update_shares==1){
            
            foreach($table_name as $key2 => $tablename){
                $updateQuery2 = "UPDATE  {$tablename} a set a.amt_payable=ROUND((a.shares * a.total_amt_recd)/100,8),
                a.final_payable= ROUND(((a.total_amt_recd - a.witholding) * a.shares / 100),8) ";
                $updateQueryResult2 = runQuery($updateQuery2, $conn);

                $updateQuery3 = "UPDATE  {$tablename} a set a.final_payable_with_gst= ROUND(a.final_payable + (final_payable * gst_percentage / 100),8) ";
                $updateQueryResult3 = runQuery($updateQuery3, $conn);
            }
            //return setErrorStack($returnArr, -1, $updateQueryResult3, null);
              
             
        }

     }
   
}

function make_array_unique($array_unique=array()){
    $array_unique_temp = [];
    foreach($array_unique as $keyc => $valuec){
        $array_unique_temp[] = trim($valuec);
    }
    $array_unique_temp = array_unique($array_unique_temp);

    return $array_unique_temp;
}

//slab = count no of records only youtube claim table
function updatePercentageViaGroup($type,  $conn)
{
    $res = array();
    $returnArr = array();
    $allClientsInfo = getActivationContentOwnerecordsGroup1(
        $type,
        $conn,
        null,
        NUll,
        null
     );

     $flag_update_shares = 0;
     $table_name = [];
     if (!noError($allClientsInfo)) { } else {

        $returnArr = array();
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_ndkids_".$type;
    $table_list_temp[] = "youtube_ecom_paid_features_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_labelengine_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_redmusic_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_ndkids_".$type;
    $table_list_temp[] = "youtube_red_music_finance_activation_report_redmusic_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_nd1_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_nd2_".$type;
    $table_list_temp[] = "youtube_video_claim_activation_report_ndkids_".$type;
    $table_list = [];
    foreach($table_list_temp as $key => $value){
      $rt_status =   checkTableExistNew($value,$conn);
      if($rt_status){
        $table_name[] = $value;
      }
    }

        $allClientsInfo = $allClientsInfo["errMsg"];
       // print_r($allClientsInfo);exit;
        foreach($allClientsInfo as $clientEmail=>$clientDetails){ 
            $total_amt_recd = $clientDetails["total_amt_recd"];
            $witholding = $clientDetails["witholding"];
             $shares = $clientDetails["shares"];
            
            //$shares = $clientDetails["shares"];
            $content_owner = $clientDetails["content_owner"];

            if(!empty($content_owner)){
                $content_owner_expl = explode(",",$content_owner);
                $content_owner_expl  = make_array_unique($content_owner_expl);
                $content_owner_arr=[];
                foreach($content_owner_expl as $keyc => $valuec){
                    $content_owner_arr[] = " ( LOWER(content_owner) = '". strtolower(trim($valuec))."' )";
                }
                $content_owner_arr_final = implode(" or ", $content_owner_arr);
                $group_name = $clientDetails["group_name"];
                $slab_percentage = get_sharespercentage_groupcontentowner($conn,$total_amt_recd,$witholding,$group_name,$shares);
                //$slab_percentage!=$shares
                if(1){
                    foreach($table_name as $key2 => $tablename){
                       $sql = "update {$tablename} set shares = '{$slab_percentage}' where ".$content_owner_arr_final;
                          
                        $getClientInfoQueryResult = runQuery($sql, $conn);
                        $flag_update_shares = 1;
                    }
    
                }
            }
          
           
        }
       // exit;
        //if shares is changed than 
        if($flag_update_shares==1){
            
            foreach($table_name as $key2 => $tablename){
                     $updateQuery2 = "UPDATE  {$tablename} a set a.amt_payable=ROUND((a.shares * a.total_amt_recd)/100,8),
                a.final_payable= ROUND(((a.total_amt_recd - a.witholding) * a.shares / 100),8) ";
                $updateQueryResult2 = runQuery($updateQuery2, $conn);

                    $updateQuery3 = "UPDATE  {$tablename} a set a.final_payable_with_gst= ROUND(a.final_payable + (final_payable * gst_percentage / 100),8) ";
                $updateQueryResult3 = runQuery($updateQuery3, $conn);
            }
            //return setErrorStack($returnArr, -1, $updateQueryResult3, null);
              
             
        }

     }
   
}