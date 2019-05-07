<?php
ini_set("display_errors", 1);

include '../lib/common.php';
include '../lib/package/loader.php';
include '../include/func.php';

//排除投手的打擊成績
define("HANJI_PITCHER_1", "投");
define("HANJI_PITCHER_2", "(投)");

//將資料從投手成績 分成上下半段 方便regex
define("HANJI_PITCHER_REC", "投手成績一覧");

//定義一壘安打的漢字
define("HANJI_HIT_1",    "安");
define("HANJI_DOUBLE_1", "2");
define("HANJI_TRIPLE_1", "3");

define("HANJI_WIN", 	"○");
define("HANJI_LOSE", 	"●");
define("HANJI_SAVE", 	"S");
define("HANJI_HP", 		"H");

$array = array('a'=>1, 'b'=>2);

var_dump((object)$array);

$iYear = 2011;
$iMonth = 9;
for ($iDay = 1 ; $iDay <= 31 ; $iDay++)
{

	//Yahoo比賽日期(測試用資料)
	$iGameDate 		= sprintf("%04d%02d%02d", $iYear, $iMonth, $iDay);
	//日本時間(測試用資料)
	$iGameDateTime	= sprintf("%04d-%02d-%02d", $iYear, $iMonth, $iDay);
	
	//初始本日投打者記錄
	utilGameDataManager::sharedDataManager()->initDailyRecord($iGameDateTime);
	
	for ($iGame = 1 ; $iGame <= 6 ; $iGame++)
	{
		//比賽編號
		$gameId = sprintf("%d%02d", $iGameDate, $iGame);
		
		//分析比賽是否結束
		if($urlToParse = sprintf("http://baseball.yahoo.co.jp/npb/game/%d/top", $gameId))
		{
			$pGameOver = '<em>試合終了</em>';
			$strHtml = implode("", file($urlToParse));
			if (strpos($strHtml, $pGameOver) >= 0)
			{
				$bIsGameOver = true;
			}
			else 
			{
				$bIsGameOver = false;
			}
			
			//比賽結束在撈資料
			if($bIsGameOver)
			{
				$urlToParse = sprintf("http://baseball.yahoo.co.jp/npb/game/%d/stats", $gameId);
				
				//去除原始碼中的換行符號，方便REx的解析
				$strHtml = implode("", file($urlToParse));
				$strHtml = str_replace("\r\n", "", $strHtml);
				$strHtml = str_replace("\n", "", $strHtml);
				
				//解析打者成績
				$pPlayerList = "/<tr><td>.*?<\/td><td class=\"pn\".*?<\/tr>/";
				$arrMatchList = array();
				preg_match_all($pPlayerList, substr($strHtml, 0, strpos($strHtml, HANJI_PITCHER_REC)), $arrMatchList);
				
				//解析打者每個欄位的資料
				$pPlayerData = '/<td.*?<\/td>/';
				$pYahooId = "/\d+/";
				
				echo '<pre>';
				echo '<br/>===============打者===============<br/><br/>';
				
				foreach ($arrMatchList[0] as $strMatchData)
				{
					//取得各欄位屬性
					$arrMatchData = array();
					preg_match_all($pPlayerData, $strMatchData, $arrMatchData);
					
					//取得Yahoo ID
					$arrMatchYahooId = array();
					preg_match_all($pYahooId, $arrMatchData[0][1], $arrMatchYahooId);
					$yahooId = $arrMatchYahooId[0][0];
					
					//清除HTML Tag
					$attrData = utilHtmlManager::replaceHtmlTag($arrMatchData[0]);
					
					//排除投手的打擊
					if($attrData[0] != HANJI_PITCHER_1 && $attrData[0] != HANJI_PITCHER_2)
					{
						$objDailyRecordH = new structDailyRecordH();
						/**
						 * [0] 位置	
						 * [1] 選手	
						 * [2] 打率	
						 * [3] 打数	
						 * [4] 得点	
						 * [5] 安打	
						 * [6] 打点	
						 * [7] 三振	
						 * [8] 四死	
						 * [9] 犠打	
						 * [10] 盗塁	
						 * [11] 失策	
						 * [12] 本塁打
						 * [13] 1回 
						 * [14] 2回 
						 * [15] 3回 
						 * [16] 4回 
						 * [17] 5回 
						 * [18] 6回 
						 * [19] 7回 
						 * [20] 8回 
						 * [21] 9回 
						 * [...] ...
						 */
						//@todo 計算積分 寫入DB
						$objDailyRecordH->g		= 1;
						//球員編號
						$objDailyRecordH->id 	= utilPlayerDataManager::sharedDataManager()->getPlayerId($yahooId);
						//比賽日期
						$objDailyRecordH->date 	= $iGameDateTime;
						//打率
						$objDailyRecordH->avg  	= $attrData[2];
						//打數
						$objDailyRecordH->ab   	= $attrData[3];
						//得點
						$objDailyRecordH->r		= $attrData[4];
						//安打
						$objDailyRecordH->h		= $attrData[5];
						//打點
						$objDailyRecordH->rbi	= $attrData[6];
						//三振
						$objDailyRecordH->k		= $attrData[7];
						//四死
						$objDailyRecordH->bb	= $attrData[8];
						//本打
						$objDailyRecordH->hr	= $attrData[12];
						//計算一二三本壘打
						$iCountArr = count($attrData);
						for ($iIndex = 13 ; $iIndex <= $iCountArr ; $iIndex++)
						{
							//三
							if(strpos($attrData[$iIndex], HANJI_TRIPLE_1) !== FALSE)
							{
								$objDailyRecordH->h3b++;
							}
							//二
							elseif(strpos($attrData[$iIndex], HANJI_DOUBLE_1) !== FALSE)
							{
								$objDailyRecordH->h2b++;
							}
							/*
							//一
							elseif(strpos($attrData[$iIndex], HANJI_HIT_1) !== FALSE)
							{
								$objDailyRecordH->h++;
							}
							*/
						}
						
						//debug info
						var_dump($objDailyRecordH);
						flush();
						
						//寫入DB
						utilGameDataManager::sharedDataManager()->updateDailyRecordH($objDailyRecordH);
						$attrData = array();
						$objDailyRecordH = null;
					}
				}
				
				echo '<br/>===============投手===============<br/><br/>';
				
				//@todo 解析投手成績
				$pPlayerList = "/<tr><td>.*?<\/td><td class=\"pn\".*?<\/tr>/";
				$arrMatchList = array();
				preg_match_all($pPlayerList, substr($strHtml, strpos($strHtml, HANJI_PITCHER_REC)), $arrMatchList);
				
				foreach ($arrMatchList[0] as $strMatchData)
				{
					//取得各欄位屬性
					$arrMatchData = array();
					preg_match_all($pPlayerData, $strMatchData, $arrMatchData);
					
					//取得Yahoo ID
					$arrMatchYahooId = array();
					preg_match_all($pYahooId, $arrMatchData[0][1], $arrMatchYahooId);
					$yahooId = $arrMatchYahooId[0][0];
					
					//清除HTML Tag
					$attrData = utilHtmlManager::replaceHtmlTag($arrMatchData[0]);
					
					$objDailyRecordP = new structDailyRecordP();
					
					/**
					 * [0] 勝敗（セーブ）	
					 * [1] 選手	
					 * [2] 防御率	
					 * [3] 投球回数	
					 * [4] 打者数	
					 * [5] 投球数
					 * [6] 被安打	
					 * [7] 被本塁打	
					 * [8] 奪三振	
					 * [9] 与四死球	
					 * [10] 失点*	
					 * [11] 自責点*
					 */
					$objDailyRecordP->g		= 1;
					//球員編號
					$objDailyRecordP->id 	= utilPlayerDataManager::sharedDataManager()->getPlayerId($yahooId);
					//比賽日期
					$objDailyRecordP->date 	= $iGameDateTime;
					//W,L,S,H
					if (strpos($attrData[0], HANJI_WIN) !== FALSE)
					{
						$objDailyRecordP->w = 1;
					}
					elseif(strpos($attrData[0], HANJI_LOSE) !== FALSE)
					{
						$objDailyRecordP->l	= 1;
					}
					elseif(strpos($attrData[0], HANJI_SAVE) !== FALSE)
					{
						$objDailyRecordP->sv = 1;
					}
					//@todo HP 目前網頁沒有列出
					elseif(strpos($attrData[0], HANJI_HP) !== FALSE)
					{
						$objDailyRecordP->hld = 1;
					}
					//防禦率
					$objDailyRecordP->era	= $attrData[2];
					//投球回數
					$arrayIP = explode(" ", $attrData[3]);
					//實際運算的IP
					$realIP	 = $arrayIP[0] + substr($arrayIP[1], 0, 1) / 3;
					//記錄上的IP
					//$objDailyRecordP->ip 	= $arrayIP[0] . '.' . substr($arrayIP[1], 0, 1);
					$objDailyRecordP->ip 	= $arrayIP[0];
					$objDailyRecordP->ip2	= substr($arrayIP[1], 0, 1);
					//被安打
					$objDailyRecordP->h		= $attrData[6];
					//被本打
					$objDailyRecordP->hr	= $attrData[7];
					//三振
					$objDailyRecordP->k		= $attrData[8];
					//四死球
					$objDailyRecordP->bb	= $attrData[9];
					//失点*
					$objDailyRecordP->r		= $attrData[10];
					//自責点*
					$objDailyRecordP->er	= $attrData[11];
					
					//@todo 計算積分 寫入DB
					var_dump($attrData,$objDailyRecordP);
					flush();
					
					//寫入DB
					utilGameDataManager::sharedDataManager()->updateDailyRecordP($objDailyRecordP);
					$attrData = array();
					$objDailyRecordP = null;
				}
			}
		}
	}
}
?>