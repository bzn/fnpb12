<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("class/myteam.class.php");
include_once("validateCookie.php");

$myteamID = $_GET['myteamid'];

if(!$myteamID)
	$myteamID = $_COOKIE['myteamid'];

$db = new DB();
$sql = "SELECT * FROM `myteam_rec_daily` WHERE `myteam_id`=".$myteamID." ORDER BY `date` DESC";
//echo $sql;
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
		<div id="my_score_records">
			<h2 class="title">積分記錄</h2>
			<table class="table bordered-table zebra-striped">
				<thead>
					<tr class="data-caption">
						<th class="data-key">日期</th>
						<th class="data-key">當日積分</th>
						<th class="data-key">積分排名</th>
						<th class="data-key">積分排名（聯盟）</th>
						<th class="data-key">資產變動</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$count = count($row);
					for($i=0;$i<$count;$i++)
					{
						$date = "<a href='myteam_roster.php?myteamid=".$myteamID."&date=".$row[$i]['date']."'>".$row[$i]['date']."</a>";
						$money = $row[$i]['money'];
						$pricemove = $row[$i]['pricemove'];
						$points = $row[$i]['points'];
						$pointsRank = $row[$i]['rank_points'];
						$pointsRankUp = $row[$i]['rank_points'] -  $row[$i+1]['rank_points'];
						if($pointsRankUp>0)
							$pointsRankUp = "▲".$pointsRankUp;
						else if($pointsRankUp<0) 
							$pointsRankUp = "▼".$pointsRankUp;
						else
							$pointsRankUp = "-";
						?>
						<tr class="data-row">
						<td class="data-value"><?php echo $date;?></td>
						<td class="data-value"><?php echo $points;?></td>
						<td class="data-value"><?php echo $pointsRank;?></td>
						<td class="data-value">1</td>
						<td class="data-value"><?php echo GetPriceFormat($money);?></td>
						</tr>
						<?php
						$lastmoney = $row[$i]['money'];
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>
  <script src="js/bootstrap.js"></script>
  <script>
    $(document).ready(function(){
      $('.dropdown-toggle').dropdown();
    });
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
</body>
</html>