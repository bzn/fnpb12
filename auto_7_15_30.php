<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("validateCookie.php");

if(!IsAdmin($_COOKIE['fbid']))
{
	echo "ADMIN ONLY";
	exit;
}
else
{
	echo $_COOKIE['fbid']." ADMIN";
}

$db = new DB();
// 先清除所有的7-15-30
$sql = "TRUNCATE TABLE `player_rec_hitter_7`";
$db->query($sql);
$sql = "TRUNCATE TABLE `player_rec_hitter_15`";
$db->query($sql);
$sql = "TRUNCATE TABLE `player_rec_hitter_30`";
$db->query($sql);
$sql = "TRUNCATE TABLE `player_rec_hitter_2012`";
$db->query($sql);
$sql = "TRUNCATE TABLE `player_rec_pitcher_7`";
$db->query($sql);
$sql = "TRUNCATE TABLE `player_rec_pitcher_15`";
$db->query($sql);
$sql = "TRUNCATE TABLE `player_rec_pitcher_30`";
$db->query($sql);
$sql = "TRUNCATE TABLE `player_rec_pitcher_2012`";
$db->query($sql);

// 取得所有打者數目（應該為2012）
$sql = "SELECT `id` FROM `player_basedata` WHERE `dh`=1";
$stmt = $db->query($sql);
$val = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

for($i=0;$i<$count;$i++)
{
	$id = $val[$i]['id'];
	// 7days
	$date = substr(GetLocalTime(),0,10);
	//$date = '2011-09-30';
	$date = new DateTime($date);
	$date->modify('-7 day');
	$date = $date->format('Y-m-d');

	$sql = "SELECT SUM(g) as g, SUM(ab) as ab, SUM(r) as r, SUM(h) as h, SUM(h2) as h2, SUM(h3) as h3, SUM(hr) as hr, SUM(rbi) as rbi, SUM(bb) as bb, SUM(k) as k, SUM(sb) as sb FROM `player_rec_hitter_daily` WHERE id=".$id." AND `date` > '".$date."'";

	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 計算平均值
	if($row['ab'])
		$avg = $row['h']/$row['ab'];
	else
		$avg = 0;
	if(($row['ab']+$row['bb']))	
		$obp = ($row['h']+$row['bb'])/($row['ab']+$row['bb']);
	else
		$obp = 0;		
	$slg = $row['h']+$row['h2']+$row['h3'];
	$ops = $obp+$slg;
	// 計算積分
	$points = -2*($row['ab']-$row['h'])+$row['r']*5+$row['h']*5+$row['h2']*5+$row['h3']*10+$row['hr']*15+$row['rbi']*5+$row['sb']*10+$row['bb']*3-$row['k'];
	if($row['g'])
		$ppg = $points/$row['g'];
	else
		$ppg = 0;

	$sql = "INSERT INTO `player_rec_hitter_7`(`id`, `points`, `ppg`, `g`, `ab`, `r`, `h`, `h2`, `h3`, `hr`, `rbi`, `bb`, `k`, `sb`, `avg`, `obp`, `slg`, `ops`) VALUES (".$id.",".$points.",".$ppg.",".$row['g'].",".$row['ab'].",".$row['r'].",".$row['h'].",".$row['h2'].",".$row['h3'].",".$row['hr'].",".$row['rbi'].",".$row['bb'].",".$row['k'].",".$row['sb'].",".$avg.",".$obp.",".$slg.",".$ops.")";
	echo $sql."<BR>";
	$db->query($sql);

	// 15days
	$date = substr(GetLocalTime(),0,10);
	//$date = '2011-09-30';
	$date = new DateTime($date);
	$date->modify('-15 day');
	$date = $date->format('Y-m-d');

	$sql = "SELECT SUM(g) as g, SUM(ab) as ab, SUM(r) as r, SUM(h) as h, SUM(h2) as h2, SUM(h3) as h3, SUM(hr) as hr, SUM(rbi) as rbi, SUM(bb) as bb, SUM(k) as k, SUM(sb) as sb FROM `player_rec_hitter_daily` WHERE id=".$id." AND `date` > '".$date."'";

	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 計算平均值
	if($row['ab'])
		$avg = $row['h']/$row['ab'];
	else
		$avg = 0;
	if(($row['ab']+$row['bb']))	
		$obp = ($row['h']+$row['bb'])/($row['ab']+$row['bb']);
	else
		$obp = 0;		
	$slg = $row['h']+$row['h2']+$row['h3'];
	$ops = $obp+$slg;
	// 計算積分
	$points = -2*($row['ab']-$row['h'])+$row['r']*5+$row['h']*5+$row['h2']*5+$row['h3']*10+$row['hr']*15+$row['rbi']*5+$row['sb']*10+$row['bb']*3-$row['k'];
	if($row['g'])
		$ppg = $points/$row['g'];
	else
		$ppg = 0;

	$sql = "INSERT INTO `player_rec_hitter_15`(`id`, `points`, `ppg`, `g`, `ab`, `r`, `h`, `h2`, `h3`, `hr`, `rbi`, `bb`, `k`, `sb`, `avg`, `obp`, `slg`, `ops`) VALUES (".$id.",".$points.",".$ppg.",".$row['g'].",".$row['ab'].",".$row['r'].",".$row['h'].",".$row['h2'].",".$row['h3'].",".$row['hr'].",".$row['rbi'].",".$row['bb'].",".$row['k'].",".$row['sb'].",".$avg.",".$obp.",".$slg.",".$ops.")";
	echo $sql."<BR>";
	$db->query($sql);

	// 30days
	$date = substr(GetLocalTime(),0,10);
	//$date = '2011-09-30';
	$date = new DateTime($date);
	$date->modify('-30 day');
	$date = $date->format('Y-m-d');

	$sql = "SELECT SUM(g) as g, SUM(ab) as ab, SUM(r) as r, SUM(h) as h, SUM(h2) as h2, SUM(h3) as h3, SUM(hr) as hr, SUM(rbi) as rbi, SUM(bb) as bb, SUM(k) as k, SUM(sb) as sb FROM `player_rec_hitter_daily` WHERE id=".$id." AND `date` > '".$date."'";

	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 計算平均值
	if($row['ab'])
		$avg = $row['h']/$row['ab'];
	else
		$avg = 0;
	if(($row['ab']+$row['bb']))	
		$obp = ($row['h']+$row['bb'])/($row['ab']+$row['bb']);
	else
		$obp = 0;		
	$slg = $row['h']+$row['h2']+$row['h3'];
	$ops = $obp+$slg;
	// 計算積分
	$points = -2*($row['ab']-$row['h'])+$row['r']*5+$row['h']*5+$row['h2']*5+$row['h3']*10+$row['hr']*15+$row['rbi']*5+$row['sb']*10+$row['bb']*3-$row['k'];
	if($row['g'])
		$ppg = $points/$row['g'];
	else
		$ppg = 0;

	$sql = "INSERT INTO `player_rec_hitter_30`(`id`, `points`, `ppg`, `g`, `ab`, `r`, `h`, `h2`, `h3`, `hr`, `rbi`, `bb`, `k`, `sb`, `avg`, `obp`, `slg`, `ops`) VALUES (".$id.",".$points.",".$ppg.",".$row['g'].",".$row['ab'].",".$row['r'].",".$row['h'].",".$row['h2'].",".$row['h3'].",".$row['hr'].",".$row['rbi'].",".$row['bb'].",".$row['k'].",".$row['sb'].",".$avg.",".$obp.",".$slg.",".$ops.")";
	echo $sql."<BR>";
	$db->query($sql);

	// 2012
	$sql = "SELECT SUM(g) as g, SUM(ab) as ab, SUM(r) as r, SUM(h) as h, SUM(h2) as h2, SUM(h3) as h3, SUM(hr) as hr, SUM(rbi) as rbi, SUM(bb) as bb, SUM(k) as k, SUM(sb) as sb FROM `player_rec_hitter_daily` WHERE id=".$id." ORDER BY `date`";

	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 計算平均值
	if($row['ab'])
		$avg = $row['h']/$row['ab'];
	else
		$avg = 0;
	if(($row['ab']+$row['bb']))	
		$obp = ($row['h']+$row['bb'])/($row['ab']+$row['bb']);
	else
		$obp = 0;		
	$slg = $row['h']+$row['h2']+$row['h3'];
	$ops = $obp+$slg;
	// 計算積分
	$points = -2*($row['ab']-$row['h'])+$row['r']*5+$row['h']*5+$row['h2']*5+$row['h3']*10+$row['hr']*15+$row['rbi']*5+$row['sb']*10+$row['bb']*3-$row['k'];
	if($row['g'])
		$ppg = $points/$row['g'];
	else
		$ppg = 0;

	$sql = "INSERT INTO `player_rec_hitter_2012`(`id`, `points`, `ppg`, `g`, `ab`, `r`, `h`, `h2`, `h3`, `hr`, `rbi`, `bb`, `k`, `sb`, `avg`, `obp`, `slg`, `ops`) VALUES (".$id.",".$points.",".$ppg.",".$row['g'].",".$row['ab'].",".$row['r'].",".$row['h'].",".$row['h2'].",".$row['h3'].",".$row['hr'].",".$row['rbi'].",".$row['bb'].",".$row['k'].",".$row['sb'].",".$avg.",".$obp.",".$slg.",".$ops.")";
	echo $sql."<BR>";
	$db->query($sql);
}


//=======================================


// 取得所有投手數目（應該為2012）
$sql = "SELECT `id` FROM `player_basedata` WHERE `p`=1";
$stmt = $db->query($sql);
$val = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

for($i=0;$i<$count;$i++)
{
	$id = $val[$i]['id'];
	// 7days
	$date = substr(GetLocalTime(),0,10);
	//$date = '2011-09-30';
	$date = new DateTime($date);
	$date->modify('-7 day');
	$date = $date->format('Y-m-d');

	$sql = "SELECT SUM(g) as g, SUM(gs) as gs, SUM(w) as w, SUM(l) as l, SUM(sv) as sv, SUM(hld) as hld, SUM(cg) as cg, SUM(sho) as sho, SUM(ip) as ip, SUM(ip2) as ip2, SUM(h) as h, SUM(r) as r, SUM(er) as er, SUM(hr) as hr, SUM(bb) as bb, SUM(k) as k FROM `player_rec_pitcher_daily` WHERE id=".$id." AND `date` > '".$date."'";

	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 計算平均值
	$ip = $row['ip']+$row['ip2']/3;
	if($ip)
		$era = 9*$row['er']/$ip;
	else if($row['er'])
		$era = 99;
	else
		$era = 0;

	if($ip)
		$whip = ($row['h']+$row['bb'])/$ip;
	else if($row['h']+$row['bb'])
		$whip = 99;
	else
		$whip = 0;

	if($row['w']+$row['l'])
		$wpct = $row['w']/($row['w']+$row['l']);
	else
		$wpct = 0;

	// 計算積分
	$points = 30*$row['w']-15*$row['l']+30*$row['sv']+15*$row['hld']+15*$row['ip']+5*$row['ip2']-5*$row['h']-10*$row['er']-3*$row['bb']+3*$row['k'];
	if($row['g'])
		$ppg = $points/$row['g'];
	else
		$ppg = 0;

	$sql = "INSERT INTO `player_rec_pitcher_7`(`id`, `points`, `ppg`, `g`, `gs`, `w`, `l`, `sv`, `hld`, `cg`, `sho`, `ip`, `ip2`, `h`, `r`, `er`, `hr`, `bb`, `k`, `era`, `whip`, `wpct`) VALUES (".$id.",".$points.",".$ppg.",".$row['g'].",".$row['gs'].",".$row['w'].",".$row['l'].",".$row['sv'].",".$row['hld'].",".$row['cg'].",".$row['sho'].",".$row['ip'].",".$row['ip2'].",".$row['h'].",".$row['r'].",".$row['er'].",".$row['hr'].",".$row['bb'].",".$row['k'].",".$era.",".$whip.",".$wpct.")";
	echo $sql."<BR>";
	$db->query($sql);

	// 15days
	$date = substr(GetLocalTime(),0,10);
	//$date = '2011-09-30';
	$date = new DateTime($date);
	$date->modify('-15 day');
	$date = $date->format('Y-m-d');

	$sql = "SELECT SUM(g) as g, SUM(gs) as gs, SUM(w) as w, SUM(l) as l, SUM(sv) as sv, SUM(hld) as hld, SUM(cg) as cg, SUM(sho) as sho, SUM(ip) as ip, SUM(ip2) as ip2, SUM(h) as h, SUM(r) as r, SUM(er) as er, SUM(hr) as hr, SUM(bb) as bb, SUM(k) as k FROM `player_rec_pitcher_daily` WHERE id=".$id." AND `date` > '".$date."'";

	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 計算平均值
	$ip = $row['ip']+$row['ip2']/3;
	if($ip)
		$era = 9*$row['er']/$ip;
	else if($row['er'])
		$era = 99;
	else
		$era = 0;

	if($ip)
		$whip = ($row['h']+$row['bb'])/$ip;
	else if($row['h']+$row['bb'])
		$whip = 99;
	else
		$whip = 0;

	if($row['w']+$row['l'])
		$wpct = $row['w']/($row['w']+$row['l']);
	else
		$wpct = 0;

	// 計算積分
	$points = 30*$row['w']-15*$row['l']+30*$row['sv']+15*$row['hld']+15*$row['ip']+5*$row['ip2']-5*$row['h']-10*$row['er']-3*$row['bb']+3*$row['k'];
	if($row['g'])
		$ppg = $points/$row['g'];
	else
		$ppg = 0;

	$sql = "INSERT INTO `player_rec_pitcher_15`(`id`, `points`, `ppg`, `g`, `gs`, `w`, `l`, `sv`, `hld`, `cg`, `sho`, `ip`, `ip2`, `h`, `r`, `er`, `hr`, `bb`, `k`, `era`, `whip`, `wpct`) VALUES (".$id.",".$points.",".$ppg.",".$row['g'].",".$row['gs'].",".$row['w'].",".$row['l'].",".$row['sv'].",".$row['hld'].",".$row['cg'].",".$row['sho'].",".$row['ip'].",".$row['ip2'].",".$row['h'].",".$row['r'].",".$row['er'].",".$row['hr'].",".$row['bb'].",".$row['k'].",".$era.",".$whip.",".$wpct.")";
	echo $sql."<BR>";
	$db->query($sql);

	// 30days
	$date = substr(GetLocalTime(),0,10);
	//$date = '2011-09-30';
	$date = new DateTime($date);
	$date->modify('-30 day');
	$date = $date->format('Y-m-d');

	$sql = "SELECT SUM(g) as g, SUM(gs) as gs, SUM(w) as w, SUM(l) as l, SUM(sv) as sv, SUM(hld) as hld, SUM(cg) as cg, SUM(sho) as sho, SUM(ip) as ip, SUM(ip2) as ip2, SUM(h) as h, SUM(r) as r, SUM(er) as er, SUM(hr) as hr, SUM(bb) as bb, SUM(k) as k FROM `player_rec_pitcher_daily` WHERE id=".$id." AND `date` > '".$date."'";

	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 計算平均值
	$ip = $row['ip']+$row['ip2']/3;
	if($ip)
		$era = 9*$row['er']/$ip;
	else if($row['er'])
		$era = 99;
	else
		$era = 0;

	if($ip)
		$whip = ($row['h']+$row['bb'])/$ip;
	else if($row['h']+$row['bb'])
		$whip = 99;
	else
		$whip = 0;

	if($row['w']+$row['l'])
		$wpct = $row['w']/($row['w']+$row['l']);
	else
		$wpct = 0;

	// 計算積分
	$points = 30*$row['w']-15*$row['l']+30*$row['sv']+15*$row['hld']+15*$row['ip']+5*$row['ip2']-5*$row['h']-10*$row['er']-3*$row['bb']+3*$row['k'];
	if($row['g'])
		$ppg = $points/$row['g'];
	else
		$ppg = 0;

	$sql = "INSERT INTO `player_rec_pitcher_30`(`id`, `points`, `ppg`, `g`, `gs`, `w`, `l`, `sv`, `hld`, `cg`, `sho`, `ip`, `ip2`, `h`, `r`, `er`, `hr`, `bb`, `k`, `era`, `whip`, `wpct`) VALUES (".$id.",".$points.",".$ppg.",".$row['g'].",".$row['gs'].",".$row['w'].",".$row['l'].",".$row['sv'].",".$row['hld'].",".$row['cg'].",".$row['sho'].",".$row['ip'].",".$row['ip2'].",".$row['h'].",".$row['r'].",".$row['er'].",".$row['hr'].",".$row['bb'].",".$row['k'].",".$era.",".$whip.",".$wpct.")";
	echo $sql."<BR>";
	$db->query($sql);

	// 2012
	$sql = "SELECT SUM(g) as g, SUM(gs) as gs, SUM(w) as w, SUM(l) as l, SUM(sv) as sv, SUM(hld) as hld, SUM(cg) as cg, SUM(sho) as sho, SUM(ip) as ip, SUM(ip2) as ip2, SUM(h) as h, SUM(r) as r, SUM(er) as er, SUM(hr) as hr, SUM(bb) as bb, SUM(k) as k FROM `player_rec_pitcher_daily` WHERE id=".$id;

	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 計算平均值
	$ip = $row['ip']+$row['ip2']/3;
	if($ip)
		$era = 9*$row['er']/$ip;
	else if($row['er'])
		$era = 99;
	else
		$era = 0;

	if($ip)
		$whip = ($row['h']+$row['bb'])/$ip;
	else if($row['h']+$row['bb'])
		$whip = 99;
	else
		$whip = 0;

	if($row['w']+$row['l'])
		$wpct = $row['w']/($row['w']+$row['l']);
	else
		$wpct = 0;

	// 計算積分
	$points = 30*$row['w']-15*$row['l']+30*$row['sv']+15*$row['hld']+15*$row['ip']+5*$row['ip2']-5*$row['h']-10*$row['er']-3*$row['bb']+3*$row['k'];
	if($row['g'])
		$ppg = $points/$row['g'];
	else
		$ppg = 0;

	$sql = "INSERT INTO `player_rec_pitcher_2012`(`id`, `points`, `ppg`, `g`, `gs`, `w`, `l`, `sv`, `hld`, `cg`, `sho`, `ip`, `ip2`, `h`, `r`, `er`, `hr`, `bb`, `k`, `era`, `whip`, `wpct`) VALUES (".$id.",".$points.",".$ppg.",".$row['g'].",".$row['gs'].",".$row['w'].",".$row['l'].",".$row['sv'].",".$row['hld'].",".$row['cg'].",".$row['sho'].",".$row['ip'].",".$row['ip2'].",".$row['h'].",".$row['r'].",".$row['er'].",".$row['hr'].",".$row['bb'].",".$row['k'].",".$era.",".$whip.",".$wpct.")";
	echo $sql."<BR>";
	$db->query($sql);
}

echo "auto_7_15_30.php執行完了";
?>