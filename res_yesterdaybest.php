<?php

include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("class/myteam.class.php");
include_once("validateCookie.php");

$myTeamID = $_COOKIE['myteamid'];
$myTeamData = GetMyTeamData($myTeamID);

$db = new DB();
$sql = "SELECT `date` FROM `system_daily_log` WHERE `score_time` != '0000-00-00 00:00:00' ORDER BY `date` DESC";
$stmt = $db->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$date = $row['date'];

$sql = "SELECT * FROM `myteam_rec_daily` WHERE `date` = '".$date."' ORDER BY `points` DESC LIMIT 10";
$stmt = $db->query($sql);
$myTeamRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `player_rec_hitter_daily` WHERE `date` = '".$date."' ORDER BY `points` DESC LIMIT 10";
$stmt = $db->query($sql);
$hitterRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `player_rec_pitcher_daily` WHERE `date` = '".$date."' ORDER BY `points` DESC LIMIT 10";
$stmt = $db->query($sql);
$pitcherRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<?php include(dirname(__FILE__)."/head_tag.php"); ?>
</head>
<body id="yesterdaybest">
	<div id="content" class="container canvas">
		<header>
			<?php include(dirname(__FILE__)."/header.php"); ?>
		</header>
		<div class="row">
			<div class="title span12">
				<h2>昨日最佳表現</h2>
				<h4 class="title">以下是 <?php echo $date;?> 的最佳表現</h4>
			</div>
		</div>
		<div id="lea_page" class="row">
			<h3 class="span12">單日玩家積分排行</h3>
			<div id="point_rank" class="span12">
				<table class="table bordered-table zebra-striped">
					<thead>
						<tr>
							<th class="table-caption" colspan="4">隊伍資訊</th>
							<th class="table-caption" colspan="2">積分</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th class="data-key">排名</th>
							<th class="data-key">隊伍名稱</th>
							<th class="data-key">隊徽</th>							
							<th class="data-key">玩家名稱</th>
							<th class="data-key">本季積分</th>
							<th class="data-key">單日積分</th>
						</tr>
						<?php
						for($i=0;$i<10;$i++)
						{
							$id = $myTeamRow[$i]['myteam_id'];
							$userdata = GetUserDataByTeamID($id);
							$fbid = substr($userdata['account'], 0, strpos($userdata['account'], "@"));
							?>
							<tr class="data-row">
							<td class="data-value"><?php echo ($i+1);?></td>
							<td class="data-value"><a href="myteam_roster.php?myteamid=<?php echo $id;?>"><?php echo GetMyTeamName($id);?></a></td>
							<td class="data-value">
								<img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/<?php echo $fbid;?>/picture?type=square">
							</td>							
							<td class="data-value">
								<div class="fbName1" id="fbName1<?php echo $fbid;?>"></div>
							</td>
							<td class="data-value"><?php echo GetPointsByMyTeamID($id);?></td>
							<td class="data-value"><?php echo $myTeamRow[$i]['points'];?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<div id="pitcher_point_rank" class="span12">
				<h3>單日投手積分排行榜</h3>
				<table class="table bordered-table zebra-striped">
					<thead>
						<tr>
							<th class="table-caption" colspan="10">選手資訊</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th class="data-key">選手名稱</th>
							<th class="data-key">位置</th>
							<th class="data-key">球隊</th>
							<th class="data-key">W</th>
							<th class="data-key">SV</th>
							<th class="data-key">IP</th>
							<th class="data-key">H</th>
							<th class="data-key">K</th>
							<th class="data-key">單日積分</th>
							<th class="data-key">價錢</th>
						</tr>
						<?php
						for($i=0;$i<10;$i++)
						{
							$id = $pitcherRow[$i]['id'];
							$playerBaseData = GetPlayerBaseData($id);
							$playerRec = GetPlayerRecByDate($id, $date);
							?>
							<tr class="data-row">
								<td class="data-value"><?php echo GetPlayerNameStr($playerBaseData);?></td>
								<td class="data-value"><?php echo GetPosStr($myTeamData, $playerBaseData);?></td>
								<td class="data-value"><?php echo GetTeamNameStrByPlayerID($id);?></td>
								<td class="data-value"><?php echo $playerRec->w;?></td>
								<td class="data-value"><?php echo $playerRec->sv;?></td>
								<td class="data-value"><?php echo $playerRec->ip.".".$playerRec->ip2;?></td>
								<td class="data-value"><?php echo $playerRec->h;?></td>
								<td class="data-value"><?php echo $playerRec->k;?></td>
								<td class="data-value"><?php echo $playerRec->points;?></td>
								<td class="data-value"><?php echo GetPriceStr($myTeamData, $playerBaseData);?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<div id="hitter_point_rank" class="span12">
				<h3>單日野手積分排行榜</h3>
				<table class="table bordered-table zebra-striped">
					<thead>
						<tr>
							<th class="table-caption" colspan="11">選手資訊</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th class="data-key">選手名稱</th>
							<th class="data-key">位置</th>
							<th class="data-key">球隊</th>
							<th class="data-key">AB</th>
							<th class="data-key">R</th>
							<th class="data-key">H</th>
							<th class="data-key">HR</th>
							<th class="data-key">RBI</th>
							<th class="data-key">SB</th>
							<th class="data-key">單日積分</th>
							<th class="data-key">買入</th>
						</tr>
						<?php
						for($i=0;$i<10;$i++)
						{
							$id = $hitterRow[$i]['id'];
							$playerBaseData = GetPlayerBaseData($id);
							$playerRec = GetPlayerRecByDate($id, $date);
							?>
							<tr class="data-row">
								<td class="data-value"><?php echo GetPlayerNameStr($playerBaseData);?></td>
								<td class="data-value"><?php echo GetPosStr($myTeamData, $playerBaseData);?></td>
								<td class="data-value"><?php echo GetTeamNameStrByPlayerID($id);?></td>
								<td class="data-value"><?php echo $playerRec->ab;?></td>
								<td class="data-value"><?php echo $playerRec->r;?></td>
								<td class="data-value"><?php echo $playerRec->h;?></td>
								<td class="data-value"><?php echo $playerRec->hr;?></td>
								<td class="data-value"><?php echo $playerRec->rbi;?></td>
								<td class="data-value"><?php echo $playerRec->sb;?></td>
								<td class="data-value"><?php echo $playerRec->points;?></td>
								<td class="data-value"><?php echo GetPriceStr($myTeamData, $playerBaseData);?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<?php include(dirname(__FILE__).'/footer.php'); ?>
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
include dirname(__FILE__) . '/FBJS.php';
?>
	
</body>
</html>