<?php
function createYoutubeRedMusicReportTable($tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $createYoutubeTableQuery = "CREATE TABLE {$tableName}  (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`adjustmentType` varchar(50) DEFAULT NULL,
        `day` varchar(10)   DEFAULT NULL,
		`country` varchar(5)   DEFAULT NULL,
		`videoID` varchar(50)  DEFAULT NULL,
		`channelID` varchar(50) DEFAULT NULL,
		`assetID` varchar(50) DEFAULT NULL,
		`assetChannelID` varchar(50)  DEFAULT NULL,
		`assetType` varchar(50) DEFAULT NULL,
		`cutsomID` varchar(50) DEFAULT NULL,
        `ISRC` varchar(50) DEFAULT NULL,
        `UPC` varchar(50) DEFAULT NULL,
        `GRid` varchar(50) DEFAULT NULL,
		`contentType` varchar(50)  DEFAULT NULL,
		`policy` varchar(50) DEFAULT NULL,
		`claimType` varchar(50)  DEFAULT NULL,
		`claimOrigin` varchar(50)   DEFAULT NULL,
		`ownedViews` int(10) DEFAULT NULL,
		`youtubeRevenueSplitAuction` decimal(18,8) DEFAULT NULL,
		`youtubeRevenueSplitReserved` decimal(18,8) DEFAULT NULL,
		`youtubeRevenueSplitPartnerSoldYoutubeServed` decimal(18,8) DEFAULT NULL,
		`youtubeRevenueSplitPartnerSoldPartnerServed` decimal(18,8) DEFAULT NULL,
		`youtubeRevenueSplit` decimal(18,8) DEFAULT NULL,
		`partnerRevenueAuction` decimal(18,8) DEFAULT NULL,
		`partnerRevenueReserved` decimal(18,8) DEFAULT NULL,
		`partnerRevenuePartnerSoldYouTubeServed` decimal(18,8) DEFAULT NULL,
		`partnerRevenuePartnerSoldPartnerServed` decimal(18,8) DEFAULT NULL,
		`partnerRevenue` decimal(18,8) DEFAULT NULL,
		`content_owner` varchar(50) DEFAULT NULL,
		`Label` varchar(50) DEFAULT NULL,
        `video_title` varchar(150) DEFAULT NULL,
        `autoassign_steps` varchar(10) DEFAULT NULL,

		 PRIMARY KEY (id),
         INDEX i (assetChannelID),
         INDEX contentType (contentType),
         INDEX channelID (channelID),
         INDEX assetID (assetID),
         INDEX `policy` (policy),
         INDEX `Label` (Label),
         INDEX `videoID` (videoID),
         INDEX `video_title` (video_title),

         INDEX `content_owner` (content_owner)
		 ) ENGINE=InnoDB DEFAULT CHARSET=UTF8";

    $createAmazonTableQueryResult = runQuery($createYoutubeTableQuery, $conn);
    // printArr($createAmazonTableQueryResult);exit;

    if (noError($createAmazonTableQueryResult)) {
        $returnArr = setErrorStack($returnArr, -1, $createAmazonTableQueryResult);
    } else {
        $returnArr = setErrorStack($returnArr, 3, null);
    }

    return $returnArr;
}
function createYoutubeVideoClaimReportTable($tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $createYoutubeTableQuery = "CREATE TABLE {$tableName}  (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`adjustmentType` varchar(50) DEFAULT NULL,
        `day` varchar(10)   DEFAULT NULL,
		`country` varchar(5)   DEFAULT NULL,
		`videoID` varchar(50)  DEFAULT NULL,
		`channelID` varchar(50) DEFAULT NULL,
		`assetID` varchar(50) DEFAULT NULL,
		`assetChannelID` varchar(50)  DEFAULT NULL,
		`assetType` varchar(50) DEFAULT NULL,
		`cutsomID` varchar(50) DEFAULT NULL,
        `contentType` varchar(50)  DEFAULT NULL,
		`policy` varchar(50) DEFAULT NULL,
		`claimType` varchar(50)  DEFAULT NULL,
		`claimOrigin` varchar(50)   DEFAULT NULL,
		`ownedViews` int(10) DEFAULT NULL,
		`youtubeRevenueSplitAuction` decimal(18,8) DEFAULT NULL,
		`youtubeRevenueSplitReserved` decimal(18,8) DEFAULT NULL,
		`youtubeRevenueSplitPartnerSoldYoutubeServed` decimal(18,8) DEFAULT NULL,
		`youtubeRevenueSplitPartnerSoldPartnerServed` decimal(18,8) DEFAULT NULL,
		`youtubeRevenueSplit` decimal(18,8) DEFAULT NULL,
		`partnerRevenueAuction` decimal(18,8) DEFAULT NULL,
		`partnerRevenueReserved` decimal(18,8) DEFAULT NULL,
		`partnerRevenuePartnerSoldYouTubeServed` decimal(18,8) DEFAULT NULL,
		`partnerRevenuePartnerSoldPartnerServed` decimal(18,8) DEFAULT NULL,
		`partnerRevenue` decimal(18,8) DEFAULT NULL,
		`content_owner` varchar(50) DEFAULT NULL,
		`Label` varchar(50) DEFAULT NULL,
        `video_title` varchar(150) DEFAULT NULL,
        `autoassign_steps` varchar(10) DEFAULT NULL,

		 PRIMARY KEY (id),
         INDEX i (assetChannelID),
         INDEX contentType (contentType),
         INDEX channelID (channelID),
         INDEX assetID (assetID),
         INDEX `policy` (policy),
         INDEX `Label` (Label),
         INDEX `videoID` (videoID),
         INDEX `video_title` (video_title),
         INDEX partnerRevenue (partnerRevenue),
         INDEX `content_owner` (content_owner)
		 ) ENGINE=InnoDB DEFAULT CHARSET=UTF8";

    $createAmazonTableQueryResult = runQuery($createYoutubeTableQuery, $conn);
    // printArr($createAmazonTableQueryResult);exit;

    if (noError($createAmazonTableQueryResult)) {
        $returnArr = setErrorStack($returnArr, -1, $createAmazonTableQueryResult);
    } else {
        $returnArr = setErrorStack($returnArr, 3, null);
    }

    return $returnArr;
}
function createYoutubeVideoClaimReportTable2($tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $createYoutubeTableQuery = "CREATE TABLE {$tableName}  (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`claim_id` varchar(15) DEFAULT NULL,
		`claim_status` varchar(10) DEFAULT NULL,
		`claim_status_detail` text DEFAULT NULL,
		`claim_origin` varchar(50) DEFAULT NULL,
		`claim_type` varchar(50) DEFAULT NULL,
		`asset_id` varchar(50) DEFAULT NULL,
		`video_id` varchar(50) DEFAULT NULL,
		`uploader` varchar(50) DEFAULT NULL,
		`channel_id` varchar(50) DEFAULT NULL,
		`channel_display_name` varchar(150) DEFAULT NULL,
		`video_title` varchar(255) DEFAULT NULL,
		`view` int(11) DEFAULT NULL,
		`matching_duration` int(11) DEFAULT NULL,
		`longest_match` int(11) DEFAULT NULL,
		`content_type` varchar(50) DEFAULT NULL,
		`reference_video_id` varchar(50) DEFAULT NULL,
		`reference_id` varchar(50) DEFAULT NULL,
		`claim_policy_id` varchar(50) DEFAULT NULL,
		`asset_policy_id` varchar(50) DEFAULT NULL,
		`claim_policy_monetize` text DEFAULT NULL,
		`claim_policy_track` text DEFAULT NULL,
		`claim_policy_block` text DEFAULT NULL,
		`asset_policy_monetize` varchar(50) DEFAULT NULL,
		`asset_policy_track` varchar(50) DEFAULT NULL,
		`asset_policy_block` varchar(50) DEFAULT NULL,
		`claim_created_date` date DEFAULT NULL,
		`video_upload_date` date DEFAULT NULL,
		`custom_id` varchar(50) DEFAULT NULL,
		`video_duration_sec` int(11) DEFAULT NULL,
		`asset_title` text DEFAULT NULL,
		`asset_labels` varchar(50) DEFAULT NULL,
		`tms` varchar(50) DEFAULT NULL,
		`director` varchar(50) DEFAULT NULL,
		`studio` varchar(50) DEFAULT NULL,
		`season` varchar(50) DEFAULT NULL,
		`episode_number` varchar(50) DEFAULT NULL,
		`episode_title` text DEFAULT NULL,
		`release_date` date DEFAULT NULL,
		`hfa_song_code` varchar(50) DEFAULT NULL,
		`isrc` varchar(50) DEFAULT NULL,
		`grid` varchar(50) DEFAULT NULL,
		`artist` varchar(50) DEFAULT NULL,
		`album` varchar(50) DEFAULT NULL,
		`record_label` varchar(50) DEFAULT NULL,
		`upc` varchar(50) DEFAULT NULL,
		`iswc` varchar(50) DEFAULT NULL,
		`writers` varchar(50) DEFAULT NULL,
		`content_owner` varchar(50) DEFAULT NULL,
         PRIMARY KEY (id),
         INDEX j (asset_id,video_id,uploader,channel_id,channel_display_name,video_title,content_type,content_owner),
         INDEX composite (video_id,channel_id)
		) ENGINE=InnoDB DEFAULT CHARSET=UTF8";

    $createAmazonTableQueryResult = runQuery($createYoutubeTableQuery, $conn);
    // printArr($createAmazonTableQueryResult);exit;

    if (noError($createAmazonTableQueryResult)) {
        $returnArr = setErrorStack($returnArr, -1, $createAmazonTableQueryResult);
    } else {
        $returnArr = setErrorStack($returnArr, 3, null);
    }

    return $returnArr;
}
function createYoutubeVideoClaimReportTable3($tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }

    $createYoutubeTableQuery = "CREATE TABLE {$tableName}  (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`approx_daily_views` varchar(50) DEFAULT NULL,
		`asset_id` varchar(50)   DEFAULT NULL,
		`asset_type` varchar(250)  DEFAULT NULL,
		`status` varchar(250) DEFAULT NULL,
		`metadata_origination` varchar(250) DEFAULT NULL,
		`custom_id` varchar(250)  DEFAULT NULL,
		`season` varchar(250) DEFAULT NULL,
		`episode_title` varchar(250) DEFAULT NULL,
		`tms` varchar(100)  DEFAULT NULL,
		`active_claims` varchar(100) DEFAULT NULL,
		`constituent_asset_id` varchar(100)  DEFAULT NULL,
		`active_reference_id` varchar(100)   DEFAULT NULL,
		`inactive_reference_id` varchar(100) DEFAULT NULL,
		`match_policy` varchar(50) DEFAULT NULL,
		`is_merged` varchar(50) DEFAULT NULL,
		`conflicting_owner` varchar(100) DEFAULT NULL,
		`ownership` varchar(100) DEFAULT NULL,
		`asset_label` varchar(50) DEFAULT NULL,
		`conflicting_country_code` varchar(50) DEFAULT NULL,
		`content_owner` varchar(100) DEFAULT NULL,
         PRIMARY KEY (id),
         INDEX j (asset_id,asset_label,content_owner)
         ) ENGINE=InnoDB DEFAULT CHARSET=UTF8";

    $createAmazonTableQueryResult = runQuery($createYoutubeTableQuery, $conn);

    // printArr($createAmazonTableQueryResult);exit;

    if (noError($createAmazonTableQueryResult)) {
        $returnArr = setErrorStack($returnArr, -1, $createAmazonTableQueryResult);
    } else {
        $returnArr = setErrorStack($returnArr, 3, null);
    }

    return $returnArr;
}

function createYoutubeRedMusicReportTable3($tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }

    $createYoutubeTableQuery = "CREATE TABLE {$tableName}  (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`approx_daily_views` varchar(50) DEFAULT NULL,
		`asset_id` varchar(50)   DEFAULT NULL,
		`asset_type` varchar(250)  DEFAULT NULL,
		`status` varchar(250) DEFAULT NULL,
		`metadata_origination` varchar(250) DEFAULT NULL,
		`custom_id` varchar(250)  DEFAULT NULL,
        `ISRC` varchar(50) DEFAULT NULL,
        `UPC` varchar(50) DEFAULT NULL,
        `GRid` varchar(50) DEFAULT NULL,
        `artist` varchar(50) DEFAULT NULL,
		`season` varchar(250) DEFAULT NULL,
		`asset_title` varchar(250) DEFAULT NULL,
		`album` varchar(100)  DEFAULT NULL,
		`label` varchar(100) DEFAULT NULL,
		`constituent_asset_id` varchar(100)  DEFAULT NULL,
		`active_reference_id` varchar(100)   DEFAULT NULL,
		`inactive_reference_id` varchar(100) DEFAULT NULL,
		`match_policy` varchar(50) DEFAULT NULL,
		`is_merged` varchar(50) DEFAULT NULL,
		`ownership` varchar(100) DEFAULT NULL,
	 	`conflicting_country_code` varchar(50) DEFAULT NULL,
         `embedded_asset_id` varchar(50) DEFAULT NULL,
         `asset_label` varchar(50) DEFAULT NULL,
         `active_claims` varchar(50) DEFAULT NULL,
		`content_owner` varchar(100) DEFAULT NULL,
         PRIMARY KEY (id),
         INDEX j (asset_id,asset_label,content_owner)
         ) ENGINE=InnoDB DEFAULT CHARSET=UTF8";

    $createAmazonTableQueryResult = runQuery($createYoutubeTableQuery, $conn);
    @unlink("polo.txt");
    file_put_contents("polo.txt", $createYoutubeTableQuery);
    @chmod("polo.txt", 0777);
    // printArr($createAmazonTableQueryResult);exit;

    if (noError($createAmazonTableQueryResult)) {
        $returnArr = setErrorStack($returnArr, -1, $createAmazonTableQueryResult);
    } else {
        $returnArr = setErrorStack($returnArr, 3, null);
    }

    return $returnArr;
}
function insertYoutubeVideoClaimReportInfo($filePath, $tableName, $conn)
{

    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $res = array();
    $currentFile = '';
    if ($filePath) {
        $files = explode(',', $filePath);
        if (count($files) > 0) {
            $currentFile = $files[0];

            $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
            $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
            //$a3 = runQuery("SET sql_log_bin = 0;", $conn);
            $insertTableQuery = "LOAD DATA INFILE '{$currentFile}'
			INTO TABLE {$tableName}
			FIELDS TERMINATED BY ','
			ENCLOSED BY '\"'
			LINES TERMINATED BY '\\n'
			IGNORE 1 ROWS (`adjustmentType`,`day`,`country`, `videoID`, `channelID`, `assetID`, `assetChannelID`, `assetType`, `cutsomID`, `contentType`, `policy`, `claimType`, `claimOrigin`, `ownedViews`, `youtubeRevenueSplitAuction`, `youtubeRevenueSplitReserved`, `youtubeRevenueSplitPartnerSoldYoutubeServed`, `youtubeRevenueSplitPartnerSoldPartnerServed`, `youtubeRevenueSplit`, `partnerRevenueAuction`, `partnerRevenueReserved`, `partnerRevenuePartnerSoldYouTubeServed`, `partnerRevenuePartnerSoldPartnerServed`,`partnerRevenue`);";
            $insertTableQueryResult = runQuery($insertTableQuery, $conn);

            $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
            //    $a3 = runQuery("SET sql_log_bin = 1;", $conn);
            if (!noError($insertTableQueryResult)) {
                return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
            }
            $arr = array_shift($files);
            if (is_array($files)) {
                $filePath = implode(',', $files);
            }
            if ($filePath) {
                return insertYoutubeVideoClaimReportInfo($filePath, $tableName, $conn);
            } else {
                return setErrorStack($returnArr, -1, $res, null);
            }
        } else {
            return setErrorStack($returnArr, -1, $res, null);
        }
    } else {
        return setErrorStack($returnArr, -1, $res, null);
    }

    /* if (noError($insertTableQueryResult)) {
return setErrorStack($returnArr, -1, $res, null);
} else {
return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
} */
}

function insertYoutubeVideoClaimReportInfo2($filePath, $tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $res = array();
    $currentFile = '';
    if ($filePath) {
        $files = explode(',', $filePath);
        if (count($files) > 0) {
            $currentFile = $files[0];

            $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
            $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
            //$a3 = runQuery("SET sql_log_bin = 0;", $conn);
            $insertTableQuery = "LOAD DATA INFILE '{$currentFile}'
			INTO TABLE {$tableName}
			FIELDS TERMINATED BY ','
			ENCLOSED BY '\"'
			LINES TERMINATED BY '\\n'
			IGNORE 1 ROWS (`claim_id`,`claim_status`, `claim_status_detail`, `claim_origin`, `claim_type`, `asset_id`, `video_id`, `uploader`, `channel_id`, `channel_display_name`, `video_title`, `view`, `matching_duration`, `longest_match`, `content_type`, `reference_video_id`, `reference_id`, `claim_policy_id`, `asset_policy_id`, `claim_policy_monetize`, `claim_policy_track`, `claim_policy_block`, `asset_policy_monetize`, `asset_policy_track`, `asset_policy_block`, `claim_created_date`, `video_upload_date`, `custom_id`, `video_duration_sec`, `asset_title`, `asset_labels`, `tms`, `director`, `studio`, `season`, `episode_number`, `episode_title`, `release_date`, `hfa_song_code`, `isrc`, `grid`, `artist`, `album`, `record_label`, `upc`, `iswc`, `writers`);";

            $insertTableQueryResult = runQuery($insertTableQuery, $conn);

            $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
            //    $a3 = runQuery("SET sql_log_bin = 1;", $conn);
            if (!noError($insertTableQueryResult)) {

                return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
            }
            $arr = array_shift($files);
            if (is_array($files)) {
                $filePath = implode(',', $files);
            }
            if ($filePath) {
                return insertYoutubeVideoClaimReportInfo2($filePath, $tableName, $conn);
            } else {
                return setErrorStack($returnArr, -1, $res, null);
            }
        } else {
            return setErrorStack($returnArr, -1, $res, null);
        }
    } else {
        return setErrorStack($returnArr, -1, $res, null);
    }

    /* if (noError($insertTableQueryResult)) {
return setErrorStack($returnArr, -1, $res, null);
} else {
return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
} */
}

function insertYoutubeRedMusicVideoReportInfo3($filePath, $tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $res = array();
    $currentFile = '';
    if ($filePath) {
        $files = explode(',', $filePath);
        if (count($files) > 0) {
            $currentFile = $files[0];
            $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
            $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
            //$a3 = runQuery("SET sql_log_bin = 0;", $conn);

            $insertTableQuery = "LOAD DATA INFILE '{$currentFile}'
								INTO TABLE {$tableName}
								FIELDS TERMINATED BY ','
								ENCLOSED BY '\"'
								LINES TERMINATED BY '\\n'
								IGNORE 1 ROWS (`approx_daily_views`,`asset_id`, `asset_type`, `status`, `metadata_origination`, `custom_id`, `isrc`, `grid`, `upc`, `artist`, `asset_title`, `album`, `label`, `constituent_asset_id`, `active_reference_id`, `inactive_reference_id`, `match_policy`, `is_merged`, `ownership`, `conflicting_country_code`, `embedded_asset_id`, `asset_label`, `active_claims`);";

            @unlink("polo.txt");
            file_put_contents("polo.txt", $insertTableQuery);
            @chmod("polo.txt", 0777);

            $insertTableQueryResult = runQuery($insertTableQuery, $conn);

            $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
            //    $a3 = runQuery("SET sql_log_bin = 1;", $conn);
            if (!noError($insertTableQueryResult)) {
                return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
            }
            $arr = array_shift($files);
            if (is_array($files)) {
                $filePath = implode(',', $files);
            }
            if ($filePath) {
                return insertYoutubeRedMusicVideoReportInfo3($filePath, $tableName, $conn);
            } else {
                return setErrorStack($returnArr, -1, $res, null);
            }
        } else {
            return setErrorStack($returnArr, -1, $res, null);
        }
    } else {
        return setErrorStack($returnArr, -1, $res, null);
    }

    /* if (noError($insertTableQueryResult)) {
return setErrorStack($returnArr, -1, $res, null);
} else {
return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
} */
}

///////////
function insertYoutubeVideoClaimReportInfo3($filePath, $tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $res = array();
    $currentFile = '';
    if ($filePath) {
        $files = explode(',', $filePath);
        if (count($files) > 0) {
            $currentFile = $files[0];
            $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
            $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
            //$a3 = runQuery("SET sql_log_bin = 0;", $conn);

            $insertTableQuery = "LOAD DATA INFILE '{$currentFile}'
								INTO TABLE {$tableName}
								FIELDS TERMINATED BY ','
								ENCLOSED BY '\"'
								LINES TERMINATED BY '\\n'
								IGNORE 1 ROWS (`approx_daily_views`,`asset_id`, `asset_type`, `status`, `metadata_origination`, `custom_id`, `season`, `episode_title`, `tms`, `active_claims`, `constituent_asset_id`, `active_reference_id`, `inactive_reference_id`, `match_policy`, `is_merged`, `conflicting_owner`, `ownership`, `asset_label`, `conflicting_country_code`);";

            @unlink("polo.txt");
            file_put_contents("polo.txt", $insertTableQuery);
            @chmod("polo.txt", 0777);

            $insertTableQueryResult = runQuery($insertTableQuery, $conn);

            $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
            //    $a3 = runQuery("SET sql_log_bin = 1;", $conn);
            if (!noError($insertTableQueryResult)) {
                return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
            }
            $arr = array_shift($files);
            if (is_array($files)) {
                $filePath = implode(',', $files);
            }
            if ($filePath) {
                return insertYoutubeVideoClaimReportInfo3($filePath, $tableName, $conn);
            } else {
                return setErrorStack($returnArr, -1, $res, null);
            }
        } else {
            return setErrorStack($returnArr, -1, $res, null);
        }
    } else {
        return setErrorStack($returnArr, -1, $res, null);
    }

    /* if (noError($insertTableQueryResult)) {
return setErrorStack($returnArr, -1, $res, null);
} else {
return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
} */
}

//red musci
function insertYoutubeRedMusicVideoReportInfo($filePath, $tableName, $conn)
{

    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $res = array();
    $currentFile = '';
    if ($filePath) {
        $files = explode(',', $filePath);
        if (count($files) > 0) {
            $currentFile = $files[0];

            $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
            $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
            //$a3 = runQuery("SET sql_log_bin = 0;", $conn);
            $insertTableQuery = "LOAD DATA INFILE '{$currentFile}'
			INTO TABLE {$tableName}
			FIELDS TERMINATED BY ','
			ENCLOSED BY '\"'
			LINES TERMINATED BY '\\n'
            IGNORE 1 ROWS (`adjustmentType`,`day`,`country`, `videoID`, `channelID`, `assetID`, `assetChannelID`, `assetType`, `cutsomID`, `ISRC`, `UPC`, `GRid`, `contentType`, `policy`, `claimType`, `claimOrigin`, `ownedViews`, `youtubeRevenueSplitAuction`, `youtubeRevenueSplitReserved`, `youtubeRevenueSplitPartnerSoldYoutubeServed`, `youtubeRevenueSplitPartnerSoldPartnerServed`, `youtubeRevenueSplit`, `partnerRevenueAuction`, `partnerRevenueReserved`, `partnerRevenuePartnerSoldYouTubeServed`, `partnerRevenuePartnerSoldPartnerServed`,`partnerRevenue`);";

            @unlink("polo.txt");
            file_put_contents("polo.txt", $insertTableQuery);
            @chmod("polo.txt", 0777);

            $insertTableQueryResult = runQuery($insertTableQuery, $conn);

            $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
            $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
            //    $a3 = runQuery("SET sql_log_bin = 1;", $conn);
            if (!noError($insertTableQueryResult)) {
                return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
            }
            $arr = array_shift($files);
            if (is_array($files)) {
                $filePath = implode(',', $files);
            }
            if ($filePath) {
                return insertYoutubeRedMusicVideoReportInfo($filePath, $tableName, $conn);
            } else {
                return setErrorStack($returnArr, -1, $res, null);
            }
        } else {
            return setErrorStack($returnArr, -1, $res, null);
        }
    } else {
        return setErrorStack($returnArr, -1, $res, null);
    }

    /* if (noError($insertTableQueryResult)) {
return setErrorStack($returnArr, -1, $res, null);
} else {
return setErrorStack($returnArr, 3, $insertTableQueryResult['query'], null);
} */
}

//activation start
function createActivationYoutubeReportTablev2($tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $createYoutubeTableQuery = "CREATE TABLE {$tableName}  (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `content_owner` varchar(50)  NOT NULL,
							  `total_amt_recd` varchar(50)  NOT NULL,
							  `shares` varchar(50)  DEFAULT NULL,
							  `amt_payable` varchar(50)  DEFAULT NULL,
							  `us_payout` varchar(50)  DEFAULT NULL,
							  `witholding` varchar(50)  DEFAULT NULL,
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

function autoAssignContentOwnerChannelCOMapStep1($tableName, $conn)
{
    $returnArr = array();
    $res = array();

    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }

    //looping through array passed to create another array of where clauses
    //$whereClause = "assetChannelID=t1.assetChannelID";
    $whereClause = '';

    $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
    $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
    $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
    //$a3 = runQuery("SET sql_log_bin = 0;", $conn);

    //content_owner
    // update on the basis of contentType='Partner-provided' with channel_co_maping-> partner_provided
    $autoAssignChannelCOMapQuery = "UPDATE {$tableName}      inner join  	channel_co_maping on {$tableName}.assetChannelID = channel_co_maping.assetChannelID  SET {$tableName}.content_owner = channel_co_maping.partner_provided , {$tableName}.Label = channel_co_maping.Label ,autoassign_steps='1'  where ({$tableName}.content_owner='' || {$tableName}.content_owner is NULL) and {$tableName}.contentType='Partner-provided' ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    // update on the basis of contentType='UGC' with channel_co_maping-> ugc
    /*
    $autoAssignChannelCOMapQuery = "UPDATE {$tableName}      inner join      channel_co_maping on {$tableName}.assetChannelID = channel_co_maping.assetChannelID  SET {$tableName}.content_owner = channel_co_maping.ugc  ,{$tableName}.Label = channel_co_maping.Label  ,autoassign_steps='1' where ({$tableName}.content_owner='' || {$tableName}.content_owner is NULL) and {$tableName}.contentType='UGC' ";
    // file_put_contents(''.$tableName . date("ymd") . '.txt', $autoAssignChannelCOMapQuery, FILE_APPEND);
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
    return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }
     */

    $autoAssignChannelCOMapQuery = "UPDATE {$tableName}      inner join  	channel_co_maping on {$tableName}.assetChannelID = channel_co_maping.assetChannelID  SET {$tableName}.content_owner = channel_co_maping.ugc  ,{$tableName}.Label = channel_co_maping.Label  ,{$tableName}.autoassign_steps='1' where ({$tableName}.content_owner='' || {$tableName}.content_owner is NULL) and {$tableName}.contentType !='Partner-provided' ";
    // file_put_contents(''.$tableName . date("ymd") . '.txt', $autoAssignChannelCOMapQuery, FILE_APPEND);
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    ////////////updating label ////////////////////////////
    //content_owner
    // update on the basis of contentType='Partner-provided' with channel_co_maping-> partner_provided
    /*
    $autoAssignChannelCOMapQuery = "UPDATE {$tableName}      inner join      channel_co_maping on {$tableName}.assetChannelID = channel_co_maping.assetChannelID  SET {$tableName}.Label = channel_co_maping.Label    where ({$tableName}.Label='' || {$tableName}.Label is  NULL) ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
    return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }
     */

    ////////////////end o updateing label //////////////////////
    $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
    $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
    //$a3 = runQuery("SET sql_log_bin = 1;", $conn);
    if (noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }
}

function autoAssignContentOwnerChannelCOMapStep2($tableName, $conn)
{
    $returnArr = array();
    $res = array();

    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }

    //looping through array passed to create another array of where clauses
    //$whereClause = "assetChannelID=t1.assetChannelID";
    $whereClause = '';

    $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
    $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
    $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
    //$a3 = runQuery("SET sql_log_bin = 0;", $conn);
    $tableNameReport1 = "";

    $findme = 'youtuberedmusic_video_report';
    $pos = strpos($tableName, $findme);

    if ($pos === false) {
        $table_exp = explode('youtube_video_claim_report2_', $tableName);
        $tableNameReport1 = "youtube_video_claim_report_" . $table_exp[1];

    } else {
        $table_exp = explode('youtuberedmusic_video_report2_redmusic_', $tableName);
        $tableNameReport1 = "youtuberedmusic_video_report_redmusic_" . $table_exp[1];

    }

    $tableView = "view_" . $tableName;
    //content_owner
    //self upate update on the basis of contentType='Partner-provided' with channel_co_maping-> partner_provided

    /*
    $autoAssignChannelCOMapQuery1 = "UPDATE {$tableName}      inner join      channel_co_maping on {$tableName}.asset_labels = channel_co_maping.Label  SET {$tableName}.content_owner = channel_co_maping.partner_provided    where   {$tableName}.content_type='PARTNER_UPLOADED' ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery1, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
    return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }
     */

    /*
    //self update on the basis of contentType='UGC' with channel_co_maping-> ugc
    $autoAssignChannelCOMapQuery2 = "UPDATE {$tableName}  inner join      channel_co_maping on {$tableName}.asset_labels = channel_co_maping.Label  SET {$tableName}.content_owner = channel_co_maping.ugc    where   {$tableName}.content_type like '%UGC%' ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery2, $conn);

     */
    //drop view if exist. view sample : view_youtube_video_claim_report2_nd1_2020_12
    $autoAssignChannelCOMapQuery3 = "DROP VIEW IF EXISTS {$tableView} ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);

    //create view
    $autoAssignChannelCOMapQuery4 = "CREATE VIEW {$tableView} AS SELECT asset_id,count(asset_id) as assetCount,asset_labels,asset_title,content_owner,content_type FROM {$tableName} GROUP by asset_id ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery4, $conn);

    //update orginal table  youtube_video_claim_report_ on asset id
    //{$tableNameReport1}.content_owner = {$tableView}.content_owner,
    $autoAssignChannelCOMapQuery5 = "UPDATE {$tableNameReport1}      inner join  	{$tableView} on {$tableNameReport1}.assetID = {$tableView}.asset_id  SET  {$tableNameReport1}.video_title = {$tableView}.asset_title    ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery5, $conn);

    @unlink("polo.txt");
    file_put_contents("polo.txt", $autoAssignChannelCOMapQuery5);
    @chmod("polo.txt", 0777);
    //update orginal table  youtube_video_claim_report_ on label
    //{$tableNameReport1}.content_owner = {$tableView}.content_owner,

    /* // update main table using label
    $autoAssignChannelCOMapQuery6 = "UPDATE {$tableNameReport1}      inner join      {$tableView} on {$tableNameReport1}.Label = {$tableView}.asset_labels  SET  {$tableNameReport1}.video_title = {$tableView}.asset_title    where ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL)  and  {$tableView}.content_owner IS NOT NULL ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery6, $conn);
     */

    //update orginal table  youtube_video_claim_report_ on asset id
    //$autoAssignChannelCOMapQuery3 = "UPDATE {$tableNameReport1}      inner join      {$tableName} on {$tableNameReport1}.assetID = {$tableName}.asset_id  SET {$tableNameReport1}.content_owner = {$tableName}.content_owner    where ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL)  and  {$tableName}.content_owner IS NOT NULL ";
    // $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);

    //update orginal table  youtube_video_claim_report_ on label
    //$autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join      {$tableName} on {$tableNameReport1}.Label = {$tableName}.asset_labels  SET {$tableNameReport1}.content_owner = {$tableName}.content_owner    where ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL)  and  {$tableName}.content_owner IS NOT NULL  ";
    //$autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    //update video title and chanele display
    //`video_title` ,   `channel_display_name` varchar(150) DEFAULT NULL,
    //$autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join      {$tableName} on {$tableNameReport1}.Label = {$tableName}.asset_labels  SET {$tableNameReport1}.video_title = {$tableName}.video_title     ";

    /*  @unlink("polo.txt");
    file_put_contents("polo.txt", $autoAssignChannelCOMapQuery);
    @chmod("polo.txt",0777);
     */

    //$autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
    $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
    //$a3 = runQuery("SET sql_log_bin = 1;", $conn);
    if (noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }
}

function autoAssignContentOwnerChannelCOMapStep3($tableName, $conn)
{$returnArr = array();
    $res = array();

    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }

    //looping through array passed to create another array of where clauses
    //$whereClause = "assetChannelID=t1.assetChannelID";
    $whereClause = '';

    $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
    $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
    $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
    //$a3 = runQuery("SET sql_log_bin = 0;", $conn);
    $tableNameReport1 = "";

    // $mystring = $tableName;
    $findme = 'youtuberedmusic_video_report';
    $pos = strpos($tableName, $findme);

    if ($pos === false) {
        $table_exp = explode('youtube_video_claim_report3_', $tableName);
        $tableNameReport1 = "youtube_video_claim_report_" . $table_exp[1];

    } else {
        $table_exp = explode('youtuberedmusic_video_report3_redmusic_', $tableName);
        $tableNameReport1 = "youtuberedmusic_video_report_redmusic_" . $table_exp[1];

    }
    $CMS = "";
    $findme = 'nd1';
    $pos = strpos($tableNameReport1, $findme);
    if ($pos === false) {

    } else {
        $CMS = "ND1";
    }

    $findme = 'nd2';
    $pos = strpos($tableNameReport1, $findme);
    if ($pos === false) {

    } else {
        $CMS = "ND2";
    }

    $findme = 'ndkids';
    $pos = strpos($tableNameReport1, $findme);
    if ($pos === false) {

    } else {
        $CMS = "ND Kids";
    }

    $findme = 'redmusic';
    $pos = strpos($tableNameReport1, $findme);
    if ($pos === false) {

    } else {
        $CMS = "ND Music";
    }

    //ceating temp table on the basis of asset_id and label 2021-05-29
    $tableView = "t_pp_" . $tableName;
    $tableView2 = "t_ugc_" . $tableName;

    /////////////////////===================Now PP ===========================

    //drop TABLE if exist. TABLE sample : view_youtube_video_claim_report_nd1_2020_12
    $autoAssignChannelCOMapQuery3 = "DROP TABLE IF EXISTS {$tableView} ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);

    // create   table for PP

    $autoAssignChannelCOMapQuery = "CREATE TABLE {$tableView} AS SELECT r1.contentType, r1.assetID, COUNT(r1.videoID) AS cnt_video_id , cm.ugc, cm.partner_provided, cm.Label FROM {$tableNameReport1} r1 inner JOIN {$tableName} r3 on r1.assetID = r3.asset_id inner JOIN channel_co_maping cm on r3.asset_label = cm.Label WHERE r1.contentType = 'Partner-provided' AND r1.content_owner IS NULL and cm.CMS='{$CMS}' GROUP BY r1.assetID   ";

    @unlink("ppCreateTableStep3.txt");
    file_put_contents("ppCreateTableStep3.txt", $autoAssignChannelCOMapQuery);
    @chmod("ppCreateTableStep3.txt", 0777);

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    addIndexTempTable($tableView, $conn, 'partner_provided');
    addIndexTempTable($tableView, $conn, 'contentType');
    addIndexTempTable($tableView, $conn, 'Label');
    addIndexTempTable($tableView, $conn, 'asset_id');
    //update $tableName  on view
    // now update on asset id
    $autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join {$tableView}  ON  {$tableNameReport1}.assetID = {$tableView}.assetID      SET {$tableNameReport1}.content_owner = {$tableView}.partner_provided,{$tableNameReport1}.autoassign_steps='3' , {$tableNameReport1}.Label={$tableView}.Label  where {$tableNameReport1}.contentType = 'Partner-provided' and  ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL) ";

    @unlink("ppUpdateTableStep3.txt");
    file_put_contents("ppUpdateTableStep3.txt", $autoAssignChannelCOMapQuery);
    @chmod("ppUpdateTableStep3.txt", 0777);

    // assetid mapping
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

/////////////////////===================end PP ===========================

/////////////////////===================Now UGC ===========================
    // create   view for UGC
    $autoAssignChannelCOMapQuery3 = "DROP TABLE IF EXISTS {$tableView2} ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);

    // Working fine query if only UGC contentType

    $autoAssignChannelCOMapQuery = "CREATE TABLE {$tableView2} AS SELECT r1.contentType, r1.assetID, COUNT(r1.videoID) AS cnt_video_id, r1.videoID, cm.ugc, cm.partner_provided, cm.Label FROM {$tableNameReport1} r1 inner JOIN {$tableName} r3 on r1.assetID = r3.asset_id inner JOIN channel_co_maping cm on r3.asset_label = cm.Label WHERE r1.contentType != 'Partner-provided' AND r1.content_owner IS NULL  and cm.CMS='{$CMS}' GROUP BY r1.assetID   ";

    @unlink("ugcCreateTableStep3.txt");
    file_put_contents("ugcCreateTableStep3.txt", $autoAssignChannelCOMapQuery);
    @chmod("ugcCreateTableStep3.txt", 0777);

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    addIndexTempTable($tableView2, $conn, 'partner_provided');
    addIndexTempTable($tableView2, $conn, 'contentType');
    addIndexTempTable($tableView2, $conn, 'Label');
    addIndexTempTable($tableView2, $conn, 'asset_id');

    //update on assett id
    $autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join  	{$tableView2} on  {$tableNameReport1}.assetID = {$tableView2}.assetID   SET {$tableNameReport1}.content_owner = {$tableView2}.ugc ,{$tableNameReport1}.autoassign_steps='3'  , {$tableNameReport1}.Label={$tableView2}.Label  where   {$tableNameReport1}.contentType != 'Partner-provided'  and  ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL) ";

    @unlink("ugcUpdateTableStep3.txt");
    file_put_contents("ugcUpdateTableStep3.txt", $autoAssignChannelCOMapQuery);
    @chmod("ugcUpdateTableStep3.txt", 0777);

    // assetid mapping commented 23rd April
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

////////////////////===================end UGC ============================

    //end===============================   2021-05-29 ============================
/*
// on the basis  of video id and asset id
    $tableView = "view_pp_" . $tableName;
    $tableView2 = "view_ugc_" . $tableName;

//drop TABLE if exist. TABLE sample : view_youtube_video_claim_report_nd1_2020_12
    $autoAssignChannelCOMapQuery3 = "DROP TABLE IF EXISTS {$tableView} ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);

// create   view for PP
    $autoAssignChannelCOMapQuery = "CREATE TABLE {$tableView} AS SELECT r1.contentType, r1.assetID, COUNT(r1.videoID) AS cnt_video_id, r1.videoID, cm.ugc, cm.partner_provided, cm.Label FROM {$tableNameReport1} r1 inner JOIN {$tableName} r3 on r1.assetID = r3.asset_id inner JOIN channel_co_maping cm on r3.asset_label = cm.Label WHERE r1.contentType = 'Partner-provided' AND r1.content_owner IS NULL and cm.CMS='{$CMS}' GROUP BY r1.assetID, r1.videoID  ";

    @unlink("pp.txt");
    file_put_contents("pp.txt", $autoAssignChannelCOMapQuery);
    @chmod("pp.txt", 0777);

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    addIndexTempTable($tableView, $conn, 'partner_provided');

// now update on video id
    $autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join {$tableView}  ON  {$tableNameReport1}.videoID = {$tableView}.videoID      SET {$tableNameReport1}.content_owner = {$tableView}.partner_provided,{$tableNameReport1}.autoassign_steps='3' where {$tableNameReport1}.contentType = 'Partner-provided' and  ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL) ";

    @unlink("polo_update_pp_step3.txt");
    file_put_contents("polo_update_pp_step3.txt", $autoAssignChannelCOMapQuery);
    @chmod("polo_update_pp_step3.txt", 0777);

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

// create   view for UGC
    $autoAssignChannelCOMapQuery3 = "DROP TABLE IF EXISTS {$tableView2} ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);

// Working fine query if only UGC contentType

    $autoAssignChannelCOMapQuery = "CREATE TABLE {$tableView2} AS SELECT r1.contentType, r1.assetID, COUNT(r1.videoID) AS cnt_video_id, r1.videoID, cm.ugc, cm.partner_provided, cm.Label FROM {$tableNameReport1} r1 inner JOIN {$tableName} r3 on r1.assetID = r3.asset_id inner JOIN channel_co_maping cm on r3.asset_label = cm.Label WHERE r1.contentType != 'Partner-provided' AND r1.content_owner IS NULL  and cm.CMS='{$CMS}' GROUP BY r1.assetID, r1.videoID  ";

    @unlink("ugc2.txt");
    file_put_contents("ugc2.txt", $autoAssignChannelCOMapQuery);
    @chmod("ugc2.txt", 0777);

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    addIndexTempTable($tableView2, $conn, 'ugc');

//update $tableName  on view // contentType = 'UGC'

//update on video id
    $autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join      {$tableView2} on  {$tableNameReport1}.videoID = {$tableView2}.videoID   SET {$tableNameReport1}.content_owner = {$tableView2}.ugc ,{$tableNameReport1}.autoassign_steps='3' where   {$tableNameReport1}.contentType != 'Partner-provided'  and  ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL) ";

    @unlink("polo3.txt");
    file_put_contents("polo3.txt", $autoAssignChannelCOMapQuery);
    @chmod("polo3.txt", 0777);

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }
    */

    $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
    $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
    //$a3 = runQuery("SET sql_log_bin = 1;", $conn);
    if (noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

     

}

function createActivationYoutubeClaimReportTablev2($tableName, $conn)
{
    $returnArr = array();
    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }
    $createYoutubeTableQuery = "CREATE TABLE {$tableName}  (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `content_owner` varchar(50)  NOT NULL,
							  `total_amt_recd` varchar(50)  NOT NULL,
							  `shares` varchar(50)  DEFAULT NULL,
							  `amt_payable` varchar(50)  DEFAULT NULL,
							  `us_payout` varchar(50)  DEFAULT NULL,
							  `witholding` varchar(50)  DEFAULT NULL,
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

function generateActicationYoutubeLabelEngineReportv2($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO {$desinationtable} (content_owner, total_amt_recd,us_payout,witholding)
					SELECT
						content_owner,
						Coalesce(ROUND(SUM(partnerRevenue),8),0),
						0,
						0
					FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";
    $updateQueryResult = runQuery($updateQuery, $conn);

    @unlink("polo_ACT.txt");
    file_put_contents("polo_ACT.txt", $updateQuery);
    @chmod("polo_ACT.txt", 0777);

    

    if (noError($updateQueryResult)) {

        $result = array_map('strrev', explode('_', strrev($sourcetable)));
        if($result[2]=="redmusic"){
            $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
            set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio')),
            a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio'))*a.total_amt_recd)/100,8),
            a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio'))/100),8)
            where b.client_username =a.content_owner and b.`status` =1 and b.client_type_details!=''";
        } else {
            $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
            set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube')),
            a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))*a.total_amt_recd)/100,8),
            a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))/100),8)
            where b.client_username =a.content_owner and b.`status` =1 and b.client_type_details!=''";
        }
      

        @unlink("polo_ACT_update.txt");
        file_put_contents("polo_ACT_update.txt", $updateQuery);
        @chmod("polo_ACT_update.txt", 0777);

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
function generateActicationYoutubeRedMusicReportv2($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO {$desinationtable} (content_owner, total_amt_recd,us_payout,witholding)
					SELECT
						content_owner,
						Coalesce(ROUND(SUM(partnerRevenue),8),0),
						Coalesce(ROUND(SUM(CASE WHEN country='US' THEN partnerRevenue END),8),0),
						Coalesce(ROUND((SUM(CASE WHEN country='US' THEN partnerRevenue END)*30/100),8),0)
					FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";
    $updateQueryResult = runQuery($updateQuery, $conn);

    @unlink("polo_ACT.txt");
    file_put_contents("polo_ACT.txt", $updateQuery);
    @chmod("polo_ACT.txt", 0777);

    if (noError($updateQueryResult)) {
        $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
		                set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio')),
						a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio'))*a.total_amt_recd)/100,8),
						a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio'))/100),8)
                        where b.client_username =a.content_owner   and b.`status` =1 and b.client_type_details!=''";


 

        @unlink("polo_ACT_update.txt");
        file_put_contents("polo_ACT_update.txt", $updateQuery);
        @chmod("polo_ACT_update.txt", 0777);

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

function generateActicationYoutubeclaimReportv2($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO {$desinationtable} (content_owner, total_amt_recd,us_payout,witholding)
					SELECT
						content_owner,
						Coalesce(ROUND(SUM(partnerRevenue),8),0),
						Coalesce(ROUND(SUM(CASE WHEN country='US' THEN partnerRevenue END),8),0),
						Coalesce(ROUND((SUM(CASE WHEN country='US' THEN partnerRevenue END)*30/100),8),0)
					FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";
    $updateQueryResult = runQuery($updateQuery, $conn);

    @unlink("polo_ACT.txt");
    file_put_contents("polo_ACT.txt", $updateQuery);
    @chmod("polo_ACT.txt", 0777);

    if (noError($updateQueryResult)) {
        $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
		                set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube')),
						a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))*a.total_amt_recd)/100,8),
						a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))/100),8)
                        where b.client_username =a.content_owner   and b.`status` =1 and b.client_type_details!=''";


 

        @unlink("polo_ACT_update.txt");
        file_put_contents("polo_ACT_update.txt", $updateQuery);
        @chmod("polo_ACT_update.txt", 0777);

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

function generateActication_youtube_red_music_video_finance_Reportv2($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO
					{$desinationtable} (content_owner, total_amt_recd,us_payout,witholding)
					SELECT
						content_owner,
						Coalesce(ROUND(SUM(partnerRevenue),8),0),
						Coalesce(ROUND(SUM(CASE WHEN country='US' THEN partnerRevenue END),8),0),
						Coalesce(ROUND((SUM(CASE WHEN country='US' THEN partnerRevenue END)*30/100),8),0)
                    FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";

    @unlink("polo_ACT.txt");
    file_put_contents("polo_ACT.txt", $updateQuery);
    @chmod("polo_ACT.txt", 0777);

    $updateQueryResult = runQuery($updateQuery, $conn);

    if (noError($updateQueryResult)) {

        $result = array_map('strrev', explode('_', strrev($sourcetable)));

        if($result[2]=="redmusic"){
            $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
            set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudioRed')),
            a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudioRed'))*a.total_amt_recd)/100,8),
            a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudioRed'))/100),8)
            where b.client_username =a.content_owner and b.`status` =1 and b.client_type_details!=''";

        } else {
            $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
            set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeRed')),
            a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeRed'))*a.total_amt_recd)/100,8),
            a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeRed'))/100),8)
            where b.client_username =a.content_owner and b.`status` =1 and b.client_type_details!=''";

        }
       
        @unlink("polo_ACT_update.txt");
        file_put_contents("polo_ACT_update.txt", $updateQuery);
        @chmod("polo_ACT_update.txt", 0777);

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

function generateActication_youtube_ecommerce_paid_features_Reportv2($sourcetable, $desinationtable, $conn)
{

    $res = array();
    $returnArr = array();
    $extraArg = array();

    $updateQuery = "INSERT INTO
					{$desinationtable} (content_owner, total_amt_recd,us_payout,witholding)
					SELECT
						content_owner,
						Coalesce(ROUND(SUM(earnings),8),0),
						Coalesce(ROUND(SUM(CASE WHEN country='US' THEN earnings END),8),0),
						Coalesce(ROUND((SUM(CASE WHEN country='US' THEN earnings END)*30/100),8),0)

                    FROM  {$sourcetable} where content_owner!='' GROUP by content_owner";

    @unlink("polo_ACT.txt");
    file_put_contents("polo_ACT.txt", $updateQuery);
    @chmod("polo_ACT.txt", 0777);

    $updateQueryResult = runQuery($updateQuery, $conn);

    if (noError($updateQueryResult)) {
        
        $result = array_map('strrev', explode('_', strrev($sourcetable)));
        if($result[2]=="redmusic"){

            $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
            set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio')),
            a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio'))*a.total_amt_recd)/100,8),
            a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutubeAudio'))/100),8)
            where b.client_username =a.content_owner and b.`status` =1 and b.client_type_details!=''";

        } else {
            $updateQuery = "UPDATE  {$desinationtable} a,crep_cms_clients b
            set a.shares = JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube')),
            a.amt_payable=ROUND((JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))*a.total_amt_recd)/100,8),
            a.final_payable= ROUND(((a.total_amt_recd-a.witholding)*JSON_UNQUOTE(JSON_EXTRACT(b.client_type_details, '$.revenueShareYoutube'))/100),8)
            where b.client_username =a.content_owner and b.`status` =1 and b.client_type_details!=''";

        }
       
        @unlink("polo_ACT_upd.txt");
        file_put_contents("polo_ACT_upd.txt", $updateQuery);
        @chmod("polo_ACT_upd.txt", 0777);

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

function addIndexTempTable($tableName, $conn, $additionalField = '')
{
    $returnArr = array();

    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " {$tableName} to create index be empty", null);
    }

    $res = array();

    $truncateTableQuery = "ALTER TABLE {$tableName} ADD INDEX( `videoID`)";
    $truncateTableQueryResult = runQuery($truncateTableQuery, $conn);

    $truncateTableQuery = "ALTER TABLE {$tableName} ADD INDEX( `assetID`)";
    $truncateTableQueryResult = runQuery($truncateTableQuery, $conn);

    if ($additionalField != '') {
        $truncateTableQuery = "ALTER TABLE {$tableName} ADD INDEX( `" . $additionalField . "`)";
        $truncateTableQueryResult = runQuery($truncateTableQuery, $conn);
    }

    if (noError($truncateTableQueryResult)) {
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $truncateTableQueryResult["errMsg"], null);
    }
}

function addIndexTempTable3($tableName, $conn, $additionalField = '')
{
    $returnArr = array();

    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " {$tableName} to create index be empty", null);
    }

    $res = array();

    if ($additionalField != '') {
        $truncateTableQuery = "ALTER TABLE {$tableName} ADD INDEX( `" . $additionalField . "`)";
        $truncateTableQueryResult = runQuery($truncateTableQuery, $conn);
    }

    if (noError($truncateTableQueryResult)) {
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $truncateTableQueryResult["errMsg"], null);
    }
}

function autoAssignContentOwnerChannelCOMapStep3_redmusic($tableName, $conn)
{$returnArr = array();
    $res = array();

    if (empty($tableName)) {
        return setErrorStack($returnArr, 4, getErrMsg(4) . " Tablename to create cannot be empty", null);
    }

    //looping through array passed to create another array of where clauses
    //$whereClause = "assetChannelID=t1.assetChannelID";
    $whereClause = '';

    $a1 = runQuery("SET GLOBAL sql_mode = '';", $conn);
    $a1 = runQuery("SET UNIQUE_CHECKS = 0;", $conn);
    $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 0;", $conn);
    //$a3 = runQuery("SET sql_log_bin = 0;", $conn);
    $tableNameReport1 = "";
    $table_exp = explode('youtuberedmusic_video_report3_redmusic_', $tableName);

    $tableNameReport1 = "youtuberedmusic_video_report_redmusic_" . $table_exp[1];
    $tableView = "view_pp_" . $tableName;
    $tableView2 = "view_ugc_" . $tableName;
    //content_owner
    // self update on the basis of  label with channel_co_maping-> Label

/*     // They said that we should consider this as partner_provided only as asset lable file does not have ugc records.
$autoAssignChannelCOMapQuery = "UPDATE {$tableName}      inner join      channel_co_maping on {$tableName}.asset_label = channel_co_maping.Label  SET {$tableName}.content_owner = channel_co_maping.partner_provided ";
$autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

if (!noError($autoAssignChannelCOMapQueryResult)) {
return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
} */

    /*  @unlink("polo.txt");
    file_put_contents("polo.txt", $autoAssignChannelCOMapQuery);
    @chmod("polo.txt",0777);
     */
    //update orginal table  youtube_video_claim_report_ on label
    //$autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join      {$tableName} on {$tableNameReport1}.Label = {$tableName}.asset_label  SET {$tableNameReport1}.content_owner = {$tableName}.content_owner,{$tableNameReport1}.video_title = {$tableName}.episode_title    where ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL)   ";

    // $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    /*
    // They said that we should consider this as partner_provided only as asset asset_id count is 1 and consider UGC count more than 1.

    $autoAssignChannelCOMapQuery = "UPDATE {$tableName}      inner join      channel_co_maping on {$tableName}.asset_label = channel_co_maping.Label  SET {$tableName}.content_owner = channel_co_maping.partner_provided ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
    return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    } */

    //drop view if exist. view sample : view_youtube_video_claim_report_nd1_2020_12
    $autoAssignChannelCOMapQuery3 = "DROP TABLE IF EXISTS {$tableView} ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);

    // create   view for PP
    $autoAssignChannelCOMapQuery = "CREATE TABLE {$tableView} AS SELECT r1.contentType, r1.assetID, COUNT(r1.videoID) AS cnt_video_id, r1.videoID, cm.ugc, cm.partner_provided, cm.Label FROM {$tableNameReport1} r1 inner JOIN {$tableName} r3 on r1.assetID = r3.asset_id inner JOIN channel_co_maping cm on r3.asset_label = cm.Label WHERE r1.contentType = 'Partner-provided' AND r1.content_owner IS NULL GROUP BY r1.assetID, r1.videoID  ";

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    addIndexTempTable($tableView, $conn);

    //update $tableName  on view
    $autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join {$tableView}  ON  {$tableNameReport1}.videoID = {$tableView}.videoID      SET {$tableNameReport1}.content_owner = {$tableView}.partner_provided where {$tableNameReport1}.contentType = 'Partner-provided' and  ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL) ";

    @unlink("polo.txt");
    file_put_contents("polo.txt", $autoAssignChannelCOMapQuery);
    @chmod("polo.txt", 0777);

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    // create   view for UGC
    $autoAssignChannelCOMapQuery3 = "DROP TABLE IF EXISTS {$tableView2} ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);

    $autoAssignChannelCOMapQuery = "CREATE TABLE {$tableView2} AS SELECT r1.contentType, r1.assetID, COUNT(r1.videoID) AS cnt_video_id, r1.videoID, cm.ugc, cm.partner_provided, cm.Label FROM {$tableNameReport1} r1 inner JOIN {$tableName} r3 on r1.assetID = r3.asset_id inner JOIN channel_co_maping cm on r3.asset_label = cm.Label WHERE r1.contentType = 'UGC' AND r1.content_owner IS NULL GROUP BY r1.assetID, r1.videoID  ";

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);
    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

    addIndexTempTable($tableView2, $conn);

    //update $tableName  on view
    $autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join  	{$tableView2} on  {$tableNameReport1}.videoID = {$tableView2}.videoID   SET {$tableNameReport1}.content_owner = {$tableView2}.ugc  where   {$tableNameReport1}.contentType = 'UGC'  and  ({$tableNameReport1}.content_owner='' || {$tableNameReport1}.content_owner is NULL) ";

    @unlink("polo1.txt");
    file_put_contents("polo1.txt", $autoAssignChannelCOMapQuery);
    @chmod("polo1.txt", 0777);

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }
/*
//update view on partner_provided count ==1
$autoAssignChannelCOMapQuery = "UPDATE {$tableView}      inner join      channel_co_maping on {$tableView}.asset_label = channel_co_maping.Label  SET {$tableView}.content_owner = channel_co_maping.partner_provided  where count_asset_id = 1";

@unlink("polo.txt");
file_put_contents("polo.txt", $autoAssignChannelCOMapQuery);
@chmod("polo.txt", 0777);

$autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

if (!noError($autoAssignChannelCOMapQueryResult)) {
return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
}

//update view on ugc count > 1
$autoAssignChannelCOMapQuery = "UPDATE {$tableView}      inner join      channel_co_maping on {$tableView}.asset_label = channel_co_maping.Label  SET {$tableView}.content_owner = channel_co_maping.ugc  where count_asset_id > 1";

$autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

if (!noError($autoAssignChannelCOMapQueryResult)) {
return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
}

//update $tableName  on view
$autoAssignChannelCOMapQuery = "UPDATE {$tableName}      inner join      {$tableView} on {$tableName}.asset_id = {$tableView}.asset_id  SET {$tableName}.content_owner = {tableView}.content_owner  ";
$autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

if (!noError($autoAssignChannelCOMapQueryResult)) {
return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
}
$autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join      {$tableView} on {$tableNameReport1}.assetID = {$tableName}.assetID  SET {$tableNameReport1}.content_owner = {$tableName}.content_owner   ";
$autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

@unlink("polo.txt");
file_put_contents("polo.txt", $autoAssignChannelCOMapQuery);
@chmod("polo.txt", 0777); */

    //update VIEW
    /*
    $autoAssignChannelCOMapQuery = "UPDATE {$tableView} inner join {$tableName} on {$tableView}.assetID = {$tableName}.asset_id  SET {$tableView}.content_owner = {$tableName}.content_owner
    where ({$tableView}.content_owner IS  NULL || {$tableView}.content_owner ='' )";

    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    //`video_title` ,   `channel_display_name` varchar(150) DEFAULT NULL,
    //$autoAssignChannelCOMapQuery = "UPDATE {$tableNameReport1}      inner join      {$tableName} on {$tableNameReport1}.Label = {$tableName}.asset_label  SET {$tableNameReport1}.video_title = {$tableName}.episode_title   ";
    // $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery, $conn);

    if (!noError($autoAssignChannelCOMapQueryResult)) {
    return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }
     */
    /*  $autoAssignChannelCOMapQuery3 = "DROP VIEW IF EXISTS {$tableView} ";
    $autoAssignChannelCOMapQueryResult = runQuery($autoAssignChannelCOMapQuery3, $conn);
     */
    $a1 = runQuery("SET UNIQUE_CHECKS = 1;", $conn);
    $a2 = runQuery("SET FOREIGN_KEY_CHECKS  = 1;", $conn);
    //$a3 = runQuery("SET sql_log_bin = 1;", $conn);
    if (noError($autoAssignChannelCOMapQueryResult)) {
        return setErrorStack($returnArr, -1, $res, null);
    } else {
        return setErrorStack($returnArr, 3, $autoAssignChannelCOMapQueryResult, null);
    }

}
