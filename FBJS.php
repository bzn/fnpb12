<?php
/**
 * 
 * Include before </body>
 */

$appID = 286768454715412;
?>

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
    	FB.api("/?ids=" + response.authResponse.userID + "&fields=id,name,picture", function(idList){
        	for(var id in idList)
        	{
        		$("#fbName").html(idList[id].name);
        	}
    	});
    	var aFBId = [];
    	$(".fbName1,.fbName2,.fbName3").each(function(){
    		var htmlid = $(this).attr("id");
    		var fbid = htmlid.substr(7);
    		aFBId.push(fbid);
    	});
		FB.api("/?ids=" + aFBId.toString() + "&fields=name", function(idList){
			for(var id in idList)
			{
				$("#fbName1" + idList[id].id).html(idList[id].name);
				$("#fbName2" + idList[id].id).html(idList[id].name);
				$("#fbName3" + idList[id].id).html(idList[id].name);
			}
		});
    	if(document.location.href.indexOf("board_friend") > 0)
    	{
			FB.api({
					"method":"friends.getAppUsers"
				},
	    		function(arrayFriendId){
	    			arrayFriendId.push(response.authResponse.userID);
	    	    	FB.api("/?ids=" + arrayFriendId.toString() + "&fields=id,name", function(idList){
		    	        $.ajax({
		    				type: "get",
		    	            data: {
		    	        	    method: "getFriendRank",
		    	        	    friendList: idList
		    	        	},
		    	            dataType: "json",
		    	            success:function(j){
			    	            if(!j.error)
			    	            {
				    	            $("#board_friend").html(j.html);
			    	            }
		    	        	}
		    	        });
	    	    	});
	    	    }
	    	);
    	}
    });
    $("#fb-root").ready(function(){
    	FB.getLoginStatus(function(response){
    		var fbid = response.authResponse.userID;

    	});
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