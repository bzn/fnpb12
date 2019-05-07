<?php

include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("class/myteam.class.php");
include_once("validateCookie.php");

/*
if(!IsAdmin($_COOKIE['fbid']))
{
	echo "ADMIN ONLY";
	exit;
}
*/

$db = new DB();

$myTeamID = $_COOKIE['myteamid'];

//IsAdmin($_COOKIE['fbid']);

for($i=0;$i<900;$i++)
{
	$idArr[$i] = $i;
	$buyArr[$i] = 0;
}

$idArr[901] = 901;

$sql = "SELECT `buy_id`, `sell_id` FROM `myteam_trade_log` WHERE `datetime`>'2012-03-30 18:00:00'";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($row as $val)
{
	$tradeData = $val;
	if($tradeData['buy_id'])
		$buyArr[$tradeData['buy_id']]++;

	if($tradeData['sell_id'])
		$buyArr[$tradeData['sell_id']]--;
}

//echo count($idArr)."<BR>";
//echo count($buyArr)."<BR>";

array_multisort($buyArr,SORT_DESC,SORT_NUMERIC,$idArr);

for($i=0;$i<count($buyArr);$i++)
{
	$playerBaseData = GetPlayerBaseData($idArr[$i]);
	$name = $playerBaseData->name;
	echo $name."		".$buyArr[$i]."<BR>";
}
?>