<?php
set_time_limit(0);
include_once("include/init.php");
include_once("include/func.php");
include_once("class/db.class.php");

$db = new DB();

// 是否已經開季
if(!IsSeasonStart())
{
	echo "尚未開季";
	exit;
}

// 取得應處理的基準日
$sql = "SELECT * FROM `system_daily_log` WHERE `unforzen_time` = '0000-00-00 00:00:00' ORDER BY `date` DESC";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($row))
{
	$date = $row[0]['date'];	
}
else
{
	echo "沒有可處理的基準日";
	exit;
}

// 是否已經執行完pricemove
if($row[0]['pricemove_time'] === "0000-00-00 00:00:00")
{
	echo "尚未處理完pricemove";
	exit;
}

$today = new DateTime($date);
$systemDailyLog = GetSystemDailyLog($today->format('Y-m-d'));
//var_dump($systemDailyLog);
if($systemDailyLog)
{
	//echo "1:".$systemDailyLog->pricemove_time."<BR>";
	//echo "2:".$systemDailyLog->unforzen_time."<BR>";

	// 確認今日pricemove過 且 尚未unforzen
	if($systemDailyLog->pricemove_time !== "0000-00-00 00:00:00" && $systemDailyLog->unforzen_time === "0000-00-00 00:00:00")
	{
		$week = $today->format('w');
		if($week%2)				// 單數週
		{
			echo "單數發放打者交易<BR>";
			AddHitterTrade(1);
		}
		else
		{
			echo "雙數發放投手交易<BR>";
			AddPitcherTrade(1);
		}
		
		// 發放交易次數
		// AddTrade(1);

		// UNFORZEN 解凍
		$sql = "UPDATE `system_daily_log` SET `unforzen_time` = '".GetUTCTime()."' WHERE `date` = '".$today->format('Y-m-d')."'";
		$db->query($sql);
		echo "新增 ".$today->format('Y-m-d')." 禮拜".$today->format('w')." 交易次數成功";

		// 新增明天的system_daily_log
		$tomorrow = $today;
  		$tomorrow->modify( '+1 day' );
		$sql = "INSERT INTO `system_daily_log` (`date`) VALUES ('".$tomorrow->format('Y-m-d')."')";
		$db->query($sql);

		exit;
	}
}

echo "<font color=red>今日 ".$today->format('Y-m-d')." 禮拜".$today->format('w')." 發放交易失敗</font>";

/*
$today = new DateTime('2012-01-23');
// 檢查今天是否有發放過交易次數（以解凍與否判斷）
$sql = "SELECT `unforzen_time` FROM `system_daily_log` WHERE `datetime` >= '".$today->format('Y-m-d')." 00:00:00' AND `datetime` <= '".$today->format('Y-m-d')." 24:00:00'";
$stmt = $db->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$week = $today->format('w');

if($row['unforzen_time'] === "0000-00-00 00:00:00")
{
	
	// 單數週
	if($week%2)
	{
		echo "單數發放打者交易<BR>";
		AddHitterTrade(1);
	}
	else
	{
		echo "雙數發放投手交易<BR>";
		AddPitcherTrade(1);
	}
	
	// 發放交易次數
	// AddTrade(1);

	// UNFORZEN 解凍
	$sql = "UPDATE `system_daily_log` SET `unforzen_time` = '".TWTime()."'";
	$db->query($sql);
	echo "新增 ".$today->format('Y-m-d')." 禮拜".$today->format('w')." 交易次數成功";
}
else
{
	echo "<font color=red>今日 ".$today->format('Y-m-d')." 禮拜".$today->format('w')." 發放交易失敗</font>";
}
*/
?>