function FantasyAPI(obj) {
	var opt = {
			accessToken: "1365169280@FB|1327980809|1ed721033e8f8b392513b4"
		},
		myTeamId = obj.myTeamId || "",
		playerId = obj.playerId || "",
		param = $.extend(opt, obj);
	
	return {
		"getUser": function(param) {
			$.ajax({
				type: "GET",
				url: "http://service.fantasy.ohdada.com/user/",
				data: param,
				dataType: "json",
				success: function(data) {
					if (!data.error) {
						console.log(data);
						return data;
					} else {
						console.log(data.error);
					}
				}
			});
		},
		"getMyTeam": function(callback) {
			$.ajax({
				type: "GET",
				url: "http://service.fantasy.ohdada.com/myteam/" + myTeamId + "/",
				data: param,
				dataType: "json",
				success: function(data){
					if (!data.error) {
						callback(data);
					} else {
						console.log(data.error);
					}
				}
			});	
		},
		"getPlayer": function(playerId, callback) {
			$.ajax({
				type: "GET",
				url: "http://service.fantasy.ohdada.com/player/" + playerId + "/",
				data: param,
				dataType: "json",
				success: function (data) {
					if (!data.error) {
						callback(data);
					} else {
						console.log(data.error);
					}
				},
				error: function(data){
					console.log(data);
				}
			});
		},
		"trade": function() {
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
					if (!j.error) {
						//do something
					}
				}
			});
		},
		"createMyTeam": function() {
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
	};
}