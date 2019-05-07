<?php
set_time_limit(0);
include_once("include/init.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("validateCookie.php");

if(!IsAdmin($_COOKIE['fbid']))
{
	echo "ADMIN ONLY";
	exit;
}
else
{
	echo $_COOKIE['fbid']." ADMIN";
}

// 計算球員積分
$db = new DB();

// 取得應處理的基準日
$sql = "SELECT * FROM `system_daily_log` WHERE `score_time` = '0000-00-00 00:00:00' ORDER BY `date`";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($row) <= 0)
{
	echo "沒有可處理的基準日";
	exit;
}

echo $date = $row[0]['date'];
$recordTime = $row[0]['unforzen_time'];
if($recordTime === "0000-00-00 00:00:00")
{
	echo "尚未解凍";
	exit;
}

//echo $date;
//exit;
//$date = "2011-09-01";

// 計算球員積分
$db = new DB();
// 取得所有打者
$sql = "SELECT * FROM `player_rec_hitter_daily` WHERE `date`='".$date."'";
echo $sql;
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($row);
$playerPointsArr = array();
foreach($row as $val)
{
	$points = -2*($val['ab']-$val['h'])+$val['r']*5+$val['h']*5+$val['h2']*5+$val['h3']*10+$val['hr']*15+$val['rbi']*5+$val['sb']*10+$val['bb']*3-$val['k'];
	$sql = "UPDATE `player_rec_hitter_daily` SET `points` = ".$points." WHERE `id` = ".$val['id']." AND `date`='".$date."'";
	echo $sql;
	echo "<BR>";
	$stmt = $db->query($sql);
	$playerPointsArr[$val['id']] = $points;
}


// 取得所有投手
$sql = "SELECT * FROM `player_rec_pitcher_daily` WHERE `date`='".$date."'";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($row as $val)
{
	$points = 30*$val['w']-15*$val['l']+30*$val['sv']+15*$val['hld']+15*$val['ip']+5*$val['ip2']-5*$val['h']-10*$val['er']-3*$val['bb']+3*$val['k'];
	
	$sql = "UPDATE `player_rec_pitcher_daily` SET `points` = ".$points." WHERE `id` = ".$val['id']." AND `date`='".$date."'";
	echo $sql;
	echo "<BR>";
	$stmt = $db->query($sql);
	$playerPointsArr[$val['id']] = $points;
}
echo "SCORER執行完了"
?>