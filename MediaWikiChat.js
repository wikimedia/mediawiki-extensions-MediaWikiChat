/* global $, mw */
var MediaWikiChat = {
	users: [],
	amIMod: false,
	amI: false,
	firstTime: true,
	interval: 7000,
	pollInterval: null,
	redoInterval: null,
	userData: [],
	focussed: true,
	title: document.title,
	away: false,

	pad: function( num, size ) {
		var s = num + '';
		while ( s.length < size ) {
			s = '0' + s;
		}
		return s;
	},

	safe: function( string ) {
		return string.replace( /[^\w\s]|/g, '' ).replace( / /g, '' );
	},

	ie: function() {
		return navigator.appVersion.indexOf( 'MSIE' ) != -1;
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
		var messageDate = new Date();
		messageDate.setTime( timestamp * 10 );
		var nowDate = new Date();

		var time = '';

		if ( nowDate.getDate() == messageDate.getDate() && nowDate.getMonth() == messageDate.getMonth() ) {
			time += mw.message( 'chat-today' ).text();
		} else {
			var months = [ 'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december' ];
			time += mw.message( months[messageDate.getMonth()] ).text();
			time += ' ' + messageDate.getDate();
		}

		time += ', ' + MediaWikiChat.pad( messageDate.getHours(), 2 );
		time += ':' + MediaWikiChat.pad( messageDate.getMinutes(), 2 );

		return time;
	},

	prettyTimestamp: function( timestamp ) {
		var dateThen = new Date();
		dateThen.setTime( timestamp * 10 );
		var dayThen = dateThen.getDate();

		var dateNow = new Date();
		var tsNow = parseInt( dateNow.getTime() / 10, 10 );
		var dayNow = dateNow.getDate();

		var diff = ( tsNow - timestamp ) / 100;

		if ( diff < 30 ) {
			return mw.message( 'just-now' ).text();
		} else if ( diff < 2 * 60 ) {
			return mw.message( 'chat-a-minute-ago' ).text();
		} else if ( diff < 60 * 60 ) {
			return mw.message( 'minutes-ago', Math.floor( diff / 60 ) ).text();
		} else {
			if ( dayNow == dayThen ) {
				return MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
			} else {
				if ( dayNow == dayThen + 1 ) { // @TODO handle 31s
					return mw.message( 'chat-yesterday' ).text().toLowerCase() + ', ' + MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
				} else {
					var day;
					var days = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];
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
			item = $( item );
			var timestamp = item.attr( 'data-timestamp' );
			var oldPretty = item.html();
			var newPretty = MediaWikiChat.prettyTimestamp( timestamp );
			if ( oldPretty != newPretty ) {
				item.fadeOut( 250, function() {
					item.html( newPretty );
					item.fadeIn( 250 );
				} );
			}
		} );
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

	getNew: function() {
		var focussed = '';
		if ( MediaWikiChat.focussed ) {
			focussed = 'true';
		}
		$.ajax( {
			url: mw.config.get( 'wgScriptPath' ) + '/api.php',
			data: { 'action': 'chatgetnew', 'format': 'json', 'focussed': focussed },
			cache: false
		} ).done( MediaWikiChat.getNewReply );
	},

	getNewReply: function( response ) {
		var data = response.chatgetnew;

		var onlineUsers = [];

		for ( var userId in data.users ) {
			var user = data.users[userId];
			MediaWikiChat.userData[userId] = { 'name': user.name, 'avatar': user.avatar, 'gender': user.gender };
			if ( user.mod ) {
				MediaWikiChat.userData[userId].mod = true;
			}
			if ( user.online && user.id != mw.user.getId() ) {
				onlineUsers[onlineUsers.length] = userId;
				MediaWikiChat.userData[userId].away = user.away;
			}
		}

		if ( mw.user.getId() == 0 ) {
			MediaWikiChat.amIMod = false;
		} else {
			MediaWikiChat.amIMod = data.users[mw.user.getId()].mod;
		}

		MediaWikiChat.doUsers( onlineUsers );

		for ( var userId2 in data.users ) { // has to be done after doUsers
			var user2 = MediaWikiChat.userData[userId2];
			if ( user2.id != mw.user.getId() ) {
				var userEscaped = MediaWikiChat.safe( user2.name );
				MediaWikiChat.greyscale( $( '#mwchat-users #' + userEscaped ), user2.away );
			}
		}

		for ( var messageTimestamp in data.messages ) {
			var message = data.messages[messageTimestamp];
			MediaWikiChat.addMessage(
				message.from,
				message['*'],
				messageTimestamp
			);
		}

		for ( var pmTimestamp in data.pms ) {
			var pm = data.pms[pmTimestamp];

			MediaWikiChat.addPrivateMessage(
				pm.from,
				pm.conv,
				pm['*'],
				pmTimestamp
			);
			var div = $( '#' + MediaWikiChat.safe( pm.conv ) + ' .mwchat-useritem-content' );
			var objDiv = $( '#' + MediaWikiChat.safe( pm.conv ) + ' .mwchat-useritem-content' );
			objDiv.animate( { 'scrollTop': div[0].scrollHeight }, 1000 );
		}

		for ( var kickTimestamp in data.kicks ) {
			var kick = data.kicks[kickTimestamp];
			MediaWikiChat.showKickMessage( MediaWikiChat.userData[kick.from], MediaWikiChat.userData[kick.to], kickTimestamp );
		}
		for ( var blockTimestamp in data.blocks ) {
			var block = data.blocks[blockTimestamp];
			MediaWikiChat.showBlockMessage( MediaWikiChat.userData[block.from], MediaWikiChat.userData[block.to], blockTimestamp );
		}
		for ( var unblockTimestamp in data.unblocks ) {
			var unblock = data.unblocks[unblockTimestamp];
			MediaWikiChat.showUnblockMessage( MediaWikiChat.userData[unblock.from], MediaWikiChat.userData[unblock.to], unblockTimestamp );
		}

		if ( data.kick ) {
			$( '#mwchat-type input' ).attr( 'disabled', 'disabled' );
			$( '#mwchat-users div input' ).attr( 'disabled', 'disabled' );
			clearInterval( MediaWikiChat.pollInterval );
			MediaWikiChat.getNew();
		}

		if ( data.interval ) {
			MediaWikiChat.restartInterval( data.interval );
			MediaWikiChat.interval = data.interval;
		}

		if ( data.messages || data.kicks || data.blocks || data.unblocks ) {
			MediaWikiChat.scrollToBottom();
		}

		MediaWikiChat.addMe();
	},

	scrollToBottom: function() {
		var div = $( '#mwchat-content' );

		if ( $( 'input[name=autoscroll]' )[0].checked ) {
			div.animate( { 'scrollTop': div[0].scrollHeight }, 1000 );
		}
	},

	jumpToLatest: function() {
		$( 'input[name=autoscroll]' )[0].checked = true;
		$( "#mwchat-jumptolatest-span" ).animate( { opacity: 0 } ); // should be done by the changed() statement at bottom but for some reason isn't
		MediaWikiChat.scrollToBottom();
	},

	showKickMessage: function( from, to, timestamp ) {
		// If .name is undefined let's just stop here
		// as it will just throw an undefined before.
		if ( !from || !from.name || !to || !to.name ) {
			return;
		}
		var message;
		if ( to.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-youve-been-kicked', from.name, mw.user ).text();
		} else if ( from.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-you-kicked', to.name, mw.user ).text();
		} else {
			message = mw.message( 'chat-kicked', from.name, to.name, from.gender, to.gender ).text();
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	showBlockMessage: function( from, to, timestamp ) {
		var message;
		if ( to.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-youve-been-blocked', from.name, mw.user ).text();
			$( '#mwchat-type input' ).attr( 'disabled', 'disabled' );
			$( '#mwchat-users div input' ).attr( 'disabled', 'disabled' );
		} else if ( from.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-you-blocked', to.name, mw.user ).text();
		} else {
			message = mw.message( 'chat-blocked', from.name, to.name, from.gender ).text();
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	showUnblockMessage: function( from, to, timestamp ) {
		var message = '';
		if ( from.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-you-unblocked', to.name, mw.user );
		} else {
			message = mw.message( 'chat-unblocked', from.name, to.name, from.gender );
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	addSystemMessage: function( text, timestamp ) {
		// stop processing the message if it's a duplicate
		if ( MediaWikiChat.messageIsDuplicate( timestamp ) ) {
			return;
		}
		var html = '<tr class="mwchat-message system mwchat-parent">'; // mwchat-parent so that sending a system message resets the parent/child system
		html += '<td colspan="3" class="mwchat-item-messagecell"><span class="mwchat-item-message">';
		html += text;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</td></tr>';

		MediaWikiChat.addGeneralMessage( html, timestamp );
	},

	messageIsDuplicate: function( timestamp ) {
		// note message is only considered duplicate if it has the same timestamp as another,
		//regardless of who posted that other message
		$( '.mwchat-item-timestamp.pretty' ).each( function( index, value ) {
			if ( $( value ).attr( 'data-timestamp' ) == timestamp ) {
				return true;
			}
		} );
		return false;
	},

	addMessage: function( userId, message, timestamp ) {
		// stop processing the message if it's a duplicate
		if ( MediaWikiChat.messageIsDuplicate( timestamp ) ) {
			return;
		}

		var fromUser = MediaWikiChat.userData[userId];
		var currentUser = MediaWikiChat.userData[mw.user.getId()];

		if ( message.substring( 0, 4 ) == '/me ' && mw.config.get( 'wgChatMeCommand' ) ) {
			return MediaWikiChat.addSystemMessage( '* ' + fromUser.name + message.substring( 3 ), timestamp );
		}

		var mention = false;
		if ( fromUser.name != currentUser.name ) { // prevents flashing when you sent the message yourself
			if ( message.toLowerCase().indexOf( currentUser.name.toLowerCase() ) != -1) {
				mention = true;
				MediaWikiChat.flashMention( mw.message( 'chat-mentioned-by', fromUser.name, fromUser.gender ).text(), message );
			} else {
				MediaWikiChat.flash( mw.message( 'chat-message-from', fromUser.name, fromUser.gender ).text(), message );
			}
		}
		var messages = $( '#mwchat-table tr.mwchat-parent' );
		var lastParent = $( messages[messages.length - 1] );
		var html = '';

		if ( lastParent.attr( 'data-username' ) == fromUser.name ) {
			lastParent.children( '.mwchat-rowspan' ).attr( 'rowspan', Number( lastParent.children( '.mwchat-rowspan' ).attr( 'rowspan' ) ) + 1 ); // increment the rowspan

			html = '<tr data-username="' + mw.html.escape( fromUser.name ) + '" class="mwchat-message">';
		} else {
			html = '<tr data-username="' + mw.html.escape( fromUser.name ) + '" class="mwchat-message mwchat-parent">';

			html += '<td rowspan=1 class="mwchat-item-user mwchat-rowspan">';
			if ( mw.config.get( 'wgChatLinkUsernames' ) ) {
				var userURL = mw.config.get( 'wgScriptPath' ) + '/index.php?title=User:' + mw.html.escape( fromUser.name );
				var userTitle = mw.config.get( 'wgFormattedNamespaces' )[2] + ':' + mw.html.escape( fromUser.name ); // @see T140546
				html += '<a href="' + userURL + '" title="' + userTitle + '" target="_blank">' + mw.html.escape( fromUser.name ) + '</a>';
			} else {
				html += mw.html.escape( fromUser.name );
			}
			html += '</td><td rowspan=1 class="mwchat-item-avatar mwchat-rowspan">';
			if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
				html += '<img src="' + fromUser.avatar + '" /></td>';
			}
		}
		html += '<td class="mwchat-item-messagecell"><span class="mwchat-item-message"';
		if ( mention ) {
			html += ' data-read="true"';
		}
		html += '>'+ message + '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</td></tr>';

		MediaWikiChat.addGeneralMessage( html, timestamp );
	},

	addGeneralMessage: function( html, timestamp ) {
		// assumes the message isn't a duplicate (already checked in addMessage and addSystemMessage)
		var elem = $( html ).appendTo( $( '#mwchat-table' ) );

		elem.on( {
			'mouseenter': function() {
				elem.find( '.pretty' ).css( 'visibility', 'hidden' );
				elem.find( '.real' ).show();
			},
			'mouseleave': function() {
				elem.find( '.real' ).hide();
				elem.find( '.pretty' ).css( 'visibility', 'visible' );
			}
		} );

		elem.find( 'a' ).attr( 'target', '_blank' );
	},

	getColourFromUsername: function( name ) {
		name = name + 'abc'; // at least 4 digits
		var one = Math.min( Math.max( Math.round( ( name.charCodeAt( 1 ) - 48 ) * 3 ), 0 ), 255 ).toString( 16 ); // the 30 and 1.3 are scaling
		if ( one.length < 2 ) {
			one = "0" + one;
		}
		var two = Math.min( Math.max( Math.round( ( name.charCodeAt( 3 ) - 48 ) * 3 ), 0 ), 255 ).toString( 16 );
		if ( two.length < 2 ) {
			two = "0" + two;
		}
		var three = Math.min( Math.max( Math.round( ( name.charCodeAt( name.length - 1 ) - 48 ) * 3 ), 0 ), 255 ).toString( 16 );
		if ( three.length < 2 ) {
			three = "0" + three;
		}
		return '#' + one + two + three;
	},

	addPrivateMessage: function( userId, convwith, message, timestamp ) {
		var user = MediaWikiChat.userData[userId];
		var convwithE = MediaWikiChat.safe( convwith );

		var html = '<div class="mwchat-message">';
		if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
			html += '<img src="' + user.avatar + '" alt="' + mw.html.escape( user.name ) + '" name="' + mw.html.escape( user.name ) + '" title="' + mw.html.escape( user.name ) + '" />';
		} else {
			html += '<span style="background-color:' + MediaWikiChat.getColourFromUsername( user.name ) + ';" class="mwchat-avatar-replacement" name="' + mw.html.escape( user.name ) + '" title="' + mw.html.escape( user.name ) + '">' + user.name.charAt( 0 ) + '</span>';
		}
		html += '<span class="mwchat-item-message">';
		html += message;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</div>';

		// Open any link in private messages in a new tab
		var elem = $( html ).appendTo( $( '#' + convwithE + ' .mwchat-useritem-content' ) );
		elem.find( 'a' ).attr( 'target', '_blank' );

		if ( user.name != mw.config.get( 'wgUserName' ) ) {
			$( '#' + convwithE ).attr( 'data-read', 'true' );
		}

		if ( userId != mw.user.getId() ) { // don't flash if we sent the message
			MediaWikiChat.flashPrivate( mw.message( 'chat-private-message-from', user.name, user.gender ).text(), message );
		}
	},

	greyscale: function( element, microseconds ) {
		element = element.children( '.mwchat-useritem-header' );

		var hours = microseconds / 360000.0; // 360000 = 1 hour.
		var tooltip = '';

		if ( hours > 1 ) {
			if ( hours > 24 ) {
				tooltip = mw.message( 'chat-idle-more', mw.user.getName() ).text();
			} else {
				tooltip = mw.message( 'chat-idle-hours', Math.round( hours ), mw.user.getName() ).text();
			}
			hours = 1;
		} else {
			var minutes = microseconds / 6000;
			if ( minutes > 10 ) {
				tooltip = mw.message( 'chat-idle-minutes', Math.round( minutes ), mw.user.getName() ).text();
			} else {
				tooltip = mw.message( 'chat-private-message' ).text();
			}
		}

		$( element ).attr( 'title', tooltip );

		// Make it so anything under 10 mins will give 0, and then the colouring happens between 10 and 60 mins
		hours = ( hours * 1.1 ) - 0.1;
		if ( hours < 0 ) {
			hours = 0;
		}

		var percent = Math.round( hours * 100 );

		$( element ).children( 'img' ).css( {
			//'-webkit-filter': 'grayscale(' + hours + ')', /* old webkit */
			'-webkit-filter': 'grayscale(' + percent + '%)', /* new webkit */
			'-moz-filter': 'grayscale(' + percent + '%)', /* mozilla */
			'-ms-filter': 'progid:DXImageTransform.Microsoft.BasicImage(grayscale=' + hours + ')', /* maybe ie */
			//'filter': 'progid:DXImageTransform.Microsoft.BasicImage(grayscale=' + hours + ')', /* maybe ie */
			'filter': 'grayscale(' + percent + '%)' /* future */
		} );

		var b = Math.round( hours * 10 + 238 ); // 238 > 248 Useritem header background gets lighter
		$( element ).css( 'background-color', 'rgb(' + b + ', ' + b + ', ' + b + ')' );

		var c = Math.round( hours * 85 ); // 0 > 85 User name text gets lighter
		$( element ).children( 'span' ).css( 'color', 'rgb(' + c + ', ' + c + ', ' + c + ')' );
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
			$( '#mwchat-no-other-users' ).hide();
		} else {
			$( '#mwchat-no-other-users' ).show();
		}

		MediaWikiChat.users = newusers;
		MediaWikiChat.firstTime = false;
	},

	addUser: function( userId, firstTime ) {
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );

		var html = '<div class="mwchat-useritem noshow" style="display:none;" data-unread="" data-name="' + mw.html.escape( user.name ) + '" data-id="' + userId + '" id="' + userE + '">';
		html += '<div class="mwchat-useritem-header">';

		if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
			html += '<img src="' + user.avatar + '" />';
		}
		html += '<span class="mwchat-useritem-user">';
		html += mw.html.escape( user.name );
		html += '</span>';
		if ( user.mod ) {
			html += '<img src="' + mw.message( 'chat-mod-image' ).escaped() + '" height="16px" alt="" title="';
			html += mw.message( 'chat-user-is-moderator' ).text() + '" />';
		}
		html += '</div><span class="mwchat-useritem-header-links">';

		if ( MediaWikiChat.amIMod && ( !user.mod ) ) {
			html += '<a class="mwchat-useritem-blocklink" href="' + mw.util.getUrl( 'Special:UserRights', { user: user.name } );
			html += '" target="_blank">' + mw.message( 'chat-block' ).text() + '</a>';

			if ( mw.config.get( 'wgChatKicks' ) ) {
				html += '&ensp;<a class="mwchat-useritem-kicklink" href="javascript:;">';
				html += mw.message( 'chat-kick' ).text() + '</a>';
			}
		}

		html += '</span>';
		html += '<div class="mwchat-useritem-window" style="display:none;">';
		html += '<div class="mwchat-useritem-content"></div>';
		html += '<input type="text" placeholder="' + mw.message( 'chat-type-your-private-message' ).text() + '" />';
		html += '</div>';
		html += '</div>';

		$( '#mwchat-users' ).append( html );
		$( '#mwchat-users #' + userE ).fadeIn();

		if ( !firstTime ) {
			MediaWikiChat.addSystemMessage( mw.message( 'chat-joined', user.name, user.gender ).text(), MediaWikiChat.now() );
			MediaWikiChat.scrollToBottom();
		}

		// Setup user actions
		$( '#mwchat-users #' + userE + ' .mwchat-useritem-header' ).click( MediaWikiChat.clickUser );

		$( '#mwchat-users #' + userE ).click( function() {
			$( this ).attr( 'data-read', '' );
		} );

		$( '#mwchat-users #' + userE + ' input' ).keypress( MediaWikiChat.userKeypress );

		$( '#mwchat-users #' + userE + ' .mwchat-useritem-kicklink' ).click( function() {
			var parent = $( this ).parent().parent();

			$.ajax( {
				type: 'POST',
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatkick', 'id': parent.attr( 'data-id' ), 'format': 'json' }
			} ).done( function() {
				MediaWikiChat.getNew();
			} );
		} );

		MediaWikiChat.flashJoinLeave( mw.message( 'chat-joined', user.name, user.gender ).text() );
	},

	removeUser: function( userId ) {
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );

		$( '#mwchat-users #' + userE ).animate( { height:0, opacity:0 }, function() { $( this ).remove(); } );

		MediaWikiChat.addSystemMessage( mw.message( 'chat-left', user.name, user.gender ).text(), MediaWikiChat.now() );
		MediaWikiChat.scrollToBottom();
		MediaWikiChat.flashJoinLeave( mw.message( 'chat-left', user.name, user.gender ).text() );
	},

	clickUser: function() {
		var parent = $( this ).parent();

		parent.children( '.mwchat-useritem-window' ).slideToggle();

		if ( parent.hasClass( 'noshow' ) ) {
			$( '.mwchat-useritem.show .mwchat-useritem-window' ).slideUp();
			$( '.mwchat-useritem.show' ).toggleClass( 'show noshow' );

			parent.toggleClass( 'show noshow' );
		}
		$( '.mwchat-useritem.show' ).toggleClass( 'show noshow' );
	},

	userKeypress: function( e ) {
		$( this ).parents( '.mwchat-useritem' ).attr( 'data-read', '' );

		var toid = $( this ).parents( '.mwchat-useritem' ).attr( 'data-id' );

		if ( e.which == 13 ) {
			$.ajax( {
				type: 'POST',
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatsendpm', 'message': $( this )[0].value, 'id': toid, 'format': 'json' }
			} ).done( function() {
				MediaWikiChat.getNew();
				MediaWikiChat.restartInterval();
			} );

			$( this ).val( '' );
		}
	},

	addMe: function() {
		if ( !MediaWikiChat.amI ) {
			var me = MediaWikiChat.userData[mw.user.getId()];

			$( '#mwchat-me span' ).html( me.name );
			$( '#mwchat-me img' ).attr( 'src', me.avatar );

			if ( me.mod ) {
				$( '#mwchat-me' ).append(
					'<img src="' + mw.message( 'chat-mod-image' ).escaped() + '" height="20px" alt="" title="' +
						mw.message( 'chat-you-are-moderator', mw.user ).text() + '" />'
				);
			}
			MediaWikiChat.amI = true;

			$( '#mwchat-container' ).animate( { opacity: 1 } );
		}
	},

	flash: function( title, message ) {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.user.options.get( 'chat-ping-message' ) ) {
				MediaWikiChat.audio( 'message' );
			}
			document.title = "* " + MediaWikiChat.title;
			if ( mw.user.options.get( 'chat-notify-message' ) ) {
				MediaWikiChat.notify( title, message );
			}
		}
	},

	flashPrivate: function( title, message ) {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.user.options.get( 'chat-ping-pm' ) ) {
				MediaWikiChat.audio( 'pm' );
			}
			document.title = "> " + MediaWikiChat.title;
			if ( mw.user.options.get( 'chat-notify-pm' ) ) {
				MediaWikiChat.notify( title, message );
			}
		}
	},

	flashMention: function( title, message ) {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.user.options.get( 'chat-ping-mention' ) ) {
				MediaWikiChat.audio( 'mention' );
			} else if ( mw.user.options.get( 'chat-ping-message' ) ) { // user may want pinging on all msgs, but not mentions
				MediaWikiChat.audio( 'message' );
			}
			document.title = "! " + MediaWikiChat.title;
			if ( mw.user.options.get( 'chat-notify-mention' ) ) {
				MediaWikiChat.notify( title, message );
			}
		}
	},

	flashJoinLeave: function( title ) {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.user.options.get( 'chat-ping-joinleave' ) ) {
				MediaWikiChat.audio( 'message' );
			}
			document.title = '- ' + MediaWikiChat.title;
			if ( mw.user.options.get( 'chat-notify-joinleave' ) ) {
				MediaWikiChat.notify( title, '' );
			}
		}
	},

	clearMentions: function() {
		$( '.mwchat-item-message[data-read="true"]' ).attr( 'data-read', '' );
	},

	audio: function( filename ) {
		var audio = document.createElement( 'audio' );
		var path = mw.config.get( 'wgScriptPath') + '/extensions/MediaWikiChat/assets/' + filename;

		var source = document.createElement( 'source' );
		source.type = 'audio/ogg';
		source.src = path + '.ogg';
		audio.appendChild( source );

		source = document.createElement( 'source' );
		source.type = 'audio/mpeg';
		source.src = path + '.mp3';
		audio.appendChild( source );

		audio.play();
	},

	notify: function( title, message ) {
		if ( !Notification ) {
			return;
		}

		if ( Notification.permission !== "granted" ) {
			Notification.requestPermission();
		}

		var notification = new Notification( title, {
			icon: mw.config.get( 'wgCanonicalServer' ) + '/favicon.ico',
			body: message
		} );

		notification.onclick = function() {
			window.focus();
		};

		setTimeout( function( notification ) {
			notification.close();
		}, 5000, notification );
	},

	restartInterval: function( interval ) {
		if ( !interval ) {
			interval = MediaWikiChat.interval;
		}
		window.clearInterval( MediaWikiChat.pollInterval );
		MediaWikiChat.pollInterval = setInterval( MediaWikiChat.getNew, interval );
	}
};

$( function() {
	$( $( '#mwchat-type input' )[0] ).keydown( function( e ) { // Send text
		MediaWikiChat.clearMentions();

		var message = $( '#mwchat-type input' )[0].value;

		if ( e.which == 13 && e.shiftKey ) {
			return false;
		} else if ( e.which == 13 ) { // Enter
			if ( message.length > mw.config.get( 'wgChatMaxMessageLength' ) ) {
				alert( mw.message( 'chat-too-long' ).text() );
			} else {
				$( '#mwchat-type input' ).val( '' );
			}

			$( '#mwchat-loading' ).attr(
				'data-queue',
				parseInt( $( '#mwchat-loading' ).attr( 'data-queue' ) ) + 1 )
			.animate( { opacity: $( '#mwchat-loading' ).attr( 'data-queue' ) } );

			$.ajax( {
				type: 'POST',
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatsend', 'message': message, 'format': 'json' }
			} ).done( function( msg ) {
				MediaWikiChat.getNewReply( msg );
				$( '#mwchat-loading' ).attr(
					'data-queue',
					parseInt( $( '#mwchat-loading' ).attr( 'data-queue' ) ) - 1 )
				.animate( { opacity: $( '#mwchat-loading' ).attr( 'data-queue' ) } );

				window.clearInterval( MediaWikiChat.newInterval );
				MediaWikiChat.newInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );

				if ( msg.chatsend && msg.chatsend.error == 'flood' ) {
					$( '#mwchat-type input' ).val( message );
					alert( mw.message( 'chat-flood' ).text() );
				}
			} );

		} else if ( e.which == 9 ) { // Tab - autocompletion
			for ( var userId in MediaWikiChat.userData ) {
				if ( userId != mw.user.getId() ) {
					if ( MediaWikiChat.userData[userId].name.toLowerCase().indexOf( $( '#mwchat-type input' )[0].value.toLowerCase() ) === 0 ) {
						$( '#mwchat-type input' )[0].value = MediaWikiChat.userData[userId].name + ': ';
						return false;
					}
				}
			}
			return false;
		} else {
			if ( message.length == 1 ) {
				MediaWikiChat.getNew(); // if the user is typing a new message, load replies so they can see any
				MediaWikiChat.restartInterval(); // before they press enter
			}
		}
	} );

	MediaWikiChat.getNew();

	MediaWikiChat.pollInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );
	MediaWikiChat.redoInterval = setInterval( MediaWikiChat.redoTimestamps, 10000 );

	$( '#mwchat-content' ).click( MediaWikiChat.clearMentions );

	$( '#mwchat-container' ).mouseup( function() { // resize user area on content resize
		var height = $( '#mwchat-content' ).height();
		$( '#mwchat-users' ).animate( { 'height': height }, 'fast' );
		$( '#mwchat-me' ).animate( { 'top': height }, 'fast' );
	} );

	$( '#mwchat-topic a').attr( 'target', '_blank'); // Open any link in chat-topic in a new tab

	$( 'input[name=autoscroll]' ).change( function() {
		if ( this.checked ) {
			$( "#mwchat-jumptolatest-span" ).animate( { opacity: 0 } );
		} else {
			$( "#mwchat-jumptolatest-span" ).animate( { opacity: 1 } );
		}
	} );

	$( "#mwchat-jumptolatest-link" ).click( function() {
		MediaWikiChat.jumpToLatest();
	} );
} );

$( window ).blur( function() {
	MediaWikiChat.focussed = false;
} );

$( window ).focus( function() {
	MediaWikiChat.focussed = true;
	document.title = MediaWikiChat.title; // restore title
} );
