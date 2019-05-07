<?php
include '../lib/common.php';
include '../lib/package/loader.php';

echo '<pre>';

$playerBaseDataList = utilPlayerDataManager::sharedDataManager()->getPlayerBaseDataList();
$reocrd2011p = utilPlayerDataManager::sharedDataManager()->get2011RecordList('p');
$reocrd2011h = utilPlayerDataManager::sharedDataManager()->get2011RecordList('h');

foreach ($playerBaseDataList as $playerBaseData)
{
	$playerRecData = new stdClass();
	$playerRecData->playerId = $playerBaseData['id'];
	//Pitcher
	if($playerBaseData['p'])
	{
		$playerRecData->updateTable = constDB::PLAYER_REC_P_2011;
		$playerRecData->updateData	= $reocrd2011p[$playerBaseData['yid']];		
	}
	//Hitter
	else 
	{
		$playerRecData->updateTable = constDB::PLAYER_REC_H_2011;
		$playerRecData->updateData	= $reocrd2011h[$playerBaseData['yid']];
	}
	utilPlayerDataManager::sharedDataManager()->updatePlayerData($playerRecData);
}
?>