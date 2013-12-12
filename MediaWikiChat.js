/* global $, mw, sajax_do_call */
var MediaWikiChat = {
	users: [],
	amIMod: false,
	amI: false,
	firstTime: true,
	interval: 10000,
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
		return string.replace( /\(\)\/\\\[\]\{\}\.\*\+\?\^=!:$\| /g, '' );
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

	now: function() {
		return Math.round( new Date().getTime() / 10 ); // we need it in 10 millisecond sizes
	},

	realTimestamp: function( timestamp ) {
		var d = new Date(), month = '';

		d.setTime( timestamp * 10 );

		var months = [ 'january', 'february', 'march', 'april', 'may', 'june',
			'july', 'august', 'september', 'october', 'november', 'december' ];
		var month = mw.message( months[d.getMonth()] ).text();

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

		if ( diff < 30 ) {
			return mw.message( 'chat-just-now' ).text();
		} else if ( diff < 90 ) {
			return mw.message( 'chat-a-minute-ago' ).text();
		} else if ( diff < 3 * 60 ) {
			return mw.message( 'chat-minutes-ago', 2 ).text();
		} else if ( diff < 7 * 60 ) {
			return mw.message( 'chat-minutes-ago', 5 ).text();
		} else if ( diff < 12 * 60 ) {
			return mw.message( 'chat-minutes-ago', 10 ).text();
		} else if ( diff < 17 * 60 ) {
			return mw.message( 'chat-quarter-of-an-hour-ago' ).text();
		} else if ( diff < 23 * 60 ) {
			return mw.message( 'chat-minutes-ago', 20 ).text();
		} else if ( diff < 27 * 60 ) {
			return mw.message( 'chat-minutes-ago', 25 ).text();
		} else if ( diff < 33 * 60 ) {
			return mw.message( 'chat-half-an-hour-ago' ).text();
		} else if ( diff < 37 * 60 ) {
			return mw.message( 'chat-minutes-ago', 35 ).text();
		} else if ( diff < 42 * 60 ) {
			return mw.message( 'chat-minutes-ago', 40 ).text();
		} else if ( diff < 47 * 60 ) {
			return mw.message( 'chat-minutes-ago', 45 ).text();
		} else if ( diff < 52 * 60 ) {
			return mw.message( 'chat-minutes-ago', 50 ).text();
		} else if ( diff < 57 * 60 ) {
			return mw.message( 'chat-minutes-ago', 55 ).text();
		} else if ( diff < 90 * 60 ) {
			return mw.message( 'chat-an-hour-ago' ).text();
		} else {
			if ( dayNow == dayThen ) {
				return MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
			} else {
				if ( dayNow == dayThen + 1 ) { // @TODO handle 31s
					return mw.message( 'chat-yesterday' ).text().toLowerCase() + ', ' + MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
				} else {
					var day;
					var days = [ 'sunday', 'monday', 'tuesday', 'wednesday',
						'thursday', 'friday', 'saturday', 'sunday' ];
					day = mw.message( days[dateThen.getDay()] ).text().toLowerCase();
					return day + ', ' +
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
			var data = response.chatgetnew;

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

			MediaWikiChat.amIMod = data.users[wgUserId].mod;

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

			if ( data.messages || data.kicks || data.blocks || data.unblocks ) {
				MediaWikiChat.scrollToBottom();
			}
			MediaWikiChat.addMe();

		});
	},

	scrollToBottom: function() {
		var div = $( '#mwchat-content' );
		div.animate( { 'scrollTop': div[0].scrollHeight }, 1000 );
	},

	showKickMessage: function( from, to, timestamp ) {
		var message;
		if ( to == wgUserName ) {
			message = mw.message( 'chat-youve-been-kicked', from ).text();
		} else if ( from == wgUserName ) {
			message = mw.message( 'chat-you-kicked', to ).text();
		} else {
			message = mw.message( 'chat-kicked', from, to ).text();
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	showBlockMessage: function( from, to, timestamp ) {
		var message;
		if ( to == wgUserName ) {
			message = mw.message( 'chat-youve-been-blocked', from ).text();
			$( '#mwchat-type input' ).attr( 'disabled', 'disabled' );
			$( '#mwchat-users div input' ).attr( 'disabled', 'disabled' );
		} else if ( from == wgUserName ) {
			message = mw.message( 'chat-you-blocked', to ).text();
		} else {
			message = mw.message( 'chat-blocked', from, to ).text();
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	showUnblockMessage: function( from, to, timestamp ) {
		if ( from == wgUserName ) {
			var message = 'You unblocked ' + to;
		} else {
			var message = from + ' unblocked ' + to;
		}

		MediaWikiChat.addSystemMessage( message, timestamp );
		$( '#mwchat-type input' ).attr( 'disabled', '' );
		$( '#mwchat-users div input' ).attr( 'disabled', '' );
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
		html += '<td class="mwchat-item-avatar">';
		if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
			html += '<img src="' + user.avatar + '" /></td>';
		}
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
			if ( $( value ).attr( 'data-timestamp' ) == timestamp ) {
				post = false;
			}
		});

		if ( post ) {
			$( '#mwchat-table' ).append( html );
		}

		MediaWikiChat.setupTimestampHover();
	},

	addPrivateMessage: function( userId, convwith, message, timestamp ) {
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );
		var convwithE = MediaWikiChat.safe( convwith );

		var html = '<div class="mwchat-message">';
		if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
			html += '<img src="' + user.avatar + '" alt="' + user.name + '" name="' + user.name + '" title="' + user.name + '" />';
		}
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
		var userE = MediaWikiChat.safe( user.name );

		var add = true;

		$.each( $( '#mwchat-users div' ), ( function( index, item ) {
			if ( item.id == user.userE ) {
				add = false;
			}
		})
		);

		if ( add ) {

			var html = '<div class="mwchat-useritem noshow" data-unread="" data-name="' + user.name + '" data-id="' + userId + '" id="' + userE + '">';
			if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
				html += '<img src="' + user.avatar + '" />';
			}
			html += '<span class="mwchat-useritem-user">';
			html += user.name;
			html += '</span>';
			if ( MediaWikiChat.amIMod ) {
				html += '<a class="mwchat-useritem-blocklink" href="' + mw.config.get( 'wgArticlePath' ).replace( '$1', 'Special:UserRights/' + user.name );
				html += '" target="_blank">' + mw.message( 'chat-block' ).text() + '</a>';
			}
			if ( user.mod ) {
				html += '<img src="' + mw.message( 'chat-mod-image').escaped() + '" height="16px" alt="" title="';
				html += mw.message( 'user-is-a moderator' ).text() + '" />';
			}

			html += ' <span class="mwchat-useritem-pmlink" style="display:none">';
			html += mw.message( 'chat-private-message' ).text() + '</span>';

			if ( !user.mod && mw.config.get( 'wgChatKicks' ) ) {
				html += '<a class="mwchat-useritem-kicklink" href="javascript:;">';
				html += mw.message( 'chat-kick' ).text() + '</a>';
			}
			html += '<div class="mwchat-useritem-window" style="display:none;">';
			html += '<div class="mwchat-useritem-content"></div>';
			html += '<input type="text" placeholder="' + mw.message( 'chat-type-your-private-message' ).text() + '" />';
			html += '</div>';
			html += '</div>';

			$( '#mwchat-users' ).append( html );
			$( '#mwchat-users #' + userE ).fadeIn();
			$( '#mwchat-users #' + userE ).click( MediaWikiChat.clickUser );

			$( '#mwchat-users #' + userE + ' input' ).keypress( MediaWikiChat.userKeypress );

			MediaWikiChat.setupUserLinks();

			if ( !firstTime ) {
				MediaWikiChat.addSystemMessage( mw.message( 'chat-joined', user.name ).text(), MediaWikiChat.now() );
				MediaWikiChat.scrollToBottom();
			}
		}
	},

	removeUser: function( userId ) {
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );

		$( '#mwchat-users #' + userE ).remove();

		MediaWikiChat.addSystemMessage( mw.message( 'chat-left', user.name ).text(), MediaWikiChat.now() );
		MediaWikiChat.scrollToBottom();
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
				MediaWikiChat.getNew( 'user keypress' );
				window.clearInterval( MediaWikiChat.newInterval );
				MediaWikiChat.newInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );
			});

			$( this ).val( '' );
		}
	},

	setupUserLinks: function() {
		$( '.mwchat-useritem-kicklink' ).click( function() {
			var parent = $( this ).parent();

			$.ajax({
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatkick', 'id': parent.attr( 'data-id' ), 'format': 'json' },
			})
			.done( function() {
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
					'<img src="' + mw.message( 'chat-mod-image').escaped() + '" height="20px" alt="" title="' + mw.message( 'chat-you-are-moderator' ).text() + '" />'
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
