<?php
session_start();
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("class/myteam.class.php");
include_once("validateCookie.php");

$myTeamID = $_COOKIE['myteamid'];
// 取得球隊資料
$myTeamData = new MyTeam($myTeamID);

$db = new DB();

// 取得最新pricemove時間
$sql = "SELECT * FROM `system_daily_log` WHERE `pricemove_time` != '00-00-00 00:00:00' ORDER BY `date` DESC LIMIT 1";
$stmt = $db->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$date = $row['date'];

$sql = "SELECT * FROM `player_rec_pitcher_daily` WHERE `date`='".$date."' ORDER BY `pricemove` DESC LIMIT 10";
$stmt = $db->query($sql);
$pitcherBestPricemoveRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `player_rec_pitcher_daily` WHERE `date`='".$date."' ORDER BY `pricemove` LIMIT 10";
$stmt = $db->query($sql);
$pitcherWorstPricemoveRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `player_rec_hitter_daily` WHERE `date`='".$date."' ORDER BY `pricemove` DESC LIMIT 10";
$stmt = $db->query($sql);
$hitterBestPricemoveRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `player_rec_hitter_daily` WHERE `date`='".$date."' ORDER BY `pricemove` LIMIT 10";
$stmt = $db->query($sql);
$hitterWorstPricemoveRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<?php include(dirname(__FILE__)."/head_tag.php"); ?>
</head>
<body id="res_pricemove">
	<div id="content" class="container canvas">
		<?php include(dirname(__FILE__)."/header.php"); ?>
		<div class="row">
			<h2 class="title span12">價格變動(<?php echo $date;?>)</h2>
		</div>
		<div id="pitch_price_movement" class="box-wrapper row">
			<h3 class="title span12">投手</h3>
			<div class="span6">
				<div id="pitch_price_up" class="table-border">
					<table class="table bordered-table zebra-striped">
						<thead>
							<tr>
								<th class="table-caption" colspan="4">上漲</th>
								<th class="table-caption">價錢</th>
							</tr>
						</thead>
						<tbody>
							<tr class="data-caption">
								<th class="name-value data-key">球員</th>
								<th class="name-value data-key">隊伍</th>
								<th class="center-value data-key">守備位置</th>
								<th class="number-value data-key">價錢</th>
								<th class="number-value data-key">價錢變動</th>	
							</tr>
							<?php
							$count = count($pitcherBestPricemoveRow);
							for($i=0;$i<$count;$i++)
							{
								$id = $pitcherBestPricemoveRow[$i]['id'];
								$playerBaseData = GetPlayerBaseData($id);
								$pricemove = $pitcherBestPricemoveRow[$i]['pricemove'];
								?>
								<tr class="data-row">
									<td class="name-value data-value"><?php echo GetPlayerNameStr($playerBaseData);?></td>
									<td class="name-value data-value"><?php echo GetTeamNameStrByPlayerID($id);?></td>
									<td class="center-value data-value"><?php echo GetPosStr($myTeamData, $playerBaseData);?></td>
									<td class="number-value data-value"><?php echo GetPriceStr($myTeamData, $playerBaseData);?></td>
									<td class="number-value data-value"><?php echo GetPricemoveStr($pricemove);?></td>
								</tr>
								<?php
							}
							?>							
						</tbody>
					</table>
				</div>
			</div>
			<div class="span6">
				<div id="pitch_price_down" class="table-border">
					<table class="table bordered-table zebra-striped">
						<thead>
							<tr>
								<th class="table-caption" colspan="4">下跌</th>
								<th class="table-caption">價錢</th>
							</tr>
						</thead>
						<tbody>
							<tr class="data-caption">
								<th class="name-value data-key">球員</th>
								<th class="name-value data-key">隊伍</th>
								<th class="center-value data-key">守備位置</th>
								<th class="number-value data-key">價錢</th>
								<th class="number-value data-key">價錢變動</th>	
							</tr>
							<?php
							$count = count($pitcherWorstPricemoveRow);
							for($i=0;$i<$count;$i++)
							{
								$id = $pitcherWorstPricemoveRow[$i]['id'];
								$playerBaseData = GetPlayerBaseData($id);
								$pricemove = $pitcherWorstPricemoveRow[$i]['pricemove'];
								?>
								<tr class="data-row">
									<td class="name-value data-value"><?php echo GetPlayerNameStr($playerBaseData);?></td>
									<td class="name-value data-value"><?php echo GetTeamNameStrByPlayerID($id);?></td>
									<td class="center-value data-value"><?php echo GetPosStr($myTeamData, $playerBaseData);?></td>
									<td class="number-value data-value"><?php echo GetPriceStr($myTeamData, $playerBaseData);?></td>
									<td class="number-value data-value"><?php echo GetPricemoveStr($pricemove);?></td>
								</tr>
								<?php
							}
							?>	
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="player_price_movement" class="box-wrapper row">
			<h3 class="title span12">野手</h3>
			<div class="span6">
				<div id="player_price_up" class="table-border">
					<table class="table bordered-table zebra-striped">
						<thead>
							<tr>
								<th class="table-caption" colspan="4">上漲</th>
								<th class="table-caption">價錢</th>
							</tr>
						</thead>
						<tbody>
							<tr class="data-caption">
								<th class="name-value data-key">球員</th>
								<th class="name-value data-key">隊伍</th>
								<th class="center-value data-key">守備位置</th>
								<th class="number-value data-key">價錢</th>
								<th class="number-value data-key">價錢變動</th>	
							</tr>
							<?php
							$count = count($hitterBestPricemoveRow);
							for($i=0;$i<$count;$i++)
							{
								$id = $hitterBestPricemoveRow[$i]['id'];
								$playerBaseData = GetPlayerBaseData($id);
								$pricemove = $hitterBestPricemoveRow[$i]['pricemove'];
								?>
								<tr class="data-row">
									<td class="name-value data-value"><?php echo GetPlayerNameStr($playerBaseData);?></td>
									<td class="name-value data-value"><?php echo GetTeamNameStrByPlayerID($id);?></td>
									<td class="center-value data-value"><?php echo GetPosStr($myTeamData, $playerBaseData);?></td>
									<td class="number-value data-value"><?php echo GetPriceStr($myTeamData, $playerBaseData);?></td>
									<td class="number-value data-value"><?php echo GetPricemoveStr($pricemove);?></td>
								</tr>
								<?php
							}
							?>	
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="span6">
				<div id="player_price_down" class="table-border">
					<table class="table bordered-table zebra-striped">
						<thead>
							<tr>
								<th class="table-caption" colspan="4">下跌</th>
								<th class="table-caption">價錢</th>
							</tr>
						</thead>
						<tbody>
							<tr class="data-caption">
								<th class="name-value data-key">球員</th>
								<th class="name-value data-key">隊伍</th>
								<th class="center-value data-key">守備位置</th>
								<th class="number-value data-key">價錢</th>
								<th class="number-value data-key">價錢變動</th>	
							</tr>
							<?php
							$count = count($hitterWorstPricemoveRow);
							for($i=0;$i<$count;$i++)
							{
								$id = $hitterWorstPricemoveRow[$i]['id'];
								$playerBaseData = GetPlayerBaseData($id);
								$pricemove = $hitterWorstPricemoveRow[$i]['pricemove'];
								?>
								<tr class="data-row">
									<td class="name-value data-value"><?php echo GetPlayerNameStr($playerBaseData);?></td>
									<td class="name-value data-value"><?php echo GetTeamNameStrByPlayerID($id);?></td>
									<td class="center-value data-value"><?php echo GetPosStr($myTeamData, $playerBaseData);?></td>
									<td class="number-value data-value"><?php echo GetPriceStr($myTeamData, $playerBaseData);?></td>
									<td class="number-value data-value"><?php echo GetPricemoveStr($pricemove);?></td>
								</tr>
								<?php
							}
							?>	
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php include(dirname(__FILE__)."/footer.php"); ?>
	</div>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
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
</body>
</html>