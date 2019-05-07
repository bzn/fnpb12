<?php
/*********************************************************************************************************
 * NO Cache
 *********************************************************************************************************/
if(! empty( $_SERVER['SERVER_SOFTWARE'] ) && strstr( $_SERVER['SERVER_SOFTWARE'], 'Apache/2' ))
{
    header( 'Cache-Control: no-store, no-cache, must-revalidate' );
    header( 'Cache-Control: post-check=0, pre-check=0', false );
}
else
{
    header( 'Cache-Control: private, pre-check=0, post-check=0, max-age=0' );
}
header( 'Expires: ' . date( 'D, d M Y H:i:s', $_SERVER['REQUEST_TIME'] ) . ' GMT' );
header( 'Last-Modified: ' . date( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Pragma: no-cache' );
header( 'Content-Type: text/html; charset=utf-8' );

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
 * Clear cookie
 *********************************************************************************************************/
//Set accessToken cookie
setcookie("accessToken", -1, time()+3600 * 24, "/", "fantasy.ohdada.com");
setcookie("userid", -1, time()+3600 * 24, "/", "fantasy.ohdada.com");
setcookie("myteamid", -1, time()+3600 * 24, "/", "fantasy.ohdada.com");
setcookie('checkCode', -1, time()+3600 * 24, "/", "fantasy.ohdada.com");
setcookie('fbid', -1, time()+3600 * 24, "/", "fantasy.ohdada.com");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $appTitle?></title>
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" title="no title">
<script src="http://www.google.com/jsapi"></script>
<script>
google.load("jquery","1.7.1");
</script>
<script src="./js/jquery.cookie.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
$.cookie("accessToken", -1, {domain: "fantasy.ohdada.com"});
$.cookie("userid", -1, {domain: "fantasy.ohdada.com"});
$.cookie("myteamid", -1, {domain: "fantasy.ohdada.com"});
$.cookie("checkCode", -1, {domain: "fantasy.ohdada.com"});
$.cookie("fbid", -1, {domain: "fantasy.ohdada.com"});
/*]]>*/
</script>
</head>
<body>
<div class="container">
Logout...
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
    FB.getLoginStatus(function(response){
    	if(response.authResponse)
    	{
    		FB.logout();
    	}
    	else
    	{
    		window.top.location.href = "index.php";
    	}
    });
    FB.Event.subscribe('auth.logout', function(response) {
		// do something with response
    	window.top.location.href = "index.php";
  	});
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