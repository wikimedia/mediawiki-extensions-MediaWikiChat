<?php
/**
 * Backend class for various static methods for the MediaWikiChat extension.
 *
 * @file
 */

use MediaWiki\MediaWikiServices;

class MediaWikiChat {

	const TYPE_MESSAGE = 0;
	const TYPE_PM = 1;
	const TYPE_BLOCK = 2;
	const TYPE_UNBLOCK = 3;
	const TYPE_KICK = 4;

	/**
	 * Get the current UNIX time with 100th seconds (i.e. 138524180871).
	 * Standard UNIX timestamp contains only 10 digits.
	 *
	 * @return Integer current UNIX timestamp + 100th seconds
	 */
	static function now() {
		$m = explode( ' ', microtime() );

		return intval( $m[1] ) * 100 +
				intval( $m[0] * 100 );
	}

	/**
	 * Get the path to the specified user's avatar image.
	 *
	 * @param $id Integer: user ID
	 * @return String avatar image path
	 */
	static function getAvatar( $id ) {
		global $wgUploadPath;

		$avatar = new wAvatar( $id, 's' );

		return $wgUploadPath . '/avatars/' . $avatar->getAvatarImage();
	}

	/**
	 * Send a message to the database that a user has been (un)blocked
	 *
	 * @param $type String: block/unblock: whether the user has been blocked or unblocked
	 * @param $user User: user that has been blocked/unblocked
	 * @param $performer User: user that did the blocking/unblocking
	 */
	static function sendSystemBlockingMessage( $type, $user, User $performer ) {
		$dbw = wfGetDB( DB_MASTER );

		$toid = $user->getId();
		$fromid = $performer->getId();
		$timestamp = self::now();

		$dbw->insert(
			'chat',
			[
				'chat_to_id' => $toid,
				'chat_user_id' => $fromid,
				'chat_timestamp' => $timestamp,
				'chat_type' => $type
			]
		);
	}

	/**
	 * Get the list of users who are online, if we have the "chat" user right.
	 *
	 * @param User $user
	 * @return Mixed array of user IDs and user names on success, boolean false
	 * if the current user doesn't have the "chat" right
	 */
	static function getOnline( User $user ) {
		global $wgChatOnlineTimeout;

		if ( $user->isAllowed( 'chat' ) ) {
			$dbr = wfGetDB( DB_REPLICA );

			$now = self::now();
			$timestamp = $now - $wgChatOnlineTimeout;

			$res = $dbr->select(
				'chat_users',
				[ 'cu_user_id', 'cu_away' ],
				[
					"cu_timestamp > $timestamp",
					"cu_user_id != {$user->getId()}"
				],
				__METHOD__
			);

			$data = [];

			foreach ( $res as $row ) {
				$away = $row->cu_away;

				$data[$row->cu_user_id] = $now - $away; // number of microseconds since user was last seen
			}
			return $data;
		} else {
			return false;
		}
	}

	/**
	 * Is the current user online or not?
	 *
	 * @param User $user
	 * @return bool whether they're online or not.
	 */
	static function amIOnline( User $user ) {
		global $wgChatOnlineTimeout;

		$dbr = wfGetDB( DB_REPLICA );

		$timestamp = self::now() - $wgChatOnlineTimeout;

		$res = $dbr->select(
			'chat_users',
			'cu_user_id',
			[
				"cu_timestamp > $timestamp",
				"cu_user_id = {$user->getId()}"
			],
			__METHOD__
		);

		return $res->numRows();
	}

	/**
	 * Get interval to poll the server from. Based on the average milliseconds between recent messages.
	 *
	 * @return Integer polling interval to use (how long between each poll)
	 */
	static function getInterval() {
		$dbr = wfGetDB( DB_REPLICA );
		$maxInterval = 30 * 1000;
		$minInterval = 3 * 1000;

		$res = $dbr->select(
			'chat',
			'chat_timestamp',
			[ 'chat_type' => self::TYPE_MESSAGE ],
			__METHOD__,
			[
				'LIMIT' => 1,
				'OFFSET' => 4,
				'ORDER BY' => 'chat_timestamp DESC'
			]
		);

		if ( $res->numRows() ) {
			$row = $res->fetchObject();
			$oldest = $row->chat_timestamp;
			$now = self::now();
			// / 5 to find average, then / 2, then * 10 as MWC timestamps are 100th seconds, JS intervals are 1000th seconds
			$av = ( $now - $oldest );

			if ( $av > $maxInterval ) {
				$av = $maxInterval;
			} elseif ( $av < $minInterval ) {
				$av = $minInterval;
			}

		return $av;
		} else { // before there are any messages to consider
			return 7 * 1000; // use 7 secs
		}
	}

	/**
	 * Parses the given message as wikitext, and replaces smileys,
	 * provided $wgChatRichMessages is enabled
	 *
	 * @param String $message message to parse
	 * @param User $user current user object
	 * @return String parsed message
	 */
	static function parseMessage( $message, $user ) {
		global $wgChatRichMessages, $wgChatUseStyleAttribute;

		$smileyString = wfMessage( 'smileys' )->plain();
		$smileyData = explode( '*', $smileyString );

		if ( is_array( $smileyData ) ) {
			$smileys = [];
			foreach ( $smileyData as $line ) {
				$line = trim( $line );
				$bits = explode( ' ', $line );

				if ( count( $bits ) > 1 ) {
					$filename = array_pop( $bits );
					foreach ( $bits as $code ) {
						$smileys[$code] = $filename;
					}
				}
			}
		}

		if ( $wgChatRichMessages ) {
			$message = str_ireplace( '[[', '[[:', $message ); // prevent users showing huge local images in chat

			if ( !$wgChatUseStyleAttribute ) {
				$message = preg_replace( '#<([a-zA-z].+?) (.?)style=["\'].+?["\'](.?)>#', '<$1 $2$3>', $message ); // remove style attribute of html elements
			}

			$message = preg_replace_callback( // prevent smileys wrapped in <nowiki> tags rendering
				"#<nowiki>(.+?)</nowiki>#i",
				function ( $matches ) use ( $smileys ) { // loop through instances of <nowiki>
					$s = $matches[0];
					foreach ( $smileys as $chars => $filename ) {
						$replacement = mb_encode_numericentity( $chars, [ 0x0, 0xffff, 0, 0xffff ], 'UTF-8' ); // converts ALL characters to html entities

						$s = str_ireplace( $chars, $replacement, $s ); // for each instance, replace smiley chars with converted versions, so they don't render
					}
					return $s;
				},
				$message
			);

			$opts = new ParserOptions( $user );
			$opts->setExternalLinkTarget( '_blank' );
			$opts->setAllowSpecialInclusion( false );
			$opts->setAllowExternalImages( false );

			$parser = new Parser();

			$message = $parser->preSaveTransform(
				$message,
				SpecialPage::getTitleFor( 'Chat', 'message' ),
				$user,
				$opts
			);

			$parseOut = $parser->parse(
				$message,
				SpecialPage::getTitleFor( 'Chat', 'message' ), // the message subpage tells our hook this is message
				$opts,
				false // $linestart = false, prevents *#:; lists rendering
			);

			$message = $parseOut->getText( [ 'enableSectionEditLinks' => false ] );
			$message = trim( $message );
		} else {
			$message = htmlentities( $message );

			$message = preg_replace( '#(http[s]?\:\/\/[^ \n]+)#', '<a target="_blank" href="$1">$1</a>', $message ); // turn URLs into links
		}

		$message = str_replace( [ '&nbsp;', '&#160;' ], ' ', $message ); // replace nonbreaking space with regular space
		$message = ' ' . $message . ' '; // to allow smileys at beginning/end of message

		$repoGroup = MediaWikiServices::getInstance()->getRepoGroup();
		foreach ( $smileys as $chars => $filename ) {
			$chars = htmlspecialchars( $chars ); // needed for replacements containing special HTML characters, and for HTML

			$file = $repoGroup->findFile( $filename );
			if ( $file ) {
				$url = $file->getFullUrl();

				$image = " <img src='$url' alt='$chars' title='$chars' /> ";

				$message = str_ireplace( " $chars ", $image, $message ); // spaces prevent converting smileys in the middle of word
			}
		}

		return trim( $message );
	}

	/**
	 * Remove entries from chat table if table is already full
	 *
	 * Prevents speeds slowing down due to massive IM tables
	 */
	static function deleteEntryIfNeeded() {
		$dbr = wfGetDB( DB_REPLICA );
		$field = $dbr->selectField(
			'chat',
			'chat_timestamp',
			[],
			__METHOD__,
			[
				'ORDER BY' => 'chat_timestamp DESC',
				'OFFSET' => 50,
				'LIMIT' => 1
			]
		);

		if ( $field ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->delete(
				'chat',
				[ "chat_timestamp < $field" ],
				__METHOD__
			);
		}
	}

	/**
	 * Update the away timestamp (last time user sent message) for the given user to now
	 *
	 * @param User $user
	 */
	static function updateAway( User $user ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update(
			'chat_users',
			[ 'cu_away' => self::now() ],
			[ 'cu_user_id' => $user->getId() ]
		);
	}
}
