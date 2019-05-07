<?php 
include_once("include/init.php");
include_once("include/func.php");

include_once("class/myteam.class.php");

// 取得當前伺服器狀態（判斷自動排程用）
//echo GetServerState();

//$playerRec7 = GetPlayerRec7(807);
//var_dump($playerRec7);

$aaa = -1;
if(!$aaa)
{
	echo "1";
}
else
{
	echo "2";
}


/*
// 比對時間大小
$datetime1 = "2012-03-18 18:00:00";
$datetime2 = "2012-03-03 11:00:01";

if($datetime1 > $datetime2)
	echo "d1 > d2";
else if($datetime1 < $datetime2)
	echo "d1 < d2";
else
	echo "d1 = d2";
*/
/*
if(IsSeasonStart2())
{
	echo "開季";
}
else
{
	echo "未開季";
}
*/
/*
if(IsForzen2())
{
	echo "凍結中";
}
else
{
	echo "未凍結";
}
*/
/*
if(IsForzen())
{
	echo "凍結中";
}
else
{
	echo "未凍結";
}
*/
/*
// 是否為開賽首日
if(IsOpenDay())
	echo "true";
else
	echo "false";
*/

/*
// 是否為凍結狀態
if(IsForzen())
	echo "true";
else
	echo "false";
*/

/*
// 關於時間 
echo "取得當前聯盟時間".GetLocalTime()."<BR>";
echo "取得UTC時間".GetUTCTime()."<BR>";
echo "取得台灣時間".GetTWTime()."<BR>";
echo "取得日本時間".GetJPTime()."<BR>";

echo "將UTC時間轉為台灣時間<BR>";
echo "1979-11-16 06:30:40 -> ".UTCToTW("1979-11-16 06:30:40")."<BR>";

echo "將台灣時間轉為UTC時間<BR>";
echo "1979-11-16 06:30:40 -> ".TWToUTC("1979-11-16 06:30:40")."<BR>";
*/

/*
// 交換球員位置
$myTeamID = 1;
$posA = 'of2';
$posB = 'ss';
PosSwitch($myTeamID, $posA, $posB);
*/

/*
// 以球員id取得當前球員價錢
$playerID = 2;
echo GetPlayerPrice($playerID);
*/

// 取得系統玩家總數
//echo GetUserCount();
// 取得系統活躍玩家總數（最近7天）
//echo GetActUserCount();

// 取得系統球隊總數
//echo GetMyteamCount();
// 取得系統活躍玩家球隊總數（最近7天）
//echo GetActMyteamCount();

/*
$myTeam = new MyTeam(1);
var_dump($myTeam->myTeamData);
*/

/*
// 取得當前台灣時間
echo TWTime();
*/

/*
// 建立聯盟
$userID		= 1;
$leagueName	= "玩家聯盟1";
$password	= "1qaz2wsx";
$desc		= "關於聯盟的描述文字";
try {
	CreateLeague($userID, $leagueName, $password, $desc);
} catch (Exception $e) {
	echo $e->getMessage();
}
*/

/*
// 取得時間
echo TWTime();
*/

/*
// 交易
$buyID		= 12;		// 購買球員ID
$sellID		= 0;		// 賣出球員ID
$myteamID	= 1;		// 我的球隊ID
$buyPos		= 'dh';		// 購買到那個守備位置（字串）

try {
	Trade($myteamID, $sellID, $buyID, $buyPos);	
} catch (Exception $e) {
	echo $e->getMessage();
}
*/

/*
// 建立隊伍
$userID			= 19;			// 我的球隊ID
$myteamName		= "測試27隊";	// 我的球隊名稱
$favTeamID		= 12;			// 我喜愛的球隊ID
try {
	CreateMyTeam($userID, $myteamName, $favTeamID);
} catch (Exception $e) {
	echo $e->getMessage();
}

*/

?>