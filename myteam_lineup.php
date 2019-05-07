<?php

include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("class/myteam.class.php");
include_once("validateCookie.php");

$myTeamID = $_COOKIE['myteamid'];

//IsAdmin($_COOKIE['fbid']);

$type = $_GET['type'];
if(!$type)
{
	if(IsSeasonStart())
		$type = 1;
	else	
		$type = 2011;
}

// 取得排名
$rankPoints = GetRankPoints($myTeamID);
$rankMoney = GetRankMoney($myTeamID);
// 取得遊戲總人數
$myTeamCount = GetMyTeamCount();

$myTeamData = new MyTeam($myTeamID);

function GetPlayerRec($id, $type)
{
	switch ($type) {
		case 1: $rec = GetPlayerRec1($id); break;
		case 7: $rec = GetPlayerRec7($id); break;
		case 15: $rec = GetPlayerRec15($id); break;
		case 30: $rec = GetPlayerRec30($id); break;
		case 2012: $rec = GetPlayerRec2012($id); break;
		case 2011: $rec = GetPlayerRec2011($id); break;				
		default: $rec = GetPlayerRec1($id); break;
	}
	return $rec;
}

if($_POST['action'] == 1)
{
	/*
	echo $_POST['select0']."<BR>";
	echo $_POST['select1']."<BR>";
	echo $_POST['select2']."<BR>";
	echo $_POST['select3']."<BR>";
	echo $_POST['select4']."<BR>";
	echo $_POST['select5']."<BR>";
	echo $_POST['select6']."<BR>";
	echo $_POST['select7']."<BR>";
	echo $_POST['select8']."<BR>";
	*/
	$isErr = false;
	// 交易暫存記憶體
	$idArr = array();
	$posArr = array();
	if($_POST['select0'] && $_POST['select0'] != 5 && $myTeamData->myTeamData->c)
	{
		array_push($idArr, $myTeamData->myTeamData->c);
		array_push($posArr, $_POST['select0']);
		$myTeamData->myTeamData->c = 0; 
	}
	if($_POST['select1'] && $_POST['select1'] != 6 && $myTeamData->myTeamData->fb)
	{
		array_push($idArr, $myTeamData->myTeamData->fb);
		array_push($posArr, $_POST['select1']);
		$myTeamData->myTeamData->fb = 0; 
	}
	if($_POST['select2'] && $_POST['select2'] != 7 && $myTeamData->myTeamData->sb)
	{
		array_push($idArr, $myTeamData->myTeamData->sb);
		array_push($posArr, $_POST['select2']);
		$myTeamData->myTeamData->sb = 0; 
	}
	if($_POST['select3'] && $_POST['select3'] != 8 && $myTeamData->myTeamData->tb)
	{
		array_push($idArr, $myTeamData->myTeamData->tb);
		array_push($posArr, $_POST['select3']);
		$myTeamData->myTeamData->tb = 0; 
	}
	if($_POST['select4'] && $_POST['select4'] != 9 && $myTeamData->myTeamData->ss)
	{
		array_push($idArr, $myTeamData->myTeamData->ss);
		array_push($posArr, $_POST['select4']);
		$myTeamData->myTeamData->ss = 0; 
	}
	if($_POST['select5'] && $_POST['select5'] != 10 && $myTeamData->myTeamData->of1)
	{
		array_push($idArr, $myTeamData->myTeamData->of1);
		array_push($posArr, $_POST['select5']);
		$myTeamData->myTeamData->of1 = 0; 
	}
	if($_POST['select6'] && $_POST['select6'] != 10 && $myTeamData->myTeamData->of2)
	{
		array_push($idArr, $myTeamData->myTeamData->of2);
		array_push($posArr, $_POST['select6']);
		$myTeamData->myTeamData->of2 = 0; 
	}
	if($_POST['select7'] && $_POST['select7'] != 10 && $myTeamData->myTeamData->of3)
	{
		array_push($idArr, $myTeamData->myTeamData->of3);
		array_push($posArr, $_POST['select7']);
		$myTeamData->myTeamData->of3 = 0; 
	}
	if($_POST['select8'] && $_POST['select8'] != 13 && $myTeamData->myTeamData->dh)
	{
		array_push($idArr, $myTeamData->myTeamData->dh);
		array_push($posArr, $_POST['select8']);
		$myTeamData->myTeamData->dh = 0; 
	}
//	var_dump($idArr);
	if($idArr)
	{
		$count = count($idArr);
		for($i=0;$i<$count;$i++)
		{
			$id = $idArr[$i];
			$pos = $posArr[$i];
			if($pos == 5)
			{
				if($myTeamData->myTeamData->c == 0)
					$myTeamData->myTeamData->c = $id;
				else
				{
					$isErr = true;
					break;
				}
			}
			else if($pos == 6)
			{
				if($myTeamData->myTeamData->fb == 0)
					$myTeamData->myTeamData->fb = $id;
				else
				{
					$isErr = true;
					break;
				}
			}
			else if($pos == 7)
			{
				if($myTeamData->myTeamData->sb == 0)
					$myTeamData->myTeamData->sb = $id;
				else
				{
					$isErr = true;
					break;
				}
			}
			else if($pos == 8)
			{
				if($myTeamData->myTeamData->tb == 0)
					$myTeamData->myTeamData->tb = $id;
				else
				{
					$isErr = true;
					break;
				}
			}
			else if($pos == 9)
			{
				if($myTeamData->myTeamData->ss == 0)
					$myTeamData->myTeamData->ss = $id;
				else
				{
					$isErr = true;
					break;
				}
			}
			else if($pos == 10)
			{
				if($myTeamData->myTeamData->of1 == 0)
					$myTeamData->myTeamData->of1 = $id;
				else if($myTeamData->myTeamData->of2 == 0)
					$myTeamData->myTeamData->of2 = $id;
				else if($myTeamData->myTeamData->of3 == 0)
					$myTeamData->myTeamData->of3 = $id;
				else
				{
					$isErr = true;
					break;
				}
			}
			else if($pos == 13)
			{
				if($myTeamData->myTeamData->dh == 0)
					$myTeamData->myTeamData->dh = $id;
				else
				{
					$isErr = true;
					break;
				}
			}
		}
		if(!$isErr)
		{
			$myTeamData->Update();
		}
		else
		{
			$errMsg = "球員位置錯誤，無法交換守備位置";
			$myTeamData = new MyTeam($myTeamID);
		}
	}
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
<body id="myteam_lineup">
	<div id="content" class="container canvas">
		<?php include(dirname(__FILE__)."/header.php"); ?>
		<div class="row" id="team_info">
			<?php
			if($errMsg) {
				echo "<div class='alert'>";
				echo $errMsg;
				echo "</div>";
			}
			?>
			<h1 id="team_name" class="title span12"><?php echo $myTeamData->myTeamData->name;?><a class="tag_info" href="myteam_scorelog.php?myteamid=<?php echo $myTeamID;?>">（歷史積分）</a></h1>
			<div class="span4">
				<h2 class="title">玩家資訊</h2>
				<div class="row">
					<div class="span2">
						<img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/<?php echo $_COOKIE['fbid'];?>/picture?type=normal"></div>
					<div id="fbName" class="span2">
						玩家名稱
					</div>
				</div>
			</div>
			<div class="span8">
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
		<h2 class="title">球隊陣容</h2>
		<div id="team_lineup" class="row">
			<ul class="span12 nav nav-pills">
				<li class="item active">
					<a href='myteam_lineup.php?type=2012'>今年紀錄</a>
				</li>
				<li class="item">
					<a href='myteam_lineup.php?type=2011'>去年紀錄</a>
				</li>
				<li class="item">
					<a href='myteam_lineup.php?type=1'>最新</a>
				</li>
				<li class="item">
					<a href='myteam_lineup.php?type=7'>最近7日</a>
				</li>
				<li class="item">
					<a href='myteam_lineup.php?type=15'>最近15日</a>
				</li>
				<li class="item">
					<a href='myteam_lineup.php?type=30'>最近30日</a>
				</li>
			</ul>
			<form id="form1" name="form1" method="post" action="" class="inline span12">
       			<input type="hidden" name="hiddenString" />
					<table class="table table-striped bordered-table">
						<thead class="table-border">
							<tr class="tbl-hd">
								<th colspan="4" class="right-border left-border">選手資訊</th>
								<th colspan="8" class="right-border">紀錄</th>
								<th colspan="3" class="right-border">積分</th>
								<th colspan="2" class="right-border">價錢</th>
							</tr>
						</thead>
						<tbody id="myteam_info" class="table-border">
							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>投手</th>
								<th>隊伍</th>
								<th class="right-border">對手</th>
								<th>IP</th>
								<th>W</th>
								<th>L</th>
								<th>S</th>
								<th>K</th>
								<th>BB</th>
								<th>ERA</th>
								<th class="right-border">WHIP</th>
								<th>最新</th>
								<th>總分</th>
								<th class="right-border">平均</th>
								<th>漲跌</th>
								<th>賣出</th>
							</tr>
							<?php
							// P1----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p1;
							if($id<=0)
							{
								$str = '<tr><th><select name="pitcher1" id="pitcher1" class="mini"><option value="1">P</option></select></th><th><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=p">Buy P</a></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<td>
										<select name="pitcher1" id="pitcher1" class="mini">
											<option value="1">P</option>
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</td>
									<td>'.sprintf("%d",$rec->w).'</td>
									<td>'.sprintf("%d",$rec->l).'</td>
									<td>'.sprintf("%d",$rec->sv).'</td>
									<td>'.sprintf("%d",$rec->k).'</td>
									<td>'.sprintf("%d",$rec->bb).'</td>
									<td>'.sprintf("%.2f",$rec->era).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->whip).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;

							// P2----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p2;
							if($id<=0)
							{
								$str = '<tr><td><select name="pitcher2" id="pitcher2" class="mini"><option value="1">P</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=p">Buy P</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<td>
										<select name="pitcher2" id="pitcher2" class="mini">
											<option value="1">P</option>
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</td>
									<td>'.sprintf("%d",$rec->w).'</td>
									<td>'.sprintf("%d",$rec->l).'</td>
									<td>'.sprintf("%d",$rec->sv).'</td>
									<td>'.sprintf("%d",$rec->k).'</td>
									<td>'.sprintf("%d",$rec->bb).'</td>
									<td>'.sprintf("%.2f",$rec->era).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->whip).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;

							// P3----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p3;
							if($id<=0)
							{
								$str = '<tr><td><select name="pitcher3" id="pitcher3" class="mini"><option value="1">P</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=p">Buy P</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<td>
										<select name="pitcher3" id="pitcher3" class="mini">
											<option value="1">P</option>
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</td>
									<td>'.sprintf("%d",$rec->w).'</td>
									<td>'.sprintf("%d",$rec->l).'</td>
									<td>'.sprintf("%d",$rec->sv).'</td>
									<td>'.sprintf("%d",$rec->k).'</td>
									<td>'.sprintf("%d",$rec->bb).'</td>
									<td>'.sprintf("%.2f",$rec->era).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->whip).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;

							// P4----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p4;
							if($id<=0)
							{
								$str = '<tr><td><select name="pitcher4" id="pitcher4" class="mini"><option value="1">P</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=p">Buy P</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<td>
										<select name="pitcher4" id="pitcher4" class="mini">
											<option value="1">P</option>
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</td>
									<td>'.sprintf("%d",$rec->w).'</td>
									<td>'.sprintf("%d",$rec->l).'</td>
									<td>'.sprintf("%d",$rec->sv).'</td>
									<td>'.sprintf("%d",$rec->k).'</td>
									<td>'.sprintf("%d",$rec->bb).'</td>
									<td>'.sprintf("%.2f",$rec->era).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->whip).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;

							// P5----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p5;
							if($id<=0)
							{
								$str = '<tr><td><select name="pitcher5" id="pitcher5" class="mini"><option value="1">P</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=p">Buy P</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<td>
										<select name="pitcher5" id="pitcher5" class="mini">
											<option value="1">P</option>
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</td>
									<td>'.sprintf("%d",$rec->w).'</td>
									<td>'.sprintf("%d",$rec->l).'</td>
									<td>'.sprintf("%d",$rec->sv).'</td>
									<td>'.sprintf("%d",$rec->k).'</td>
									<td>'.sprintf("%d",$rec->bb).'</td>
									<td>'.sprintf("%.2f",$rec->era).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->whip).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;
							?>
							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>捕手</th>
								<th>隊伍</th>
								<th class="right-border">對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th class="right-border">SLG</th>
								<th>最新</th>
								<th>總分</th>
								<th class="right-border">平均</th>
								<th>漲跌</th>
								<th>賣出</th>
							</tr>
							<?php
							// C-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->c;
							if($id<=0)
							{
								$str = '<tr><td><select name="select0" id="select0" class="mini"><option value="5">C</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=c">Buy C</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select0" id="select0" class="mini">';
								if($data->c) $str = $str.'<option selected value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>一壘</th>
								<th>隊伍</th>
								<th class="right-border">對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th class="right-border">SLG</th>
								<th>最新</th>
								<th>總分</th>
								<th class="right-border">平均</th>
								<th>漲跌</th>
								<th>賣出</th>
							</tr>
							<?php
							// 1B-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->fb;
							if($id<=0)
							{
								$str = $str = '<tr><td><select name="select1" id="select1" class="mini"><option value="6">1B</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=fb">Buy 1B</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select1" id="select1" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option selected value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;
							?>
							
							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>二壘</th>
								<th>隊伍</th>
								<th class="right-border">對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th class="right-border">SLG</th>
								<th>最新</th>
								<th>總分</th>
								<th class="right-border">平均</th>
								<th>漲跌</th>
								<th>賣出</th>
							</tr>
							<?php
							// 2B-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->sb;
							if($id<=0)
							{
								$str = '<tr><td><select name="select2" id="select2" class="mini"><option value="7">2B</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=sb">Buy 2B</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select2" id="select2" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option selected value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>三壘</th>
								<th>隊伍</th>
								<th class="right-border">對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th class="right-border">SLG</th>
								<th>最新</th>
								<th>總分</th>
								<th class="right-border">平均</th>
								<th>漲跌</th>
								<th>賣出</th>
							</tr>
							<?php
							// 3B-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->tb;
							if($id<=0)
							{
								$str = '<tr><td><select name="select3" id="select3" class="mini"><option value="8">3B</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=tb">Buy 3B</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select3" id="select3" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option selected value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>游擊</th>
								<th>隊伍</th>
								<th class="right-border">對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th class="right-border">SLG</th>
								<th>最新</th>
								<th>總分</th>
								<th class="right-border">平均</th>
								<th>漲跌</th>
								<th>賣出</th>
							</tr>
							<?php
							// 3B-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->ss;
							if($id<=0)
							{
								$str = '<tr><td><select name="select4" id="select4" class="mini"><option value="9">SS</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=ss">Buy SS</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select4" id="select4" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option selected value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>外野</th>
								<th>隊伍</th>
								<th class="right-border">對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th class="right-border">SLG</th>
								<th>最新</th>
								<th>總分</th>
								<th class="right-border">平均</th>
								<th>漲跌</th>
								<th>賣出</th>
							</tr>
							<?php
							// OF1-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->of1;
							if($id<=0)
							{
								$str = '<tr><td><select name="select5" id="select5" class="mini"><option value="10">OF</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=of">Buy OF</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select5" id="select5" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option selected value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;

							// OF2-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->of2;
							if($id<=0)
							{
								$str = '<tr><td><select name="select6" id="select6" class="mini"><option value="10">OF</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=of">Buy OF</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select6" id="select6" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option selected value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;

							// OF3-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->of3;
							if($id<=0)
							{
								$str = '<tr><td><select name="select7" id="select7" class="mini"><option value="10">OF</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=of">Buy OF</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select7" id="select7" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option selected value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>指定</th>
								<th>隊伍</th>
								<th class="right-border">對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th class="right-border">SLG</th>
								<th>最新</th>
								<th>總分</th>
								<th class="right-border">平均</th>
								<th>漲跌</th>
								<th>賣出</th>
							</tr>
							<?php
							// DH-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->dh;
							if($id<=0)
							{
								$str = '<tr><td><select name="select8" id="select8" class="mini"><option value="13">DH</option></select></td><td><a href="http://fantasy.ohdada.com/res_playerlist.php?pos=dh">Buy DH</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRec($id, $type);									
								$rec1 = GetPlayerRec1($id);
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><td><select name="select8" id="select8" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option selected value="13">DH</option>';
								$str = $str.'
										</select>
									</td>
									<td>'.GetPlayerNameStr($data).'</td>
									<td>'.GetTeamNameMinStr($teamID).'</td>
									<td class="right-border">'.GetVSTeamNameMinStr($teamID).'</td>
									<td>'.sprintf("%d",$rec->g).'</td>
									<td>'.sprintf("%d",$rec->ab).'</td>
									<td>'.sprintf("%d",$rec->r).'</td>
									<td>'.sprintf("%d",$rec->hr).'</td>
									<td>'.sprintf("%d",$rec->rbi).'</td>
									<td>'.sprintf("%d",$rec->sb).'</td>
									<td>'.sprintf("%.2f",$rec->avg).'</td>
									<td class="right-border">'.sprintf("%.2f",$rec->slg).'</td>
									<td>'.sprintf("%d",$rec1->points).'</td>
									<td>'.sprintf("%d",$rec->points).'</td>
									<td class="right-border">'.sprintf("%.1f",$rec->ppg).'</td>
									<td>'.GetPricemoveStr($rec1->pricemove).'</td>
									<td>'.GetPriceStr($myTeamData, $data).'</td>
								</tr>';
							}
							echo $str;
							?>
						</tbody>
					</table>
				
					<input type="submit" name="Submit" value="交換守備位置" class="btn btn-primary"/>
			        <input type="hidden" name="action" value="1" />
			    </form>
		</div>
		<?php include(dirname(__FILE__).'/footer.php'); ?>
	</div>
	<!-- scripts concatenated and minified via ant build script-->
	<script src="js/plugins.js"></script>
	<!-- end scripts-->
	<!--[if lt IE 7 ]>
		<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->

<?php 
include(dirname(__FILE__).'/FBJS.php');
?>

</body>
</html>