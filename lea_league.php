<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("validateCookie.php");

$myTeamID = $_COOKIE['myteamid'];

$leaID = $_GET['leaid'];
if(!$leaID)
{
	$leaID = GetDefaultLeagueID($myTeamID);
}

$leaName = GetLeagueName($leaID);

$db = new DB();
$sql = "SELECT `myteam_id` FROM `league_myteam_index` WHERE `league_id`=".$leaID;
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();
$idArray = array();
$pointsArray = array();
$moneyArray = array();
$fbidArray = array();
for($i=0;$i<$count;$i++)
{
	$sql = "SELECT `id`, `points`, `money` FROM `myteam_data` WHERE id=".$row[$i]['myteam_id'];
	$stmt = $db->query($sql);
	$myteamRow = $stmt->fetch(PDO::FETCH_ASSOC);
	$userdata = GetUserDataByTeamID($row[$i]['myteam_id']);
	array_push($idArray, $myteamRow['id']);
	array_push($pointsArray, $myteamRow['points']);
	array_push($moneyArray, $myteamRow['money']);
	array_push($fbidArray, substr($userdata['account'], 0, strpos($userdata['account'], "@")));
}
array_multisort($pointsArray,SORT_DESC,SORT_NUMERIC,$idArray,$moneyArray,$fbidArray);
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
      <?php include(dirname(__FILE__)."/header.php"); ?>
    </header>

    <div class="row" id="mytrade_record">
		<h2 class="title span12"><?php echo $leaName;?> - 積分Top20</h2>
	</div>
	<div id="trade_records">
		<table class="table table-striped table-bordered table-condensed">
			
			<thead>
				<tr>
					<th class="data-key">Rank</th>
			          <th class="data-key">球隊名稱</th>
			          <th class="data-key">榮譽</th>
			          <th class="data-key">隊徽</th>
			          <th class="data-key">玩家名稱</th>
			          <th class="data-key">球隊資產</th>
			          <th class="data-key">本季積分</th>
				</tr>
			</thead>
			<tbody>
      		<?php
//        	$count = count($row);
        	for($i=0;$i<20;$i++)
        	{
        		$id = $idArray[$i];
        		if($id)
        		{
	        		$points = $pointsArray[$i];
	        		$money = $moneyArray[$i];
	        		$fbid = $fbidArray[$i];
	         		?>
	  		  		<tr class="data-caption">
	  					<td class="data-value"><?php echo ($i+1);?></td>
			            <td class="data-value"><?php echo "<a href='myteam_roster.php?myteamid=".$id."'>".GetMyTeamName($id)."</a>";?></td>
			            <td class="data-value"></td>
			            <td class="data-value">
			            	<img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/<?php echo $fbid;?>/picture?type=square">
						</td>
			            <td class="data-value">
			            	<div class="fbName1" id="fbName1<?php echo $fbid;?>"></div>
						</td>
			            <td class="data-value"><?php echo GetPriceFormat($money);?></td>
			            <td class="data-value"><?php echo $points;?></td>
	  				</tr>
	  				<?php 
	  			}
      		}
        	?>	
			</tbody>
		</table>
	</div>

	<?php
	array_multisort($moneyArray,SORT_DESC,SORT_NUMERIC,$idArray,$pointsArray,$fbidArray);
	?>

	<div class="row" id="mytrade_record">
		<h2 class="title span12"><?php echo $leaName;?> - 資產Top20</h2>
	</div>
	<div id="trade_records">
		<table class="table table-striped table-bordered table-condensed">
			
			<thead>
				<tr>
					<th class="data-key">Rank</th>
			          <th class="data-key">球隊名稱</th>
			          <th class="data-key">榮譽</th>
			          <th class="data-key">隊徽</th>
			          <th class="data-key">玩家名稱</th>
			          <th class="data-key">球隊資產</th>
			          <th class="data-key">本季積分</th>
				</tr>
			</thead>
			<tbody>
      		<?php
//        	$count = count($row);
        	for($i=0;$i<20;$i++)
        	{
        		$id = $idArray[$i];
        		if($id)
        		{
	        		$points = $pointsArray[$i];
	        		$money = $moneyArray[$i];
	        		$fbid = $fbidArray[$i];
	         		?>
	  		  		<tr class="data-caption">
	  					<td class="data-value"><?php echo ($i+1);?></td>
			            <td class="data-value"><?php echo "<a href='myteam_roster.php?myteamid=".$id."'>".GetMyTeamName($id)."</a>";?></td>
			            <td class="data-value">★★★</td>
			            <td class="data-value">
			            	<img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/<?php echo $fbid;?>/picture?type=square">
						</td>
			            <td class="data-value">
			            	<div class="fbName2" id="fbName2<?php echo $fbid;?>"></div>
			            </td>
			            <td class="data-value"><?php echo GetPriceFormat($money);?></td>
			            <td class="data-value"><?php echo $points;?></td>
	  				</tr>
	  				<?php 
	  			}
      		}
        	?>	
			</tbody>
		</table>
	</div>
	<footer>
		© OH!DADA 2012
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
include dirname(__FILE__) . '/FBJS.php';
?>

</body>
</html>