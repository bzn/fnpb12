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
  'unifunc' => 'content_4f78158681fef8_41616807',
  'has_nocache_code' => false,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f78158681fef8_41616807')) {function content_4f78158681fef8_41616807($_smarty_tpl) {?><!-- 
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
            				<td><a href="./myteam_roster.php?myteamid=580">小鷹狩</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100002156043966/picture?type=square"></td>
            				<td>Suica Chen</td>
            				<td>50.27M</td>
            				<td>393</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">2</span></td>
            				<td><a href="./myteam_roster.php?myteamid=953">ConNee</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100001122478385/picture?type=square"></td>
            				<td>Jin Sian Kang</td>
            				<td>50.51M</td>
            				<td>116</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">3</span></td>
            				<td><a href="./myteam_roster.php?myteamid=967">狼</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1050792473/picture?type=square"></td>
            				<td>Cavalry Wolf</td>
            				<td>50.03M</td>
            				<td>28</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge badge-info">4</span></td>
            				<td><a href="./myteam_roster.php?myteamid=1106">京都水曜貓</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/1318245886/picture?type=square"></td>
            				<td>陳彥昌</td>
            				<td>50.00M</td>
            				<td>0</td>
            			</tr>
            		
            			<tr>
            				<td><span class="badge">5</span></td>
            				<td><a href="./myteam_roster.php?myteamid=349">只能想念你</a></td>
            				<td>★★★</td>
            				<td><img class="thumbnail" id="fbPicture" src="https://graph.facebook.com/100000105438313/picture?type=square"></td>
            				<td>Yi-Cheng Li</td>
            				<td>50.00M</td>
            				<td>0</td>
            			</tr>
            		
		</tbody>
	</table>
</div><?php }} ?>