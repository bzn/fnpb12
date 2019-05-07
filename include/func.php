<?php
/*
 * func.php
 */
include_once(dirname(__FILE__)."/init.php");
include_once(dirname(__FILE__)."/define.php");
include_once(dirname(__FILE__)."/../class/db.class.php");
include_once(dirname(__FILE__)."/../class/myteam.class.php");

/*
	// PDO範例
	$sql = "SELECT * FROM `player_basedata` WHERE id = 11";
	$stmt = $DB_NPB->query($sql);
	// 設定查詢結果的資料格式，之後可以省去 fetch 時的格式設定
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	// 取得查詢結果的列數
	$count = $stmt->rowCount ();
	// 取得一列的查詢結果
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	// 取得所有的查詢結果列
	$dataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
	var_dump($dataArray);
*/

function IsAdmin($fbID)
{
	if($fbID == 614384297)
		return true;
	return false;
}

// 取得球隊總分
function GetPointsByMyTeamID($myTeamID)
{
	if($myTeamID <= 0)
		return;
	$db = new DB();
	$sql = "SELECT `points` FROM `myteam_data` WHERE `id`=".$myTeamID;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['points'];
}

function GetUserNameByTeamID($myTeamID)
{
	if($myTeamID <= 0)
		return;
	// 取得userid
	$db = new DB();
	$sql = "SELECT `user_id` FROM `user_myteam_index` WHERE `myteam_id`=".$myTeamID;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $userID = $row['user_id'];
	// 取得名稱
	// ....
}

function GetMyTeamName($myTeamID)
{
	if($myTeamID <= 0)
		return;
	$db = new DB();
	$sql = "SELECT `name` FROM `myteam_data` WHERE `id`=".$myTeamID;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['name'];
}

function GetUserDataByTeamID($myTeamID)
{
	if($myTeamID <= 0)
		return false;
	$db = new DB();
	$sql = "SELECT A.* FROM `user_data` AS A LEFT JOIN 
							`user_myteam_index` AS B ON A.id=B.user_id 
			WHERE B.`myteam_id`=".$myTeamID;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row;
}

function GetMoneyFormat($money)
{
	$str = sprintf("%.2fM",$money/100);
	return $str;
}

// 取得最新排名
function GetRankPoints($myTeamID)
{
	if($myTeamID <= 0)
		return;
	$db = new DB();
	$sql = "SELECT `rank_points` FROM `myteam_rec_daily` WHERE `myteam_id` = '".$myTeamID."' ORDER BY `date` DESC";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if(empty($row['rank_points']))
		$row['rank_points'] = "-";
	return $row['rank_points'];
}

function GetRankMoney($myTeamID)
{
	if($myTeamID <= 0)
		return;
	$db = new DB();
	$sql = "SELECT `rank_money` FROM `myteam_rec_daily` WHERE `myteam_id` = '".$myTeamID."' ORDER BY `date` DESC";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if(empty($row['rank_money']))
		$row['rank_money'] = "-";
	return $row['rank_money'];
}

// 球季是否已經開始
function IsSeasonStart2()
{
	//return false;
	$db = new DB();
	// 如果system_daily_log的第一筆做過pricemove就是球季開始了
	$sql = "SELECT `pricemove_time` FROM `system_daily_log` ORDER BY `date`";
	echo $sql;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	echo $row['pricemove_time'];
	if($row['pricemove_time'] === "0000-00-00 00:00:00")
		return false;
	else
		return true;
}

// 球季是否已經開始
function IsSeasonStart()
{
	//return false;
	$db = new DB();
	// 如果system_daily_log的第一筆做過pricemove就是球季開始了
	$sql = "SELECT `pricemove_time` FROM `system_daily_log` ORDER BY `date`";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if($row['pricemove_time'] === "0000-00-00 00:00:00")
		return false;
	else
		return true;
}

// 是否為開賽首日
function IsOpenDay()
{
	$datetime = GetLocalTime();
	$date = substr($datetime, 0, 10);
	if($date === OPEN_DATE)
		return true;
	else
		return false;
}

// 當前交易是否凍結
function IsForzen2()
{
	$db = new DB();
	$localTime = "2012-03-30 17:59:00";
	echo $date = substr($localTime, 0, 10);
	
	// 今天已經unforzen
	$systemDailyLog = GetSystemDailyLog($date);
	if($systemDailyLog)
	{
		//var_dump($systemDailyLog);
		// 檢查該比賽當天，是否已經unforzen
		if($systemDailyLog->unforzen_time > "0000-00-00 00:00:00")
		{
			return false;
		}
	}
	else
	{
		// 賽季未開始
		return false;	
	}
	
	// 取得今天的第一場比賽時間
	$sql = "SELECT `datetime` FROM `schedule_data` WHERE `datetime` > '".$date." 00:00:00' AND `datetime` < '".$date." 24:00:00' ORDER BY `datetime`";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$todayGameDatetime = $row['datetime'];

	// 今天有比賽
	if($todayGameDatetime)
	{
		//echo "今天有比賽";
		// 今天比賽已經開始
		if($localTime >= $todayGameDatetime)
		{
			// 今天尚未unforzen
			return true;
		}		
	}
	else	// 今天無比賽
	{
		//echo "今天沒有比賽";
		// 已經日本時間1800
		if($localTime >= $date." 18:00:00")
		{
			return true;
		}			
	}

	// 如果前一個日子未凍結
	$sql = "SELECT * FROM system_daily_log 
			WHERE `date`<'".$date."' ORDER BY `date` DESC";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$systemDailyLog = $row;
	if($systemDailyLog && $systemDailyLog['unforzen_time'] === "0000-00-00 00:00:00")
		return true;
	return false;
}

// 當前交易是否凍結
function IsForzen()
{
	$db = new DB();
	$localTime = GetLocalTime();
	$date = substr($localTime, 0, 10);
	
	// 今天已經unforzen
	$systemDailyLog = GetSystemDailyLog($date);
	if($systemDailyLog)
	{
		//var_dump($systemDailyLog);
		// 檢查該比賽當天，是否已經unforzen
		if($systemDailyLog->unforzen_time > "0000-00-00 00:00:00")
		{
			return false;
		}
	}
	else
	{
		// 賽季未開始
		return false;	
	}
	
	// 取得今天的第一場比賽時間
	$sql = "SELECT `datetime` FROM `schedule_data` WHERE `datetime` > '".$date." 00:00:00' AND `datetime` < '".$date." 24:00:00' ORDER BY `datetime`";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$todayGameDatetime = $row['datetime'];

	// 今天有比賽
	if($todayGameDatetime)
	{
		//echo "今天有比賽";
		// 今天比賽已經開始
		if($localTime >= $todayGameDatetime)
		{
			// 今天尚未unforzen
			return true;
		}		
	}
	else	// 今天無比賽
	{
		//echo "今天沒有比賽";
		// 已經日本時間1800
		if($localTime >= $date." 18:00:00")
		{
			return true;
		}			
	}

	// 如果前一個日子未凍結
	$sql = "SELECT * FROM system_daily_log 
			WHERE `date`<'".$date."' ORDER BY `date` DESC";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$systemDailyLog = $row;
	if($systemDailyLog && $systemDailyLog['unforzen_time'] === "0000-00-00 00:00:00")
		return true;
	return false;
}

function IsOwnPlayer($myTeamData, $playerID)
{
	if($myTeamData->myTeamData->p1 == $playerID)	return true;
	if($myTeamData->myTeamData->p2 == $playerID)	return true;
	if($myTeamData->myTeamData->p3 == $playerID)	return true;
	if($myTeamData->myTeamData->p4 == $playerID)	return true;
	if($myTeamData->myTeamData->p5 == $playerID)	return true;
	if($myTeamData->myTeamData->c == $playerID)		return true;
	if($myTeamData->myTeamData->fb == $playerID)	return true;
	if($myTeamData->myTeamData->sb == $playerID)	return true;
	if($myTeamData->myTeamData->tb == $playerID)	return true;
	if($myTeamData->myTeamData->ss == $playerID)	return true;
	if($myTeamData->myTeamData->of1 == $playerID)	return true;
	if($myTeamData->myTeamData->of2 == $playerID)	return true;
	if($myTeamData->myTeamData->of3 == $playerID)	return true;
	if($myTeamData->myTeamData->dh == $playerID)	return true;
	return false;
}

function GetPriceByDate($playerID, $date)
{
	$db = new DB();
	if(IsPitcher($playerID))
		$tableName = "player_rec_pitcher_daily";
	else
		$tableName = "player_rec_hitter_daily";
	$sql = "SELECT `price` FROM `".$tableName."` WHERE `id`=".$playerID." AND `date`=".$date;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['price'];
}

function GetPriceStr($myTeamData, $playerBaseData)
{
	if(!$playerBaseData)
		return null;
	if(IsForzen())
	{
		$str = sprintf("%.2fM",$playerBaseData->nowprice/100);
		return $str;
	}
	else if(IsOwnPlayer($myTeamData, $playerBaseData->id))
	{
		$str1 = "<a href='tool_trade.php?sellid=".$playerBaseData->id."'>";
		$str2 = sprintf("%.2fM",$playerBaseData->nowprice/100);
		return $str1.$str2;
	}
	else
	{
		$str1 = "<a href='tool_trade.php?buyid=".$playerBaseData->id."'>";
		$str2 = sprintf("%.2fM",$playerBaseData->nowprice/100);
		return $str1.$str2;
	}
}

function GetPriceFormat($price)
{
	$str = sprintf("%.2fM",$price/100);
	return $str;
}

function GetPricemoveStr($pricemove)
{
	if($pricemove>0)
	{
		$str1 = '<font color=red>▲';
	}
	else if($pricemove<0)
	{
		$str1 = '<font color=green>▼';
	}
	else
	{	
		return '-';
	}
	$str2 = sprintf("%.2fM",$pricemove/100);
	return $str1.$str2.'</font>';
}

function GetTeamNameStrByPlayerID($playerID)
{
	$teamID = GetTeamIDByPlayerID($playerID);
	return GetTeamNameStr($teamID);
}

// 取得球隊ID
function GetTeamIDByPlayerID($playerID)
{
	if($playerID<=0)
		return null;
	$db = new DB();
	$sql = "SELECT * FROM `team_player_index` WHERE player_id=".$playerID;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['team_id'];
}

// 取得下場對戰球隊的隊名（短）
function GetVSTeamNameMinStr($teamID)
{
	if($teamID<=0)
		return null;
	$db = new DB();
	$LocalTime = GetLocalTime();
	$sql = "SELECT * FROM `schedule_data` WHERE (away_team_id=".$teamID." OR home_team_id=".$teamID.") AND `datetime` >= '".$LocalTime."' ORDER BY `datetime`";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$homeID = $row['home_team_id'];
	$awayID = $row['away_team_id'];
	if($awayID == $teamID)
	{
		$str = "<a href='res_team.php?teamid=".$homeID."'>@".GetTeamNameMin($homeID)."</a>";
	}
	else if($homeID == $teamID)
	{
		$str = "<a href='res_team.php?teamid=".$awayID."'>".GetTeamNameMin($awayID)."</a>";
	}
	return $str;
}

// 取得下場對戰球隊的隊名
function GetVSTeamNameStr($teamID)
{
	$teamName = GetTeamName($teamID);
	$str = "<a href='res_team.php?teamid=".$teamID."'>".$teamName."</a>";
	return $str;
}

function GetTeamNameMinStr($teamID)
{
	$teamName = GetTeamNameMin($teamID);
	$str = "<a href='res_team.php?teamid=".$teamID."'>".$teamName."</a>";
	return $str;
}

function GetTeamNameStr($teamID)
{
	$teamName = GetTeamName($teamID);
	$str = "<a href='res_team.php?teamid=".$teamID."'>".$teamName."</a>";
	return $str;
}

function GetPlayerName($playerID)
{
	$playerBaseData = GetPlayerBaseData($playerID);
	// 取代空白
	$name = str_replace("　","",$playerBaseData->name);
	return $name;
}

function GetPlayerNameStr($playerBaseData)
{
	// 取代空白
	$name = str_replace("　","",$playerBaseData->name);
	$str = "<a href='res_playerdata.php?playerid=".$playerBaseData->id."'>".$name."</a>";

	return $str;
}

function IsPitcher($playerID)
{
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		return true;
	else
		return false;
}

// 取得守備位置字串
function GetPosStr($myTeamData, $playerBaseData)
{
	if(!IsOwnPlayer($myTeamData, $playerBaseData->id))
	{
		$str;
		if($playerBaseData->p) $str = $str."<a href='tool_trade.php?buyid=".$playerBaseData->id."&buypos=p'>P</a> ";
	    if($playerBaseData->c) $str = $str."<a href='tool_trade.php?buyid=".$playerBaseData->id."&buypos=c'>C</a> ";
	    if($playerBaseData->fb) $str = $str."<a href='tool_trade.php?buyid=".$playerBaseData->id."&buypos=fb'>1B</a> ";
	    if($playerBaseData->sb) $str = $str."<a href='tool_trade.php?buyid=".$playerBaseData->id."&buypos=sb'>2B</a> ";
	    if($playerBaseData->tb) $str = $str."<a href='tool_trade.php?buyid=".$playerBaseData->id."&buypos=tb'>3B</a> ";
	    if($playerBaseData->ss) $str = $str."<a href='tool_trade.php?buyid=".$playerBaseData->id."&buypos=ss'>SS</a> ";                 
	    if($playerBaseData->of) $str = $str."<a href='tool_trade.php?buyid=".$playerBaseData->id."&buypos=of'>OF</a> ";
	    if($playerBaseData->dh) $str = $str."<a href='tool_trade.php?buyid=".$playerBaseData->id."&buypos=dh'>DH</a> ";
	}
	else
	{
		$str;
		if($playerBaseData->p) $str = "P ";
	    if($playerBaseData->c) $str = $str."C ";
	    if($playerBaseData->fb) $str = $str."1B ";
	    if($playerBaseData->sb) $str = $str."2B ";
	    if($playerBaseData->tb) $str = $str."3B ";
	    if($playerBaseData->ss) $str = $str."SS ";                 
	    if($playerBaseData->of) $str = $str."OF ";
	    if($playerBaseData->dh) $str = $str."DH ";	
	}
    return $str;
}

// 取得球隊名稱
function GetTeamName($teamID)
{
	// 避免讀取資料庫
	switch ($teamID) {
		case 1: return "Giants";
		case 2: return "Swallows";
		case 3: return "BayStars";
		case 4: return "Dragons";
		case 5: return "Tigers";
		case 6: return "Carps";
		case 7: return "Lions";
		case 8: return "Fighters";
		case 9: return "Marines";
		case 10: return "Eagles";
		case 11: return "Buffaloes";
		case 12: return "Hawks";
		default: return null;
	}
}

// 取得球隊縮寫
function GetTeamNameMin($teamID)
{
	// 避免讀取資料庫
	switch ($teamID) {
		case 1: return "G";
		case 2: return "Ys";
		case 3: return "Yb";
		case 4: return "D";
		case 5: return "T";
		case 6: return "C";
		case 7: return "L";
		case 8: return "F";
		case 9: return "M";
		case 10: return "E";
		case 11: return "BS";
		case 12: return "H";
		default: return null;
	}
}

// 取得最近10天賽程
function GetLatestScheduleByTeamID($teamID)
{
	$db = new DB();
	$LocalTime = GetLocalTime();
	//$LocalTime = "2011-06-06 00:00:00";
	// 未來10天的賽程
	$sql = "SELECT * FROM schedule_data WHERE (away_team_id =".$teamID." OR home_team_id=".$teamID.") AND `datetime` >= '".$LocalTime."' ORDER BY `datetime` LIMIT 10";
	$stmt = $db->query($sql);
	return $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 取得過去記錄
function GetTeamPlayerID($teamID)
{
	$db = new DB();
	$sql = "SELECT `team_player_index`.`player_id`, `player_basedata`.`no` FROM `team_player_index`, `player_basedata` WHERE `team_player_index`.`player_id` = `player_basedata`.`id` AND `team_player_index`.`team_id`=".$teamID;
	$stmt = $db->query($sql);
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$count = $stmt->rowCount();
	$idArray = array();
	$noArray = array();
	for($i=0;$i<$count;$i++)
	{
		array_push($idArray, $row[$i]['player_id']);
		array_push($noArray, $row[$i]['no']);
	}

	// 依照背號排序
	array_multisort($noArray, SORT_ASC, SORT_NUMERIC, $idArray);
	return $idArray;
}

// 取得過去記錄
function GetPlayerRecDaily($playerID)
{
	if($playerID<=0)
		return null;
	// 取得守備位置
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		$tableName = "`player_rec_pitcher_daily`";
	else if($playerBaseData->dh)
		$tableName = "`player_rec_hitter_daily`";

	$db = new DB();
	$sql = "SELECT * FROM ".$tableName." WHERE id = ".$playerID." AND `g`>=1 ORDER BY `date` DESC";

	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

	for($i=0;$i<$count;$i++)
	{
		$obj[$i] = (object)$row[$i];
	}
	return $obj;
}

function GetPlayerRecByDate($playerID, $date)
{
	if($playerID<=0)
		return null;
	// 取得守備位置
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		$tableName = "`player_rec_pitcher_daily`";
	else if($playerBaseData->dh)
		$tableName = "`player_rec_hitter_daily`";

	$db = new DB();
	$sql = "SELECT * FROM ".$tableName." WHERE id = ".$playerID." AND `date` = '".$date."'";
	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $data = (object)$row;
}

// 取得過去記錄
function GetPlayerRec1($playerID)
{
	if($playerID<=0)
		return null;
	// 取得守備位置
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		$tableName = "`player_rec_pitcher_daily`";
	else if($playerBaseData->dh)
		$tableName = "`player_rec_hitter_daily`";

	$db = new DB();
	$sql = "SELECT * FROM ".$tableName." WHERE id = ".$playerID." ORDER BY `date` DESC LIMIT 1";

	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $data = (object)$row;
}

// 取得過去記錄
function GetPlayerRec7($playerID)
{
	if($playerID<=0)
		return null;
	// 取得守備位置
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		$tableName = "`player_rec_pitcher_7`";
	else if($playerBaseData->dh)
		$tableName = "`player_rec_hitter_7`";

	$db = new DB();
	$sql = "SELECT * FROM ".$tableName." WHERE id = ".$playerID;

	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $data = (object)$row;
}

// 取得過去記錄
function GetPlayerRec15($playerID)
{
	if($playerID<=0)
		return null;
	// 取得守備位置
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		$tableName = "`player_rec_pitcher_15`";
	else if($playerBaseData->dh)
		$tableName = "`player_rec_hitter_15`";

	$db = new DB();
	$sql = "SELECT * FROM ".$tableName." WHERE id = ".$playerID;

	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $data = (object)$row;
}

// 取得過去記錄
function GetPlayerRec30($playerID)
{
	if($playerID<=0)
		return null;
	// 取得守備位置
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		$tableName = "`player_rec_pitcher_30`";
	else if($playerBaseData->dh)
		$tableName = "`player_rec_hitter_30`";

	$db = new DB();
	$sql = "SELECT * FROM ".$tableName." WHERE id = ".$playerID;

	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $data = (object)$row;
}

// 取得過去記錄
function GetPlayerRec2012($playerID)
{
	if($playerID<=0)
		return null;
	// 取得守備位置
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		$tableName = "`player_rec_pitcher_2012`";
	else if($playerBaseData->dh)
		$tableName = "`player_rec_hitter_2012`";

	$db = new DB();
	$sql = "SELECT * FROM ".$tableName." WHERE id = ".$playerID;

	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $data = (object)$row;
}

// 取得過去記錄
function GetPlayerRec2011($playerID)
{
	if($playerID<=0)
		return null;
	// 取得守備位置
	$playerBaseData = GetPlayerBaseData($playerID);
	if($playerBaseData->p)
		$tableName = "`player_rec_pitcher_2011`";
	else if($playerBaseData->dh)
		$tableName = "`player_rec_hitter_2011`";

	$db = new DB();
	$sql = "SELECT * FROM ".$tableName." WHERE id = ".$playerID;

	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $data = (object)$row;
}

// 取得伺服器狀態
function GetServerState()
{
	$systeamDailyLog = GetLatestSystemDailyLog();
	$isForzen = IsForzen();
	
	if($systeamDailyLog->pricemove_time === "0000-00-00 00:00:00")
		$isPricemoved = false;
	else
		$isPricemoved = true;

	if($systeamDailyLog->unforzen_time === "0000-00-00 00:00:00")
		$isUnforzen = false;
	else
		$isUnforzen = true;

	if($systeamDailyLog->score_time === "0000-00-00 00:00:00")
		$isScored = false;
	else
		$isScored = true;

	if(!$isForzen && !$isPricemoved && !$isUnforzen && !$isScored)
	{
		return SERVER_STATE_NORAML;
	}
	else if($isForzen && !$isPricemoved && !$isUnforzen && !$isScored)
	{
		return SERVER_STATE_FORZEN;
	}
	else if($isForzen && $isPricemoved && !$isUnforzen && !$isScored)
	{
		return SERVER_STATE_PRICEMOVED;
	}
	else if(!$isForzen && $isPricemoved && $isUnforzen && !$isScored)
	{
		return SERVER_STATE_UNFORZEN;
	}
	else if(!$isForzen && $isPricemoved && $isUnforzen && $isScored)
	{
		return SERVER_STATE_NORAML;
	}
	else
	{
		return SERVER_STATE_ERROR;
	}
}

// 增加所有玩家球隊交易次數
function AddTrade($val)
{
	$db = new DB();
	$sql = "UPDATE `myteam_data` SET `trade` = `trade` + ".$val;
	$db->query($sql);
}

function AddPitcherTrade($val)
{
	$db = new DB();
	$sql = "UPDATE `myteam_data` SET `trade_p` = `trade_p` + ".$val;
	$db->query($sql);
}

function AddHitterTrade($val)
{
	$db = new DB();
	$sql = "UPDATE `myteam_data` SET `trade_h` = `trade_h` + ".$val;
	$db->query($sql);
}

// 取得球員價錢
function GetPlayerPrice($playerID)
{
	$db = new DB();
	$sql = "SELECT nowprice FROM player_basedata WHERE id = ".$playerID;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['nowprice'];	
}

// 取得系統玩家總數
function GetUserCount()
{
	$db = new DB();
	$sql = "SELECT count(*) as cnt FROM user_data";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['cnt'];
}

// 取得系統活躍玩家總數（最近7天）
function GetActUserCount()
{
	$datetime1 = GetLocalTime();
	$mm = substr($datetime1,5,2);
	$dd = substr($datetime1,8,2);
	$yy = substr($datetime1,0,4);
	$h = substr($datetime1,11,2);
	$i = substr($datetime1,14,2);
	$s = substr($datetime1,17,2);

	$mktime = gmmktime($h-168,$i,$s,$mm,$dd,$yy);
	$datetime2 = gmdate("Y-m-d H:i:s",$mktime);

	$db = new DB();
	$sql = "SELECT count(*) as cnt FROM user_data WHERE `login_time` > '".$datetime2."' AND `login_time` < '".$datetime1."'";
	echo $sql;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['cnt'];
}

// 取得系統玩家球隊總數
function GetMyteamCount()
{
	$db = new DB();
	$sql = "SELECT count(*) as cnt FROM myteam_data";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['cnt'];
}

// 取得系統活躍玩家球隊總數（最近7天）
function GetActMyteamCount()
{
	$datetime1 = GetLocalTime();
	$mm = substr($datetime1,5,2);
	$dd = substr($datetime1,8,2);
	$yy = substr($datetime1,0,4);
	$h = substr($datetime1,11,2);
	$i = substr($datetime1,14,2);
	$s = substr($datetime1,17,2);

	$mktime = gmmktime($h,$i,$s,$mm,$dd-7,$yy);
	$datetime2 = gmdate("Y-m-d H:i:s",$mktime);

	$db = new DB();
	$sql = "SELECT count(*) as cnt FROM myteam_data WHERE `modified` > '".$datetime2."' AND `modified` < '".$datetime1."'";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['cnt'];
}

// 取得最新的系統每日資訊
function GetLatestSystemDailyLog()
{
	$db = new DB();
	$sql = "SELECT * FROM system_daily_log 
			ORDER BY `date` DESC";

	$stmt = $db->query($sql);
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(count($row))
		return (object)$row[0];
	else
		return null;
}

// 取得系統每日資訊
function GetSystemDailyLog($date)
{
	$db = new DB();
	$sql = "SELECT * FROM system_daily_log 
			WHERE `date`='".$date."'";

	//echo $sql;
	$stmt = $db->query($sql);
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(count($row))
		return (object)$row[0];
	else
		return null;
}

// 取得聯盟local時間
function GetLocalTime()
{
	if(LEAGUE_NAME === "MLB")
	{
		// ....
	}
	else if(LEAGUE_NAME === "NPB")
	{
		return GetJPTime();
	}
	else if(LEAGUE_NAME === "CPBL")
	{
		return GetTWTime();	
	}
}

// UTC轉日本時間
function UTCToJP($datetime)
{
	$tz_offset = 9;
	$mm = substr($datetime,5,2);
	$dd = substr($datetime,8,2);
	$yy = substr($datetime,0,4);
	$h = substr($datetime,11,2);
	$i = substr($datetime,14,2);
	$s = substr($datetime,17,2);
	$time = mktime($h,$i,$s,$mm,$dd,$yy) + ($tz_offset * 60 * 60);
	return date('Y-m-d H:i:s', $time);
}
// 日本時間轉UTC
function JPToUTC($datetime)
{
	$tz_offset = 9;
	$mm = substr($datetime,5,2);
	$dd = substr($datetime,8,2);
	$yy = substr($datetime,0,4);
	$h = substr($datetime,11,2);
	$i = substr($datetime,14,2);
	$s = substr($datetime,17,2);
	$time = mktime($h,$i,$s,$mm,$dd,$yy) - ($tz_offset * 60 * 60);
	return date('Y-m-d H:i:s', $time);
}

// UTC轉台灣時間
function UTCToTW($datetime)
{
	$tz_offset = 8;
	$mm = substr($datetime,5,2);
	$dd = substr($datetime,8,2);
	$yy = substr($datetime,0,4);
	$h = substr($datetime,11,2);
	$i = substr($datetime,14,2);
	$s = substr($datetime,17,2);
	$time = mktime($h,$i,$s,$mm,$dd,$yy) + ($tz_offset * 60 * 60);
	return date('Y-m-d H:i:s', $time);
}
// 台灣時間轉UTC
function TWToUTC($datetime)
{
	$tz_offset = 8;
	$mm = substr($datetime,5,2);
	$dd = substr($datetime,8,2);
	$yy = substr($datetime,0,4);
	$h = substr($datetime,11,2);
	$i = substr($datetime,14,2);
	$s = substr($datetime,17,2);
	$time = mktime($h,$i,$s,$mm,$dd,$yy) - ($tz_offset * 60 * 60);
	return date('Y-m-d H:i:s', $time);
}

// 取得UTC時間
function GetUTCTime()
{
	return date('Y-m-d H:i:s', GetTimeStampByTimezone());
}

// 取得台灣時間
function GetTWTime()
{
	$mktime = gmmktime(gmdate("H")+8,gmdate("i"),gmdate("s"),gmdate("m"),gmdate("d"),gmdate("Y"));
	return gmdate("Y-m-d H:i:s",$mktime);
}

// 取得日本時間
function GetJPTime()
{
	$mktime = gmmktime(gmdate("H")+9,gmdate("i"),gmdate("s"),gmdate("m"),gmdate("d"),gmdate("Y"));
	return gmdate("Y-m-d H:i:s",$mktime);
}

// 依照時區取得時間
function GetTimeStampByTimezone($tz_offset = 0)
{
    if ($tz_offset > 14 || $tz_offset < -12)
        $tz_offset = 0; // timezone offset range: -12 ~ 14

    return time() + mktime(0, 0, 0, 1, 1, 1970) + ($tz_offset * 60 * 60);
}

/*
// 取得台灣現在時間字串
function TWTime()
{
	$mktime = gmmktime(gmdate("H")+8,gmdate("i"),gmdate("s"),gmdate("m"),gmdate("d"),gmdate("Y"));
	return gmdate("Y-m-d H:i:s",$mktime);
}
*/

// 取得預設聯盟id
function GetDefaultLeagueID($myTeamID)
{
	$db = new DB();
	$sql = "SELECT * FROM `league_myteam_index` WHERE `myteam_id`=".$myTeamID." ORDER BY `league_id`";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['league_id'];
}

// 加入聯盟
function JoinLeague($myTeamID, $leagueID, $password = null)
{
	$db = new DB();
	// 檢查密碼
	$leagueData = GetLeagueData($leagueID);

	if(empty($leagueData->password) || $leagueData->password === $password)
	{
		$sql = "INSERT INTO `league_myteam_index` (`league_id`, `myteam_id`) VALUES (".$leagueID.", ".$myTeamID.")";
		$db->query($sql);
		// 重新計算counter
		$sql = "SELECT * FROM `league_myteam_index` WHERE `league_id`=".$leagueID;
		$stmt = $db->query($sql);
		$count = $stmt->rowCount();
		$sql = "UPDATE `league_data` SET `counter`=".$count." WHERE `id`=".$leagueID;
		$db->query($sql);
		return true;
	}
	else
		throw new Exception('ERR_PASSWORD',ERR_PASSWORD);
}

function GetLeagueName($leaID)
{
	$db = new DB();
	$sql = "SELECT `name` FROM `league_data` WHERE `id`=".$leaID;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row['name'];
}

// 創立聯盟
function CreateLeague($userID, $leagueName, $password = null, $desc = null)
{
	$db = new DB();
	// 檢查使用者創立聯盟資格
	$sql = "SELECT * FROM `league_data` WHERE owner_id=".$userID;
	$stmt = $db->query($sql);
	$count = $stmt->rowCount();
	if($count)
		throw new Exception('ERR_ALREADY_CREATED',ERR_ALREADY_CREATED);
	// 檢查聯盟名稱是否可以使用
	$sql = "SELECT * FROM `league_data` WHERE name='".$leagueName."'";
	$stmt = $db->query($sql);
	$count = $stmt->rowCount();
	if($count)
		throw new Exception('ERR_NAME_REPEAT',ERR_NAME_REPEAT);
	// 創立聯盟
	$sql = "INSERT INTO `league_data` (`owner_id`, `name`, `password`, `desc`, `counter`) VALUES (".$userID.", '".$leagueName."', '".$password."', '".$desc."', 1)";
	$db->query($sql);
	$leagueID = $db->lastInsertId();
	// 創立者球隊應加入聯盟
	JoinLeague(GetMyTeamID($userID), $leagueID, $password);
	return true;
}

// 取得聯盟基本資料
function GetLeagueData($leagueID)
{
	$db = new DB();
	$sql = "SELECT * FROM `league_data` WHERE id = ".$leagueID;
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$count = $stmt->rowCount();

	if($count)
		return $leagueData = (object)$row;
	else
		return null;
}

// 使用者擁有的球隊ID
function GetMyTeamID($userID)
{
	$db = new DB();
	$sql = "SELECT * FROM user_myteam_index WHERE user_id='".$userID."'";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$myTeamIndex = (object)$row;
	return $myTeamIndex->myteam_id;
}

// 使用者擁有的球隊數目
function GetUserMyTeamCount($userID)
{
	$db = new DB();
	$sql = "SELECT * FROM user_myteam_index WHERE user_id='".$userID."'";
	$stmt = $db->query($sql);
	return $count = $stmt->rowCount();
}

// 取得我的球隊資料
function GetMyTeamData($myTeamID)
{
	$db = new DB();
	// 取得球隊資料
	$sql = "SELECT * FROM `myteam_data` WHERE id = ".$myTeamID;
	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);	
	return $myTeamData = (object)$row;
}

// myteam的名稱是否可以使用
function IsMyTeamNameCanUse($myTeamName)
{
	$db = new DB();
	$sql = "SELECT * FROM myteam_data WHERE name='".$myTeamName."'";
	$stmt = $db->query($sql);
	$count = $stmt->rowCount();
	if($count > 0)	
		return false;
	else
		return true;
}

// 創隊
function CreateMyTeam($userID, $myTeamName, $favTeamID)
{
	$db = new DB();

	// 檢查該user是否隊伍數目超過上限
	if(GetUserMyTeamCount($userID) > 0)
		throw new Exception('ERR_ALREADY_CREATED',ERR_ALREADY_CREATED);

	// 檢查隊名是否重複
	if(!IsMyTeamNameCanUse($myTeamName))
		throw new Exception('ERR_NAME_REPEAT',ERR_NAME_REPEAT);

	// 檢查是否有喜愛的隊伍
	if(!GetLeagueData($favTeamID))
		throw new Exception('ERR_DATA_NULL',ERR_DATA_NULL);

	// 加入一筆隊伍資料
	$sql = "INSERT INTO `myteam_data` (`name`, `cash`, `money`, `trade`, `trade_p`, `trade_h`, `create`, `modified`) VALUES ('".$myTeamName."', 5000, 5000, 3, 3, 3, '".GetLocalTime()."', '".GetLocalTime()."')";
	//echo $sql."<br>";
	$db->query($sql);
	$myTeamID = $db->lastInsertId();

	// 建立隊伍和使用者的連結
	$sql = "INSERT INTO `user_myteam_index` (`user_id`, `myteam_id`) VALUES (".$userID.", ".$myTeamID.")";
	//echo $sql."<br>";
	$db->query($sql);

	// 加入聯盟（以喜愛隊伍為準）
	JoinLeague($myTeamID, $favTeamID);
	return true;
}

// 取得球員個人資料
function GetPlayerInfo($playerID)
{
	$db = new DB();
	$sql = "SELECT * FROM `player_info` WHERE id = ".$playerID;
	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $myTeamData = (object)$row;
}

// 取得球員基本資料
function GetPlayerBaseData($playerID)
{
	if(!$playerID)
		return null;
	$db = new DB();
	$sql = "SELECT * FROM `player_basedata` WHERE id = ".$playerID;
	//echo $sql;
	$stmt = $db->query($sql);
	$count = $stmt->rowCount ();
	if($count <= 0)
		return null;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $myTeamData = (object)$row;
}

// 買賣球員
function Trade($myTeamID, $sellID, $buyID, $buyPos = null)
{
	// 確認系統未凍結
	if(IsForzen())
		throw new Exception('系統凍結中',ERR_FORZEN_NOW);

	// 取得球隊資料
	$myTeamData = new MyTeam($myTeamID);	

	// 如果有賣出球員
	if($sellID)
	{
		$isOK = $myTeamData->Sell($sellID);
//		if(!$isOK)
//			echo "S".$isOK."<BR>";
	}
	else
		$sellID = 0;

	// 如果有買入球員
	if($buyID)
	{
		$isOK = $myTeamData->Buy($buyID, $buyPos);
//		if(!$isOK)
//			echo "B".$isOK."<BR>";
	}
	else
		$buyID = 0;

	$myTeamData->Update();

	$db = new DB();
	// 記錄一筆交易log
	$sql = "INSERT INTO `myteam_trade_log` (myteam_id, buy_id, sell_id, datetime) VALUES (".$myTeamID.",".$buyID.",".$sellID.",'".GetLocalTime()."')";
//	echo $sql."<BR>";
	$stmt = $db->query($sql);

	return true;
}

// 交換位置
function PosSwitch($myTeamID, $posA, $posB)
{
	// 確認系統未凍結
	if(IsForzen())
		throw new Exception('系統凍結中',ERR_FORZEN_NOW);

	// 取得球隊資料
	$myTeamData = new MyTeam($myTeamID);	

	$myTeamData->PosSwitch($posA, $posB);

	$myTeamData->Update();

	return true;
}
?>