<?php
/**
 * Main backend class for the MediaWikiChat extension.
 *
 * @file
 */
class MediaWikiChat {

	public $data = array();

	/**
	 * @todo FIXME: this looks like it could and should go away and be replaced
	 * with wfTimestampNow(), but that might require a database schema change
	 */
	function now() {
		$m = explode( ' ', microtime() );

		return intval( $m[1] ) * 100 +
				intval( $m[0] * 100 );
	}

	/**
	 * Get the path to the specified user's avatar image.
	 *
	 * @param $id Integer: user ID
	 * @return String: avatar image path
	 */
	function getAvatar( $id ) {
		global $wgUploadPath;
		
		$avatar = new wAvatar( $id, 's' );

		return $wgUploadPath . '/avatars/' . $avatar->getAvatarImage();
	}

	/**
	 * Hook for user rights changes
	 */
	public static function onUserRights( $user, array $add, array $remove ) {
		if ( in_array( 'blockedfromchat', $add ) ) {
			$this->sendSystemBlockingMessage( 'block', $user );
		}

		if ( in_array( 'blockedfromchat', $remove ) ) {
			$this->sendSystemBlockingMessage( 'unblock', $user );
		}

		return true;
	}
	/**
	 * Send a message to the db that a user has been (un)blocked
	 * 
	 * @param $type String: block/unblock: whether the user has been blocked or unblocked
	 * @param $user User: user that has been blocked/unblocked
	 */
	function sendSystemBlockingMessage( $type, $user ) {
		$dbw = wfGetDB( DB_MASTER );

		$id = $user->getId();
		$name = $user->getName();
		$timestamp = MediaWikiChat::now();

		$dbw->insert(
			'chat',
			array(
				'chat_to_id' => $id,
				'chat_to_name' => $name,
				'chat_timestamp' => $timestamp,
				'chat_type' => $type
			)
		);
	}
	
	/**
	 * Perform a kick on the user details given.
	 * 
	 * @param String $toName: name of user to kick
	 * @param Integer $toId: id of user to kick
	 * @return boolean: success
	 */
	function kick( $toName, $toId ) {
		global $wgUser;
		
		$toUser = User::newFromId( $toID );

		if ( $wgUser->isAllowed( 'modchat' ) && ( ! $toUser->isAllowed( 'modchat' ) ) ) {
			$dbw = wfGetDB( DB_MASTER );

			$fromId = $wgUser->getId();
			$fromName = $wgUser->getName();
			$timestamp = MediaWikiChat::now();

			$dbw->insert(
				'chat',
				array(
					'chat_to_id' => $toId,
					'chat_to_name' => $toName,
					'chat_user_id' => $fromId,
					'chat_user_name' => $fromName,
					'chat_timestamp' => $timestamp,
					'chat_type' => 'kick'
				),
				__METHOD__
			);

			// Log the kick to Special:Log/chat
			$logEntry = new ManualLogEntry( 'chat', 'kick' );
			$logEntry->setPerformer( $wgUser );
			$page = SpecialPage::getTitleFor( 'Chat' );
			$logEntry->setTarget( $page );
			$logEntry->setParameters( array(
				'4::kick' => $toName,
			) );

			$logId = $logEntry->insert();

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Send a message to chat, if the user is allowed to chat and the provided
	 * message isn't empty.
	 *
	 * @param $message String: user-supplied message
	 * @return Mixed: timestamp (on success), or boolean false (if message sending
	 *		failed because message was blank, or user is blocked
	 */
	function sendMessage( $message ) {
        global $wgUser;

		if ( $wgUser->isAllowed( 'chat' ) ) {
			if ( $message != '' ) {
				$dbw = wfGetDB( DB_MASTER );

				$id = $wgUser->getId();
				$name = $wgUser->getName();
				$timestamp = MediaWikiChat::now();

				$dbw->insert(
					'chat',
					array(
						'chat_user_id' => $id,
						'chat_user_name' => $name,
						'chat_timestamp' => $timestamp,
						'chat_message' => $message,
						'chat_type' => 'message'
					)
				);

				$logEntry = new ManualLogEntry( 'chat', 'send' ); // Action bar in log foo
				$logEntry->setPerformer( $wgUser ); // User object, the user who did this action
				$page = SpecialPage::getTitleFor( 'Chat' );
				$logEntry->setTarget( $page ); // The page that this log entry affects
				//$logEntry->setComment( $reason ); // User provided comment, optional
				$logEntry->setParameters( array(
					'4::message' => $message,
				) );

				$logid = $logEntry->insert();
		
				$this->deleteEntryIfNeeded();

				return $timestamp;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Send a private message message a user, if the user is allowed to chat 
	 * and the provided message isn't empty.
	 *
	 * @param $message String: user-supplied message
	 * @param String $toName: username of user to send message to
	 * @param Integer $toId: id of user to send message to
	 * @return Mixed: timestamp (on success), or boolean false (if message sending
	 *		failed because message was blank, or user is blocked
	 */
	function sendPM( $message, $toName, $toId ) {
		global $wgUser;

		if ( $wgUser->isAllowed( 'chat' ) ) {
			$dbw = wfGetDB( DB_MASTER );

			$fromId = $wgUser->getID();
			$fromName = $wgUser->getName();
			$timestamp = MediaWikiChat::now();

			$dbw->insert(
				'chat',
				array(
					'chat_to_id' => $toId,
					'chat_to_name' => $toName,
					'chat_user_id' => $fromId,
					'chat_user_name' => $fromName,
					'chat_timestamp' => $timestamp,
					'chat_message' => $message,
					// @todo FIXME: this doesn't make ANY SENSE -- use constants
					// and change the chat_type field definition accordingly
					'chat_type' => 'private message'
				),
				__METHOD__
			);

			$this->deleteEntryIfNeeded();

			return $timestamp;
		} else {
			return false;
		}
	}

	/**
	 * Get the list of users who are online, if we have the "chat" user right.
	 *
	 * @return Mixed: array of user IDs and user names on success, boolean false
	 *                if the current user doesn't have the "chat" right
	 */
	function getOnline() {
        global $wgUser;

		if ( $wgUser->isAllowed( 'chat' ) ) {
			$dbr = wfGetDB( DB_SLAVE );

			$timestamp = MediaWikiChat::now() - 2 * 60 * 100; // minus 2 mins

			$res = $dbr->select(
				'chat_users',
				array( 'cu_user_name', 'cu_user_id' ),
				array(
					"cu_timestamp > $timestamp",
					"cu_user_id != {$wgUser->getId()}"
				),
				__METHOD__
			);

			$data = array();

			foreach ( $res as $row ) {
				$id = $row->cu_user_id;
				$name = $row->cu_user_name;

				$data[] = array( $name, $id );
			}
			return $data;
		} else {
			return false;
		}
	}

	/**
	 * Get average milliseconds beteen recent messages. Note: not currently in use
	 * 
	 * @return Integer: average milliseconds between message sends
	 */
	function getInterval() {
		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select(
			'chat',
			'chat_timestamp',
			array( 'chat_type' => 'message' ),
			__METHOD__,
			array(
				'LIMIT' => 5,
				'ORDER BY' => 'chat_timestamp DESC'
			)
		);
		
		$i = 0;
		
		foreach( $res as $row ){
			if ( $i == 0 ) {
				$latest = $row;
			} elseif ( $i == 4 ){
				$oldest = $row;
			}
			$i++;
		}

		$latestTime = $latest->chat_timestamp;
		$oldestTime = $oldest->chat_timestamp;

		$av = ( $latestTime - $oldestTime ) / 5;

		return $av;
	}

	function parseMessage( $message ) {
		$s2 = wfMessage( 'smileys' )->plain();
		$sm2 = explode( '* ', $s2 );

		$smileys = array();

		if ( is_array( $sm2 ) ) {
			foreach ( $sm2 as $line ) {
				$bits = explode( ' ', $line );

				if ( count( $bits ) > 1 ) {
					$chars = trim( $bits[0] );
					$filename = trim( $bits[1] );

					$image = "[[!File:$filename|x20px|alt=$chars|link=|$chars]]";

					$smileys[$chars] = $image;
				}
			}
		}

		$message = ' ' . $message . ' ';

		if ( is_array( $smileys ) ) {
			foreach ( $smileys as $chars => $image ) {
				$chars = preg_quote( $chars );

				$message = preg_replace( '` ' . $chars . ' `', ' ' . $image . ' ', $message );
			}
		}

		$message = trim( $message );

		$message = str_ireplace( '[[File:', '[[:File:', $message );
		$message = str_ireplace( '[[!File:', '[[File:', $message );

		$message = "MWCHAT$message";

		$opts = new ParserOptions();
		$opts->setEditSection( false );
		$opts->setExternalLinkTarget( '_blank' );
		$opts->setAllowSpecialInclusion( false );
		$opts->setAllowExternalImages( false );

		$parser = new Parser();
		$parseOut = $parser->parse(
			$message,
			SpecialPage::getTitleFor( 'Chat' ),
			$opts
		);

		$text = $parseOut->getText();
		$text = str_replace( 'MWCHAT', '', $text );
		return $text;
	}

	/**
	 * Hook for parser, to parse chat messages slightly differently,
	 * not parsing tables, double underscores, and headings
	 */
	public static function onParserBeforeInternalParse( &$parser, &$text, &$strip_state ) {
		if ( strpos( $text, 'MWCHAT' ) === false ) {
			return true;
		} else {
			$text = $parser->replaceVariables( $text );

			//$text = $this->doTableStuff( $text );

			$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

			//$text = $this->doDoubleUnderscore( $text );

			//$text = $this->doHeadings( $text );
			if ( $parser->mOptions->getUseDynamicDates() ) {
				$df = DateFormatter::getInstance();
				$text = $df->reformat( $parser->mOptions->getDateFormat(), $text );
			}
			$text = $parser->replaceInternalLinks( $text );
			$text = $parser->doAllQuotes( $text );
			$text = $parser->replaceExternalLinks( $text );

			$text = str_replace( $parser->mUniqPrefix.'NOPARSE', '', $text );

			$text = $parser->doMagicLinks( $text );
			//$text = $this->formatHeadings( $text, $origText, $isMain );

			return false;
		}
	}
	/**
	 * Remove entries from chat table if table is already full 
	 * 
	 * Prevents speeds slowing down due to massive IM tables
	 */
	function deleteEntryIfNeeded(){
		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );
		$field = $dbr -> selectField(
				'chat',
				'chat_timestamp',
				array(),
				__METHOD__,
				array(
						'ORDER_BY' => 'chat_timestamp DESC',
						'OFFSET' => 50,
						'LIMIT' => 1
				)
		);
		
		if( is_int( $field ) ){
			$dbw -> delete(
					'chat',
					array( "chat_timestamp < $field" )
			);
		}
	}

	/** 
	 * Main function to get everything that's happened since the client's
	 * last request.
	 * 
	 * @return string: JSON encoded string of all data
	 */
	function getNew() {
		global $wgUser;

		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );

		$resT = $dbr->select(
			'chat_users',
			array( 'cu_timestamp' ),
			array( "cu_user_id = {$wgUser->getId()}" ),
			__METHOD__
		);

		$fO = $resT->fetchObject();

		if ( is_object( $fO ) ) {
			$lastCheck = $fO->cu_timestamp;
		} else {
			$lastCheck = 0;
		}

		if ( !$lastCheck ) {
			$dbw->insert(
				'chat_users',
				array(
					'cu_user_id' => $wgUser->getId(),
					'cu_user_name' => $wgUser->getName(),
					'cu_timestamp' => 0,
				),
				__METHOD__
			);
			$lastCheck = 0;
		}

		$thisCheck = MediaWikiChat::now();

		$updateT = $dbw->update(
			'chat_users',
			array(
				'cu_timestamp' => $thisCheck,
				'cu_user_name' => $wgUser->getName()
		 	),
			array(
				'cu_user_id' => $wgUser->getId(),
		 	),
			__METHOD__
		);

		$res = $dbr->select(
			'chat',
			array(
				'chat_user_name', 'chat_user_id', 'chat_message',
				'chat_timestamp', 'chat_type', 'chat_to_name', 'chat_to_id'
			),
			array( "chat_timestamp > $lastCheck" ),
			'',
			__METHOD__,
			array(
				'LIMIT' => 20,
				'ORDER BY' => 'chat_timestamp DESC'
			)
		);

		foreach ( $res as $row ) {
			if ( $row->chat_type == 'message' ) {

				$id = $row->chat_user_id;
				$name = $row->chat_user_name;
				$message = $row->chat_message;
				$timestamp = $row->chat_timestamp;
				$avatar = MediaWikiChat::getAvatar( $id );

				$message = $this->parseMessage( $message );

				$this->data['messages'][] = array(
					'name' => $name,
					'message' => $message,
					'timestamp' => $timestamp,
				);

				$this->data['users'][$name][1] = $avatar;
				$this->data['users'][$name][0] = $id;
			} elseif ( $row->chat_type == 'private message'
					&& (
					 	$row->chat_user_name == $wgUser->getName()
					 	|| $row->chat_to_name == $wgUser->getName()
					 ) ) {

				$message = $row->chat_message;
				$timestamp = $row->chat_timestamp;

				$fromid = $row->chat_user_id;
				$fromname = $row->chat_user_name;
				$toid = $row->chat_to_id;
				$toname = $row->chat_to_name;

				if ( $fromname == $wgUser->getName() ) {
					$convwith = $toname;
					//$convid = $toid;
				} else {
					$convwith = $fromname;
					//$convid = $fromid;
				}

				$fromavatar = MediaWikiChat::getAvatar( $fromid );
				$toavatar = MediaWikiChat::getAvatar( $toid );

				$message = MediaWikiChat::parseMessage( $message );

				$this->data['pms'][] = array(
					'message' => $message,
					'timestamp' => $timestamp,
					'from' => $fromname,
					'conv' => $convwith
				);

				$this->data['users'][$fromname][1] = $fromavatar;
				$this->data['users'][$toname][1] = $toavatar;
				$this->data['users'][$fromname][0] = $fromid;
				$this->data['users'][$toname][0] = $toid;
			} elseif ( $row->chat_type == 'kick' ) {
				if ( $row->chat_to_name == $wgUser->getName() ) {
					$this->data['kick'] = true;
				}
				$this->data['system'][] = array(
					'type' => 'kick',
					'from' => $row->chat_user_name,
					'to' => $row->chat_to_name,
					'timestamp' => $row->chat_timestamp
				);
			} elseif ( $row->chat_type == 'block' ) {
				$this->data['system'][] = array(
					'type' => 'block',
					'to' => $row->chat_to_name,
					'timestamp' => $row->chat_timestamp
				);
			} elseif ( $row->chat_type == 'unblock' ) {
				$this->data['system'][] = array(
					'type' => 'unblock',
					'to' => $row->chat_to_name,
					'timestamp' => $row->chat_timestamp
				);
			}
		}

		$this->data['me'] = $wgUser->getName();

		$this->data['users'][$wgUser->getName()] = array(
			$wgUser->getId(),
			MediaWikiChat::getAvatar( $wgUser->getId() )
		);

		$onlineUsers = MediaWikiChat::getOnline();

		foreach ( $onlineUsers as $user ) {
			$this->data['online'][] = $user[0];
			$x = MediaWikiChat::getAvatar( $user[1] );

			$this ->data['users'][$user[0]][0] = $user[1];
			$this ->data['users'][$user[0]][1] = MediaWikiChat::getAvatar( $user[1] );
		}

		//$this->data['interval'] = MediaWikiChat::getInterval();

		$this->data['now'] = MediaWikiChat::now();

		$this->data['mods'] = array();

		foreach ( $this->data['users'] as $name => $arr ) {
			$user = User::newFromName( $name );
			$groups = $user->getGroups();

			if ( in_array( 'chatmod', $groups ) || in_array( 'sysop', $groups ) ) {
				$this->data['mods'][] = $name;
			}
		}

		$myGroups = $wgUser->getGroups();
		if ( in_array( 'chatmod', $myGroups ) || in_array( 'sysop', $myGroups ) ) {
			$this->data['amIMod'] = true;
		} else {
			$this->data['amIMod'] = false;
		}

		if ( !$wgUser->isAllowed( 'chat' ) ) {
			$this->data['kick'] = true; // if user has since been blocked from chat, kick them now
		}

		return json_encode( $this->data );
	}

}