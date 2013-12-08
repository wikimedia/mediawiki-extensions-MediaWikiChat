/* global $, mw, sajax_do_call */
var MediaWikiChat = {
	users: [],
	amIMod: false,
	amI: false,
	firstTime: true,
	interval: 15000,
	newInterval: null,
	redoInterval: null,
	userData: [],

	pad: function( num, size ) {
		var s = num + '';
		while ( s.length < size ) {
			s = '0' + s;
		}
		return s;
	},

	safe: function( string ) {
		var nochars = [
			'/', '(', ')', '[', ']', '{', '}', '.', '*', '+', '?', '^', '=',
			'!', ':', '$', '|', ' '
		];

		nochars.forEach( function( character ) {
			var patt = new RegExp( '\\' + character, 'g' );
			string = string.replace( patt, '' );
		});

		return string;
	},

	unique: function( array ) {
		var a = array.concat();

		for ( var i = 0; i < a.length; ++i ) {
			for ( var j = i + 1; j < a.length; ++j ) {
				if ( a[i] === a[j] ) {
					a.splice( j--, 1 );
				}
			}
		}

		return a;
	},

	timestampFromDate: function( date ) {
		var y = MediaWikiChat.pad( date.getUTCFullYear(), 4 );
		var m = MediaWikiChat.pad( date.getUTCMonth() + 1, 2 );
		var d = MediaWikiChat.pad( date.getUTCDate(), 2 );
		var h = MediaWikiChat.pad( date.getUTCHours(), 2 );
		var i = MediaWikiChat.pad( date.getUTCMinutes(), 2 );
		var s = MediaWikiChat.pad( date.getUTCSeconds(), 2 );
		return y + m + d + h + i + s;
	},

	realTimestamp: function( timestamp ) {
		var d = new Date(), month = '';

		d.setTime( timestamp * 10 );

		switch ( d.getMonth() ) {
			case 0:
				month = 'January';
				break;
			case 1:
				month = 'February';
				break;
			case 2:
				month = 'March';
				break;
			case 3:
				month = 'April';
				break;
			case 4:
				month = 'May';
				break;
			case 5:
				month = 'June';
				break;
			case 6:
				month = 'July';
				break;
			case 7:
				month = 'August';
				break;
			case 8:
				month = 'September';
				break;
			case 9:
				month = 'October';
				break;
			case 10:
				month = 'November';
				break;
			case 11:
				month = 'December';
				break;
		}

		var date = d.getDate();
		var hours = MediaWikiChat.pad( d.getHours(), 2 );
		var mins = MediaWikiChat.pad( d.getMinutes(), 2 );

		return month + ' ' + date + ', ' + hours + ':' + mins;
	},

	prettyTimestamp: function( timestamp ) {
		var dateThen = new Date();
		dateThen.setTime( timestamp * 10 );
		var dayThen = dateThen.getDate();

		var dateNow = new Date();
		var tsNow = parseInt( dateNow.getTime() / 10 );
		var dayNow = dateNow.getDate();

		var diff = ( tsNow - timestamp ) / 100;

		if ( diff < 30 ) { // 30
			return 'just now';
		} else if ( diff < 90 ) { // 130
			return 'a minute ago';
		} else if ( diff < 3 * 60 ) { // 300
			return '2 minutes ago';
		} else if ( diff < 7 * 60 ) { // 700
			return '5 minutes ago';
		} else if ( diff < 12 * 60 ) { // 1200
			return '10 minutes ago';
		} else if ( diff < 17 * 60 ) { // 1700
			return 'quarter of an hour ago';
		} else if ( diff < 23 * 60 ) { // 2500
			return '20 minutes ago';
		} else if ( diff < 27 * 60 ) { // 2500
			return '25 minutes ago';
		} else if ( diff < 37 * 60 ) { // 3700
			return 'half an hour ago';
		} else if ( diff < 50 * 60 ) { // 5000
			return '45 minutes ago';
		} else if ( diff < 90 * 60 ) { // 13000
			return 'an hour ago';
		} else {
			if ( dayNow == dayThen ) {
				return MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
			} else {
				if ( dayNow == dayThen + 1 ) { // @TODO handle 31s
					return 'yesterday, ' + MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
				} else {
					var day;
					switch ( dateThen.getDay() ) {
						case 0:
							day = 'sunday, ';
							break;
						case 1:
							day = 'monday, ';
							break;
						case 2:
							day = 'tuesday, ';
							break;
						case 3:
							day = 'wednesday, ';
							break;
						case 4:
							day = 'thursday, ';
							break;
						case 5:
							day = 'friday, ';
							break;
						case 6:
							day = 'saturday, ';
							break;
					}
					return day +
						MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' +
						MediaWikiChat.pad( dateThen.getMinutes(), 2 );
				}
			}
		}
	},

	redoTimestamps: function() {
		$.each( $( '.mwchat-item-timestamp.pretty' ), function( index, item ) {
			var timestamp = $( this ).attr( 'data-timestamp' );
			var oldPretty = $( this ).html();
			var newPretty = MediaWikiChat.prettyTimestamp( timestamp );
			if ( oldPretty != newPretty ) {
				$( this ).fadeOut( 250, function() {
					$( this ).html( newPretty );
					$( this ).fadeIn( 250 );
				});
			}
		});
	},

	htmlTimestamp: function( timestamp ) {
		var html = '<span class="mwchat-item-timestamp-container">';
		html += '<span class="mwchat-item-timestamp pretty" data-timestamp="' + timestamp + '">';
		html += MediaWikiChat.prettyTimestamp( timestamp );
		html += '</span><span class="mwchat-item-timestamp real" style="display:none;">';
		html += MediaWikiChat.realTimestamp( timestamp );
		html += '</span></span>';
		return html;
	},

	getNew: function( called ) {
		$.ajax({
			url: mw.config.get( 'wgScriptPath' ) + '/api.php',
			data: { 'action': 'chatgetnew', 'format': 'json' },
		})
		.done( function( response ) {

			console.log( response );

			var data = response.chatgetnew;

			console.log(data);
			MediaWikiChat.global = data;

			var onlineUsers = [];

			for ( var userId in data.users ) {
				var obj = data.users[userId];
				MediaWikiChat.userData[userId] = { 'name': obj.name, 'avatar': obj.avatar };
				if ( obj.mod ) {
					MediaWikiChat.userData[userId].mod = true;
				}
				if ( obj.online ) {
					onlineUsers[onlineUsers.length] = userId;
				}
			}

			MediaWikiChat.doUsers( onlineUsers );

			for ( var timestamp in data.messages ) {
				var obj = data.messages[timestamp];
				MediaWikiChat.addMessage(
					obj.from,
					obj['*'],
					timestamp
				);
			}

			for ( var timestamp in data.pms ) {
				var obj = data.pms[timestamp];

				var convWith = obj.conv;
				console.log( convWith );

				MediaWikiChat.addPrivateMessage(
					obj.from,
					convWith,
					obj['*'],
					timestamp
				);
				var div = $( '#' + MediaWikiChat.safe( convWith ) + ' .mwchat-useritem-content' );
				var objDiv = $( '#' + MediaWikiChat.safe( convWith ) + ' .mwchat-useritem-content' );
				objDiv.animate( { 'scrollTop': div[0].scrollHeight }, 1000 );
			}

			for ( var timestamp in data.kicks ) {
				var obj = data.kicks[timestamp];
				MediaWikiChat.showKickMessage( obj.from, obj.to, timestamp );
			}
			for ( var timestamp in data.blocks ) {
				var obj = data.blocks[timestamp];
				MediaWikiChat.showBlockMessage( obj.from, obj.to, timestamp );
			}
			for ( var timestamp in data.unblocks ) {
				var obj = data.unblocks[timestamp];
				MediaWikiChat.showUnblockMessage( obj.from, obj.to, timestamp );
			}

			if ( data.kick ) {
				$( '#mwchat-type input' ).attr( 'disabled', 'disabled' );
				$( '#mwchat-users div input' ).attr( 'disabled', 'disabled' );
				clearInterval( MediaWikiChat.newInterval );
				MediaWikiChat.getNew();
			}

			MediaWikiChat.amIMod = data.users[wgUserId].mod;

			var div = $( '#mwchat-content' );
			var objDiv = $( '#mwchat-content' );
			objDiv.animate( { 'scrollTop': div[0].scrollHeight }, 1000 );

			MediaWikiChat.addMe();

		});
	},

	showKickMessage: function( from, to, timestamp ) {
		var message;
		if ( to == wgUserName ) {
			message = 'You have been kicked by ' + from + '. Refresh the page to chat';
		} else if ( from == wgUserName ) {
			message = 'You kicked ' + to;
		} else {
			message = from + ' kicked ' + to;
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	showBlockMessage: function( to, timestamp ) {
		var message;
		if ( to == wgUserName ) {
			message = 'You have been blocked <a href="">(details)</a>';
		} else {
			message = to + ' has been blocked <a href="">(details)</a>';
		}

		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	showUnblockMessage: function( to, timestamp ) {
		var message = to + ' has been unblocked <a href="">(details)</a>';

		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	addSystemMessage: function( text, timestamp ) {
		var html = '<tr class="mwchat-message system">';
		html += '<td colspan="3" class="mwchat-item-messagecell"><span class="mwchat-item-message">';
		html += text;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</td></tr>';

		MediaWikiChat.addGeneralMessage( html, timestamp );
	},

	addMessage: function( userId, message, timestamp ) {
		var user = MediaWikiChat.userData[userId];
		var html = '<tr class="mwchat-message">';
		html += '<td class="mwchat-item-user">';
		html += user.name;
		html += '</td>';
		html += '<td class="mwchat-item-avatar"><img src="' + user.avatar + '" /></td>';
		html += '<td class="mwchat-item-messagecell"><span class="mwchat-item-message">';
		html += message;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</td></tr>';

		MediaWikiChat.addGeneralMessage( html, timestamp );
	},

	addGeneralMessage: function( html, timestamp ) {
		var post = true;

		$( '.mwchat-item-timestamp.pretty' ).each( function( index, value ) {
			//console.log($(value).attr("data-timestamp") + " =? " + timestamp);
			if ( $( value ).attr( 'data-timestamp' ) == timestamp ) {
				//console.log("==");
				post = false;
			}
		});

		if ( post ) {
			$( '#mwchat-table' ).append( html );
		} else {
			console.log( 'message not posted' );
		}

		MediaWikiChat.setupTimestampHover();
	},

	addPrivateMessage: function( userId, convwith, message, timestamp ) {
		//console.log("addPM");
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );
		var convwithE = MediaWikiChat.safe( convwith );

		var html = '<div class="mwchat-message">';
		html += '<img src="' + user.avatar + '" alt="' + user.name + '" name="' + user.name + '" title="' + user.name + '" />';
		html += '<span class="mwchat-item-message">';
		html += message;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</div>';

		$( '#' + convwithE + ' .mwchat-useritem-content' ).append( html );

		if ( user.name != wgUserName ) {
			$( '#' + convwithE ).attr( 'data-read', 'true' );
		}
	},

	doUsers: function( newusers ) {
		var allusers = MediaWikiChat.users.concat( newusers );
		allusers = MediaWikiChat.unique( allusers );

		allusers.forEach( function( userId ) {
			if ( newusers.indexOf( userId ) == -1 ) {
				MediaWikiChat.removeUser( userId );
			} else if ( newusers.indexOf( userId ) != -1 && MediaWikiChat.users.indexOf( userId ) == -1 ) {
				MediaWikiChat.addUser(
					userId,
					MediaWikiChat.firstTime
				);
			}
		});

		if ( allusers.length ){
			$("#mwchat-no-other-users").hide();
		} else {
			$("#mwchat-no-other-users").show();
		}

		MediaWikiChat.users = newusers;
		MediaWikiChat.firstTime = false;
	},

	addUser: function( userId, firstTime ) {
		var user = MediaWikiChat.userData[userId];
		console.log( userId );

		var userE = MediaWikiChat.safe( user.name );

		var add = true;

		$.each( $( '#mwchat-users div' ), ( function( index, item ) {
			if ( item.id == user.userE ) {
				add = false;
			}
		})
		);

		if ( add ) {

			var html = '<div class="mwchat-useritem noshow" data-unread="" data-name="' + user.name + '" data-id="' + userId + '" id="' + userE + '"><img src="';
			html += user.avatar;
			html += '" /><span class="mwchat-useritem-user">';
			html += user.name;
			html += '</span>';
			if ( MediaWikiChat.amIMod ) {
				html += '<a class="mwchat-useritem-blocklink" href="' + mw.config.get( 'wgArticlePath' ).replace( '$1', 'Special:UserRights/' + user.name ) + '" target="_blank">block</a>';
			}
			if ( user.mod ) {
				html += '<img src="http://meta.brickimedia.org/images/thumb/c/cb/Golden-minifigure.png/16px-Golden-minifigure.png" height="16px" alt="mod" title="This user is a moderator" />';
			}

			html += ' <span class="mwchat-useritem-pmlink" style="display:none">(private message)</span>';

			if ( !user.mod ) {
				html += '<a class="mwchat-useritem-kicklink" href="javascript:;">kick</a>';
			}
			html += '<div class="mwchat-useritem-window" style="display:none;">';
			html += '<div class="mwchat-useritem-content"></div>';
			html += '<input type="text" placeholder="Type your private message" />';
			html += '</div>';
			html += '</div>';

			$( '#mwchat-users' ).append( html );
			$( '#mwchat-users #' + userE ).fadeIn();
			$( '#mwchat-users #' + userE ).click( MediaWikiChat.clickUser );

			$( '#mwchat-users #' + userE + ' input' ).keypress( MediaWikiChat.userKeypress );

			MediaWikiChat.setupUserLinks();

			if ( !firstTime ) {
				var date = new Date();
				var timestamp = MediaWikiChat.timestampFromDate( date );
				MediaWikiChat.addSystemMessage( user.name + ' has joined the chat', timestamp );
			}
		}
	},

	removeUser: function( userId ) {
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );

		$( '#mwchat-users #' + userE ).remove();

		var date = new Date();
		var timestamp = MediaWikiChat.timestampFromDate( date );
		MediaWikiChat.addSystemMessage( user.name + ' has left the chat', timestamp );
	},

	clickUser: function( e ) {
		$( this ).attr( 'data-read', '' );

		if ( $( this ).hasClass( 'noshow' ) ) {
			$( this ).children( '.mwchat-useritem-window' ).slideDown();

			$( '.mwchat-useritem.show .mwchat-useritem-window' ).slideUp();
			$( '.mwchat-useritem.show' ).toggleClass( 'show noshow' );

			$( this ).toggleClass( 'show noshow' );
		}
	},

	userKeypress: function( e ) {
		$( this ).parents( '.mwchat-useritem' ).attr( 'data-read', '' );

		if ( e.which == 13 ) {
			var toid = $( this ).parents( '.mwchat-useritem' ).attr( 'data-id' );

			$.ajax({
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatsendpm', 'message': $( this )[0].value, 'id': toid, 'format': 'json' },
			})
			.done( function( response ) {
				console.log( response );
				MediaWikiChat.getNew( 'user keypress' );
				window.clearInterval( MediaWikiChat.newInterval );
				MediaWikiChat.newInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );
			});

			$( this ).val( '' );
		}
	},

	setupUserLinks: function() {
		$( '.mwchat-useritem-kicklink' ).click( function() {
			console.log( 'click' );
			var parent = $( this ).parent();

			$.ajax({
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatkick', 'id': parent.attr( 'data-id' ), 'format': 'json' },
			})
			.done( function() {
				console.log( 'return' );
				MediaWikiChat.getNew( 'kick' );
			});
		} );

		$( '.mwchat-useritem' ).hover( function() {
			$( this ).find( '.mwchat-useritem-pmlink' ).fadeIn(200);
		}, function() {
			$( this ).find( '.mwchat-useritem-pmlink' ).fadeOut(200);
		} );
	},

	addMe: function() {
		if ( !MediaWikiChat.amI ) {

			var me = MediaWikiChat.userData[wgUserId];

			$( '#mwchat-me span' ).html( me.name );
			$( '#mwchat-me img' ).attr( 'src', me.avatar );

			if ( me.mod ) {
				$( '#mwchat-me' ).append(
					'<img src="http://meta.brickimedia.org/images/c/cb/Golden-minifigure.png" height="20px" alt="moderator" title="This user is a moderator" />'
				);
			}
			MediaWikiChat.amI = true;
		}
	},

	setupTimestampHover: function() {
		$( '.mwchat-message' ).hover( function() {
			$( this ).find( '.pretty' ).hide();
			$( this ).find( '.real' ).show();
		}, function() {
			$( this ).find( '.real' ).hide();
			$( this ).find( '.pretty' ).show();
		});
	}
};

$( document ).ready( function() {
	$( $( '#mwchat-type input' )[0] ).keypress( function( e ) {
		if ( e.which == 13 && e.shiftKey ) {
			return false;
		} else if ( e.which == 13 ) {
			$.ajax({
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatsend', 'message': $( '#mwchat-type input' )[0].value, 'format': 'json' },
			})
			.done( function( response ) {
				console.log( response );
				MediaWikiChat.getNew( 'main input keypress' );
				window.clearInterval( MediaWikiChat.newInterval );
				MediaWikiChat.newInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );
			});

			$( '#mwchat-type input' ).val( '' );
		}
	});

	MediaWikiChat.getNew( 'starter' );

	setTimeout( MediaWikiChat.getNew, 2500 );

	MediaWikiChat.newInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );
	MediaWikiChat.redoInterval = setInterval( MediaWikiChat.redoTimestamps, MediaWikiChat.interval / 2 );
} );
