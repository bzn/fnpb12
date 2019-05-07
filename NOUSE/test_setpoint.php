<?php
session_start();
set_time_limit(0);
include_once(dirname(__FILE__)."/init.php");
include_once(dirname(__FILE__)."/define.php");
include_once(dirname(__FILE__)."/func.php");
include_once(dirname(__FILE__)."/../class/db.class.php");
include_once(dirname(__FILE__)."/../class/myteam.class.php");

$db = new DB();
// 取得所有打者
$sql = "SELECT * FROM `player_rec_hitter_2011`";
$stmt = $db->npb->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($row as $val)
{
	$points = -2*($val['ab']-$val['h'])+$val['r']*5+$val['h']*5+$val['2b']*5+$val['3b']*10+$val['hr']*15+$val['rbi']*5+$val['sb']*10+$val['bb']*3-$val['k'];
	if($val['g'])
		$ppg = round($points/$val['g'], 1);
	else
		$ppg = 0;
	$sql = "UPDATE `player_rec_hitter_2011` SET points = ".$points.", ppg = ".$ppg." WHERE id = ".$val[id];
	echo $sql;
	echo "<BR>";
	$stmt = $db->query($sql);
}

// 取得所有投手
$sql = "SELECT * FROM `player_rec_pitcher_2011`";
$stmt = $db->npb->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($row as $val)
{
	$points = 30*$val['w']-15*$val['l']+30*$val['sv']+15*$val['hld']+15*$val['ip']+5*$val['ip2']-5*$val['h']-10*$val['er']-3*$val['bb']+3*$val['k'];
	
	if($val['g'])
		$ppg = round($points/$val['g'], 1);
	else
		$ppg = 0;
	$sql = "UPDATE `player_rec_pitcher_2011` SET points = ".$points.", ppg = ".$ppg." WHERE id = ".$val[id];
	echo $sql;
	echo "<BR>";
	$stmt = $db->query($sql);
}

?>