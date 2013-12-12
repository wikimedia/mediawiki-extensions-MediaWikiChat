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
		$toname = $user->getName();
		$fromid = $wgUser->getId();
		$fromname = $wgUser->getName();
		$timestamp = MediaWikiChat::now();

		$dbw->insert(
			'chat',
			array(
				'chat_to_id' => $toid,
				'chat_to_name' => $toname,
				'chat_user_id' => $fromid,
				'chat_user_name' => $fromname,
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
		global $wgUser;

		if ( $wgUser->isAllowed( 'chat' ) ) {
			$dbr = wfGetDB( DB_SLAVE );

			$timestamp = MediaWikiChat::now() - 1 * 60 * 100; // minus 1 mins

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

				$data[$id] = $name;
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
	static function getInterval() {
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
	 * Parses the given message as wikitext, and replaces smileys
	 *
	 * @param String $message
	 * @return String
	 */
	static function parseMessage( $message ) {
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

		$message = str_ireplace( '[[File:', '[[:File:', $message );
		$message = str_ireplace( '[[!File:', '[[File:', $message );

		$message = "MWCHAT $message";

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
		$text = ltrim( $text );
		return $text;
	}

	/**
	 * Remove entries from chat table if table is already full
	 *
	 * Prevents speeds slowing down due to massive IM tables
	 */
	static function deleteEntryIfNeeded() {
		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );
		$field = $dbr->selectField(
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

		if ( is_int( $field ) ) {
			$field = intval( $field );
			$dbw->delete(
				'chat',
				array( "chat_timestamp < $field" ),
				__METHOD__
			);
		}
	}
}
