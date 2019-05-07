<?php
include_once("validateCookie.php");
include_once("include/func.php");
require 'Smarty/libs/Smarty.class.php';
$myTeamID = $_COOKIE['myteamid'];

require 'lib/package/Constant/constDB.php';
require 'lib/package/Utility/utilDataConnection.php';
require 'lib/package/Utility/IDataManager.php';
require 'lib/package/Utility/utilPlayerDataManager.php';
require 'lib/package/Utility/utilPage.php';

$myTeamData = new MyTeam($_COOKIE['myteamid']);
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<?php include(dirname(__FILE__)."/head_tag.php"); ?>
</head>
<body id="res_playerlist">
	<div id="content" class="container canvas">
		<header>
			<?php include(dirname(__FILE__)."/header.php"); ?>
		</header>
		<div class="row" id="team_info">
			<div class="span12">
				<h2 class="title">隊伍資訊</h2>
				<table class="table table-striped table-bordered table-condensed">
					<tbody><tr>
						<th>總積分</th>
						<td><?php echo number_format($myTeamData->myTeamData->points, 0, '.' ,',');?></td>
						<th>現金（總資產）</th>
						<td><?php echo GetMoneyFormat($myTeamData->myTeamData->cash)."（".GetMoneyFormat($myTeamData->myTeamData->money)."）";?></td>
					</tr>
					<tr>
						<th>積分總排名</th>
						<td><?php echo $rankPoints." / ".$myTeamCount;?></td>
						<th>資產排名</th>
						<td><?php echo $rankMoney." / ".$myTeamCount;?></td>
					</tr>
					<tr>
						<th>投手交易次數</th>
						<td><?php echo $myTeamData->myTeamData->trade_p;?></td>
						<th>打者交易次數</th>
						<td><?php echo $myTeamData->myTeamData->trade_h;?></td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="row" id="playerlist">
			<h2 class="title span12">搜尋球員</h2>
		</div>
<?php
/*
$Smarty = new Smarty();
$Smarty->setTemplateDir(dirname(__FILE__) . '/Smarty/templates');
$Smarty->setCompileDir(dirname(__FILE__) . '/Smarty/templates_c');
$Smarty->setCacheDir(dirname(__FILE__) . '/Smarty/cache');
$Smarty->caching = 2;
$Smarty->cache_lifetime = 10;
*/
if(empty($_REQUEST['year'])) $_REQUEST['year'] = 'this';
//if(empty($_REQUEST['type'])) $_REQUEST['type'] = 'p';
if(empty($_REQUEST['pos'])) $_REQUEST['pos'] = 'p';
if(empty($_REQUEST['orderby'])) $_REQUEST['orderby'] = 'nowprice';
if(empty($_REQUEST['order'])) $_REQUEST['order'] = 'DESC';
if(empty($_REQUEST['page'])) $_REQUEST['page'] = '1';
if(empty($_REQUEST['rowPerPage'])) $_REQUEST['rowPerPage'] = 20;

$strParamPage = 'year='.$_REQUEST['year'].'&'.
				//'type='.$_REQUEST['type'].'&'.
				'pos='.$_REQUEST['pos'].'&'.
				'orderby='.$_REQUEST['orderby'].'&'.
				'order='.$_REQUEST['order'].'&'.
				'rowPerPage='.$_REQUEST['rowPerPage'].'&';

$strParamHead = 'year='.$_REQUEST['year'].'&'.
				//'type='.$_REQUEST['type'].'&'.
				'pos='.$_REQUEST['pos'].'&'.
				'rowPerPage='.$_REQUEST['rowPerPage'].'&';

$strParamPos = 'year='.$_REQUEST['year'].'&'.
				'page='.$_REQUEST['page'].'&'.
				'rowPerPage='.$_REQUEST['rowPerPage'].'&';

$strParamYear = 'pos='.$_REQUEST['pos'].'&'.
				'orderby='.$_REQUEST['orderby'].'&'.
				'order='.$_REQUEST['order'].'&'.
				'rowPerPage='.$_REQUEST['rowPerPage'].'&';
/*
$iCacheID = $_REQUEST['year'] . '|' . 
			$_REQUEST['type'] . '|' . 
			$_REQUEST['pos'] . '|' . 
			$_REQUEST['orderby'] . '|' . 
			$_REQUEST['order'] . '|' . 
			$_REQUEST['page'];

if($Smarty->isCached(basename(__FILE__).'.tpl', $iCacheID))
{
	echo  $Smarty->fetch(basename(__FILE__).'.tpl', $iCacheID);
}
else 
{
*/
	$html = '
		<div id="content" class="container canvas">
			<div>
				<a class="btn '.(($_REQUEST['year'] == 'this') ? 'btn-info' : '' ).'" href="?'.$strParamYear.'year=this">2012</a> 
				<a class="btn '.(($_REQUEST['year'] == '2011') ? 'btn-info' : '' ).'" href="?'.$strParamYear.'year=2011">2011</a> 
				<a class="btn '.(($_REQUEST['pos'] == 'p') ? 'btn-info' : '' ).'" href="?'.$strParamPos.'pos=p">P</a> 
				<a class="btn '.(($_REQUEST['pos'] == 'c') ? 'btn-info' : '' ).'" href="?'.$strParamPos.'pos=c">C</a> 
				<a class="btn '.(($_REQUEST['pos'] == 'fb') ? 'btn-info' : '' ).'" href="?'.$strParamPos.'pos=fb">1B</a> 
				<a class="btn '.(($_REQUEST['pos'] == 'sb') ? 'btn-info' : '' ).'" href="?'.$strParamPos.'pos=sb">2B</a> 
				<a class="btn '.(($_REQUEST['pos'] == 'tb') ? 'btn-info' : '' ).'" href="?'.$strParamPos.'pos=tb">3B</a> 
				<a class="btn '.(($_REQUEST['pos'] == 'ss') ? 'btn-info' : '' ).'" href="?'.$strParamPos.'pos=ss">SS</a> 
				<a class="btn '.(($_REQUEST['pos'] == 'of') ? 'btn-info' : '' ).'" href="?'.$strParamPos.'pos=of">OF</a>
				<a class="btn '.(($_REQUEST['pos'] == 'dh') ? 'btn-info' : '' ).'" href="?'.$strParamPos.'pos=dh">DH</a>
			</div>
			<br/>
			<table class="table table-striped table-bordered table-condensed">
	';
	
	$dataPlayerList = utilPlayerDataManager::sharedDataManager()->getPlayerList($_REQUEST);
	$playerList = $dataPlayerList['playerList'];
	$rowTotal	= $dataPlayerList['rowTotal'];
	
	$objPage = new utilPage($rowTotal);
	$objPage->GoPage($_REQUEST['page'], $_REQUEST['rowPerPage']);
	
	$objMyTeamData = GetMyTeamData($myTeamID);
	
	//var_dump($playerList);
	switch ($_REQUEST['pos'])
	{
		case 'p':
			$html .= '
			<thead>
				<tr>
					<th>Name</th>
					<th>Team</th>
					<th>Pos</th>
					<th>'.getHeadStr('G','g', $strParamHead).'</th>
					<th>'.getHeadStr('W','w', $strParamHead).'</th>
					<th>'.getHeadStr('L','l', $strParamHead).'</th>
					<th>'.getHeadStr('SV','sv', $strParamHead).'</th>
					<th>'.getHeadStr('IP','ip', $strParamHead).'</th>
					<th>'.getHeadStr('H','h', $strParamHead).'</th>
					<th>'.getHeadStr('HR','hr', $strParamHead).'</th>
					<th>'.getHeadStr('BB','bb', $strParamHead).'</th>
					<th>'.getHeadStr('K','k', $strParamHead).'</th>
					<th>'.getHeadStr('WHIP','whip', $strParamHead).'</th>
					<th>'.getHeadStr('R','r', $strParamHead).'</th>
					<th>'.getHeadStr('ER','er', $strParamHead).'</th>
					<th>'.getHeadStr('ERA','era', $strParamHead).'</th>
					<th>
						Last
					</th>
					<th>'.getHeadStr('Pts','points', $strParamHead).'</th>
					<th>'.getHeadStr('PPG','pg', $strParamHead).'</th>
					<th>'.getHeadStr('Price','nowprice', $strParamHead).'</th>
				</tr>
			</thead>
			<tbody>
			';
			if(count($playerList))
			{
				foreach ($playerList as $playerData)
				{
					$objPlayerData = GetPlayerBaseData($playerData['id']);
					$html .= '
					<tr>
						<td>'.GetPlayerNameStr($objPlayerData).'</td>
						<td>'.GetTeamNameMinStr($playerData['team_id']).'</td>
						<td>'.GetPosStr($objMyTeamData, $objPlayerData).'</td>
						<td>'.$playerData['g'].'</td>
						<td>'.$playerData['w'].'</td>
						<td>'.$playerData['l'].'</td>
						<td>'.$playerData['sv'].'</td>
						<td>'.$playerData['ip'].'</td>
						<td>'.$playerData['h'].'</td>
						<td>'.$playerData['hr'].'</td>
						<td>'.$playerData['bb'].'</td>
						<td>'.$playerData['k'].'</td>
						<td>'.sprintf("%.2f",$playerData['whip']).'</td>
						<td>'.$playerData['r'].'</td>
						<td>'.$playerData['er'].'</td>
						<td>'.sprintf("%.2f",$playerData['era']).'</td>
						<td>'.GetPlayerRec1($playerData['id'])->points.'</td>
						<td>'.$playerData['points'].'</td>
						<td>'.sprintf("%.1f",$playerData['ppg']).'</td>
						<td>'.GetPriceStr($objMyTeamData, $objPlayerData).'</td>
					</tr>
					';
				}
			}
			break;
		default:
			$html .= '
			<thead>
				<tr>
					<th>Name</th>
					<th>Team</th>
					<th>Pos</th>
					<th>'.getHeadStr('G','g', $strParamHead).'</th>
					<th>'.getHeadStr('AB','ab', $strParamHead).'</th>
					<th>'.getHeadStr('R','r', $strParamHead).'</th>
					<th>'.getHeadStr('H','h', $strParamHead).'</th>
					<th>'.getHeadStr('2B','h2', $strParamHead).'</th>
					<th>'.getHeadStr('3B','h3', $strParamHead).'</th>
					<th>'.getHeadStr('HR','hr', $strParamHead).'</th>
					<th>'.getHeadStr('RBI','rbi', $strParamHead).'</th>
					<th>'.getHeadStr('SB','B.sb', $strParamHead).'</th>
					<th>'.getHeadStr('BB','bb', $strParamHead).'</th>
					<th>'.getHeadStr('SO','k', $strParamHead).'</th>
					<th>'.getHeadStr('SLG','slg', $strParamHead).'</th>
					<th>'.getHeadStr('AVG','avg', $strParamHead).'</th>
					<th>Last</th>
					<th>'.getHeadStr('Pts','points', $strParamHead).'</th>
					<th>'.getHeadStr('PPG','pg', $strParamHead).'</th>
					<th>'.getHeadStr('Price','nowprice', $strParamHead).'</th>
				</tr>
			</thead>
			<tbody>
			';
				if(count($playerList))
			{
				foreach ($playerList as $playerData)
				{
					$objPlayerData = GetPlayerBaseData($playerData['id']);
					$html .= '
					<tr>
						<td>'.GetPlayerNameStr($objPlayerData).'</td>
						<td>'.GetTeamNameMinStr($playerData['team_id']).'</td>
						<td>'.GetPosStr($objMyTeamData, $objPlayerData).'</td>
						<td>'.$playerData['g'].'</td>
						<td>'.$playerData['ab'].'</td>
						<td>'.$playerData['r'].'</td>
						<td>'.$playerData['h'].'</td>
						<td>'.$playerData['h2'].'</td>
						<td>'.$playerData['h3'].'</td>
						<td>'.$playerData['hr'].'</td>
						<td>'.$playerData['rbi'].'</td>
						<td>'.$playerData['sb'].'</td>
						<td>'.$playerData['bb'].'</td>
						<td>'.$playerData['k'].'</td>
						<td>'.sprintf("%.3f",$playerData['slg']).'</td>
						<td>'.sprintf("%.3f",$playerData['avg']).'</td>
						<td>'.GetPlayerRec1($playerData['id'])->points.'</td>
						<td>'.$playerData['points'].'</td>
						<td>'.sprintf("%.1f",$playerData['ppg']).'</td>
						<td>'.GetPriceStr($objMyTeamData, $objPlayerData).'</td>
					</tr>
					';
				}
			}
			break;
	}
	$html .= '
			</tbody>
			</table>
		</div>
	';
echo $html;

echo $objPage->getHtml($_SERVER['PHP_SELF'], $strParamPage);
?>
	<?php include(dirname(__FILE__).'/footer.php'); ?>
	</div>
</body>
</html>

<?php 
function getHeadStr($title_ = '', $orderby_ = '', $strParamHead_ = '')
{
	$html = '
		<a href="?'.$strParamHead_.'orderby='.$orderby_.'&order='.(($_REQUEST['orderby'] == $orderby_) ? ($_REQUEST['order'] == 'DESC') ? 'ASC' : 'DESC' : 'DESC').'">
		'.$title_.'
		'.(($_REQUEST['orderby'] == $orderby_) ? ($_REQUEST['order'] == 'DESC') ? '<i class="icon-arrow-down"></i>' : '<i class="icon-arrow-up"></i>' : '').'
		</a>
	';
	return $html;
}
?>