<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("validateCookie.php");

$myTeamID = $_COOKIE['myteamid'];
$playerID = $_GET['playerid'];
// 取得球隊資料
$myTeamData = new MyTeam($myTeamID);	

$playerBaseData = GetPlayerBaseData($playerID);
$teamID = GetTeamIDByPlayerID($playerID);
$playerInfo = GetPlayerInfo($playerID);
$playerRec2011 = GetPlayerRec2011($playerID);
$playerRec2012 = GetPlayerRec2012($playerID);
$playerRec1 = GetPlayerRec1($playerID);
$playerRec7 = GetPlayerRec7($playerID);
$playerRec15 = GetPlayerRec15($playerID);
$playerRec30 = GetPlayerRec30($playerID);
$playerRecDaily = GetPlayerRecDaily($playerID);
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<?php include(dirname(__FILE__)."/head_tag.php"); ?>
</head>
<body id="player_data">
	<div id="content" class="container canvas">
		<header>
			<?php include(dirname(__FILE__)."/header.php"); ?>
		</header>
		<div class="row" id="player_profile">
			<div class="span8">
				<div class="row">
					<div class="span2">
						<img class="thumbnail" src="image/team/<?php echo $teamID;?>.jpg" alt="player_thumbnail">
					</div>
					<div class="span6">
						<h4 id="player_basic_info">
							<span class="label warning">HOT</span>
			                <span class="label notice">COLD</span>
							<b><?php echo $playerBaseData->name;?></b>			                
			                 | <?php echo GetTeamNameStr($teamID);?>
			                 | #<?php echo $playerBaseData->no;?> | 
			                 <?php echo GetPosStr($myTeamData, $playerBaseData); ?>
						</h4>
						<dl id="player_datail">
							<p>生年月日: <?php echo $playerInfo->birthday;?></p>
			                <p>投打: <?php if($playerInfo->pitch === "R") echo "右"; else echo "左";?>投<?php if($playerInfo->bat === "R") echo "右"; else if($playerInfo->bat === "L") echo "左"; else if($playerInfo->bat === "S") echo "兩"?>打</p>
			                <p>順位: 
			                <?php 
			                if($playerInfo->draft_year>0) 
			                  echo $playerInfo->draft_year."年";
			                if($playerInfo->draft_round>0) 
			                  echo "第".$playerInfo->draft_round."輪";
			                if($playerInfo->draft_pick>0) 
			                  echo "第".$playerInfo->draft_pick."選";
			                if($playerInfo->draft_year == 0 && $playerInfo->draft_round && $playerInfo->draft_pick)
			                  echo "-";
			                ?>
			                </p>
						</dl>
					</div>
				</div>
			</div>
			<div class="span4">
			<?php 
			if(IsForzen())
			{
				?>
				<button class="btn primary"><?php printf("$%.2fM",$playerBaseData->nowprice/100);?>（凍結）</button>
				<?php
			}
			else if(IsOwnPlayer($myTeamData, $playerBaseData))
			{
				?>
				<a class="btn btn-danger" href="tool_trade.php?sellid=<?php echo $playerBaseData->id;?>"><?php printf("$%.2fM 賣出",$playerBaseData->nowprice/100);?></a>
				<?php
			}
			else
			{
				?>
				<a class="btn btn-success" href="tool_trade.php?buyid=<?php echo $playerBaseData->id;?>"><?php printf("$%.2fM 買入",$playerBaseData->nowprice/100);?></a>
				<?php
			}
			?>
			</div>
		</div>
		<div id="player_info" class="tabbable">
			<ul class="nav-tabs nav">
				<li class="active"><a href="#player_records" data-toggle="tab">球員資訊</a></li>
				<li><a href="#season_records" data-toggle="tab">本季明細</a></li>
			</ul>
			<div class="tab-content">
				<div id="player_records" class="tab-pane active">
					<table class="table bordered-table zebra-striped">
						<thead>
							<tr>
								<th class="table-caption" colspan="15">紀錄</th>
							</tr>
						</thead>
						<tbody>
							<?php if($playerBaseData->p){?>
							<tr class="data-caption">
								<th class="data-key"></th>
								<th class="data-key">G</th>
								<th class="data-key">IP</th>
								<th class="data-key">W</th>
								<th class="data-key">L</th>
								<th class="data-key">SV</th>								
								<th class="data-key">K</th>
								<th class="data-key">BB</th>
								<th class="data-key">ERA</th>
								<th class="data-key">WHIP</th>
								<th class="data-key">積分</th>
								<th class="data-key">平均</th>
							</tr>
							
							<tr class="data-row">
								<td class="data-value">本季</td>
								<td class="data-value"><?php echo $playerRec2012->g;?></td>
								<td class="data-value"><?php echo $playerRec2012->ip.".".$playerRec2012->ip2;?></td>
								<td class="data-value"><?php echo $playerRec2012->w;?></td>
								<td class="data-value"><?php echo $playerRec2012->l;?></td>
								<td class="data-value"><?php echo $playerRec2012->sv;?></td>							
								<td class="data-value"><?php echo $playerRec2012->k;?></td>
								<td class="data-value"><?php echo $playerRec2012->bb;?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec2012->era);?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec2012->whip);?></td>
								<td class="data-value"><?php echo $playerRec2012->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec2012->ppg);?></td>
							</tr>
						
							<tr class="data-row">
								<td class="data-value">昨日</td>
								<td class="data-value"><?php echo $playerRec1->g;?></td>
								<td class="data-value"><?php echo $playerRec1->ip.".".$playerRec1->ip2;?></td>
								<td class="data-value"><?php echo $playerRec1->w;?></td>
								<td class="data-value"><?php echo $playerRec1->l;?></td>
								<td class="data-value"><?php echo $playerRec1->sv;?></td>								
								<td class="data-value"><?php echo $playerRec1->k;?></td>
								<td class="data-value"><?php echo $playerRec1->bb;?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec1->era);?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec1->whip);?></td>
								<td class="data-value"><?php echo $playerRec1->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec1->ppg);?></td>
							</tr>
						
							<tr class="data-row">
								<td class="data-value">7天</td>
								<td class="data-value"><?php echo $playerRec7->g;?></td>
								<td class="data-value"><?php echo $playerRec7->ip.".".$playerRec7->ip2;?></td>
								<td class="data-value"><?php echo $playerRec7->w;?></td>
								<td class="data-value"><?php echo $playerRec7->l;?></td>
								<td class="data-value"><?php echo $playerRec7->sv;?></td>								
								<td class="data-value"><?php echo $playerRec7->k;?></td>
								<td class="data-value"><?php echo $playerRec7->bb;?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec7->era);?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec7->whip);?></td>
								<td class="data-value"><?php echo $playerRec7->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec7->ppg);?></td>
							</tr>
						
							<tr class="data-row">
								<td class="data-value">15天</td>
								<td class="data-value"><?php echo $playerRec15->g;?></td>
								<td class="data-value"><?php echo $playerRec15->ip.".".$playerRec15->ip2;?></td>
								<td class="data-value"><?php echo $playerRec15->w;?></td>
								<td class="data-value"><?php echo $playerRec15->l;?></td>
								<td class="data-value"><?php echo $playerRec15->sv;?></td>					
								<td class="data-value"><?php echo $playerRec15->k;?></td>
								<td class="data-value"><?php echo $playerRec15->bb;?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec15->era);?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec15->whip);?></td>
								<td class="data-value"><?php echo $playerRec15->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec15->ppg);?></td>
							</tr>
						
							<tr class="data-row">
								<td class="data-value">30天</td>
								<td class="data-value"><?php echo $playerRec30->g;?></td>
								<td class="data-value"><?php echo $playerRec30->ip.".".$playerRec30->ip2;?></td>
								<td class="data-value"><?php echo $playerRec30->w;?></td>
								<td class="data-value"><?php echo $playerRec30->l;?></td>
								<td class="data-value"><?php echo $playerRec30->sv;?></td>								
								<td class="data-value"><?php echo $playerRec30->k;?></td>
								<td class="data-value"><?php echo $playerRec30->bb;?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec30->era);?></td>
								<td class="data-value"><?php printf("%.2f",$playerRec30->whip);?></td>
								<td class="data-value"><?php echo $playerRec30->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec30->ppg);?></td>
							</tr>
							<?php }else{?>
							<tr class="data-caption">
								<th class="data-key"></th>
								<th class="data-key">G</th>
								<th class="data-key">AB</th>
								<th class="data-key">R</th>
								<th class="data-key">HR</th>
								<th class="data-key">RBI</th>
								<th class="data-key">SB</th>
								<th class="data-key">AVG</th>
								<th class="data-key">OBP</th>
								<th class="data-key">SLG</th>
								<th class="data-key">積分</th>
								<th class="data-key">平均</th>
							</tr>
							
							<tr class="data-row">
								<td class="data-value">本季</td>
								<td class="data-value"><?php echo $playerRec2012->g;?></td>
								<td class="data-value"><?php echo $playerRec2012->ab;?></td>
								<td class="data-value"><?php echo $playerRec2012->r;?></td>
								<td class="data-value"><?php echo $playerRec2012->hr;?></td>
								<td class="data-value"><?php echo $playerRec2012->rbi;?></td>
								<td class="data-value"><?php echo $playerRec2012->sb;?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec2012->avg);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec2012->obp);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec2012->slg);?></td>
								<td class="data-value"><?php echo $playerRec2012->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec2012->ppg);?></td>
							</tr>
						
							<tr class="data-row">
								<td class="data-value">本日</td>
								<td class="data-value"><?php echo $playerRec1->g;?></td>
								<td class="data-value"><?php echo $playerRec1->ab;?></td>
								<td class="data-value"><?php echo $playerRec1->r;?></td>
								<td class="data-value"><?php echo $playerRec1->hr;?></td>
								<td class="data-value"><?php echo $playerRec1->rbi;?></td>
								<td class="data-value"><?php echo $playerRec1->sb;?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec1->avg);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec1->obp);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec1->slg);?></td>
								<td class="data-value"><?php echo $playerRec1->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec1->ppg);?></td>
							</tr>
						
							<tr class="data-row">
								<td class="data-value">7天</td>
								<td class="data-value"><?php echo $playerRec7->g;?></td>
								<td class="data-value"><?php echo $playerRec7->ab;?></td>
								<td class="data-value"><?php echo $playerRec7->r;?></td>
								<td class="data-value"><?php echo $playerRec7->hr;?></td>
								<td class="data-value"><?php echo $playerRec7->rbi;?></td>
								<td class="data-value"><?php echo $playerRec7->sb;?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec7->avg);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec7->obp);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec7->slg);?></td>
								<td class="data-value"><?php echo $playerRec7->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec7->ppg);?></td>
							</tr>
						
							<tr class="data-row">
								<td class="data-value">15天</td>
								<td class="data-value"><?php echo $playerRec15->g;?></td>
								<td class="data-value"><?php echo $playerRec15->ab;?></td>
								<td class="data-value"><?php echo $playerRec15->r;?></td>
								<td class="data-value"><?php echo $playerRec15->hr;?></td>
								<td class="data-value"><?php echo $playerRec15->rbi;?></td>
								<td class="data-value"><?php echo $playerRec15->sb;?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec15->avg);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec15->obp);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec15->slg);?></td>
								<td class="data-value"><?php echo $playerRec15->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec15->ppg);?></td>
							</tr>
						
							<tr class="data-row">
								<td class="data-value">30天</td>
								<td class="data-value"><?php echo $playerRec30->g;?></td>
								<td class="data-value"><?php echo $playerRec30->ab;?></td>
								<td class="data-value"><?php echo $playerRec30->r;?></td>
								<td class="data-value"><?php echo $playerRec30->hr;?></td>
								<td class="data-value"><?php echo $playerRec30->rbi;?></td>
								<td class="data-value"><?php echo $playerRec30->sb;?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec30->avg);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec30->obp);?></td>
								<td class="data-value"><?php printf("%.3f",$playerRec30->slg);?></td>
								<td class="data-value"><?php echo $playerRec30->points;?></td>
								<td class="data-value"><?php printf("%.1f",$playerRec30->ppg);?></td>
							</tr>
							<?php }?>
						</tbody>
					</table>
					<table class="table bordered-table zebra-striped">
						<thead>
							<tr>
								<th class="table-caption" colspan="13">最近5日</th>
							</tr>
						</thead>
						<tbody>
							<?php if($playerBaseData->p){?>
							<tr class="data-caption">
								<th class="data-key">日期</th>
								<th class="data-key">Dev</th>
								<th class="data-key">IP</th>
								<th class="data-key">H</th>
								<th class="data-key">R</th>
								<th class="data-key">ER</th>
								<th class="data-key">K</th>
								<th class="data-key">BB</th>								
								<th class="data-key">積分</th>
								<th class="data-key">價錢</th>
							</tr>
							<?php 
							for($i=0;$i<5;$i++)
							{
								if($playerRecDaily[$i])
								{
							?>
								<tr class="data-row">
									<td class="data-value"><?php echo $playerRecDaily[$i]->date;?></td>
									<td class="data-value"><?php if($playerRecDaily[$i]->w) echo "W"; if($playerRecDaily[$i]->l) echo "L"; if($playerRecDaily[$i]->sv) echo "Sv";?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->ip;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->h;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->r;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->er;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->k;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->bb;?></td>									
									<td class="data-value"><?php echo $playerRecDaily[$i]->points;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->price;?></td>
								</tr>
								<?php }}?>
							
							<?php }else{?>
							<tr class="data-caption">
								<th class="data-key">日期</th>
								<th class="data-key">AB</th>
								<th class="data-key">R</th>
								<th class="data-key">H</th>
								<th class="data-key">2B</th>
								<th class="data-key">3B</th>
								<th class="data-key">HR</th>
								<th class="data-key">RBI</th>
								<th class="data-key">SB</th>
								<th class="data-key">K</th>
								<th class="data-key">BB</th>
								<th class="data-key">積分</th>
								<th class="data-key">價錢</th>
							</tr>
								<?php 
								for($i=0;$i<5;$i++)
								{
									if($playerRecDaily[$i])
									{
								?>
								<tr class="data-row">
									<td class="data-value"><?php echo $playerRecDaily[$i]->date;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->ab;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->r;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->h;?></td>
									<td class="data-value"></td>
									<td class="data-value"></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->hr;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->rbi;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->sb;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->k;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->bb;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->points;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->price;?></td>
								</tr>
								<?php }}?>
							<?php }?>
						</tbody>
					</table>
					<table class="table bordered-table zebra-striped">
						<thead>
							<tr>
								<th class="table-caption" colspan="15">過去紀錄</th>
							</tr>
						</thead>
						<tbody>
							<?php if($playerBaseData->p) {?>
							<tr class="data-caption">
								<th class="data-key">年度</th>
								<th class="data-key">G</th>
								<th class="data-key">IP</th>
								<th class="data-key">W</th>
								<th class="data-key">L</th>
								<th class="data-key">SV</th>
								<th class="data-key">K</th>
								<th class="data-key">BB</th>								
								<th class="data-key">ERA</th>
								<th class="data-key">WHIP</th>
								<th class="data-key">積分</th>
								<th class="data-key">平均</th>
							</tr>
							
								<tr class="data-row">
									<td class="data-value">2011</td>
									<td class="data-value"><?php echo $playerRec2011->g;?></td>
									<td class="data-value"><?php echo $playerRec2011->ip;?></td>
									<td class="data-value"><?php echo $playerRec2011->w;?></td>
									<td class="data-value"><?php echo $playerRec2011->l;?></td>
									<td class="data-value"><?php echo $playerRec2011->sv;?></td>
									<td class="data-value"><?php echo $playerRec2011->k;?></td>
									<td class="data-value"><?php echo $playerRec2011->bb;?></td>									
									<td class="data-value"><?php printf("%.2f",$playerRec2011->era);?></td>
									<td class="data-value"><?php printf("%.2f",$playerRec2011->whip);?></td>
									<td class="data-value"><?php printf("%.2f",$playerRec2011->points);?></td>
									<td class="data-value"><?php printf("%.2f",$playerRec2011->ppg);?></td>
								</tr>
							<?php }else{?>
							<tr class="data-caption">
								<th class="data-key">年度</th>
								<th class="data-key">G</th>
								<th class="data-key">AB</th>
								<th class="data-key">R</th>
								<th class="data-key">HR</th>
								<th class="data-key">RBI</th>
								<th class="data-key">SB</th>
								<th class="data-key">AVG</th>
								<th class="data-key">OBP</th>
								<th class="data-key">SLG</th>
								<th class="data-key">積分</th>
								<th class="data-key">平均</th>
							</tr>
							
								<tr class="data-row">
									<td class="data-value">2011</td>
									<td class="data-value"><?php echo $playerRec2011->g;?></td>
									<td class="data-value"><?php echo $playerRec2011->ab;?></td>
									<td class="data-value"><?php echo $playerRec2011->r;?></td>
									<td class="data-value"><?php echo $playerRec2011->hr;?></td>
									<td class="data-value"><?php echo $playerRec2011->rbi;?></td>
									<td class="data-value"><?php echo $playerRec2011->sb;?></td>
									<td class="data-value"><?php printf("%.3f",$playerRec2011->avg);?></td>
									<td class="data-value"><?php printf("%.2f",$playerRec2011->obp);?></td>
									<td class="data-value"><?php printf("%.2f",$playerRec2011->slg);?></td>
									<td class="data-value"><?php printf("%.2f",$playerRec2011->points);?></td>
									<td class="data-value"><?php printf("%.2f",$playerRec2011->ppg);?></td>
								</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
				<div id="season_records" class="tab-pane">
					<table class="table bordered-table zebra-striped">
						<thead>
							<tr>
								<th class="table-caption" colspan="11">本季紀錄明細</th>
							</tr>
						</thead>
						<tbody>
						<?php if($playerBaseData->p){?>
							<tr class="data-caption">
								<th class="data-key">日期</th>
								<th class="data-key">Dev</th>
								<th class="data-key">IP</th>
								<th class="data-key">H</th>
								<th class="data-key">R</th>
								<th class="data-key">ER</th>
								<th class="data-key">K</th>
								<th class="data-key">BB</th>								
								<th class="data-key">積分</th>
								<th class="data-key">價錢</th>
							</tr>
							
							<?php 
							$count = count($playerRecDaily);
							for($i=0;$i<$count;$i++)
							{
							?>
								<tr class="data-row">
									<td class="data-value"><?php echo $playerRecDaily[$i]->date;?></td>
									<td class="data-value"><?php if($playerRecDaily[$i]->w) echo "W"; if($playerRecDaily[$i]->l) echo "L"; if($playerRecDaily[$i]->sv) echo "Sv";?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->ip;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->h;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->r;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->er;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->k;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->bb;?></td>									
									<td class="data-value"><?php echo $playerRecDaily[$i]->points;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->price;?></td>
								</tr>
							<?php }?>
						<?php }else{?>
							<tr class="data-caption">
								<th class="data-key">日期</th>
								<th class="data-key">AB</th>
								<th class="data-key">H</th>
								<th class="data-key">R</th>
								<th class="data-key">HR</th>
								<th class="data-key">RBI</th>
								<th class="data-key">SB</th>
								<th class="data-key">K</th>
								<th class="data-key">BB</th>
								<th class="data-key">積分</th>
								<th class="data-key">價錢</th>
							</tr>
							
							<?php 
							$count = count($playerRecDaily);
							for($i=0;$i<$count;$i++)
							{
							?>
								<tr class="data-row">
									<td class="data-value"><?php echo $playerRecDaily[$i]->date;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->ab;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->h;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->r;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->hr;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->rbi;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->sb;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->k;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->bb;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->points;?></td>
									<td class="data-value"><?php echo $playerRecDaily[$i]->price;?></td>
								</tr>
							<?php }?>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
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
</body>
</html>