<?php
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
	if ($_POST['method'] = 'getAccessToken')
	{
		//Facebook PHP SDK
		require 'facebook-php-sdk/src/facebook.php';
		$facebook = new Facebook(array(
			'appId'		=> $appID,
			'secret'	=> $appSecret
		));
		//Check facebook id
		if($facebook->getUser() == $_POST['fbid'])
		{
			//Check base auth
			if(utilAuth::checkBaseAuth($_POST['baseAuth']))
			{
				$account 				= $_POST['fbid'] . '@FB';
				$json['accessToken']	= utilAuth::getAccessToken($account);
				$json['result']			= true;
				
				$arrAccessToekn = explode("|", $json['accessToken']); 
				$account = $arrAccessToekn[0];
				$userData = utilUserDataManager::sharedDataManager()->getUserData($account);
				
				$json['userid'] 	= $userData['id'];
				$json['myteamid']	= $userData['myteam'][0]['id'];
				
				$json['checkCode']  = utilAuth::getCheckCode($json['accessToken'], $json['userid'], $json['myteamid']);
				
				//Set accessToken cookie
				setcookie("accessToken", $json['accessToken'], time()+3600 * 24, "/", "fantasy.ohdada.com");
				setcookie("userid", $userData['id'], time()+3600 * 24, "/", "fantasy.ohdada.com");
				setcookie("myteamid", $userData['myteam'][0]['id'], time()+3600 * 24, "/", "fantasy.ohdada.com");
				setcookie('checkCode', $json['checkCode'], time()+3600 * 24, "/", "fantasy.ohdada.com");
				setcookie('fbid', $_POST['fbid'], time()+3600 * 24, "/", "fantasy.ohdada.com");
			}
			else 
			{
				$json['result']		= false;
				$json['msg']		= 'Auth failed!';
			}
		}
		else 
		{
			$json['result']		= false;
			$json['msg']		= 'Facebook id error!';			
		}
		echo json_encode($json);
	}
	exit();
}
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<?php include(dirname(__FILE__)."/head_tag.php"); ?>
	<script type="text/javascript">
	/*<![CDATA[*/

	/*]]>*/
	</script>
</head>
<body>
<div class="container">
	<section id="content" class="container canvas">
		<div class="page-header navbar-wrapper">
	    	<h1>
	    		<img class="fantasy-logo" src="image/logo.png">
				<div class="text-center">日本職棒夢幻經營遊戲</div>
			</h1>
		</div>
		<div class="row">
			<div class="span4">
				<div class="box coach-board">
					<h2 class="box-title">組織你的夢幻球隊</h2>
					<p class="box-content">
						在Fanstasy Allstar中，你可以組織一支你的夢幻球隊。球員的現實比賽表現影響你的球隊積分。只要資金足夠，你可以買進任何喜愛的球員為隊伍效力。<a href="faq.php">[詳細說明]</a>
					</p>
				</div>
	    	</div>
			<div class="span4">
				<div class="pitcher-index">
					<img src="image/pitcher.png" />
				</div>
				<div class="box coach-board">
					<h2 class="box-title">'12球季全新出發</h2>
					<p class="box-content">
						新的一季，我們送上了全新的球員資料，社群要素也將循序推出。沉寂兩季的 oh!dada 團隊再次為各位大大服務！
					</p>
				</div>
	    	</div>
			<div class="span4">
				<div class="box coach-board">
					<h2 class="box-title">用Facebook帳號玩！</h2>
					<p class="box-content">
						為了帶給大家更多的社群樂趣，我們整合了Facebook帳號，登入也變得更簡單。
						<div class="fb-login-button" scope="publish_actions,publish_stream,email"></div>
					</p>
				</div>
				<div class="player-index">
					<img src="image/player.png" />
				</div>
	    	</div>
		</div>
	</section>
	<?php include(dirname(__FILE__).'/footer.php'); ?>
</div>

<div id="fb-root"></div>
<script>
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
    FB.Event.subscribe('auth.statusChange', function(response) {
        if(response.status === 'connected')
        {
	    	if (response.authResponse)
	        {
	        	FB.api('/me', function(userInfo){
	                // logged in and connected user, someone you know
	                $.ajax({
	    				type: "post",
	            	    data: {
	                	    method:"getAccessToken",
	                	    baseAuth: "<?php echo utilAuth::getBaseAuth()?>",
	                	    fbid: response.authResponse.userID
	                	},
	            	    dataType: "json",
	            	    success:function(j){
							if(!j.error)
							{
								if(!j.myteamid)
								{
									window.location.href = "tool_createteam.php";
								}
								else
								{
									window.location.href = "myteam_lineup.php";
								}
							}
	            		}
	            	});
	        	});
	        }
        }
  	});
    //FB.Canvas.setSize({ width: "100%", height: 2400 });
};

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