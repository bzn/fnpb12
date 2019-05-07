<header class="header">
<div class="navbar-wrapper">
	<div class="navbar" data-dropdown="dropdown">
		<div class="navbar-inner" >
			<div class="container canvas" style="position:relative;">
				<a href="index.php"><img src="image/logo.png" width="200" height="100" class="fantasy-logo"></a>
				<ul class="nav padding-logo">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle">我的球隊</a>
						<ul class="dropdown-menu">
							<li><a href="myteam_lineup.php">球員名單</a></li>
							<li><a href="myteam_tradelog.php">交易記錄</a></li>
							<li><a href="myteam_scorelog.php">積分記錄</a></li>
						</ul>
					</li>
					
					<li class="dropdown">
						<a href="#" class="dropdown-toggle">我的聯盟</a>
						<ul class="dropdown-menu">
							<li><a href="lea_league.php">查看我的聯盟</a></li>
							<li><a href="lea_list.php">查看所有聯盟</a></li>
<?php /*
							<li><a href="lea_index.php">聯盟首頁</a></li>
							<li><a href="lea_all.php">查看所有聯盟</a></li>
							<li><a href="lea_invite.php">邀請朋友加入聯盟</a></li>
*/?>							
						</ul>
					</li>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle">記錄室</a>
						<ul class="dropdown-menu">
							<li><a href="res_playerlist.php">搜尋球員</a></li>
							<li><a href="res_pricemove.php">薪資變動</a></li>
							<li><a href="http://baseball.yahoo.co.jp/npb/schedule/" target=_blank>對戰賽程</a></li>
							<li><a href="res_yesterdaybest.php">昨日最佳表現</a></li>
							<?php /*
							<li><a href="res_bargain.php">物超所值球員</a></li>
							<li><a href="res_performer.php">最有價值球員</a></li>
							*/?>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle">排行榜</a>
						<ul class="dropdown-menu">
						<?php /*						
							<li><a href="#">聯盟積分排行榜</a></li>
							<li><a href="#">聯盟資產排行榜</a></li>
						*/ ?>
							<li><a href="board_points.php">積分Top50</a></li>
							<li><a href="board_money.php">資產Top50</a></li>
							<li><a href="board_friend.php">朋友Top50</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle">幫助</a>
						<ul class="dropdown-menu">
							<li><a href="https://www.facebook.com/FantasyAllstar" target=_blank>facebook粉絲團</a></li>
							<li><a href="faq.php">遊戲說明</a></li>
						</ul>
					</li>
					<li class="active"><a href="invite.php">邀請朋友</a></li>
				</ul>                
				<div class="nav secondary-nav">
				</div>
			</div>
		</div><!-- /navbar-inner -->
	</div><!-- /navbar -->
	<iframe style="position:absolute;top:10px;right:0px;width:100px" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FFantasyAllstar&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=286768454715412" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
	<div style="position:absolute;top:-24px;right:0px;" class="fb-logout-button"><a href="./logout.php" class="fb_button fb_button_medium"><span class="fb_button_text">Log Out</span></a></div>
</div>
</header>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-252630-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>