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
  'unifunc' => 'content_4f77bf23e09721_86685401',
  'has_nocache_code' => false,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f77bf23e09721_86685401')) {function content_4f77bf23e09721_86685401($_smarty_tpl) {?><!-- 
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
            				<td><a href="./myteam_roster.php?myteamid=3">我愛沈文程與鄭進一</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1230161027/picture?type=square"></td>
            				<td>EiPing Chen</td>
            				<td>50.39M</td>
            				<td>172</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">2</span></td>
            				<td><a href="./myteam_roster.php?myteamid=2">布魯斯爆炸</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/614384297/picture?type=square"></td>
            				<td>Bluezhin Lee</td>
            				<td>50.49M</td>
            				<td>164</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge badge-info">3</span></td>
            				<td><a href="./myteam_roster.php?myteamid=1">呆呆</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1365169280/picture?type=square"></td>
            				<td>Ahdai Tsai</td>
            				<td>50.35M</td>
            				<td>123</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">4</span></td>
            				<td><a href="./myteam_roster.php?myteamid=5">菜大呆</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1373688633/picture?type=square"></td>
            				<td>Daidai Tsai</td>
            				<td>50.15M</td>
            				<td>79</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">5</span></td>
            				<td><a href="./myteam_roster.php?myteamid=4">復仇者聯盟</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/562969631/picture?type=square"></td>
            				<td>Randy Lien</td>
            				<td>50.15M</td>
            				<td>24</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">6</span></td>
            				<td><a href="./myteam_roster.php?myteamid=692">彗星裡翻船</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/714895396/picture?type=square"></td>
            				<td>泥米雪</td>
            				<td>49.91M</td>
            				<td>0</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">7</span></td>
            				<td><a href="./myteam_roster.php?myteamid=790">noob</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1433467987/picture?type=square"></td>
            				<td>Dada Artist</td>
            				<td>49.97M</td>
            				<td>0</td>
            			</tr>
            		
		</tbody>
	</table>
</div><?php }} ?>