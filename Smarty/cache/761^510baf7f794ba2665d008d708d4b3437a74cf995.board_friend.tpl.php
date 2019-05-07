<?php /*%%SmartyHeaderCode:15710218984f7179b873d005-50669867%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '510baf7f794ba2665d008d708d4b3437a74cf995' => 
    array (
      0 => '/home4/ohohdada/public_html/fantasy/Smarty/templates/board_friend.tpl',
      1 => 1333185347,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15710218984f7179b873d005-50669867',
  'cache_lifetime' => 21600,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_4f77ee24ee45a0_27728473',
  'has_nocache_code' => false,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f77ee24ee45a0_27728473')) {function content_4f77ee24ee45a0_27728473($_smarty_tpl) {?><!-- 
<div class="row" id="mytrade_record">
	<h2 class="title span12">朋友金榜Top50</h2>
</div>
<div id="board_friend">
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
				
		</tbody>
	</table>
</div>
 -->
<div class="row" id="mytrade_record">
	<h2 class="title span12">朋友積分榜Top50</h2>
</div>
<div id="board_friend">
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
			
            			<tr>
            				<td><span class="badge">1</span></td>
            				<td><a href="./myteam_roster.php?myteamid=57">SuperHawks</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100000231885729/picture?type=square"></td>
            				<td>蘇力儀</td>
            				<td>50.77M</td>
            				<td>408</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge badge-info">2</span></td>
            				<td><a href="./myteam_roster.php?myteamid=691">DARK GiBon</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1819755729/picture?type=square"></td>
            				<td>Wang Po Chao</td>
            				<td>50.70M</td>
            				<td>336</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">3</span></td>
            				<td><a href="./myteam_roster.php?myteamid=161">kawazoekazuki</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100000029365052/picture?type=square"></td>
            				<td>川添一樹</td>
            				<td>50.16M</td>
            				<td>251</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">4</span></td>
            				<td><a href="./myteam_roster.php?myteamid=14">中日ドラゴンズ</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100000154091468/picture?type=square"></td>
            				<td>Kashiwa Wei So</td>
            				<td>50.69M</td>
            				<td>245</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">5</span></td>
            				<td><a href="./myteam_roster.php?myteamid=3">我愛沈文程與鄭進一</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1230161027/picture?type=square"></td>
            				<td>EiPing Chen</td>
            				<td>50.39M</td>
            				<td>177</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">6</span></td>
            				<td><a href="./myteam_roster.php?myteamid=90">ARIAカンパニー</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100000356704273/picture?type=square"></td>
            				<td>劉俊德</td>
            				<td>49.95M</td>
            				<td>156</td>
            			</tr>
            		
		</tbody>
	</table>
</div><?php }} ?>