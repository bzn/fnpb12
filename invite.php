<?php
include 'validateCookie.php';
include 'lib/package/loader.php';

/*********************************************************************************************************
 * Base settings
 *********************************************************************************************************/
//Facebook App ID
$appID          = 286768454715412;
$appSecret		= '2d5d950ab074791c51132df46028059a';
//網頁抬頭
$appTitle       = 'Fantasy Allstar';
//畫布網址
$appCanvasUrl   = 'http://apps.facebook.com/fantasyohdada/';
//粉絲頁編號
$fans_page_id   = 286768454715412;
//讚物件編號
$like_obj_id    = 286768454715412;

/*********************************************************************************************************
 * Ajax Get Auth Token
 *********************************************************************************************************/
if ($_POST['method'])
{
	exit();
}
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<?php include 'head_tag.php';?>
</head>
<body id="res_playerlist">
<div id="content" class="container canvas">
	<header>
		<?php include(dirname(__FILE__)."/header.php"); ?>
	</header>
	<section id="global">
		<div class="page-header">
	    	<h1>
	    		現在就邀請你的好友來一起組隊較勁
				<small>人多就是好玩</small>
				<input type="button" class="btn btn-info" value="點此邀請" onclick="invite()" />
			</h1>
		</div>
		<div class="row">
			<div class="span12">
				<h2></h2>
				<p>
					<div class="fb-like-box" data-href="http://www.facebook.com/FantasyAllstar" data-width="760" data-show-faces="true" data-stream="true" data-header="true"></div>
				</p>
	    	</div>
		</div>
	</section>
</div>

<div id="fb-root"></div>
<script>
var friend = [];
window.fbAsyncInit = function() {
    FB.init({
        appId: '<?php echo $appID ?>', 
        status: true, 
        cookie: true,
        xfbml: true,
        frictionlessRequests: true,
        channelURL : document.location.protocol + '//<?php echo $_SERVER['HTTP_HOST']?>/channel.php',
        oauth : true
    });
    FB.api(
		{"method":"friends.getAppUsers"},
        function(response){
			$.merge(friend, response);
    });
};

function invite(){
	FB.ui({
			method:"apprequests", 
			display:"popup",
			exclude_ids: friend,
			message: "快來一起組隊較勁!!"
		},
		function(response){
		/**
		* Response
		*
		* request	: ‘<request_object_id>_<user_id>’
		* to : An array of the recipient user IDs for the request that was created.
		*/
	}); 
}

//Load the SDK Asynchronously
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document)); 
</script>
</body>
</html>