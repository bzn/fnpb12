<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/base/jquery-ui.css" type="text/css" />
<script src="http://www.google.com/jsapi"></script>
<script>
google.load("jquery","1.7.1");
google.load("jqueryui","1.8.16");
</script>
<script type="text/javascript">
/*<![CDATA[*/

var bInAjax = false;

$.ajaxSetup({
    type: "get",
    dataType : "json",
    //cache: false,
    beforeSend : function(){
        bInAjax = true;
    },
    complete : function(){
    	bInAjax = false;
    }
});

$().ready(function(){
	
});

function createMyTeam(objPost){
	$.ajax({
		crossDomain: true,
		type: "POST",
		url: "http://service.fantasy.ohdada.com/createMyTeam/",
		data: {
			accessToken: "1365169280@FB|1327980809|1ed721033e8f8b392513b4",
			myTeamName: objPost.myTeamName,
			favTeamId: objPost.favTeamId
		},
		dataType: "json",
		success:function(j){
			if(!j.error)
			{
				//do something
			}
		}
	});
}

function createLeague(objPost){
	$.ajax({
		crossDomain: true,
		type: "POST",
		url: "http://service.fantasy.ohdada.com/createLeague/",
		data: {
			accessToken: "1365169280@FB|1327980809|1ed721033e8f8b392513b4",
			leagueName: objPost.leagueName
		},
		dataType: "json",
		success:function(j){
			if(!j.error)
			{
				//do something
			}
		}
	});
}

function joinLeague(objPost){
	$.ajax({
		crossDomain: true,
		type: "POST",
		url: "http://service.fantasy.ohdada.com/joinLeague/",
		data: {
			accessToken: "1365169280@FB|1327980809|1ed721033e8f8b392513b4",
			myTeamId: objPost.myTeamId,
			leagueId: objPost.leagueId,
			password: objPost.password
		},
		dataType: "json",
		success:function(j){
			if(!j.error)
			{
				//do something
			}
		}
	});
}

function trade(objPost){
	$.ajax({
		crossDomain: true,
		type: "POST",
		url: "http://service.fantasy.ohdada.com/trade/",
		data: {
			accessToken: "1365169280@FB|1327980809|1ed721033e8f8b392513b4",
			myTeamId: objPost.myTeamId,
			sellId: objPost.sellId,
			buyId: objPost.buyId,
			buyPos: objPost.buyPos
		},
		dataType: "json",
		success:function(j){
			if(!j.error)
			{
				//do something
			}
		}
	});	
}

function getPlayer(playerId){
	//get players
	$.ajax({
		type: "GET",
		url: "http://service.fantasy.ohdada.com/player/" + playerId + "/",
		data: {
			accessToken: "1365169280@FB|1327980809|1ed721033e8f8b392513b4"
		},
		dataType: "json",
		success:function(j){
			if(!j.error)
			{
				//do somethine
			}
		}
	});
}

function getMyTeam(myTeamId){
	$.ajax({
		type: "GET",
		url: "http://service.fantasy.ohdada.com/myteam/" + myTeamId + "/",
		data: {
			accessToken: "1365169280@FB|1327980809|1ed721033e8f8b392513b4"
		},
		dataType: "json",
		success:function(j){
			if(!j.error)
			{
				//do somethine
			}
		}
	});	
}

function getUser(){
	$.ajax({
		type: "GET",
		url: "http://service.fantasy.ohdada.com/user/",
		data: {
			accessToken: "1365169280@FB|1327980809|1ed721033e8f8b392513b4"
		},
		dataType: "json",
		success:function(j){
			if(!j.error)
			{
				//do somethine
			}
		}
	});
}

/*]]>*/
</script>
</head>
<body>
<label>Method</label><br/>
<label>[GET]</label><br/>
<a class="btn" href="javascript:getPlayer(1)">getPlayer</a> <br/>
<a class="btn" href="javascript:getMyTeam(1)">getMyTeam</a> <br/>
<a class="btn" href="javascript:getUser()">getUser</a> <br/>

<br/><label>[POST]</label><br/>
<a class="btn" href="javascript:createMyTeam({})">createMyTeam</a> <br/>
<a class="btn" href="javascript:createLeague({})">createLeague</a> <br/>
<a class="btn" href="javascript:joinLeague({})">joinLeague</a> <br/>
<a class="btn" href="javascript:trade({})">trade</a> <br/>

</body>
</html>