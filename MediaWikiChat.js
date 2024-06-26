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

	/**
	 * Pad the given num with zeroes if the need be.
	 *
	 * @param {number} num Number to (potentially) pad
	 * @param {number} size Always 2, apparently
	 * @return {string} Potentially zero-padded number as a string
	 */
	pad: function( num, size ) {
		var s = num + '';
		while ( s.length < size ) {
			s = '0' + s;
		}
		return s;
	},

	/**
	 * Strip spaces and some other characters from a given string.
	 * This is used to build certain <div> elements' IDs.
	 *
	 * @param {string} string Source string (usually a username) to manipulate
	 * @return {string} "Sanitized" string
	 */
	safe: function( string ) {
		return string.replace( /[^\w\s]|/g, '' ).replace( / /g, '' );
	},

	/**
	 * Given an array, makes it contain only unique elements.
	 *
	 * @param {array} array Source array to manipulate
	 * @return {array} Array with duplicate elements removed
	 */
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

	/**
	 * Get the current UNIX time, e.g. 171400152309.
	 * It is in 10 millisecond sizes, meaning that the returned timestamps have
	 * two more digits than an average TS_MW timestamp usually used by MediaWiki,
	 * so you need to strip those away should you wish to convert the output of
	 * this method into TS_MW for whatever reason.
	 *
	 * @return {number}
	 */
	now: function() {
		return Math.round( new Date().getTime() / 10 ); // we need it in 10 millisecond sizes
	},

	/**
	 * Get the real, human-readable date string from a given numerical timestamp.
	 * The output of this method is currently visually hidden in the HTML (see htmlTimestamp() below).
	 *
	 * @param {number} timestamp Such as 171400152309
	 * @return {string} Time string, e.g. "today, 03:52" or "January 02, 13:46"
	 */
	realTimestamp: function( timestamp ) {
		var messageDate = new Date();
		messageDate.setTime( timestamp * 10 );
		var nowDate = new Date();

		var time = '';

		if ( nowDate.getDate() == messageDate.getDate() && nowDate.getMonth() == messageDate.getMonth() ) {
			time += mw.message( 'chat-today' ).escaped();
		} else {
			var months = [ 'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december' ];
			time += mw.message( months[messageDate.getMonth()] ).escaped();
			time += ' ' + messageDate.getDate();
		}

		time += ', ' + MediaWikiChat.pad( messageDate.getHours(), 2 );
		time += ':' + MediaWikiChat.pad( messageDate.getMinutes(), 2 );

		return time;
	},

	/**
	 * Given a timestamp, returns the date as an "X ago" string.
	 *
	 * @param {number} timestamp Such as 171400152309
	 * @return {string} Time string, e.g. "just now", "24 minutes ago", "yesterday", "Thursday, 09:32"
	 */
	prettyTimestamp: function( timestamp ) {
		var dateThen = new Date();
		dateThen.setTime( timestamp * 10 );
		var dayThen = dateThen.getDate();

		var dateNow = new Date();
		var tsNow = parseInt( dateNow.getTime() / 10, 10 );
		var dayNow = dateNow.getDate();

		var diff = ( tsNow - timestamp ) / 100;

		if ( diff < 30 ) {
			return mw.message( 'just-now' ).escaped();
		} else if ( diff < 2 * 60 ) {
			return mw.message( 'chat-a-minute-ago' ).escaped();
		} else if ( diff < 60 * 60 ) {
			return mw.message( 'minutes-ago', Math.floor( diff / 60 ) ).text();
		} else {
			if ( dayNow == dayThen ) {
				return MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
			} else {
				if ( dayNow == dayThen + 1 ) { // @TODO handle 31s
					return mw.message( 'chat-yesterday' ).escaped().toLowerCase() + ', ' + MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
				} else {
					var day;
					var days = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];
					day = mw.message( days[dateThen.getDay()] ).escaped().toLowerCase();
					return day + ', ' +
						MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' +
						MediaWikiChat.pad( dateThen.getMinutes(), 2 );
				}
			}
		}
	},

	/**
	 * Update timestamps, if and when the need be.
	 * This is a setInterval() callback method.
	 */
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

	/**
	 * Common helper method for building timestamp HTML to be used in various parts
	 * of the chat UI (main chat, but also private messages).
	 *
	 * @param {number} timestamp Such as 171400152309
	 * @return {string} HTML
	 */
	htmlTimestamp: function( timestamp ) {
		var html = '<span class="mwchat-item-timestamp-container">';
		html += '<span class="mwchat-item-timestamp pretty" data-timestamp="' + timestamp + '">';
		html += MediaWikiChat.prettyTimestamp( timestamp );
		html += '</span><span class="mwchat-item-timestamp real" style="display:none;">';
		html += MediaWikiChat.realTimestamp( timestamp );
		html += '</span></span>';
		return html;
	},

	/**
	 * Ping the chat server for any and all new messages and events etc.
	 */
	getNew: function() {
		var focussed = '';
		if ( MediaWikiChat.focussed ) {
			focussed = 'true';
		}
		$.ajax( {
			url: mw.config.get( 'wgScriptPath' ) + '/api.php',
			data: {
				'action': 'chatgetnew',
				'format': 'json',
				'focussed': focussed
			},
			cache: false
		} ).done( MediaWikiChat.getNewReply );
	},

	/**
	 * Callback for getNew(), updates user data etc. based on the response returned by the server.
	 *
	 * @param {object} response Data returned by the "chatgetnew" API module
	 */
	getNewReply: function( response ) {
		var data = response.chatgetnew;

		var onlineUsers = [];

		for ( var userId in data.users ) {
			var user = data.users[userId];
			MediaWikiChat.userData[userId] = {
				'name': user.name,
				'avatar': user.avatar,
				'gender': user.gender
			};
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

	/**
	 * It does exactly what it says on the tin. Shocking, right?!
	 */
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
			message = mw.message( 'chat-you-unblocked', to.name, mw.user ).text();
		} else {
			message = mw.message( 'chat-unblocked', from.name, to.name, from.gender ).text();
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	/**
	 * Add a system message. This used for:
	 * - kicks
	 * - /me does stuff (when wgChatMeCommand is set to true)
	 * - probably more
	 *
	 * @param {string} text Text, e.g. "Moderator has kicked ADisruptiveUser"
	 * @param {number} timestamp Such as 171400152309
	 * @param {object} pmData Private messaging data, for when this is a private message.
	 */
	addSystemMessage: function( text, timestamp, pmData = {} ) {
		// stop processing the message if it's a duplicate
		if ( MediaWikiChat.messageIsDuplicate( timestamp ) ) {
			return;
		}

		var privateClass = ( pmData && pmData.isPrivate ? ' private-message' : '' );
		// mwchat-parent so that sending a system message resets the parent/child system
		var html = '<tr class="mwchat-message system mwchat-parent' + privateClass + '">';
		html += '<td colspan="3" class="mwchat-item-messagecell"><span class="mwchat-item-message">';
		html += text;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</td></tr>';

		MediaWikiChat.addGeneralMessage( html, timestamp, pmData );
	},

	messageIsDuplicate: function( timestamp ) {
		// note message is only considered duplicate if it has the same timestamp as another,
		// regardless of who posted that other message
		$( '.mwchat-item-timestamp.pretty' ).each( function( index, value ) {
			if ( $( value ).attr( 'data-timestamp' ) == timestamp ) {
				return true;
			}
		} );
		return false;
	},

	/**
	 * Add a message of some kind somewhere, unless it's an exact duplicate
	 * (as determined by its timestamp).
	 *
	 * @param {number} userId User ID of the person who sent the message
	 * @param {string} message Message text
	 * @param {number} timestamp Such as 171400152309
	 */
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

	/**
	 * @param {string} html HTML to append
	 * @param {number} timestamp
	 * @param {object} pmData Private messaging data, for when this is a private message.
	 */
	addGeneralMessage: function( html, timestamp, pmData = {} ) {
		// assumes the message isn't a duplicate (already checked in addMessage and addSystemMessage)
		var target = '#mwchat-table';
		if ( pmData && pmData.isPrivate && pmData.otherUser ) {
			target = '#' + MediaWikiChat.safe( pmData.otherUser ) + ' .mwchat-useritem-content';
		}
		var elem = $( html ).appendTo( $( target ) );

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

	/**
	 * Get the color for a user's user name.
	 *
	 * Only used when SocialProfile is *not* installed, as then avatars aren't available,
	 * so coloring is used to visually distinguish users on the user list.
	 * If SocialProfile is installed, this method is never called.
	 *
	 * @param {string} name User name
	 * @return {string} Hex color code
	 */
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

	/**
	 * Render a private message.
	 *
	 * @param {number} userId User ID of the user who is the recipient
	 * @param {string} convwith User name of the person who sent the private message
	 * @param {string} message Private message contents
	 * @param {number} timestamp
	 */
	addPrivateMessage: function( userId, convwith, message, timestamp ) {
		var user = MediaWikiChat.userData[userId];
		var convwithE = MediaWikiChat.safe( convwith );

		// Parse /me correctly if enabled (T146550)
		if ( message.substring( 0, 4 ) == '/me ' && mw.config.get( 'wgChatMeCommand' ) ) {
			MediaWikiChat.addSystemMessage(
				'* ' + user.name + message.substring( 3 ),
				timestamp,
				{
					isPrivate: true,
					otherUser: convwith
				}
			);
		} else {
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
		}

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

	/**
	 * Set the cosmetic properties on users who have been idle for some time.
	 *
	 * @param {jQuery} element
	 * @param {number} microseconds
	 */
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
				tooltip = mw.message( 'chat-private-message' ).escaped();
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
			'filter': 'grayscale(' + percent + '%)' /* future */
		} );

		var b = Math.round( hours * 10 + 238 ); // 238 > 248 Useritem header background gets lighter
		$( element ).css( 'background-color', 'rgb(' + b + ', ' + b + ', ' + b + ')' );

		var c = Math.round( hours * 85 ); // 0 > 85 User name text gets lighter
		$( element ).children( 'span' ).css( 'color', 'rgb(' + c + ', ' + c + ', ' + c + ')' );
	},

	/**
	 * Ensure that the users present are listed only once in the user list.
	 * If no other users are present on the chat, show the i18n message
	 * stating so.
	 *
	 * @param {array} newUsers Array containing the user's user name ('name'),
	 *   avatar and gender info, keyed on the user ID
	 */
	doUsers: function( newUsers ) {
		var allUsers = MediaWikiChat.users.concat( newUsers );
		allUsers = MediaWikiChat.unique( allUsers );

		allUsers.forEach( function( userId ) {
			if ( newUsers.indexOf( userId ) == -1 ) {
				MediaWikiChat.removeUser( userId );
			} else if ( newUsers.indexOf( userId ) != -1 && MediaWikiChat.users.indexOf( userId ) == -1 ) {
				MediaWikiChat.addUser(
					userId,
					MediaWikiChat.firstTime
				);
			}
		} );

		if ( allUsers.length ) {
			$( '#mwchat-no-other-users' ).hide();
		} else {
			$( '#mwchat-no-other-users' ).show();
		}

		MediaWikiChat.users = newUsers;
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
			html += '<img src="' + MediaWikiChat.getChatModImage() + '" height="16px" alt="" title="';
			html += mw.message( 'chat-user-is-moderator' ).text() + '" />';
		}
		html += '</div><span class="mwchat-useritem-header-links">';

		if ( MediaWikiChat.amIMod && ( !user.mod ) ) {
			html += '<a class="mwchat-useritem-blocklink" href="' + mw.util.getUrl( 'Special:UserRights', { user: user.name } );
			html += '" target="_blank">' + mw.message( 'chat-block' ).escaped() + '</a>';

			if ( mw.config.get( 'wgChatKicks' ) ) {
				html += '&ensp;<a class="mwchat-useritem-kicklink" href="javascript:;">';
				html += mw.message( 'chat-kick' ).escaped() + '</a>';
			}
		}

		html += '</span>';
		html += '<div class="mwchat-useritem-window" style="display:none;">';
		html += '<div class="mwchat-useritem-content"></div>';
		html += '<input type="text" placeholder="' + mw.message( 'chat-type-your-private-message' ).escaped() + '" />';
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

			( new mw.Api() ).postWithToken( 'csrf', {
				'action': 'chatkick',
				'id': parent.attr( 'data-id' ),
				'format': 'json'
			} ).done( function() {
				MediaWikiChat.getNew();
			} );
		} );

		MediaWikiChat.flashJoinLeave( mw.message( 'chat-joined', user.name, user.gender ).text() );
	},

	/**
	 * Remove a user from the chat and announce that they've left to the whole room.
	 *
	 * @param {number} userId User ID of the user whom to remove
	 */
	removeUser: function( userId ) {
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );

		$( '#mwchat-users #' + userE ).animate( { height:0, opacity:0 }, function() { $( this ).remove(); } );

		MediaWikiChat.addSystemMessage( mw.message( 'chat-left', user.name, user.gender ).text(), MediaWikiChat.now() );
		MediaWikiChat.scrollToBottom();
		MediaWikiChat.flashJoinLeave( mw.message( 'chat-left', user.name, user.gender ).text() );
	},

	/**
	 * Handle clicks on user names on the user list, i.e. show or hide the PM window
	 * for the user in question.
	 */
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

	/**
	 * Handle the user pressing the Enter key.
	 * This is an event callback, the actual event registration happens in addUser().
	 *
	 * @param {Event} e
	 */
	userKeypress: function( e ) {
		$( this ).parents( '.mwchat-useritem' ).attr( 'data-read', '' );

		var toid = $( this ).parents( '.mwchat-useritem' ).attr( 'data-id' );

		if ( e.which == 13 ) {
			( new mw.Api() ).postWithToken( 'csrf', {
				'action': 'chatsendpm',
				'message': $( this )[0].value,
				'id': toid,
				'format': 'json'
			} ).done( function() {
				MediaWikiChat.getNew();
				MediaWikiChat.restartInterval();
			} );

			$( this ).val( '' );
		}
	},

	/**
	 * Add the current user to the chat user list (and flag them as a chat moderator
	 * if they are one) if they're not present in the list already.
	 */
	addMe: function() {
		if ( !MediaWikiChat.amI ) {
			var me = MediaWikiChat.userData[mw.user.getId()];

			$( '#mwchat-me span' ).html( me.name );
			$( '#mwchat-me img' ).attr( 'src', me.avatar );

			if ( me.mod ) {
				$( '#mwchat-me' ).append(
					'<img src="' + MediaWikiChat.getChatModImage() + '" height="20px" alt="" title="' +
						mw.message( 'chat-you-are-moderator', mw.user ).text() + '" />'
				);
			}
			MediaWikiChat.amI = true;

			$( '#mwchat-container' ).animate( { opacity: 1 } );
		}
	},

	/**
	 * Get the small chat mod "badge" image, either:
	 * 1) the image from the extension's assets/ dir, or
	 * 2) a local override configured on-wiki via [[MediaWiki:Chat-mod-image]]
	 *
	 * @return {string} URL to the chat mod "badge" image
	 */
	getChatModImage: function() {
		// Support a custom chat mod image indicator, but default to using the
		// local image from the extension's assets/ directory
		// @see T209024
		var customChatModImage = mw.message( 'chat-mod-image' ).escaped();
		var chatModImage = customChatModImage;
		if ( customChatModImage === '' ) {
			chatModImage = mw.config.get( 'wgExtensionAssetsPath' ) + '/MediaWikiChat/assets/chatmod.png';
		}
		return chatModImage;
	},

	/**
	 * If the chat window does not have focus, make the chat window flash,
	 * prepend a bullet to its title and optionally play a sound if the user
	 * has the relevant user preferences enabled.
	 *
	 * @param {string} title Chat window title (document.title)
	 * @param {string} message
	 */
	flash: function( title, message ) {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.user.options.get( 'chat-ping-message' ) ) {
				MediaWikiChat.audio( 'message' );
			}
			document.title = '* ' + MediaWikiChat.title;
			if ( mw.user.options.get( 'chat-notify-message' ) ) {
				MediaWikiChat.notify( title, message );
			}
		}
	},

	/**
	 * If the chat window does not have focus, and the user receives
	 * a private message, make the chat window flash, prepend a > sign to its
	 * title and optionally play a sound if the user has the relevant user
	 * preferences enabled.
	 *
	 * @param {string} title Chat window title (document.title)
	 * @param {string} message
	 */
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

	/**
	 * If the chat window does not have focus, and the user receives a mention,
	 * make the chat window flash, prepend an exclamation mark to its title and
	 * optionally play a sound if the user has the relevant user preferences enabled.
	 *
	 * @param {string} title Chat window title (document.title)
	 * @param {string} message
	 */
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

	/**
	 * If the chat window does not have focus, and the user has opted to receive
	 * notifications (whether audio or using the browser's Notification API, or both)
	 * when someone joins or leaves the chat, make the chat window flash, prepend a dash
	 * to its title, play the sound and/or fire off the notification.
	 *
	 * @param {string} title Chat window title (document.title)
	 * @param {string} message
	 */
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

	/**
	 * Mark all mentions as seen.
	 */
	clearMentions: function() {
		$( '.mwchat-item-message[data-read="true"]' ).attr( 'data-read', '' );
	},

	/**
	 * Play an audio file from MediaWikiChat's assets/ directory.
	 * It is assumed that both MP3 and OGG Vorbis versions of the sound exist in
	 * that directory.
	 *
	 * @param {string} filename File name *without* the extension
	 */
	audio: function( filename ) {
		var audio = document.createElement( 'audio' );
		var path = mw.config.get( 'wgExtensionAssetsPath' ) + '/MediaWikiChat/assets/' + filename;

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

	/**
	 * Send a notification using the browser's Notification API, but only if
	 * the browser suppors that API and the user has opted into it.
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/API/notification
	 *
	 * @param {string} title Notification title
	 * @param {string} message Notification contents
	 */
	notify: function( title, message ) {
		if ( !Notification ) {
			return;
		}

		if ( Notification.permission !== 'granted' ) {
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

	/**
	 * Restart polling interval.
	 *
	 * @param {number} interval New polling interval; if not given, defaults to MediaWikiChat.interval
	 */
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
				alert( mw.message( 'chat-too-long' ).escaped() );
			} else {
				$( '#mwchat-type input' ).val( '' );
			}

			$( '#mwchat-loading' ).attr(
				'data-queue',
				parseInt( $( '#mwchat-loading' ).attr( 'data-queue' ) ) + 1 )
			.animate( { opacity: $( '#mwchat-loading' ).attr( 'data-queue' ) } );

			( new mw.Api() ).postWithToken( 'csrf', {
				'action': 'chatsend',
				'message': message,
				'format': 'json'
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
					alert( mw.message( 'chat-flood' ).escaped() );
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

	$( '#mwchat-topic a' ).attr( 'target', '_blank' ); // Open any link in chat-topic in a new tab

	$( 'input[name=autoscroll]' ).change( function() {
		if ( this.checked ) {
			$( '#mwchat-jumptolatest-span' ).animate( { opacity: 0 } );
		} else {
			$( '#mwchat-jumptolatest-span' ).animate( { opacity: 1 } );
		}
	} );

	$( '#mwchat-jumptolatest-link' ).click( function() {
		MediaWikiChat.jumpToLatest();
	} );
} );

// Mark when the chat window loses focus
$( window ).blur( function() {
	MediaWikiChat.focussed = false;
} );

// When the chat window regains focus, set the appropriate flag and restore the original page title
// (i.e. clear out any and all markers which may have been set by the various flash*() methods)
$( window ).focus( function() {
	MediaWikiChat.focussed = true;
	document.title = MediaWikiChat.title; // restore title
} );
