<?php
session_start();
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");

$db = new DB();
$sql = "SELECT * FROM `myteam_data` ORDER BY `money` DESC LIMIT 50";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($row);
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
			<h2 class="title span12">資產金榜Top50</h2>
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
        $count = count($row);
        for($i=0;$i<$count;$i++)
        {
        	$userdata = GetUserDataByTeamID($row[$i]['id']);
        	$fbid	  = substr($userdata['account'], 0, strpos($userdata['account'], "@"));
          ?>
  				<tr class="data-caption">
  					<td class="data-value"><?php echo ($i+1);?></td>
		            <td class="data-value"><a href='myteam_roster.php?myteamid=<?php echo $row[$i]['id'];?>'><?php echo $row[$i]['name'];?></a></td>
		            <td class="data-value"></td>
		            <td class="data-value">
		            	<img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/<?php echo $fbid;?>/picture?type=square">
					</td>
		            <td class="data-value">
		            	<div class="fbName1" id="fbName1<?php echo $fbid;?>"></div>
					</td>
		            <td class="data-value"><?php echo GetPriceFormat($row[$i]['money']);?></td>
		            <td class="data-value"><?php echo $row[$i]['points'];?></td>
  				</tr>
  			<?php 
        }
        ?>	
			</tbody>
		</table>
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

<?php 
include 'FBJS.php';
?>

</body>
</html>