<?php
//Access control allow Header
//header("Access-Control-Allow-Origin: http://fantasy.ohdada.com");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST");
//GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['method'])
{
	//lib
	include dirname(__FILE__).'/../lib/common.php';
	include dirname(__FILE__).'/../lib/package/loader.php';
	
	header("Content-Type:application/json;charset=UTF-8");
	
	//Check access token
	parse_str($_GET['query']);
	if(!utilAuth::checkAccessToken($accessToken)){
		$json['error']['message'] 	= 'An active access token must be used to query information about the current user.';
		$json['error']['type']		= 'AuthException';
		echo json_encode($json);
		exit();
	}
	
	try{
		switch ($_GET['method'])
		{
			//取得球員基本資料
			case 'player':
				//球員編號
				$playerId = $_GET['parm1'];
				$json['data'] = utilPlayerDataManager::sharedDataManager()->getPlayerData($playerId);
				echo json_encode($json);
				break;
			//取得球隊資料
			case 'myteam':
				$myTeamId = $_GET['parm1'];
				$json['data'] = utilMyTeamDataManager::sharedDataManager()->getMyTeamById($myTeamId);
				echo json_encode($json);
				break;
			//取得玩家資訊
			case 'user':
				$arrAccessToekn = explode("|", $accessToken); 
				$account = $arrAccessToekn[0];
				$json['data'] = utilUserDataManager::sharedDataManager()->getUserData($account);
				echo json_encode($json);
				break;
			default:
				$json['excetopn'] = 'No method for call.';
				echo json_encode($json);
				break;
		}
	}catch(Exception $e){
		$json['error']['type'] 		= get_class($e);
		$json['error']['code'] 		= $e->getCode();
		$json['error']['message'] 	= $e->getMessage();
		echo json_encode($json);
	}
}
//POST
elseif($_SERVER['REQUEST_METHOD'] == 'POST')
{
	//lib
	include dirname(__FILE__).'/../lib/common.php';
	include dirname(__FILE__).'/../lib/package/loader.php';
	
	header("Content-Type:text/javascript;charset=UTF-8");
		
	//Check access token
	$accessToken = $_POST['accessToken'];
	if(!utilAuth::checkAccessToken($accessToken)){
		$json['error']['message'] 	= 'An active access token must be used to query information about the current user.';
		$json['error']['type']		= 'AuthException';
		echo json_encode($json);
		exit();
	}
	
	//分析POST的REQUEST URI
	$arrRequestURI 	= explode("/", $_SERVER['REQUEST_URI']);
	$method 		= $arrRequestURI[1];
	
	try{
		//Parse token
		$arrAccessToekn = explode("|", $accessToken);
		//User account 
		$account 		= $arrAccessToekn[0];
		//User data
		$userData 		= utilUserDataManager::sharedDataManager()->getUserData($account);
		//User id
		$userId 		= $userData['id'];
		
		switch ($method)
		{
			//創立球隊
			case 'createMyTeam':
				$myTeamName = trim($_POST['myTeamName']);
				$favTeamId	= intval($_POST['favTeamId']);
				
				$json['success'] = utilMyTeamDataManager::sharedDataManager()->createMyTeam($userId, $myTeamName, $favTeamId);
				echo json_encode($json);
				break;
			//創立聯盟
			case 'createLeague':
				$leagueName 	= trim($_POST['leagueName']);
				
				$json['success'] = utilLeagueDataManager::sharedDataManager()->createLeague($userId, $leagueName);
				echo json_encode($json);
				break;
			//加入聯盟
			case 'joinLeague':
				$myTeamId 	= $_POST['myTeamId'];
				$leagueId	= $_POST['leagueId'];
				$password	= $_POST['password'];
				
				$json['success'] = utilLeagueDataManager::sharedDataManager()->joinLeague($myTeamId, $leagueId, $password);
				echo json_encode($json);
				break;
			//交易球員
			case 'trade':
				$myTeamId 	= $_POST['myTeamId'];
				$sellId		= $_POST['sellId'];
				$buyId		= $_POST['buyId'];
				$buyPos		= $_POST['buyPos'];
				
				$json['success'] = utilMyTeamDataManager::sharedDataManager()->trade($myTeamId_, $sellId_, $buyId_, $buyPos_);
				echo json_encode($json);
				break;
			default:
				break;
		}
	}catch(Exception $e){
		$json['error']['type'] 		= get_class($e);
		$json['error']['code'] 		= $e->getCode();
		$json['error']['message'] 	= $e->getMessage();
		echo json_encode($json);
	}
}
?>