<?php
ini_set("display_errors", 1);

include '../lib/common.php';
include '../lib/package/loader.php';

//utilDataManager::sharedDataManager()->updatePlayerData(array());

$arrayPlayerType = array('b','p');
//$arrayPlayerType = array('p');
//$arrayPlayerType = array('b');
$arrayTeamId = array(4, 5, 1, 2, 6, 3, 12, 7, 9, 8, 11, 376);

define("HANJI_POS_C", "捕手");
define("HANJI_POS_OF", "外野手");

echo '<pre>';

foreach ($arrayPlayerType as $playerType)
{
	foreach ($arrayTeamId as $teamId)
	{
		//解析網址
		$urlToParse = sprintf("http://baseball.yahoo.co.jp/npb/teams/%d/memberlist?type=%s", $teamId, $playerType);
		
		//去除原始碼中的換行符號，方便REx的解析
		$strTeamHtml = implode("", file($urlToParse));
		$strTeamHtml = str_replace("\r\n", "", $strTeamHtml);
		$strTeamHtml = str_replace("\n", "", $strTeamHtml);
		
		//解析每位球員
		$pPlayerList = "/<tr class=\\\"[cell]*\\\".*?<\/tr>/";
		$arrMatchList = array();
		preg_match_all($pPlayerList, $strTeamHtml, $arrMatchList);
		
		//解析每個欄位的資料
		$pPlayerData = '/<td.*?<\/td>/';
		$pYahooId = "/\/\d*?\//";
		
		//球員Info
		$pPlayerInfo = '/<tr.*?th class=\"p2\".*?<\/tr>/';
		//球員Birthday
		$pPlayerBirthData 	= '/\d+年\d+月\d+日/';
		$pPlayerBirthYMD	= '/\d+/';
		//球員position
		$pPlayerPos			= '/<span class=\"position\">.*?<\/span>/';
		//球員選序
		$pPlayerDraft		= '/\d+年（.*?<\/td>/';
		$pPlayerDraftYRP    = '/\d+/';
		foreach ($arrMatchList[0] as $strMatchData)
		{
			echo sprintf("<br/>======== 選手類別 : %s ========<br/>", $playerType);
			echo sprintf("<br/>======== 隊伍編號 : %d ========<br/>", $teamId);
			
			//取得Yahoo ID
			$arrMatchYahooId = array();
			preg_match_all($pYahooId, $strMatchData, $arrMatchYahooId);
			$yahooId = str_replace("/", "", $arrMatchYahooId[0][0]);
			
			//取得各欄位屬性
			$arrMatchData = array();
			preg_match_all($pPlayerData, $strMatchData, $arrMatchData);
			$attrData = $arrMatchData[0];
			
			echo sprintf("<br/>======== Yahoo Player Id : %d ========<br/>", $yahooId);
			
			//Player base data
			$playerBaseData = new stdClass();
			//Yahoo Id
			$playerBaseData->yahooId = $yahooId;
			//更新資料表
			$playerBaseData->updateTable = constDB::PLAYER_BASEDATA;
			//更新資料
			$playerBaseData->updateData = array();
			//投手
			if($playerType == 'p')
			{
				$playerBaseData->updateData['p'] = 1;
			}
			//打者
			else 
			{
				$playerBaseData->updateData['dh'] = 1;
			}
			
			$playerBaseData->updateData['no'] 	= utilHtmlManager::replaceHtmlTag($attrData[0]);
			$playerBaseData->updateData['name']	= utilHtmlManager::replaceHtmlTag($attrData[1]);
			
			//Player info data
			$playerInfoData = new stdClass();
			//Yahoo id
			$playerInfoData->yahooId = $yahooId;
			//更新資料表
			$playerInfoData->updateTable = constDB::PLAYER_INFO;
			//更新資料
			$playerInfoData->updateData	= array();

			//playerInfo
			$urlToParse = sprintf("http://baseball.yahoo.co.jp/npb/player/%d/", $yahooId);
			
			//去除原始碼中的換行符號，方便REx的解析
			$strInfoHtml = implode("", file($urlToParse));
			$strInfoHtml = str_replace("\r\n", "", $strInfoHtml);
			$strInfoHtml = str_replace("\n", "", $strInfoHtml);
			
			//取得球員的生日
			$arrMatchInfo = array();
			preg_match_all($pPlayerInfo, $strInfoHtml, $arrMatchInfo);
			$strPlayerBirthday = $arrMatchInfo[0][0];
			//var_dump($arrMatchInfo);
			$arrMatchBirthday = array();
			preg_match_all($pPlayerBirthData, $strPlayerBirthday, $arrMatchBirthday);
			$strPlayerBirthday = $arrMatchBirthday[0][0];
			
			$arrMatchBirthYMD = array();
			preg_match_all($pPlayerBirthYMD, $strPlayerBirthday, $arrMatchBirthYMD);
			
			$playerInfoData->updateData['birthday'] = sprintf("%d-%d-%d", $arrMatchBirthYMD[0][0], $arrMatchBirthYMD[0][1], $arrMatchBirthYMD[0][2]);
			
			//投打慣用手
			$strPlayerPitchBat = $arrMatchInfo[0][1]; 
			if(strpos($strPlayerPitchBat, "右投げ右打ち") >= 0)
			{
				$playerInfoData->updateData['pitch'] 	= 'R';
				$playerInfoData->updateData['bat'] 		= 'R';
			}
			elseif(strpos($strPlayerPitchBat, "右投げ左打ち") >= 0)
			{
				$playerInfoData->updateData['pitch'] 	= 'R';
				$playerInfoData->updateData['bat'] 		= 'L';
			}
			elseif(strpos($strPlayerPitchBat, "右投げ両打ち") >= 0) 
			{
				$playerInfoData->updateData['pitch'] 	= 'R';
				$playerInfoData->updateData['bat'] 		= 'S';
			}
			elseif(strpos($strPlayerPitchBat, "左投げ右打ち") >= 0)
			{
				$playerInfoData->updateData['pitch'] 	= 'L';
				$playerInfoData->updateData['bat'] 		= 'R';
			}
			elseif(strpos($strPlayerPitchBat, "左投げ左打ち") >= 0)
			{
				$playerInfoData->updateData['pitch'] 	= 'L';
				$playerInfoData->updateData['bat'] 		= 'L';
			}
			elseif(strpos($strPlayerPitchBat, "左投げ両打ち") >= 0) 
			{
				$playerInfoData->updateData['pitch'] 	= 'L';
				$playerInfoData->updateData['bat'] 		= 'S';
			}
			
			//選秀序
			$strPlayerDraft = $arrMatchInfo[0][2];
			$arrMatchPlayerDraft = array();
			preg_match_all($pPlayerDraft, $strPlayerDraft, $arrMatchPlayerDraft);
			
			$strPlayerDraft = $arrMatchPlayerDraft[0][0]; 
			
			$arrMatchPlayerDraft = array();
			preg_match_all($pPlayerDraftYRP, $strPlayerDraft, $arrMatchPlayerDraft);
			//var_dump($arrMatchPlayerDraft);

			if(strpos($strPlayerDraft, "位") >= 0)
			{
				$playerInfoData->updateData['draft_year'] 	= $arrMatchPlayerDraft[0][0];
				$playerInfoData->updateData['draft_round'] 	= 1;
				$playerInfoData->updateData['draft_pick'] 	= $arrMatchPlayerDraft[0][1];
			}
			elseif(strpos($strPlayerDraft, "巡目") >= 0)
			{
				$playerInfoData->updateData['draft_year'] 	= $arrMatchPlayerDraft[0][0];
				$playerInfoData->updateData['draft_round'] 	= $arrMatchPlayerDraft[0][1];
				$playerInfoData->updateData['draft_pick'] 	= 0;
			}
			elseif(strpos($strPlayerDraft, "-") >= 0) 
			{
				$playerInfoData->updateData['draft_year'] 	= 0;
				$playerInfoData->updateData['draft_round'] 	= 0;
				$playerInfoData->updateData['draft_pick'] 	= 0;				
			}
			else 
			{
				$playerInfoData->updateData['draft_year'] 	= $arrMatchPlayerDraft[0][0];
				$playerInfoData->updateData['draft_round'] 	= 0;
				$playerInfoData->updateData['draft_pick'] 	= 0;
			}
			//var_dump($playerInfoData);
			
			
			// 新增資料表記錄出場次數與守備位置次數 取得球員的位置(野手的位置)
			if ($playerType == 'b')
			{
				$arrMatchPos = array();
				preg_match_all($pPlayerPos, $strInfoHtml, $arrMatchPos);
				var_dump($arrMatchPos);
				$strMatchPos = $arrMatchPos[0][0];
				if(strpos($strMatchPos, HANJI_POS_C) !== FALSE)
				{
					$playerBaseData->updateData['c'] = 1;
				}
				elseif(strpos($strMatchPos, HANJI_POS_OF) !== FALSE)
				{
					$playerBaseData->updateData['of'] = 1;
				}
			}
			
			//Player record data
			$playerRecData = new stdClass();
			//Yahoo Id
			$playerRecData->yahooId = $yahooId;
			
			$attrData = utilHtmlManager::replaceHtmlTag($attrData);
			
			//投手
			if($playerType == 'p')
			{
				$playerRecData->updateTable = constDB::PLAYER_REC_P_2012;
				/**
				 * id	
				 * points 積分	
				 * ppg 積分/試合數（小數點1位）	
				 * g 試合數	
				 * gs 先發（完投+試合當初）	
				 * w 勝利	
				 * l 失敗	
				 * sv 救援	
				 * hld 中繼（ホールド）	
				 * cg 完投	
				 * sho 完封（無点勝）	
				 * ip 投球回数（小數點1位）	
				 * h 被安打	
				 * r 失点	
				 * er 自責点	
				 * hr 被本塁打	
				 * bb 四球+死球	
				 * k 奪三振	
				 * era 防御率（小數點2位）	
				 * whip (安打+四壞)/投球局數 （小數點2位）	
				 * wpct 勝場÷（勝場+敗場）（小數點2位）
				 */
				$playerRecData->updateData['g'] 	= $attrData[3];
				$playerRecData->updateData['w'] 	= $attrData[7];
				$playerRecData->updateData['l'] 	= $attrData[8];
				$playerRecData->updateData['sv'] 	= $attrData[11];
				$playerRecData->updateData['hld'] 	= $attrData[9];
				$playerRecData->updateData['cg'] 	= $attrData[4];
				$playerRecData->updateData['sho'] 	= $attrData[5];
				
				$arrayIP = explode(" ", $attrData[14]);
				//實際運算的IP
				$realIP	 = $arrayIP[0] + substr($arrayIP[1], 0, 1) / 3;
				//記錄上的IP
				//$playerRecData->updateData['ip'] 	= $arrayIP[0] . '.' . substr($arrayIP[1], 0, 1);
				$playerRecData->updateData['ip'] 	= $arrayIP[0];
				$playerRecData->updateData['ip2'] 	= substr($arrayIP[1], 0, 1);
				
				$playerRecData->updateData['h'] 	= $attrData[15];
				$playerRecData->updateData['r'] 	= $attrData[22];
				$playerRecData->updateData['er'] 	= $attrData[23];
				$playerRecData->updateData['hr'] 	= $attrData[16];
				$playerRecData->updateData['bb'] 	= $attrData[18] + $attrData[19];
				$playerRecData->updateData['k'] 	= $attrData[17];
				$playerRecData->updateData['era'] 	= $attrData[2];
				
				$playerRecData->updateData['whip'] 	= ($realIP) ? ($playerRecData->updateData['h'] + $playerRecData->updateData['bb']) / $realIP : 0 ;
				$playerRecData->updateData['wpct']  = ($playerRecData->updateData['w']+$playerRecData->updateData['l']) ?
														$playerRecData->updateData['w'] / ($playerRecData->updateData['w']+$playerRecData->updateData['l']) : 0;
				//@todo 先發（完投＋試合當初）
				$pPlayerDataGS = '/<tr class=\"yjM\".*?<\/tr>/';
				$arrPlayerDataGS = array();
				preg_match_all($pPlayerDataGS, $strInfoHtml, $arrPlayerDataGS);
				
				$pPlayerDataTD = '/<td.*?<\/td>/';
				$arrPlayerDataTD = array();
				preg_match_all($pPlayerDataTD, $arrPlayerDataGS[0][0], $arrPlayerDataTD);
				$arrPlayerDataTD[0] = utilHtmlManager::replaceHtmlTag($arrPlayerDataTD[0]);
				$playerRecData->updateData['gs'] = intval($arrPlayerDataTD[0][3]) + intval($arrPlayerDataTD[0][5]);
			}
			//打者
			else 
			{
				$playerRecData->updateTable = constDB::PLAYER_REC_H_2012;
				/**
				 * id	
				 * points 積分	
				 * ppg 積分/試合數	
				 * g 試合數	
				 * ab 打數	
				 * r 得点	
				 * h 安打	
				 * 2b 二塁打	
				 * 3b 三塁打	
				 * hr 本塁打	
				 * rbi 打点	
				 * bb 四球+死球	
				 * k 三振	
				 * sb 盗塁	
				 * cs 盗塁死	
				 * avg 打率（小數點3位）	
				 * obp 出塁率（小數點3位）	
				 * slg 長打率（小數點3位）	
				 * ops 綜合攻擊指標（小數點3位）
				 */
				$playerRecData->updateData['g'] 	= $attrData[3];
				$playerRecData->updateData['ab'] 	= $attrData[5];
				$playerRecData->updateData['r'] 	= $attrData[12];
				$playerRecData->updateData['h'] 	= $attrData[6];
				$playerRecData->updateData['2b'] 	= $attrData[7];
				$playerRecData->updateData['3b'] 	= $attrData[8];
				$playerRecData->updateData['hr'] 	= $attrData[9];
				$playerRecData->updateData['rbi'] 	= $attrData[11];
				$playerRecData->updateData['bb'] 	= $attrData[14] + $attrData[15];
				$playerRecData->updateData['k'] 	= $attrData[13];
				$playerRecData->updateData['sb'] 	= $attrData[18];
				$playerRecData->updateData['avg'] 	= $attrData[2];
				$playerRecData->updateData['obp'] 	= $attrData[19];
				$playerRecData->updateData['slg'] 	= $attrData[20];
				$playerRecData->updateData['ops'] 	= $attrData[19] + $attrData[20];
			}
			var_dump($playerBaseData, $playerInfoData, $playerRecData);
			/**
			 * playerBaseData一定要先insert才會產出系統的playerId
			 */
			utilPlayerDataManager::sharedDataManager()->updatePlayerData($playerBaseData);
			utilPlayerDataManager::sharedDataManager()->updatePlayerData($playerInfoData);
			utilPlayerDataManager::sharedDataManager()->updatePlayerData($playerRecData);
			utilPlayerDataManager::sharedDataManager()->updatePlayerTeamIndex($yahooId, $teamId);
			
			flush();
		}
		echo '<br/>';
	}
}
echo '</pre>';
?>