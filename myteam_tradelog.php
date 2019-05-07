<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("class/myteam.class.php");
include_once("validateCookie.php");

$myteamID = $_COOKIE['myteamid'];
$db = new DB();
$sql = "SELECT * FROM myteam_trade_log WHERE myteam_id=".$myteamID;
//echo $sql;
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
    <?php include(dirname(__FILE__)."/header.php"); ?>
		<div class="row" id="mytrade_record">
			<h2 class="span12 title">我的交易記錄</h2>
		</div>
		<div id="trade_records">
		<table class="table bordered-table zebra-striped">
			
			<thead>
				<tr>
					<th class="table-caption">交易時間</th>
					<th class="table-caption">選手</th>
					<th class="table-caption">買入價格</th>
					<th class="table-caption">賣出價格</th>
				</tr>
			</thead>
			
			<tbody>
				
        <?php
        $count = count($row);
        for($i=0;$i<$count;$i++)
        {
          $buyID = $row[$i]['buy_id'];
          $sellID = $row[$i]['sell_id'];
          $datetime = $row[$i]['datetime'];
          $date = substr($datetime, 0, 10);
          if($buyID>0) $playerID = $buyID;
          elseif ($sellID>0)  $playerID = $sellID;
          $name = GetPlayerName($playerID);
          $price = GetPriceByDate($playerID, $date);
          ?>
          <tr class="data-row">
          <td class="data-value"><?php echo $datetime;?></td>
          <td class="data-value"><?php echo $name;?></td>
          <td class="data-value"><?php if($buyID) echo $price; else echo"-";?></td>
          <td class="data-value"><?php if($sellID) echo $price; else echo"-";?></td>
                    </tr>
          <?php
        }
        ?>				
			</tbody>
		</table>
	</div>
		<footer>
			<?php include(dirname(__FILE__)."/footer.php"); ?>
		</footer>
	</div>
	<!-- scripts concatenated and minified via ant build script-->
	<script src="js/plugins.js"></script>
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