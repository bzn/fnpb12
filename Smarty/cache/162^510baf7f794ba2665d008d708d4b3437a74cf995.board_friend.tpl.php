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
  'unifunc' => 'content_4f77d32db225a9_77883639',
  'has_nocache_code' => false,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f77d32db225a9_77883639')) {function content_4f77d32db225a9_77883639($_smarty_tpl) {?><!-- 
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
            				<td><span class="badge badge-info">1</span></td>
            				<td><a href="./myteam_roster.php?myteamid=142">poppeen</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100001024791648/picture?type=square"></td>
            				<td>張哲嘉</td>
            				<td>50.83M</td>
            				<td>414</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">2</span></td>
            				<td><a href="./myteam_roster.php?myteamid=732">浪速野燕</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1141468153/picture?type=square"></td>
            				<td>顆粒漁</td>
            				<td>50.63M</td>
            				<td>263</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">3</span></td>
            				<td><a href="./myteam_roster.php?myteamid=703">血手</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100002757012507/picture?type=square"></td>
            				<td>顆粒漁</td>
            				<td>50.54M</td>
            				<td>236</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">4</span></td>
            				<td><a href="./myteam_roster.php?myteamid=71">浪速之鷹</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1514038148/picture?type=square"></td>
            				<td>吳哲彰</td>
            				<td>50.40M</td>
            				<td>217</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">5</span></td>
            				<td><a href="./myteam_roster.php?myteamid=3">我愛沈文程與鄭進一</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1230161027/picture?type=square"></td>
            				<td>EiPing Chen</td>
            				<td>50.39M</td>
            				<td>172</td>
            			</tr>
            		
		</tbody>
	</table>
</div><?php }} ?>