<?php
/**
 * Backend class for various static methods for the MediaWikiChat extension.
 *
 * @file
 */
class MediaWikiChat {

	const TYPE_MESSAGE = 0;
	const TYPE_PM = 1;
	const TYPE_BLOCK = 2;
	const TYPE_UNBLOCK = 3;
	const TYPE_KICK = 4;

	/**
	 * Get the current UNIX time with microseconds (i.e. 138524180871).
	 * Standard UNIX timestamp contains only 10 digits.
	 *
	 * @return Integer: current UNIX timestamp + microseconds
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
	 * @return String: avatar image path
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
	 */
	static function sendSystemBlockingMessage( $type, $user ) {
		global $wgUser;

		$dbw = wfGetDB( DB_MASTER );

		$toid = $user->getId();
		$fromid = $wgUser->getId();
		$timestamp = MediaWikiChat::now();

		$dbw->insert(
			'chat',
			array(
				'chat_to_id' => $toid,
				'chat_user_id' => $fromid,
				'chat_timestamp' => $timestamp,
				'chat_type' => $type
			)
		);
	}

	/**
	 * Get the list of users who are online, if we have the "chat" user right.
	 *
	 * @return Mixed: array of user IDs and user names on success, boolean false
	 *                if the current user doesn't have the "chat" right
	 */
	static function getOnline() {
		global $wgUser, $wgChatOnlineTimeout;

		if ( $wgUser->isAllowed( 'chat' ) ) {
			$dbr = wfGetDB( DB_SLAVE );

			$timestamp = MediaWikiChat::now() - $wgChatOnlineTimeout;

			$res = $dbr->select(
				'chat_users',
				'cu_user_id',
				array(
					"cu_timestamp > $timestamp",
					"cu_user_id != {$wgUser->getId()}"
				),
				__METHOD__
			);

			$data = array();

			foreach ( $res as $row ) {
				$id = $row->cu_user_id;

				$data[] = $id;
			}
			return $data;
		} else {
			return false;
		}
	}

	/**
	 * Is the current user online or not?
	 *
	 * @return boolean: whether they're online or not.
	 */
	static function amIOnline() {
		global $wgUser, $wgChatOnlineTimeout;

		$dbr = wfGetDB( DB_SLAVE );

		$timestamp = MediaWikiChat::now() - $wgChatOnlineTimeout;

		$res = $dbr->select(
			'chat_users',
			'cu_user_id',
			array(
				"cu_timestamp > $timestamp",
				"cu_user_id = {$wgUser->getId()}"
			),
			__METHOD__
		);

		return $res->numRows();
	}

	/**
	 * Get average milliseconds beteen recent messages. Note: not currently in use
	 *
	 * @return Integer: average milliseconds between message sends
	 */
	static function getInterval() {
		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select(
			'chat',
			'chat_timestamp',
			array( 'chat_type' => MediaWikiChat::TYPE_MESSAGE ),
			__METHOD__,
			array(
				'LIMIT' => 5,
				'ORDER BY' => 'chat_timestamp DESC'
			)
		);

		$i = 0;

		foreach ( $res as $row ) {
			if ( $i == 0 ) {
				$latest = $row;
			} elseif ( $i == 4 ) {
				$oldest = $row;
			}
			$i++;
		}

		$latestTime = $latest->chat_timestamp;
		$oldestTime = $oldest->chat_timestamp;

		$av = ( $latestTime - $oldestTime ) / 5;

		return $av;
	}

	/**
	 * Parses the given message as wikitext, and replaces smileys,
	 * provided $wgChatRichMessages is enabled
	 *
	 * @param String $message: message to parse
	 * @return String: parsed message
	 */
	static function parseMessage( $message ) {
		global $wgChatRichMessages;

		if ( $wgChatRichMessages ) {

			$rawSmileyData = wfMessage( 'smileys' )->plain();
			$smileyData = explode( '* ', $rawSmileyData );

			$smileys = array();

			if ( is_array( $smileyData ) ) {
				foreach ( $smileyData as $line ) {
					$bits = explode( ' ', $line );

					if ( count( $bits ) > 1 ) {
						$chars = trim( $bits[0] );
						$charsSafe = htmlspecialchars( $chars );
						$filename = trim( $bits[1] );

						$image = "[[!File:$filename|x20px|alt=$charsSafe|link=|$charsSafe]]";

						$smileys[$chars] = $image; // given chars give given image
						$smileys[strtolower( $chars )] = $image; // given chars in upper or
						$smileys[strtoupper( $chars )] = $image; // lower case give given image
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

			$message = str_ireplace( '[[File:', '[[:File:', $message ); // prevent users showing huge local images in chat
			$message = str_ireplace( '[[!File:', '[[File:', $message );

			$message = "MWCHAT $message"; // flag to show the parser this is a chat message

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

			$message = $parseOut->getText();
			$message = str_replace( 'MWCHAT', '', $message );
			$message = ltrim( $message );
		} else {
			$message = htmlentities($message);
		}

		return $message;
	}

	/**
	 * Remove entries from chat table if table is already full
	 *
	 * Prevents speeds slowing down due to massive IM tables
	 */
	static function deleteEntryIfNeeded() {
		$dbr = wfGetDB( DB_SLAVE );
		$field = $dbr->selectField(
			'chat',
			'chat_timestamp',
			array(),
			__METHOD__,
			array(
				'ORDER BY' => 'chat_timestamp DESC',
				'OFFSET' => 50,
				'LIMIT' => 1
			)
		);

		if ( $field ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->delete(
				'chat',
				array( "chat_timestamp < $field" ),
				__METHOD__
			);
		}
	}
}