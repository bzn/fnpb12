<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("validateCookie.php");
$teamID = $_GET['teamid'];

$myTeamID = $_COOKIE['myteamid'];
// 取得球隊資料
$myTeamData = new MyTeam($myTeamID);	

// 取得所有球隊列表
// ....
// 取得賽程表
$schedule = GetLatestScheduleByTeamID($teamID);
//$schedule = array_reverse($schedule);
// 取得全隊名單
$pitcherBaseDataArr = array();
$hitterBaseDataArr = array();
$teamPlayerIDArr = GetTeamPlayerID($teamID);
// 取得所有球員年度資料
$count = count($teamPlayerIDArr);
for($i=0;$i<$count;$i++)
{
	$playerID = $teamPlayerIDArr[$i];
	$playerBaseData = GetPlayerBaseData($playerID);
	// 避免二軍球員
	if(strlen($playerBaseData->no)<=2)
	{
		if($playerBaseData->p)
			array_push($pitcherBaseDataArr, $playerBaseData);
		else
			array_push($hitterBaseDataArr, $playerBaseData);
	}
}

// 依照球員背號sort $pitcherBaseDataArr 和 $hitterBaseDataArr
// ....

/*
$playerBaseData = GetPlayerBaseData($playerid);
$playerInfo = GetPlayerInfo($playerid);
$playerRec2011 = GetPlayerRec2011($playerid);
$playerRecDaily = GetPlayerRecDaily($playerid);
*/
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<?php include(dirname(__FILE__)."/head_tag.php"); ?>
</head>
<body>
	<div id="content" class="container canvas">
		<?php include(dirname(__FILE__)."/header.php"); ?>
		<div id="select_team" class="row container">
			<div class="imageblock controls docs-input-sizes row overflow box-wrapper">
				<div class="span2">
					<img class="thumbnail" src="image/team/<?php echo $teamID;?>.jpg" alt="player_thumbnail">
				</div>
				<h1 class="team_name"><?php echo GetTeamName($teamID);?></h1>
				<select name="team" ONCHANGE="location = this.options[this.selectedIndex].value;" class="pos-right">
					<option>選擇球隊</option>
					<option value="res_team.php?teamid=1">Giants</option>
					<option value="res_team.php?teamid=2">Swallows</option>
					<option value="res_team.php?teamid=3">BayStars</option>
					<option value="res_team.php?teamid=4">Dragons</option>
					<option value="res_team.php?teamid=5">Tigers</option>
					<option value="res_team.php?teamid=6">Carps</option>
					<option value="res_team.php?teamid=7">Lions</option>
					<option value="res_team.php?teamid=8">Fighters</option>
					<option value="res_team.php?teamid=9">Marines</option>
					<option value="res_team.php?teamid=10">Eagles</option>
					<option value="res_team.php?teamid=11">Buffaloes</option>
					<option value="res_team.php?teamid=12">Hawks</option>
				</select>
			</div>
			<div id="match_lineup">
				<h2>最近賽程</h2>
				<table class="table calendar">
					<thead>
						<tr>
						<?php
							for($i=0;$i<10;$i++)
							{
								$homeID = $schedule[$i]['home_team_id'];
								$awayID =  $schedule[$i]['away_team_id'];
								echo "<th class='day-field'>";
								echo substr($schedule[$i]['datetime'],5,5);
								echo "<BR>";
								echo "<a class='team' href='res_team.php?teamid=".$awayID."'>";
								echo GetTeamNameMin($awayID);
								echo "</a>";
								echo "@<a class='team' href='res_team.php?teamid=".$homeID."'>";
								echo GetTeamNameMin($homeID);
								echo "</a>";
								echo "</th>";
							}
						?>
						</tr>
					</thead>
				</table>
			</div>
			<div id="pitcher_lineup">
				<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr class="tbl-hd">
						<th colspan="3">投手資訊</th>
						<th colspan="9">紀錄</th>
						<th colspan="3">積分</th>
						<th colspan="2">價錢</th>
					</tr>
				</thead>
				<tbody id="myteam_info">
					<tr>
						<th>背號</th>
						<th>選手</th>
						<th class="mini">位置</th>
						<th>G</th>
						<th>IP</th>
						<th>W</th>
						<th>L</th>
						<th>SV</th>
						<th>BB</th>
						<th>K</th>
						<th>ERA</th>
						<th>WHIP</th>
						<th>最新</th>
						<th>總積分</th>
						<th>平均</th>
						<th>漲跌</th>
						<th>價錢</th>
					</tr>
					<?php 
					$pCount = count($pitcherBaseDataArr);
					for($i=0;$i<$pCount;$i++)
					{
						
						$playerBaseData = (object) $pitcherBaseDataArr[$i];
						$playerRec1 = GetPlayerRec1($playerBaseData->id);
						if(IsSeasonStart())
						{
							$playerRec = GetPlayerRec2012($playerBaseData->id);
						}
						else
						{
							$playerRec = GetPlayerRec2011($playerBaseData->id);
						}
						
						// 不處理active = false的球員
						//if($playerBaseData->active)
						{
							?>

							<tr>
								<th><?php echo $playerBaseData->no;?></th>
								<th>
									<?php echo GetPlayerNameStr($playerBaseData);?>
								</th>
								<th>
									<?php echo GetPosStr($myTeamData, $playerBaseData);?>
								</th>
								<th><?php echo $playerRec->g;?></th>
								<th><?php echo $playerRec->ip.".".$playerRec->ip2;?></th>
								<th><?php echo $playerRec->w;?></th>
								<th><?php echo $playerRec->l;?></th>
								<th><?php echo $playerRec->sv;?></th>
								<th><?php echo $playerRec->bb;?></th>
								<th><?php echo $playerRec->k;?></th>
								<th><?php printf("%.2f",$playerRec->era);?></th>
								<th><?php printf("%.2f",$playerRec->whip);?></th>
								<th><?php echo $playerRec1->points;?></th>
								<th><?php echo $playerRec->points;?></th>
								<th><?php printf("%.1f",$playerRec->ppg);?></th>
								<th><?php echo GetPricemoveStr($playerRec1->pricemove);?></th>
								<th><?php echo GetPriceFormat($playerBaseData->nowprice); ?></th>
							</tr>
							
							<?php
						}
					}
					?>
					
				</tbody>
			</table>
			</div>

			<div id="player_lineup">
				<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr class="tbl-hd">
						<th colspan="3">打者資訊</th>
						<th colspan="9">紀錄</th>
						<th colspan="3">積分</th>
						<th colspan="2">價錢</th>
					</tr>
				</thead>
				<tbody id="myteam_info">
					<tr>
						<th>背號</th>
						<th>選手</th>
						<th class="mini">位置</th>
						<th>G</th>
						<th>AB</th>
						<th>R</th>
						<th>HR</th>
						<th>RBI</th>
						<th>SB</th>
						<th>AVG</th>
						<th>OBP</th>
						<th>SLG</th>
						<th>最新</th>
						<th>總積分</th>
						<th>平均</th>
						<th>漲跌</th>
						<th>價錢</th>
					</tr>
					<?php 
					$pCount = count($hitterBaseDataArr);
					for($i=0;$i<$pCount;$i++)
					{						
						$playerBaseData = (object) $hitterBaseDataArr[$i];
						$playerRec1 = GetPlayerRec1($playerBaseData->id);
						if(IsSeasonStart())
						{
							$playerRec = GetPlayerRec2012($playerBaseData->id);
						}
						else
						{
							$playerRec = GetPlayerRec2011($playerBaseData->id);
						}
						// 不處理active = false的球員
						//if($playerBaseData->active)
						{
							?>

							<tr>
								<th>
									<?php echo $playerBaseData->no;?>
								</th>
								<th>
									<?php echo GetPlayerNameStr($playerBaseData);?>
								</th>
								<th>
									<?php echo GetPosStr($myTeamData, $playerBaseData);?>
								</th>
								<th><?php echo $playerRec->g;?></th>
								<th><?php echo $playerRec->ab;?></th>
								<th><?php echo $playerRec->r;?></th>
								<th><?php echo $playerRec->hr;?></th>
								<th><?php echo $playerRec->rbi;?></th>
								<th><?php echo $playerRec->sb;?></th>
								<th><?php printf("%.3f",$playerRec->avg);?></th>
								<th><?php printf("%.3f",$playerRec->obp);?></th>
								<th><?php printf("%.3f",$playerRec->slg);?></th>
								<th><?php echo $playerRec1->points;?></th>
								<th><?php echo $playerRec->points;?></th>
								<th><?php printf("%.1f",$playerRec->ppg);?></th>
								<th><?php echo GetPricemoveStr($playerRec1->pricemove);?></th>
								<th><?php echo GetPriceFormat($playerBaseData->nowprice); ?></th>
							</tr>
							
							<?php
						}
					}
					?>
					
				</tbody>
			</table>
			</div>
		</div>
		<footer>
			© oh!dada 2012
		</footer>
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