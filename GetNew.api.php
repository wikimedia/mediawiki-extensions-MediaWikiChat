<?php


class ChatGetNewAPI extends ApiBase {

	public function execute() {
		global $wgUser, $wgChatSocialAvatars;

		$result = $this->getResult();
		$mName = $this->getModuleName();

		if ( $wgUser->isAllowed( 'chat' ) ) {

			$dbr = wfGetDB( DB_SLAVE );
			$dbw = wfGetDB( DB_MASTER );

			$thisCheck = MediaWikiChat::now();

			$res = $dbr->selectField(
					'chat_users',
					array( 'cu_timestamp' ),
					array( 'cu_user_id' => $wgUser->getId() ),
					__METHOD__
			);

			$lastCheck = strval( $res );

			if ( $lastCheck ) {
				$dbw->update(
					'chat_users',
					array( 'cu_timestamp' => $thisCheck ),
					array( 'cu_user_id' => $wgUser->getId() ),
					__METHOD__
				);
			} else {
				$dbw->insert(
					'chat_users',
					array(
						'cu_user_id' => $wgUser->getId(),
						'cu_timestamp' => $thisCheck,
					),
					__METHOD__
				);
				$lastCheck = 0;
			}

			$res = $dbr->select(
				'chat',
				array( 'chat_user_id', 'chat_message', 'chat_timestamp', 'chat_type', 'chat_to_id' ),
				array( "chat_timestamp > $lastCheck" ),
				'',
				__METHOD__,
				array(
					'LIMIT' => 20,
					'ORDER BY' => 'chat_timestamp DESC'
				)
			);

			$users = array();

			foreach ( $res as $row ) {
				if ( $row->chat_type == MediaWikiChat::TYPE_MESSAGE ) {

					$id = $row->chat_user_id;
					$message = $row->chat_message;
					$timestamp = $row->chat_timestamp;

					$message = MediaWikiChat::parseMessage( $message );

					$result->addValue( array( $mName, 'messages', $timestamp ), 'from', strval( $id ) );
					$result->addValue( array( $mName, 'messages', $timestamp ), '*', $message );

					$users[$id] = true; // ensure message sender is in users list

				} elseif ( $row->chat_type == MediaWikiChat::TYPE_PM
						&& (
							$row->chat_user_id == $wgUser->getId()
							|| $row->chat_to_id == $wgUser->getId()
						) ) {

					$message = $row->chat_message;
					$timestamp = $row->chat_timestamp;

					$fromid = $row->chat_user_id;
					$toid = $row->chat_to_id;

					if ( $fromid == $wgUser->getId() ) {
						$convwith = User::newFromId($toid)->getName();
					} else {
						$convwith = User::newFromId($fromid)->getName();
					}

					$message = MediaWikiChat::parseMessage( $message );

					$result->addValue( array( $mName, 'pms', $timestamp ), '*', $message );
					$result->addValue( array( $mName, 'pms', $timestamp ), 'from', $fromid );
					$result->addValue( array( $mName, 'pms', $timestamp ), 'conv', $convwith );

					$users[$fromid] = true; // ensure pm sender is in users list
					$users[$toid] = true; // ensure pm receiver is in users list

				} elseif ( $row->chat_type == MediaWikiChat::TYPE_KICK ) {
					if ( $row->chat_to_id == $wgUser->getId() ) {
						$result->addValue( $mName, 'kick', true );
					}
					$timestamp = $row->chat_timestamp;
					$result->addValue( array( $mName, 'kicks', $timestamp ), 'from', $row->chat_user_id );
					$result->addValue( array( $mName, 'kicks', $timestamp ), 'to', $row->chat_to_id );

				} elseif ( $row->chat_type == MediaWikiChat::TYPE_BLOCK ) {
					$timestamp = $row->chat_timestamp;
					$result->addValue( array( $mName, 'blocks', $timestamp ), 'from', $row->chat_user_id );
					$result->addValue( array( $mName, 'blocks', $timestamp ), 'to', $row->chat_to_id );

				} elseif ( $row->chat_type == MediaWikiChat::TYPE_UNBLOCK ) {
					$timestamp = $row->chat_timestamp;
					$result->addValue( array( $mName, 'unblocks', $timestamp ), 'from', $row->chat_user_id );
					$result->addValue( array( $mName, 'unblocks', $timestamp ), 'to', $row->chat_to_id );
				}
			}

			$users[$wgUser->getId()] = true; // ensure current user is in the users list

			$onlineUsers = MediaWikiChat::getOnline();
			foreach ( $onlineUsers as $id ) {
				$users[$id] = true; // ensure all online users are present in the users list
			}
			$genderCache = GenderCache::singleton();
			foreach ( $users as $id => $tr ) {
				$userObject = User::newFromId( $id );
				$idString = strval( $id );

				$result->addValue( array( $mName, 'users', $idString ), 'name', $userObject->getName() );
				if ( $wgChatSocialAvatars ) {
					$result->addValue( array( $mName, 'users', $idString ), 'avatar', MediaWikiChat::getAvatar( $id ) );
				}
				if ( in_array( $id, $onlineUsers ) ) {
					$result->addValue( array( $mName, 'users', $idString ), 'online', true );
				}
				$groups = $userObject->getGroups();
				if ( in_array( 'chatmod', $groups ) || in_array( 'sysop', $groups ) ) {
					$result->addValue( array( $mName, 'users', $idString ), 'mod', true );
				}
				$gender = $genderCache->getGenderOf( $userObject );
				$result->addValue( array( $mName, 'users', $idString, ), 'gender', $gender );
			}

			$result->addValue( $mName, 'now', MediaWikiChat::now() );

			if ( !$wgUser->isAllowed( 'chat' ) ) {
				$result->addValue( $mName, 'kick', true ); // if user has since been blocked from chat, kick them now
			}

		} else {
			$result->addValue( $this->getModuleName(), 'error', 'blockedfromchat' );
		}

		return true;
	}

	public function getDescription() {
		return 'Get new messages in the chat.';
	}

	public function getAllowedParams() {
		return parent::getAllowedParams();
	}

	public function getParamDescription() {
		return parent::getParamDescription();
	}

	public function getExamples() {
		return array(
				'api.php?action=chatgetnew'
				=> 'Get new messages in the chat'
		);
	}
}