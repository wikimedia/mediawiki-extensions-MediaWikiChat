<?php
/**
 * Main backend class for the MediaWikiChat extension.
 *
 * @file
 */
class MediaWikiChat {

	public $data;

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
	 * @todo FIXME: this is both horribly platform *and* project-specific
	 * @param $id Integer: user ID
	 * @return String: avatar image path
	 */
	function getAvatar( $id ) {
		if ( is_file( '/var/www/wiki/images/avatars/' . $id . '_s.png' ) ) {
			$avatar = '/images/avatars/' . $id . '_s.png';
		} elseif ( is_file( '/var/www/wiki/images/avatars/' . $id . '_s.jpg' ) ) {
			$avatar = '/images/avatars/' . $id . '_s.jpg';
		} else {
			$avatar = '/images/avatars/default_s.gif';
		}

		return $avatar;
	}

	public static function onUserRights( $user, array $add, array $remove ) {
		if ( in_array( 'blockedfromchat', $add ) ) {
			$this->sendSystemBlockingMessage( 'block', $user );
		}

		if ( in_array( 'blockedfromchat', $remove ) ) {
			$this->sendSystemBlockingMessage( 'unblock', $user );
		}

		return true;
	}

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

	function kick( $toName, $toId ) {
		global $wgUser;

		if ( $wgUser->isAllowed( 'modchat' ) ) {
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
	 * @return Mixed: timestamp (on success), error message (if the provided
	 *                message to send was empty) or boolean false (if the user
	 *                doesn't have the "chat" user right)
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

				return $timestamp;
			} else {
				// @todo FIXME: i18n
				return 'blank messages not allowed';
			}
		} else {
			return false;
		}
	}

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
			// @todo FIXME: wtf?
			trigger_error( "You are blocked from chat", E_USER_ERROR );
			return false;
		}
	}

	function getInterval() {
		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select(
			'chat',
			'chat_timestamp',
			'',
			__METHOD__,
			array(
				'LIMIT' => 5,
				'ORDER BY' => 'chat_timestamp DESC'
			)
		);

		// @todo FIXME: everything below here is just so wrong on so many
		// different levels
		$res2 = $dbr->selectSQLText(
			'chat',
			'chat_timestamp',
			'',
			__METHOD__,
			array(
				'LIMIT' => 5,
				'ORDER BY' => 'chat_timestamp DESC'
			)
		);

		return get_class_methods( $res );

		$latest = $res->fetchRow();
		$latest = $latest['chat_timestamp'];
		$res->fetchRow();
		$res->fetchRow();
		$res->fetchRow();
		$oldest = $res->fetchRow();
		$oldest = $oldest['chat_timestamp'];

		$av = ( $latest - $oldest ) / 5;

		return $av;
	}

	function parseMessage( $message ) {
		$s2 = wfMessage( 'smileys' )->plain();
		$sm2 = explode( '* ', $s2 );

		$this->data['debug']['log'][] = 'parse';
		$this->data['debug']['log'][] = $sm2;

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

		$this->data['messager'] = $message;

		$message = ' ' . $message . ' ';

		$this->data['debug']['log'][] = $smileys;

		if ( is_array( $smileys ) ) {
			foreach ( $smileys as $chars => $image ) {
				$chars = preg_quote( $chars );

				$this->data['debug']['messager1'][] = $message;

				$message = preg_replace( '` ' . $chars . ' `', ' ' . $image . ' ', $message );

				$this->data['debug']['messager2'][] = $message;
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

	function getNew() {
		global $wgUser;

		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );

		$this->data = array();

		$resT = $dbr->select(
			'chat_users',
			array( 'cu_timestamp' ),
			array( "cu_user_id = {$wgUser->getId()}" ),
			__METHOD__
		);

		$resTt = $dbr->selectSQLText(
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
		$text = $dbr->selectSQLText(
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

		$this->data['debug']['log'][] = $lastCheck;
		$this->data['debug']['log'][] = $text;
		$this->data['debug']['log'][] = get_class( $res );
		$results = 0;

		foreach ( $res as $row ) {
			if ( $row->chat_type == 'message' ) {
				$this->data['debug']['log'][] = 'result!';
				$results += 1;

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

				$convwith = 'convwith was not defined';
				$this->data['debug']['log'][] = $convwith;

				if ( $fromname == $wgUser->getName() ) {
					$convwith = $toname;
					//$convid = $toid;
				} else {
					$convwith = $fromname;
					//$convid = $fromid;
				}

				//echo $convwith;

				$fromavatar = MediaWikiChat::getAvatar( $fromid );
				$toavatar = MediaWikiChat::getAvatar( $toid );

				$message = MediaWikiChat::parseMessage( $message );

				$this->data['debug']['log'][] = $convwith;

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

		$this->data['debug']['log'][] = $results;

		$this->data['debug']['lastcheck'] = $lastCheck;
		$this->data['debug']['thischeck'] = $thisCheck;

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

		$this->data['interval'] = MediaWikiChat::getInterval();

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