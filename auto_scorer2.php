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

$date = $row[0]['date'];
$recordTime = $row[0]['unforzen_time'];
if($recordTime === "0000-00-00 00:00:00")
{
	echo "尚未解凍";
	exit;
}

//$date = "2011-09-01";

// 取得所有打者
$sql = "SELECT `id`, `points` FROM `player_rec_hitter_daily` WHERE `date`='".$date."'";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($row as $val)
{
	$playerPointsArr[$val['id']] = $val['points'];
}

// 取得所有打者
$sql = "SELECT `id`, `points` FROM `player_rec_pitcher_daily` WHERE `date`='".$date."'";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($row as $val)
{
	$playerPointsArr[$val['id']] = $val['points'];
}

// 計算所有玩家球隊積分
$sql = "SELECT * FROM `myteam_rec_daily` WHERE `date`='".$date."'";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "計算當日積分<BR>";
echo "共".count($row)."隊伍";

foreach ($row as $val) 
{
	// 必須陣容全滿才寫入分數
	if($val[p1]>0&&$val[p2]>0&&$val[p3]>0&&$val[p4]>0&&$val[p5]>0&&$val[c]>0&&$val[fb]>0&&$val[sb]>0&&$val[tb]>0&&$val[ss]>0&&$val[of1]>0&&$val[of2]>0&&$val[of3]>0&&$val[dh]>0)
	{
		$points =	$playerPointsArr[$val[p1]] + 
					$playerPointsArr[$val[p2]] + 
					$playerPointsArr[$val[p3]] + 
					$playerPointsArr[$val[p4]] + 
					$playerPointsArr[$val[p5]] + 
					$playerPointsArr[$val[c]] + 
					$playerPointsArr[$val[fb]] + 
					$playerPointsArr[$val[sb]] + 
					$playerPointsArr[$val[tb]] + 
					$playerPointsArr[$val[ss]] + 
					$playerPointsArr[$val[of1]] + 
					$playerPointsArr[$val[of2]] + 
					$playerPointsArr[$val[of3]] + 
					$playerPointsArr[$val[dh]];
		echo $val['myteam_id']." => ".$points."<BR>";
	}
	else
	{
		$points = 0;
		echo $val['myteam_id']." => ".$points."陣容未滿<BR>";
	}
	
	$sql = "UPDATE `myteam_rec_daily` SET `points` = ".$points." WHERE myteam_id=".$val['myteam_id']." AND `date`='".$date."'";
	$db->query($sql);
}

// 更新球隊總積分
$sql = "SELECT * FROM `myteam_data`";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "計算總積分<BR>";
foreach ($row as $val) 
{
	$id = $val['id'];
	$sql = "SELECT SUM(points) as points FROM `myteam_rec_daily` WHERE `myteam_id`=".$id;
	$stmt2 = $db->query($sql);
	$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
	$points = $row2['points'];

	$sql = "UPDATE `myteam_data` SET `points`=".$points." WHERE `id`=".$id;
	$db->query($sql);
	echo $id." => ".$points."<BR>";
}

// 排行榜
$sql = "SELECT `id`, `points` FROM `myteam_data` ORDER BY `points` DESC";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "計算積分排行<BR>";
$rank = 1;
$counter = 1;
$pointsTemp = 0;
foreach ($row as $val) 
{
	$id = $val['id'];
	$points = $val['points'];
	// 如果跟前一員的積分相同
	if($pointsTemp == $points)
	{
		
	}
	else
	{
		$rank = $counter;
	}
	$pointsTemp = $points;
		
	$sql = "UPDATE `myteam_rec_daily` SET `rank_points`=".$rank." WHERE `myteam_id`=".$id." AND `date`='".$date."'";
	echo $points." ".$sql."<BR>";
	$db->query($sql);
	$counter++;
}

$sql = "UPDATE `system_daily_log` SET `score_time` = '".GetLocalTime()."' WHERE `date` = '".$date."'";
echo $sql;
$db->query($sql);
?>