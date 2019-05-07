<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("class/myteam.class.php");
include_once("validateCookie.php");

$myTeamID = $_GET['myteamid'];
$date = $_GET['date'];			// 可供調取的安全資料時間

$db = new DB();
$sql = "SELECT `date` FROM `system_daily_log` WHERE `pricemove_time` != '0000-00-00 00:00:00' ORDER BY `date` DESC";
$stmt = $db->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$date2 = $row['date'];			// 最近一次可供調取的安全資料時間

if(!$date2)
{
	// 未開賽
	$errMsg = "尚未有資料可供觀看";
}
else if(!$date)
{
	$date = $date2;
}
else if(strtotime($date2." 00:00:00") >= strtotime($date." 00:00:00"))
{
	// 合法的時間
}
else
{
	// 不合法的時間
	// 非用戶本人不可以觀看資料
	if($myTeamID != $_COOKIE['myteamID'])
	{
		$errMsg = "尚未有資料可供觀看";
		$date = $date2;
	}
}

if($errMsg)
	$myTeamData = null;
else
	$myTeamData = new MyTeam($myTeamID, $date);
$userdata 	= GetUserDataByTeamID($myTeamID);
$fbid		= substr($userdata['account'], 0, strpos($userdata['account'], "@"));
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
						<img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/<?php echo $fbid;?>/picture?type=normal">
					</div>
					<div id="fbName1<?php echo $fbid?>" class="fbName1 span2">
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
		<h2 class="title">球隊陣容（<?php echo $date;?>）</h2>
		<div id="team_lineup" class="row">
			<form id="form1" name="form1" method="post" action="" class="inline span12">
       			<input type="hidden" name="hiddenString" />
					<table class="table table-striped bordered-table">
						<thead class="table-border">
							<tr class="tbl-hd">
								<th colspan="4" class="right-border left-border">選手資訊</th>
								<th colspan="8" class="right-border">紀錄</th>
								<th colspan="1" class="right-border">積分</th>
								<th colspan="1" class="right-border">價錢</th>
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
								<th>積分</th>
								<th>價錢</th>
							</tr>
							<?php
							// P1----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p1;
							if($id<=0)
							{
								$str = '<tr><th><select name="pitcher1" id="pitcher1" class="mini"><option value="1">P</option></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<th>
										<select name="pitcher1" id="pitcher1" class="mini">
											<option value="1">P</option>
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</th>
									<th>'.sprintf("%d",$rec->w).'</th>
									<th>'.sprintf("%d",$rec->l).'</th>
									<th>'.sprintf("%d",$rec->sv).'</th>
									<th>'.sprintf("%d",$rec->k).'</th>
									<th>'.sprintf("%d",$rec->bb).'</th>
									<th>'.sprintf("%.2f",$rec->era).'</th>
									<th>'.sprintf("%.2f",$rec->whip).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;

							// P2----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p2;
							if($id<=0)
							{
								$str = '<tr><th><select name="pitcher2" id="pitcher2" class="mini"><option value="1">P</option></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<th>
										<select name="pitcher2" id="pitcher2" class="mini">
											<option value="1">P</option>
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</th>
									<th>'.sprintf("%d",$rec->w).'</th>
									<th>'.sprintf("%d",$rec->l).'</th>
									<th>'.sprintf("%d",$rec->sv).'</th>
									<th>'.sprintf("%d",$rec->k).'</th>
									<th>'.sprintf("%d",$rec->bb).'</th>
									<th>'.sprintf("%.2f",$rec->era).'</th>
									<th>'.sprintf("%.2f",$rec->whip).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;

							// P3----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p3;
							if($id<=0)
							{
								$str = '<tr><th><select name="pitcher3" id="pitcher3" class="mini"><option value="1">P</option></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<th>
										<select name="pitcher3" id="pitcher3" class="mini">
											<option value="1">P</option>
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</th>
									<th>'.sprintf("%d",$rec->w).'</th>
									<th>'.sprintf("%d",$rec->l).'</th>
									<th>'.sprintf("%d",$rec->sv).'</th>
									<th>'.sprintf("%d",$rec->k).'</th>
									<th>'.sprintf("%d",$rec->bb).'</th>
									<th>'.sprintf("%.2f",$rec->era).'</th>
									<th>'.sprintf("%.2f",$rec->whip).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;

							// P4----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p4;
							if($id<=0)
							{
								$str = '<tr><th><select name="pitcher4" id="pitcher4" class="mini"><option value="1">P</option></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<th>
										<select name="pitcher4" id="pitcher4" class="mini">
											<option value="1">P</option>
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</th>
									<th>'.sprintf("%d",$rec->w).'</th>
									<th>'.sprintf("%d",$rec->l).'</th>
									<th>'.sprintf("%d",$rec->sv).'</th>
									<th>'.sprintf("%d",$rec->k).'</th>
									<th>'.sprintf("%d",$rec->bb).'</th>
									<th>'.sprintf("%.2f",$rec->era).'</th>
									<th>'.sprintf("%.2f",$rec->whip).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;

							// P5----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->p5;
							if($id<=0)
							{
								$str = '<tr><th><select name="pitcher5" id="pitcher5" class="mini"><option value="1">P</option></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '
								<tr>
									<th>
										<select name="pitcher5" id="pitcher5" class="mini">
											<option value="1">P</option>
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->ip).'.'.sprintf("%d",$rec->ip2).'</th>
									<th>'.sprintf("%d",$rec->w).'</th>
									<th>'.sprintf("%d",$rec->l).'</th>
									<th>'.sprintf("%d",$rec->sv).'</th>
									<th>'.sprintf("%d",$rec->k).'</th>
									<th>'.sprintf("%d",$rec->bb).'</th>
									<th>'.sprintf("%.2f",$rec->era).'</th>
									<th>'.sprintf("%.2f",$rec->whip).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
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
								<th>積分</th>
								<th>價錢</th>
							</tr>
							<?php
							// C-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->c;
							if($id<=0)
							{
								$str = '<tr><th><select name="select0" id="select0" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select0" id="select0" class="mini">';
								if($data->c) $str = $str.'<option selected value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>一壘</th>
								<th>隊伍</th>
								<th>下次對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th>SLG</th>
								<th>積分</th>
								<th>價錢</th>
							</tr>
							<?php
							// 1B-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->fb;
							if($id<=0)
							{
								$str = '<tr><th><select name="select1" id="select2" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select1" id="select1" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option selected value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;
							?>
							
							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>二壘</th>
								<th>隊伍</th>
								<th>下次對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th>SLG</th>
								<th>積分</th>
								<th>價錢</th>
							</tr>
							<?php
							// 2B-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->sb;
							if($id<=0)
							{
								$str = '<tr><th><select name="select2" id="select2" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select2" id="select2" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option selected value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>三壘</th>
								<th>隊伍</th>
								<th>下次對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th>SLG</th>
								<th>積分</th>
								<th>價錢</th>
							</tr>
							<?php
							// 3B-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->tb;
							if($id<=0)
							{
								$str = '<tr><th><select name="select3" id="select3" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select3" id="select3" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option selected value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>游擊</th>
								<th>隊伍</th>
								<th>下次對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th>SLG</th>
								<th>積分</th>
								<th>價錢</th>
							</tr>
							<?php
							// 3B-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->ss;
							if($id<=0)
							{
								$str = '<tr><th><select name="select4" id="select4" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select4" id="select4" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option selected value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>外野</th>
								<th>隊伍</th>
								<th>下次對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th>SLG</th>
								<th>積分</th>
								<th>價錢</th>
							</tr>
							<?php
							// OF1-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->of1;
							if($id<=0)
							{
								$str = '<tr><th><select name="select5" id="select5" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select5" id="select5" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option selected value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;

							// OF2-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->of2;
							if($id<=0)
							{
								$str = '<tr><th><select name="select6" id="select6" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select6" id="select6" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option selected value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;

							// OF3-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->of3;
							if($id<=0)
							{
								$str = '<tr><th><select name="select7" id="select7" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);	
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select7" id="select7" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option selected value="10">OF</option>';
								if($data->dh) $str = $str.'<option value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
								</tr>';
							}
							echo $str;
							?>

							<tr class="tbl-caption">
								<th class="mini">守備位置</th>
								<th>指定</th>
								<th>隊伍</th>
								<th>下次對手</th>
								<th>G</th>
								<th>AB</th>
								<th>R</th>
								<th>HR</th>
								<th>RBI</th>
								<th>SB</th>
								<th>AVG</th>
								<th>SLG</th>
								<th>積分</th>
								<th>價錢</th>
							</tr>
							<?php
							// DH-----------------------------------------------------------------------------
							$id = $myTeamData->myTeamData->dh;
							if($id<=0)
							{
								$str = '<tr><th><select name="select8" id="select8" class="mini"></select></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>';
							}
							else
							{
								$data = GetPlayerBaseData($id);
								$rec = GetPlayerRecByDate($id, $date);								
								$teamID = GetTeamIDByPlayerID($id);
								$str = '<tr><th><select name="select8" id="select8" class="mini">';
								if($data->c) $str = $str.'<option value="5">C</option>';
								if($data->fb) $str = $str.'<option value="6">1B</option>';
								if($data->sb) $str = $str.'<option value="7">2B</option>';
								if($data->tb) $str = $str.'<option value="8">3B</option>';
								if($data->ss) $str = $str.'<option value="9">SS</option>';
								if($data->of) $str = $str.'<option value="10">OF</option>';
								if($data->dh) $str = $str.'<option selected value="13">DH</option>';
								$str = $str.'
										</select>
									</th>
									<th>'.GetPlayerNameStr($data).'</th>
									<th>'.GetTeamNameMinStr($teamID).'</th>
									<th>'.GetVSTeamNameMinStr($teamID).'</th>
									<th>'.sprintf("%d",$rec->g).'</th>
									<th>'.sprintf("%d",$rec->ab).'</th>
									<th>'.sprintf("%d",$rec->r).'</th>
									<th>'.sprintf("%d",$rec->hr).'</th>
									<th>'.sprintf("%d",$rec->rbi).'</th>
									<th>'.sprintf("%d",$rec->sb).'</th>
									<th>'.sprintf("%.2f",$rec->avg).'</th>
									<th>'.sprintf("%.2f",$rec->slg).'</th>
									<th>'.sprintf("%d",$rec->points).'</th>
									<th>'.GetPriceStr($myTeamData, $data).'</th>
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
		<footer>
			© oh!dada 2012
		</footer>
	</div>
	<!-- scripts concatenated and minified via ant build script-->
	<script src="js/plugins.js"></script>
	<!-- end scripts-->

	<!--[if lt IE 7 ]>
		<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->

<?php 
include dirname(__FILE__) . '/FBJS.php';
?>

</body>
</html>