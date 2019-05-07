<?php
ini_set("display_errors", 1);

include '../lib/common.php';
include '../lib/package/loader.php';
include '../include/func.php';

echo '<pre>';

//Regular express
//取得比賽日期
$pGameDate 		= '/<div class=\"LinkCenter\">.*?<\/div>/';
//取得比賽日期(MD)
$pGameDateMD	= '/\d+/';
//取得比賽資訊
$pGameInfo		= '/<table.*?class=\"teams\">.*?<\/table>.*?<\/table>/';
//取得比賽編號	
$pGameId		= '/\/npb\/game\/\d+\/top/';
//取得比賽隊伍編號
$pGameTeamId	= '/\/npb\/teams\/\d+/';
//取得比賽時間
$pGameTime		= '/\d{2}:\d{2}/';
//取得比賽分數
$pGameScore		= '/<td class=\"score_r\">.*?<\/td>/';

//當年度的賽程表
$iGameDateY = date("Y");

$scheduleData = new structSchedule();

for ($iMonth = 3 ; $iMonth <= 11 ; $iMonth++)
{
	for ($iDay = 1 ; $iDay <= 31 ; $iDay++)
	{
		$urlToParse = sprintf("http://baseball.yahoo.co.jp/npb/schedule/?&date=%d%02d%02d",
								$iGameDateY, $iMonth, $iDay);
		
		//去除原始碼中的換行符號，方便REx的解析
		$strScheduleHtml = implode("", file($urlToParse));
		$strScheduleHtml = str_replace("\r\n", "", $strScheduleHtml);
		$strScheduleHtml = str_replace("\n", "", $strScheduleHtml);
		
		//取得比賽日期
		$arrMatchGameDate = array();
		preg_match_all($pGameDate, $strScheduleHtml, $arrMatchGameDate);
		$strGameDate = $arrMatchGameDate[0][0];
		$arrMatchGameDateMD = array();
		preg_match_all($pGameDateMD, $strGameDate, $arrMatchGameDateMD);
		
		//同一天的賽程就不重複解析
		if($iGameDateM != $arrMatchGameDateMD[0][0] ||
			$iGameDateD	!= $arrMatchGameDateMD[0][1])
		{
			$iGameDateM = $arrMatchGameDateMD[0][0];
			$iGameDateD	= $arrMatchGameDateMD[0][1];
			
			//比賽內容
			$arrMatchGameInfo = array();
			preg_match_all($pGameInfo, $strScheduleHtml, $arrMatchGameInfo);
			
			//當天是否有比賽
			if($arrMatchGameInfo[0])
			{
				foreach ($arrMatchGameInfo[0] as $strGameInfo)
				{
					// 取得比賽編號
					$arrMatchGameId = array();
					preg_match_all($pGameId, $strGameInfo, $arrMatchGameId);
					if($arrMatchGameId[0][0])
					{
						$arrGameId = explode("/", $arrMatchGameId[0][0]);
						$scheduleData->yid = $arrGameId[3];
					}
					
					//取得比賽隊伍
					$arrMatchTeamId = array();
					preg_match_all($pGameTeamId, $strGameInfo, $arrMatchTeamId);
					$arrTeamId = array_unique($arrMatchTeamId[0]); 
					//主場隊伍編號
					$arrTeamIdAway 	= explode("/", $arrTeamId[0]);
					$scheduleData->away_team_id = utilTeamDataManager::sharedDataManager()->getTeamId($arrTeamIdAway[3]);
					//客場隊伍編號
					$arrTeamIdHome 	= explode("/", $arrTeamId[2]);
					$scheduleData->home_team_id = utilTeamDataManager::sharedDataManager()->getTeamId($arrTeamIdHome[3]);
					
					//取得比賽時間
					$arrMatchGameTime = array();
					preg_match_all($pGameTime, $strGameInfo, $arrMatchGameTime);
					$strGameTime = $arrMatchGameTime[0][0]; 
					$arrGameTime = explode(":", $strGameTime);
					$iGameTimeH	= $arrGameTime[0];
					$iGameTimeM	= $arrGameTime[1];
					
					//寫入DB時轉成UTC
					$scheduleData->datetime = sprintf("%04d-%02d-%02d %02d:%02d:%02d",
													$iGameDateY, $iGameDateM, $iGameDateD, $iGameTimeH, $iGameTimeM, 0); 
					//$scheduleData->datetime = JPToUTC($scheduleData->datetime);
					
					//取得比賽分數
					$arrMatchTeamScore = array();
					preg_match_all($pGameScore, $strGameInfo, $arrMatchTeamScore);
					$arrTeamScore = $arrMatchTeamScore[0];
					$arrTeamScore = utilHtmlManager::replaceHtmlTag($arrTeamScore);
					$scheduleData->away_team_score = $arrTeamScore[0];
					$scheduleData->home_team_score = $arrTeamScore[1];
					
					//@todo 更新賽程表
					var_dump($scheduleData);
					utilScheduleDataManager::sharedDataManager()->updateScheduleData($scheduleData);
				}
			}
		}
	}
}
?>