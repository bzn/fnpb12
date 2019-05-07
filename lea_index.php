<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("validateCookie.php");

$myTeamID = $_COOKIE['myteamid'];

$db = new DB();
$sql = "SELECT `league_id` FROM `league_myteam_index` WHERE `myteam_id`=".$myTeamID;
$stmt = $db->query($sql);
$leaRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<body id="lea_index">
	<div id="content" class="container canvas">
		<header>
			<?php include(dirname(__FILE__)."/header.php"); ?>
		</header>
		<div class="row">
			<div class="caption">
				<div class="span9">
					<h3 class="caption">正在參與聯盟</h3>
					<ul class="list">
					<?php 
						for($i=0;$i<$count;$i++)
						{
							$leaID = $leaRow[$i]['league_id'];
							$leaName = GetLeagueName($leaID);
							?>
							<li class="overflow lea_item">
								<div class="info_box">
									<div class="lea_rank_status">
										<?php //20/50 (40%)?>
									</div>
									<div class="info_content">
										<h5 class="lea_name"><?php echo "<a href='lea_league.php?leaid=".$leaID."'>".$leaName."</a>";?></h5>
										<div class="lea_action">
										<?php 
										//	<button>邀請</button>
										//	<button>管理</button>
										?>
											<button>退出</button>
										</div>
									</div>
								</div>
							</li>
							<?php
						}
					?>
					</ul>
				</div>
			</div>
			<div class="span3">
				<div class="box">
					<h3>最近新增聯盟</h3>
					<ul class="list">
						<li class="lea_item">Oh dada</li>
						<li class="lea_item">Spider Man</li>
					</ul>
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