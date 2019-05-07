<?php
/*
 * auto_pricemove.php
 * 每日自動球員薪資運算機
 * by bluezhin
 */

session_start();
set_time_limit(0);
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");

$isDebug = false;

if(!IsAdmin($_COOKIE['fbid']))
{
	echo "ADMIN ONLY";
	exit;
}

// 單日價格變動基準(1000,000)
$pricemoveUnit = 400;
// 保證不跌之佔有比率%
$lowpercent = 5;
// 保證漲之佔有比率%3
$highpercent = 30;
// pricemove執行時間（必須所有比賽都已經開始的保險時間，暫定為20:00）
// 也許改為當日所有比賽開賽後的1小時之後執行可能更為妥當
$exeHR = 20;

$db = new DB();

// 取得應處理的基準日
$sql = "SELECT `date` FROM `system_daily_log` ORDER BY `date` DESC";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($row))
{
	$today = $row[0]['date'];	
}	
else
{
	// 開賽首日的特別處理
	if(IsOpenDay())
	{
		echo "開賽首日：新增第一筆log";
		// 新增第一筆system_daily_log
		$sql = "INSERT INTO `system_daily_log` (`date`) VALUES ('".OPEN_DATE."')";
		if(!$isDebug) $db->query($sql);
		$today = OPEN_DATE;
	}
	else
	{
		echo "沒有需要處理的pricemove";
		exit;
	}
}

// 也許改為基準日所有比賽開賽後的1小時之後執行可能更為妥當
// ....
// 20:00才執行（不論是哪個聯盟，20:00照理說一定都forzen了）	
// 目前寫法會有跨日誤判危險
// ....
$datetime = GetLocalTime();
$yy = substr($datetime,0,4);
$mm = substr($datetime,5,2);
$dd = substr($datetime,8,2);
$h = substr($datetime,11,2);
$i = substr($datetime,14,2);
$s = substr($datetime,17,2);
$mktime1 = mktime($h,$i,$s,$mm,$dd,$yy);
$mktime2 = mktime($exeHR,0,0,$mm,$dd,$yy);
if($mktime2 > $mktime1)
{
	echo "20:00之後才能執行";
	//exit;
}

// 需運算的基準日
$mm = substr($today,5,2);
$dd = substr($today,8,2);
$yy = substr($today,0,4);
$h = substr($today,11,2);
$i = substr($today,14,2);
$s = substr($today,17,2);

$mktime = gmmktime($h,$i,$s,$mm,$dd-1,$yy);
$yesterday = gmdate("Y-m-d H:i:s",$mktime);

if(IsOpenDay())
{
	echo "開賽初日不做價錢變動";

	// ....

	// 開始時間為很久很久以前
	$starttime = "2000-01-01 00:00:00";
	// 結束時間為現在
	$endtime = GetLocalTime();
}
else
{
	// 開始時間為前日pricemove_time
	$systemDailyLog = GetSystemDailyLog($yesterday);
	$starttime = $systemDailyLog->pricemove_time;
	// 結束時間為現在
	$endtime = GetLocalTime();
}


echo "計算 ".$starttime." 到 ".$endtime." 的價錢變動<BR>";

// 取得統計數量
$myTeamCount = GetMyteamCount();
$actMyTeamCount = GetActMyteamCount();
$userCount = GetUserCount();
$actUserCount = GetActUserCount();
echo "活躍使用者數/使用者數：".$actUserCount." / ".$userCount."<BR>";
echo "活躍隊伍數/隊伍數：".$actMyTeamCount." / ".$myTeamCount."<BR>";

// 確認執行時機得宜（已經yahootime，尚未unforzen）
// ....

// 取得所有球員
$sql = "SELECT `id`, `name`, `baseprice`, `nowprice`, `p` FROM `player_basedata`";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($row);
//echo "<BR>";

// 不處理active = false的球員
// ....

// 每日記錄
foreach ($row as $val)
{
	$playerDataArr[$val['id']] = $val;
}

// 取得本日交易記錄================
$sql = "SELECT * FROM `myteam_trade_log` WHERE `datetime` > '".$starttime."' AND `datetime` < '".$endtime."'";
echo $sql."<BR>";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($row);
//echo "<BR>";

foreach($row as $val)
{
	$tradeData = $val;
//	var_dump($tradeData);
//	echo "<BR>";

	if($tradeData['buy_id'] > 0)
		$playerDataArr[$tradeData['buy_id']]['buy_count']++;
		
	if($tradeData['sell_id'] > 0)
		$playerDataArr[$tradeData['sell_id']]['sell_count']++;
}

// 計算買賣總和，和價錢變動
foreach ($playerDataArr as $val)
{
	$val['sum'] = $val['buy_count'] - $val['sell_count'];
	
	//套用價格變動公式
	if($actMyTeamCount)
		$val['pricemove'] = round($pricemoveUnit * ($val['sum'] / $actMyTeamCount) );
	else
		$val['pricemove'] = 0;

	// 檢查目前佔有率
	$sql = "SELECT `myteam_id` FROM `myteam_trade_log` WHERE buy_id = ".$val['id'];
	$buyCount = $db->rowCount($sql);
	$sql = "SELECT `myteam_id` FROM `myteam_trade_log` WHERE sell_id = ".$val['id'];
	$sellCount = $db->rowCount($sql);
	$ownCount = $buyCount - $sellCount;

	// 每日持續跌價
	$val['pricemove'] -= 2;

	//如果佔有率低於設定
//	$percent = $ownCount/$myTeamCount;
//	if($percent/100 <= $lowpercent)
//		$val['pricemove'] -= 2;

//	if($percent/100 >= $highpercent)
//		$val['pricemove'] += 2;
//	echo " 佔有人數:".$ownCount;	
	$val['own_count'] = $ownCount;

	// 重新計算當前價格
	if($val['p'] == 1)
		$sql = "SELECT SUM(pricemove) FROM `player_rec_pitcher_daily` WHERE id=".$val['id']." AND `date` < '".$today."'";
	else
		$sql = "SELECT SUM(pricemove) FROM `player_rec_hitter_daily` WHERE id=".$val['id']." AND `date` < '".$today."'";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$nowPrice = $row['SUM(pricemove)'] + $val['baseprice'];
	
	// 薪資變動上下限修正
	if($val['pricemove'] > 300)
		$val['pricemove'] = 300;
	else if($val['pricemove'] < -300)
		$val['pricemove'] = -300;

	// 開賽首日不變動
	if(IsOpenDay())
		$val['pricemove'] = 0;

	// 計算新的薪資
	$newPrice = $nowPrice + $val['pricemove'];

	// 薪資下限修正
	if($newPrice < 50)
	{
		$val['pricemove'] += 50 - $newPrice;
		$newPrice = 50;
	}
	
	// 更新
	$playerDataArr[$val['id']] = $val;

	// 顯示除錯訊息
	$playerdata = (object)$val;
	echo $playerdata->name." ";
	printf("%.2fM",$newPrice/100);
	if($playerdata->pricemove > 0)
		echo " <font color=red>▲";
	else if($playerdata->pricemove < 0)
		echo " <font color=green>▼";
	else
		echo " <font>";
	printf("%.2fM",abs($playerdata->pricemove/100));
	echo " </font>";
	echo "OwnCount:".$ownCount;
	echo "OwnPercent:".$percent;
	echo "<BR>";

	// 寫入daily rec
	if($val['p'] == 1)
	{
		$tableName = "`player_rec_pitcher_daily`";
	}
	else
	{
		$tableName = "`player_rec_hitter_daily`";	
	}

	// 如果已經有資料就update
	$sql = "SELECT * FROM ".$tableName." WHERE `id` = ".$val['id']." AND `date` = '".$today."'";
	$rowCount = $db->rowCount($sql);
	if($rowCount)
	{
		echo "<font color=red>[REDO]</font>";
		$sql = "UPDATE ".$tableName." SET `own_count` = '".$val['own_count']."', `buy_count` = '".$val['buy_count']."', `sell_count` = '".$val['sell_count']."', `pricemove` = '".$val['pricemove']."', '".$newPrice."'  WHERE `id` = ".$val['id']." AND `date` = '".$today."'";
	}
	else
	{
		$sql = "INSERT INTO ".$tableName." (`id`, `buy_count`, `sell_count`,`own_count`, `pricemove`, `price`, `date`) 
		VALUES ('".$val['id']."', '".$val['buy_count']."', '".$val['sell_count']."', '".$val['own_count']."', '".$val['pricemove']."', '".$newPrice."', '".$today."') ";
	}
	echo $sql."<BR>";
	if(!$isDebug) $db->query($sql);

	// 更新basedata
	$sql = "UPDATE `player_basedata` SET `nowprice` = ".$newPrice." WHERE `id` = ".$val['id'];
	if(!$isDebug) $db->query($sql);
}

// 計算玩家球隊薪資變動
$sql = "SELECT * FROM `myteam_data`";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$myTeamArr = (object)$row;
//var_dump($myteamArr);

// 若是今天執行過
$sql = "SELECT count(*) as cnt FROM `myteam_rec_daily` WHERE `date` = '".$today."'";
$stmt = $db->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row['cnt'])
{
	$sql = "DELETE FROM `myteam_rec_daily` WHERE `date` = '".$today."'";
	if(!$isDebug) $db->query($sql);
	echo "移除舊有資料<BR>";
}

foreach ($myTeamArr as $val) 
{
	$sql = "SELECT `money` FROM `myteam_rec_daily` WHERE `date` = '".substr($yesterday, 0, 10)."'";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$lastMoney = $row['money'];

	$money =	$val[cash] + 
				$playerDataArr[$val[p1]]['nowprice'] + 
				$playerDataArr[$val[p2]]['nowprice'] + 
				$playerDataArr[$val[p3]]['nowprice'] + 
				$playerDataArr[$val[p4]]['nowprice'] + 
				$playerDataArr[$val[p5]]['nowprice'] + 
				$playerDataArr[$val[c]]['nowprice'] + 
				$playerDataArr[$val[fb]]['nowprice'] + 
				$playerDataArr[$val[sb]]['nowprice'] + 
				$playerDataArr[$val[tb]]['nowprice'] + 
				$playerDataArr[$val[ss]]['nowprice'] + 
				$playerDataArr[$val[of1]]['nowprice'] + 
				$playerDataArr[$val[of2]]['nowprice'] + 
				$playerDataArr[$val[of3]]['nowprice'] + 
				$playerDataArr[$val[dh]]['nowprice'];


	echo $val[cash].", ".$playerDataArr[$val[p1]]['nowprice'].", ".$playerDataArr[$val[p2]]['nowprice'].", ".$playerDataArr[$val[p3]]['nowprice'].", ".$playerDataArr[$val[p4]]['nowprice'].", ".$playerDataArr[$val[p5]]['nowprice'].", ".$playerDataArr[$val[c]]['nowprice'].", ".$playerDataArr[$val[fb]]['nowprice'].", ".$playerDataArr[$val[sb]]['nowprice'].", ".$playerDataArr[$val[tb]]['nowprice'].", ".$playerDataArr[$val[ss]]['nowprice'].", ".$playerDataArr[$val[of1]]['nowprice'].", ".$playerDataArr[$val[of2]]['nowprice'].", ".$playerDataArr[$val[of3]]['nowprice'].", ".$playerDataArr[$val[dh]]['nowprice']."<BR>";
							
	$pricemove = $money - $lastMoney;

	if(IsOpenDay())
	{
		$money = 5000;
		$pricemove = 0;
	}

	echo $val[id]."	".$money."(".$pricemove.")"."<BR>";

	// 更新myteam_basedata
	$sql = "UPDATE `myteam_data` SET `money` = ".$money." WHERE id = ".$val['id'];
	echo $sql."<BR>";
	if(!$isDebug) $db->query($sql);

	// 更新myteam_rec_daily
	$sql = "INSERT INTO `myteam_rec_daily`(`myteam_id`, `date`, `cash`, `money`,`pricemove` , `points`,`trade`, `trade_p`, `trade_h`, `p1`, `p2`, `p3`, `p4`, `p5`, `c`, `fb`, `sb`, `tb`, `ss`, `of1`, `of2`, `of3`, `dh`) VALUES (".$val['id'].",'".$today." 00:00:00',".$val[cash].",".$money.",".$pricemove.",0,".$val[trade].",".$val[trade_p].",".$val[trade_h].",".$val[p1].",".$val[p2].",".$val[p3].",".$val[p4].",".$val[p5].",".$val[c].",".$val[fb].",".$val[sb].",".$val[tb].",".$val[ss].",".$val[of1].",".$val[of2].",".$val[of3].",".$val[dh].")";
	echo $sql."<BR>";
	if(!$isDebug) $db->query($sql);
}

/*
// 更新球員漲跌Top10
$sql = "SELECT * FROM `player_rec_hitter_daily` ORDER BY `pricemove` DESC WHERE `date` = '".$today."' LIMIT 10";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($row);
*/

// 更新球隊資產Top20
// ....
// 更新聯盟資產Top20
// ....

// 排行榜
$sql = "SELECT `id`, `money` FROM `myteam_data` ORDER BY `money` DESC";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "計算積分排行<BR>";
$rank = 1;
$counter = 1;
$moneyTemp = 0;
foreach ($row as $val) 
{
	$id = $val['id'];
	$money = $val['money'];
	// 如果跟前一員的積分相同
	if($moneyTemp == $money)
	{
		
	}
	else
	{
		$rank = $counter;
	}
	$moneyTemp = $money;
		
	$sql = "UPDATE `myteam_rec_daily` SET `rank_money`=".$rank." WHERE `myteam_id`=".$id." AND `date`='".$today."'";
	echo $money." ".$sql."<BR>";
	if(!$isDebug) $db->query($sql);
	$counter++;
}


// 成功後新增一筆pricemove_time到system_daily_log（並更新玩家,隊伍數）
$sql = "UPDATE `system_daily_log` SET `pricemove_time` = '".GetLocalTime()."', `myteam_count` = ".$myTeamCount.", `active_myteam_count` = ".$actMyTeamCount.", `user_count` = ".$userCount.", `active_user_count` = ".$actUserCount." WHERE `date` >= '".$today."'";
if(!$isDebug) $db->query($sql);
//echo $sql;

echo "<BR><font color=green><b>".$today." PRICEMOVE 完成</b></font><BR>";

?>
