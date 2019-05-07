<?php
ini_set("display_errors", 1);

include '../lib/common.php';
include '../lib/package/loader.php';

echo '<pre>';

define("HANJI_GAME_DELAY", "試合前中止");
define("HANJI_GAME_END", "試合終了");

$pGameTime = '/\d{2}:\d{2}/';

$strNowTime	 = date("YmdHis");
//$strGameDate = date("Ymd");
$strGameDate = '20110412';

$iGameCount = 0;
$iGameDelay = 0;
$iGameEnd	= 0;
$bGameEnd 	= true;

//@todo 如果今天比賽已經都結束了 就不用檢查了
if(0)
{
	exit();
}

//檢查比賽是否結束
for ($iGame = 1 ; $iGame <= 6 ; $iGame++)
{
	$iGameId = sprintf("%d%02d", $strGameDate, $iGame);
	$urlToParse = sprintf("http://baseball.yahoo.co.jp/npb/game/%d/top", $iGameId);
	
	//檢查Url是否存在
	if(utilHtmlManager::isUrlExist($urlToParse))
	{
		$iGameCount++;
		//去除原始碼中的換行符號，方便REx的解析
		$strGameHtml = implode("", file($urlToParse));
		$strGameHtml = str_replace("\r\n", "", $strGameHtml);
		$strGameHtml = str_replace("\n", "", $strGameHtml);
		
		//檢查是否延賽
		if(strpos($strGameHtml, HANJI_GAME_DELAY) === FALSE)
		{
			if (strpos($strGameHtml, HANJI_GAME_END) === FALSE)
			{
				$bGameEnd = false;
			}
			else 
			{
				$iGameEnd++;
			}
		}
		else 
		{
			$iGameDelay++;
		}
	}
}

echo sprintf("本日(%s)共有(%d)場賽事<br/>比賽結束：(%d)場<br/>延賽：(%d)場", $strGameDate, $iGameCount, $iGameEnd, $iGameDelay);

//@todo 如果沒有比賽的話，20:00更新flag
if(0)
{
	exit();
}

//@todo 如果比賽已經全部結束了要做哪些事情
if($bGameEnd)
{
	
}
?>