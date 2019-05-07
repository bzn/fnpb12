<?php
include_once("validateCookie.php");
include_once("include/func.php");
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<?php include(dirname(__FILE__)."/head_tag.php"); ?>
</head>
<body id="res_playerlist">
	<div id="content" class="container canvas">
		<header>
			<?php include(dirname(__FILE__)."/header.php"); ?>
		</header>
		<div class="row" id="about_faq">
			<h2 class="title span12">FAQ</h2>
			<div class="span12">
				<h3>■ 什麼是 Fantasy Allstar？</h3>
				<p class="box box-light">
					在Fantasy Allstar裡你可以組織一支你的夢幻球隊。你選擇的球員在現實比賽中表現，會影響到你球隊當日的積分，只要球隊資金足夠，你可以買進任何喜愛的球員來為你的隊伍效力。
				</p>
				<h3>■ 這個遊戲該怎麼玩？</h3>
				<p class="box box-light">
					每個人組成的球隊有5名投手的空位與10名野手（包含指定打擊）。只要這些選手當天在實際的比賽中上場比賽並獲得成績，就會依照以下的標準來計分。積分越高你的球隊排名也就越前面，這就是這個遊戲本身的意義，當然球員表現不好也是會有負分的情形，而投手和打者積分的算法是不一樣的。
				</p>
				<h3>■ 積分是怎麼計算的？</h3>
				<div class="box box-light">
					<p>
						<img src="image/rule.jpg">
					</p>
					<p>
						舉例來說
					</p>
					<p>
						A打者今日4打數(出局數是3)，1支二壘打，有2分的打點和2次三振
					</p>
					<p>
						-2 x 3 + 10 x 1 + 5 x 2 - 1 x 2 = 12 分 
					</p>
					<p>
						B投手今日投球6局，被安打3支，自責分2分，並拿下8次三振
					</p>
					<p>
						15 x 6 - 5 x 3 - 10 x 2 + 3 x 8 = 79 分 
					</p>
					<em><font color="red">[注意!!] 你必須買滿球隊所有的球員空位，系統才會幫你計分</font></em>
				</div>
				<h3>■ 要怎麼交易球員？</h3>
				<div class="box box-light">
					<p>有的球員可能表現不佳，系統每個禮拜會發放有限的交易次數，讓你在金額許可的情形下交易出去，每週一、週三、周五的(pm19:00)會各發放1次投手交易次數，週二、週四、週六(pm19:00)各發送1次打者交易，沒用完的交易次數是可以累積的，但是要注意的是，交易目前沒有辦法反悔到上一步。</p>
					<p>而在3/30晚上開幕戰之前是可以任意交易的，並不會扣交易次數。</p>
				</div>
				<h3>■ 關於交易凍結</h3>
				<div class="box box-light">
					<p>如果某名球員當天有比賽，那麼球員在比賽開始之後就會進入凍結狀態，在球賽開始到系統計算球員價錢漲跌的這段時間，球員的購買連結會失效，這個時候玩家不能夠買賣此名球員。因此你如果看準了某名球員要買(或賣)，必須在該天第一場球賽開始之前就進行交易，這樣才會納入計分。</p>
				</div>
				<h3>■ 球員價錢的變動</h3>
				<p class="box box-light">
					遊戲中每支球隊所做的買賣，會影響到隔日球員的身價起伏，簡單的說，你在低價的時候買進的球員，假如日後其他隊伍也跟著買進，第二天這個球員的身價就會提升，而你再將他賣出的時候，你的球隊就可以賺到這個價差，進而有更多的資金可以運用，反之如果球員跌價，球隊的資金也會跟著變少。
				</p>
				<h3>■ Fantasy Allstar為甚麼要用臉書登入？</h3>
				<p class="box box-light">為了避免帳號浮濫，我們改用臉書帳號認證的方式登入，同時這樣也可以讓遊戲中的對手可以藉由臉書帳號進行交流，同時利用粉絲團的方式讓大家有參予討論遊戲的空間。</p>

				<h3>■ 新手容易發生的錯誤</h3>
				<div class="box box-light">
					<p>1. 5名投手與10名野手的空位必須全部買滿，才能夠計算分數，若是裏頭有空位未買，則當天分數將不予計分，所以一定要全部買滿才能計分。</p>
					<p>2.比賽計分的球員名單，是在該天第一場比賽就凍結，作為計分的依據。因此如果當天中午就有比賽，計分標準就是在中午凍結，就算中午的比賽結束，晚上的比賽還未開始，球員更動也只是影響到隔天的計分，對於晚上的比賽已經沒有影響，所以如果中午有比賽，請務必在中午比賽開始前，就將當天的陣容安排好。</p>
					<p>3.在開幕戰3月30日之前，玩家可以任意的更換自己的陣容，並不扣交易次數，但開幕戰之後，每次的更換陣容都會扣交易次數唷！</p>
				</div>
			</div>
		</div>
	<?php include(dirname(__FILE__).'/footer.php'); ?>
	</div>
</body>
</html>