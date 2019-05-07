<?php
include 'validateCookie.php';
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");


if($_REQUEST['method'])
{
	switch ($_REQUEST['method'])
	{
		case 'getFriendRank':
			//Smarty//
			require dirname(__FILE__).'/Smarty/libs/Smarty.class.php';
            $smarty = new Smarty();
            $smarty->setTemplateDir(dirname(__FILE__).'/Smarty/templates/');
            $smarty->setCompileDir(dirname(__FILE__).'/Smarty/templates_c/');
            $smarty->setCacheDir(dirname(__FILE__).'/Smarty/cache/');
            $smarty->cache_lifetime = 3600 * 6;
            //$smarty->cache_lifetime = 10 * 6;
            // 開啟smarty cache
            $smarty->caching = 2;
            
			//檢查cache頁面是否存在
            if($smarty->isCached('board_friend.tpl', $_COOKIE['userid']))
            {
                $json['html'] = $smarty->fetch('board_friend.tpl', $_COOKIE['userid']);
                
            }
            else 
            {
            	$db = new DB();
            	$json['freindList'] = $_REQUEST['friendList'];
            	$aFBId 			= array();
            	$aFBName 		= array();
            	$aMoney			= array();
            	$aPoint			= array();
            	$aMyteamName	= array();
            	$aMyteamId		= array();
            	foreach ($_REQUEST['friendList'] as $friend)
            	{
            		$account = $friend['id'] . '@FB';
            		$sql = sprintf("SELECT C.id,C.name,C.money,C.points FROM `user_data` AS A 
            						LEFT JOIN `user_myteam_index` AS B ON A.id=B.user_id 
            						LEFT JOIN `myteam_data` AS C ON B.myteam_id=C.id 
            						WHERE 1 AND A.account='%s'", $account);
            		if($stmt = $db->query($sql))
            		{
            			$row = $stmt->fetch(PDO::FETCH_ASSOC);
            			
            			array_push($aFBId, $friend['id']);
            			array_push($aFBName, $friend['name']);
            			array_push($aMoney, $row['money']);
            			array_push($aPoint, $row['points']);
            			array_push($aMyteamName, $row['name']);
            			array_push($aMyteamId, $row['id']);            			
            		}
            	}
            	$count = count($aMyteamId);
            	
            	//Sort by money
            	/*
            	array_multisort($aMoney, SORT_DESC, SORT_NUMERIC, $aFBId, $aFBName, $aPoint, $aMyteamName, $aMyteamId);
            	
            	
            	$htmlMoney = '';
            	for ($iRank = 1 ; $iRank <= $count; $iRank++)
            	{
            		$iIndex = $iRank - 1;
            		$rank = ($_COOKIE['myteamid'] == $aMyteamId[$iIndex]) ? 
            				'<span class="badge badge-info">'.$iRank.'</span>' : 
            				'<span class="badge">'.$iRank.'</span>' ;
            		$htmlMoney .= '
            			<tr>
            				<td>'.$rank.'</td>
            				<td><a href="./myteam_roster.php?myteamid='.$aMyteamId[$iIndex].'">'.$aMyteamName[$iIndex].'</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/'.$aFBId[$iIndex].'/picture?type=square"></td>
            				<td>'.$aFBName[$iIndex].'</td>
            				<td>'.GetMoneyFormat($aMoney[$iIndex]).'</td>
            				<td>'.number_format($aPoint[$iIndex]).'</td>
            			</tr>
            		';
            	}
            	*/
            	//Sort by point
            	array_multisort($aPoint, SORT_DESC, SORT_NUMERIC, $aFBId, $aFBName, $aMoney, $aMyteamName, $aMyteamId);
            	
            	$htmlPoint = '';
                for ($iRank = 1 ; $iRank <= $count; $iRank++)
            	{
            		$iIndex = $iRank - 1;
            		$rank = ($_COOKIE['myteamid'] == $aMyteamId[$iIndex]) ? 
            				'<span class="badge badge-info">'.$iRank.'</span>' : 
            				'<span class="badge">'.$iRank.'</span>' ;
            		$htmlPoint .= '
            			<tr>
            				<td>'.$rank.'</td>
            				<td><a href="./myteam_roster.php?myteamid='.$aMyteamId[$iIndex].'">'.$aMyteamName[$iIndex].'</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/'.$aFBId[$iIndex].'/picture?type=square"></td>
            				<td>'.$aFBName[$iIndex].'</td>
            				<td>'.GetMoneyFormat($aMoney[$iIndex]).'</td>
            				<td>'.number_format($aPoint[$iIndex]).'</td>
            			</tr>
            		';
            	}            	
            	
                //$smarty->assign('htmlMoney', $htmlMoney);
                $smarty->assign('htmlPoint', $htmlPoint);
                $json['html'] = $smarty->fetch('board_friend.tpl', $_COOKIE['userid']);
            }
            echo json_encode($json);
			break;
		default:
			break;
	}
	exit();
}
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <?php include(dirname(__FILE__)."/head_tag.php"); ?>
</head>
<body id="create_team">
  <div id="content" class="container canvas">
    <header>
    <style>
	.badge {
	  padding: 1px 9px 2px;
	  font-size: 12.025px;
	  font-weight: bold;
	  white-space: nowrap;
	  color: #ffffff;
	  background-color: #999999;
	  -webkit-border-radius: 9px;
	  -moz-border-radius: 9px;
	  border-radius: 9px;
	}
	.badge:hover {
	  color: #ffffff;
	  text-decoration: none;
	  cursor: pointer;
	}
	.badge-error {
	  background-color: #b94a48;
	}
	.badge-error:hover {
	  background-color: #953b39;
	}
	.badge-warning {
	  background-color: #f89406;
	}
	.badge-warning:hover {
	  background-color: #c67605;
	}
	.badge-success {
	  background-color: #468847;
	}
	.badge-success:hover {
	  background-color: #356635;
	}
	.badge-info {
	  background-color: #3a87ad;
	}
	.badge-info:hover {
	  background-color: #2d6987;
	}
	.badge-inverse {
	  background-color: #333333;
	}
	.badge-inverse:hover {
	  background-color: #1a1a1a;
	}
	</style>
      <?php include(dirname(__FILE__)."/header.php"); ?>
    </header>
    	<div id="board_friend">LOADING...</div>
		<footer>
			© oh!dada 2012
		</footer>
	</div>
	<script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
	<!--[if lt IE 7 ]>
		<script src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->
<?php 
include 'FBJS.php';
?>

</body>
</html>