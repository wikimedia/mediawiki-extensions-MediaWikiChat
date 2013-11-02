var last = '0';
var users = [];
var global;
var me;
var now;

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

function safe(string){
	var nochars = ['/', '(', ')', '[', ']', '{', '}', '.', '*', '+', '?', '^', '=', '!', ':', '$', '|', ' '];

	nochars.forEach(function(char){
		var patt = new RegExp('\\'+char, 'g');
		string = string.replace(patt, '');
		//console.log('/\\'+char+'/g');
	});
	return string;
}

function unique(array) {
    var a = array.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }
    return a;
};

function timestampFromDate(date){
	var y = pad(date.getUTCFullYear(), 4);
    var m = pad(date.getUTCMonth() + 1, 2);
    var d = pad(date.getUTCDate(), 2);
    var h = pad(date.getUTCHours(), 2);
    var i = pad(date.getUTCMinutes(), 2);;
    var s = pad(date.getUTCSeconds(), 2);
	return y+m+d+h+i+s;
}

function realTimestamp(timestamp){
	var d = new Date();
	
	d.setTime(timestamp * 10);
	
	switch(d.getMonth()){
	case 0:
		var month = 'January';
		break;
	case 1:
		var month = 'February';
		break;
	case 2:
		var month = 'March';
		break;
	case 3:
		var month = 'April';
		break;
	case 4:
		var month = 'May';
		break;
	case 5:
		var month = 'Jun';
		break;
	case 6:
		var month = 'July';
		break;
	case 7:
		var month = 'August';
		break;
	case 8:
		var month = 'September';
		break;
	case 9:
		var month = 'October';
		break;
	case 10:
		var month = 'November';
		break;
	case 11:
		var month = 'December';
		break;
	}
	
	var date = d.getDate();
	var hours = pad(d.getHours(), 2);
	var mins = pad(d.getMinutes(), 2);
	
	return month + " " + date + ", " + hours + ":" + mins;
}

function prettyTimestamp(timestamp){
	var dateThen = new Date();
	dateThen.setTime(timestamp * 10);
	var dayThen = dateThen.getDate();
	
	var dateNow = new Date();
	var tsNow = parseInt(dateNow.getTime() / 10);
	var dayNow = dateNow.getDate();
	
	var diff = (tsNow - timestamp) / 100;
	
	if(diff < 30){//30
		return "just now";
	} else if(diff < 90){//130
		return "a minute ago";
	} else if(diff < 3*60){//300
		return "2 minutes ago";
	} else if(diff < 7*60){//700
		return "5 minutes ago";
	} else if(diff < 12*60){//1200
		return "10 minutes ago";
	} else if(diff < 17*60){//1700
		return "quarter of an hour ago";
	} else if(diff < 23*60){//2500
		return "20 minutes ago"
	} else if(diff < 27*60){//2500
		return "25 minutes ago"
	} else if(diff < 37*60){//3700
		return "half an hour ago";
	} else if(diff < 50*60){//5000
		return "45 minutes ago";
	} else if(diff < 90*60){//13000
		return "an hour ago";
	} else {
		
		if(dayNow == dayThen){
			console.log(dateThen);
			return pad(dateThen.getHours(), 2) + ":" + pad(dateThen.getMinutes(), 2);
			
		} else {
			if(dayNow == dayThen + 1){ //@TODO handle 31s
				return "yesterday, " + pad(dateThen.getHours(), 2) + ":" + pad(dateThen.getMinutes(), 2);
				
			} else {
				switch(dateThen.getDay()){
				case 0:
					var day = 'sunday, ';
					break;
				case 1:
					var day = 'monday, ';
					break;
				case 2:
					var day = 'tuesday, ';
					break;
				case 3:
					var day = 'wednesday, ';
					break;
				case 4:
					var day = 'thursday, ';
					break;
				case 5:
					var day = 'friday, ';
					break;
				case 6:
					var day = 'saturday, ';
					break;
				}
				return day + pad(dateThen.getHours(), 2) + ":" + pad(dateThen.getMinutes(), 2);
			}
		}
	}
}

function redoTimestamps(){
	$.each($(".mwchat-item-timestamp.pretty"), function(index, item){

		var timestamp = $(this).attr('data-timestamp');
		var oldpretty = $(this).html();
		var newpretty = prettyTimestamp(timestamp);
		if( oldpretty != newpretty){
			$(this).fadeOut(250, function(){
				$(this).html(newpretty);
				$(this).fadeIn(250);
			});
		}
	});
}

function htmlTimestamp(timestamp){
	var html = "<span class='mwchat-item-timestamp-container'>";
	html += "<span class='mwchat-item-timestamp pretty' data-timestamp='" + timestamp + "'>";
	html += prettyTimestamp(timestamp);
	html += "</span><span class='mwchat-item-timestamp real' style='display:none;'>";
	html += realTimestamp(timestamp);
	html += "</span></span>";
	return html;
}

var obj2;
var user2;

function getNew(called){
	
	sajax_do_call(
		"getNew",
		[],
		function(request){
			console.log('called from ' + called + ' at ' + new Date().getTime() );
    		console.log(request);

			var data = JSON.parse(request.response);
			
			console.log(data);
			global = data;
			
			if('messages' in data){
			
				data['messages'].forEach(function(obj){
				addMessage(obj['name'], obj['message'], data['users'][obj['name']][1], obj['timestamp']);
				});
			
				//$("#mwchat-content").animate({scrollTop: $(this).scrollHeight}, 1000)
				var div = $("#mwchat-content");
				//div.scrollTop = div.scrollHeight;
				//div.animate({'scrollTop': div.scrollHeight}, 1000);//{'scrollTop', div.scrollHeight}
				//var objDiv = document.getElementById("mwchat-content");
				var objDiv = $("#mwchat-content");
				//objDiv.scrollTop = objDiv.scrollHeight;
				objDiv.animate({'scrollTop': div[0].scrollHeight}, 1000);


			}
			
			if('online' in data){
				doUsers(data['online'], data);
			}
			
			if('pms' in data){
				data['pms'].forEach(function(obj){
					
					if(users.indexOf(obj['conv']) != -1){
						
						console.log("doing conv" + obj['conv']);
					
						var Ruser = obj['conv'];
						console.log(obj);
						obj2 = obj;
						//console.log(Ruser+"user1");
						//console.log(safe(Ruser)+"user2");
						addPrivateMessage(obj['from'], Ruser, obj['message'], data['users'][obj['from']][1], obj['timestamp']);
						console.log(obj);
						//console.log(Ruser+'safe(user)');
						var div = $("#" + safe(Ruser) + " .mwchat-useritem-content");
						//div.scrollTop = div.scrollHeight;
						//div.animate({'scrollTop': div.scrollHeight}, 1000);//{'scrollTop', div.scrollHeight}
						//var objDiv = document.getElementById("mwchat-content");
						var objDiv = $("#" + safe(Ruser) + " .mwchat-useritem-content");
						//objDiv.scrollTop = objDiv.scrollHeight;
						objDiv.animate({'scrollTop': div[0].scrollHeight}, 1000);
					
					}
				});
			}
			
			if('me' in data){
				me = data['me'];
			}
			
			if('kick' in data){
				$("#mwchat-type input").attr('disabled', 'disabled');
				$("#mwchat-users div input").attr('disabled', 'disabled');
				clearInterval(newInterval);
			}
			
			if('system' in data){
				data['system'].forEach(function(obj){
					switch(obj['type']){
						case 'kick':
							showKickMessage(obj['from'], obj['to'], obj['timestamp'])
							break;
						case 'block':
							showBlockMessage(obj['to'], obj['timestamp']);
							break;
						case 'unblock':
							showUnblockMessage(obj['to'], obj['timestamp']);
							break;
					}
				});
			}
			
			addMe(data);
			
			now = data['now'];
		}
	);
	var date = new Date();
	last = timestampFromDate(date);
}

function showKickMessage(from, to, timestamp){
	if(to == me){
		var message = "You have been kicked by " + from + ". Refresh the page to chat";
	} else if(from == me){
		var message = "You kicked " + to;
	} else {
		var message = from + " kicked " + to;
	}
	addSystemMessage(message, timestamp);
}

function showBlockMessage(to, timestamp){
	if(to == me){
		var message = "You have been blocked <a href=''>(details)</a>";
	} else {
		var message = to + " has been blocked <a href=''>(details)</a>";
	}
	addSystemMessage(message, timestamp);
}

function showUnblockMessage(to, timestamp){
	var message = to + " has been unblocked <a href=''>(details)</a>";
	
	addSystemMessage(message, timestamp);
}

function addSystemMessage(text, timestamp){

	var html = "<tr class='mwchat-message system'>";
	html += "<td colspan=3 class='mwchat-item-messagecell'><span class='mwchat-item-message'>";
	html += text;
	html += "</span>";
	html += htmlTimestamp(timestamp);
	html += "</td></tr>";
	
	addGeneralMessage(html, timestamp);
}

function addMessage(user, message, url, timestamp){
	
	var html = "<tr class='mwchat-message'>";
	html += "<td class='mwchat-item-user'>";
	html += user;
	html += "</td>";
	html += "<td class='mwchat-item-avatar'><img src='";
	html += url;
	html += "' /></td>";
	html += "<td class='mwchat-item-messagecell'><span class='mwchat-item-message'>";
	html += message;
	html += "</span>";
	html += htmlTimestamp(timestamp);
	html += "</td></tr>";

	addGeneralMessage(html, timestamp);
}

function addGeneralMessage(html, timestamp){
	var post = true;
	
	$(".mwchat-item-timestamp.pretty").each(function(index, value){
		console.log($(value).attr("data-timestamp") + " =? " + timestamp);
		if($(value).attr("data-timestamp") == timestamp){
			console.log("==");
			post = false;
		}
	});
	
	if( post ){
		$("#mwchat-table").append(html);
	} else {
		console.log("message not posted");
	}
	setupTimestampHover();
}

var selector;
var ghtml;
var guser

function addPrivateMessage(user, convwith, message, url, timestamp){
	console.log("addPM");
	var userE = safe(user);
	var convwithE = safe(convwith);
	
	var html = "<div class='mwchat-message'>";
	html += "<img src='";
	html += url;
	html += "' alt='" + user + "' name='" + user + "' title='" + user + "' />";
	html += "<span class='mwchat-item-message'>";
	html += message;
	html += "</span>";
	html += htmlTimestamp(timestamp);
	html += "</div>";
	
	$("#" + convwithE + " .mwchat-useritem-content").append(html);
	console.log("appending html to:");
	console.log($("#" + convwithE + " .mwchat-useritem-content"));
	console.log(html);
	ghtml = html
	selector = $("#" + convwithE + " .mwchat-useritem-content");
	guser = convwithE;
	
	if(user != me){	
		$("#" + convwithE).attr('data-read', 'true');
	}
}

var amIMod;

function doUsers(newusers, data){
	var allusers = users.concat(newusers);
	allusers = unique(allusers);
	
	amIMod = data['amIMod'];
	
	allusers.forEach(function(user){
		if( newusers.indexOf(user) == -1){
			removeUser(user);
		}  else if( newusers.indexOf(user) != -1 && users.indexOf(user) == -1 ){
			var mod = false;
			if(data['mods'].indexOf(user) != -1){
				mod = true;
			}
			addUser(user, data['users'][user][1], data['users'][user][0], mod);
		}
	})
	
	users = newusers;
}

function addUser(user, url, id, mod){
	var userE = safe(user);
	
	var add = true;
	
	$.each($("#mwchat-users div"), (function(index, item){
		
			if( item.id == user ){
				add = false;
			}
		})
	);
	if( add ){
		console.log("adding " + user);
		
		var html = "<div class='mwchat-useritem noshow' data-unread='' data-name='" + user + "' data-id='" + id + "' id='" + userE + "'><img src='";
		html += url;
		html += "' /><span class='mwchat-useritem-user'>";
		html += user;
		html += "</span>";
		if(amIMod){
			html += "<a class='mwchat-useritem-blocklink' href='" + wgArticlePath.replace('$1', 'Special:UserRights/'+user) + "' target='_blank'>block</a>";
			html += "<a class='mwchat-useritem-kicklink' href='javascript:;'>kick</a>";
		}
		if(mod){
			html += "<img src='http://meta.brickimedia.org/images/thumb/c/cb/Golden-minifigure.png/16px-Golden-minifigure.png' height='16px' alt='mod' title='This user is a moderator' />";
		}
		html += "<div class='mwchat-useritem-window' style='display:none;'>";
		html += "<div class='mwchat-useritem-content'></div>";
		html += "<input type='text' placeholder='Type your private message' />"
		html += "</div>";
		html += "</div>";

		$("#mwchat-users").append(html);
		$("#mwchat-users #" + userE).fadeIn();
		$("#mwchat-users #" + userE).click(clickUser);

		$("#mwchat-users #" + userE + " input").keypress(userKeypress);
		
		setupKickLinks();
	}
}

function removeUser(user){
	var userE = safe(user);
	
	$("#mwchat-users #" + userE).remove();
}

function clickUser(e){
	$(this).attr('data-read', '');	
	
	if($(this).hasClass('noshow')){
		$(this).children('.mwchat-useritem-window').slideDown();
	
		$(".mwchat-useritem.show .mwchat-useritem-window").slideUp();
		$(".mwchat-useritem.show").toggleClass('show noshow');
	
		$(this).toggleClass('show noshow');
	}

}

$($("#mwchat-type input")[0]).keypress(function(e) {

    if(e.which == 13 && e.shiftKey) {
    	
    	return false;
    	
    } else if(e.which == 13) {
		
		console.log('sendM');
        
        sajax_do_call(
        	"sendMessage",
        	[$("#mwchat-type input")[0].value],
        	function(request){
                getNew('main input keypress');
                window.clearInterval(newInterval);
                newInterval = setInterval(getNew, interval);
        	}
        );
        
        $("#mwchat-type input").val('');
    }
});

function userKeypress(e) {
	$(this).parents('.mwchat-useritem').attr('data-read', '');
	
    if(e.which == 13) {
    	
    	var toname = $(this).parents('.mwchat-useritem').attr('data-name');
    	var toid = $(this).parents('.mwchat-useritem').attr('data-id');
        
        sajax_do_call(
        	"sendPM",
        	[$(this)[0].value, toname, toid],
        	function(request){
        		console.log(request);
                getNew('user keypress');
                window.clearInterval(newInterval);
                newInterval = setInterval(getNew, interval);
        	}
        );
    	$(this).val('');
    }
}

function setupKickLinks(){
	$(".mwchat-useritem-kicklink").click(function(){
		var parent = $(this).parent();
			
		sajax_do_call(
				"kick",
				[parent.attr('data-name'), parent.attr('data-id')],
        		function(request){
					getNew('kick');
				}
		);
	});
}

var amI = false;

function addMe(data){
	if(!amI){
		console.log("adding me");
		console.log(data);
		$("#mwchat-me span").html(data['me']);
		
		$("#mwchat-me img").attr('src', data['users'][data['me']][1]);
		
		if(data['mods'].indexOf(data['me']) != -1){
			$("#mwchat-me").append("<img src='http://meta.brickimedia.org/images/c/cb/Golden-minifigure.png' height='20px' alt='moderator' title='This user is a moderator' />");
		}
		
		amI = true;
	}
}

function setupTimestampHover(){
	$(".mwchat-message").hover(function(){
		$(this).find(".pretty").hide();
		$(this).find(".real").show();
	}, function(){
		$(this).find(".real").hide();
		$(this).find(".pretty").show();
	});
}
getNew('starter');

interval = 25000;

setTimeout(getNew, 2500);

var newInterval = setInterval(getNew, interval);
var redoInterval = setInterval(redoTimestamps, interval/2);

