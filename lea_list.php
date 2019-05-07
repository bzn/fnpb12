<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("validateCookie.php");

$myTeamID = $_COOKIE['myteamid'];
$myLeaID = GetDefaultLeagueID($myTeamID);

$db = new DB();
$sql = "SELECT * FROM `league_data` ORDER BY `id`";
$stmt = $db->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();
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
		<h2 class="title span12">檢視所有聯盟</h2>
	</div>
	<div id="trade_records">
		<table class="table table-striped table-bordered table-condensed">
			
			<thead>
				<tr>
					<th class="data-key">聯盟</th>
			          <th class="data-key">描述</th>
			          <th class="data-key">人數</th>
			          <th class="data-key">密碼</th>
			          <th class="data-key">加入</th>
				</tr>
			</thead>
			<tbody>
      		<?php
        	for($i=0;$i<$count;$i++)
        	{
        		$id = $row[$i]['id'];
        		$name = GetLeagueName($id);
        		$desc = $row[$i]['desc'];
        		$counter = $row[$i]['counter'];
         		?>
  		  		<tr class="data-caption">
  					<td class="data-value"><?php echo "<a href='lea_league.php?leaid=".$id."'>".$name."</a>";?></td>
		            <td class="data-value"><?php echo $desc;?></td>
		            <td class="data-value"><?php echo $counter;?></td>
		            <td class="data-value"></td>
		            <td class="data-value"></td>
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


</body>
</html>