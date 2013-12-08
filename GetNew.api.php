<?php

class ChatGetNewAPI extends APIBase {

	public function execute() {
		global $wgUser;

		$result = $this->getResult();
		$mName = $this->getModuleName();

		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );

		$thisCheck = MediaWikiChat::now();

		$res = $dbr->selectField(
				'chat_users',
				array( 'cu_timestamp' ),
				array( "cu_user_id = {$wgUser->getId()}" ),
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
					'cu_user_name' => $wgUser->getName(),
					'cu_timestamp' => $thisCheck,
				),
				__METHOD__
			);
		}

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

		$users = array();

		foreach ( $res as $row ) {
			if ( $row->chat_type == MediaWikiChat::TYPE_MESSAGE ) {

				$id = $row->chat_user_id;
				$name = $row->chat_user_name;
				$message = $row->chat_message;
				$timestamp = $row->chat_timestamp;

				$message = MediaWikiChat::parseMessage( $message );

				$result->addValue( array( $mName, 'messages', $timestamp ), 'from', strval( $id ) );
				$result->addValue( array( $mName, 'messages', $timestamp ), 'text', $message );

				$users[$id] = $name; // ensure message sender is in users list

			} elseif ( $row->chat_type == MediaWikiChat::TYPE_PM
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
				} else {
					$convwith = $fromname;
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
				$result->addValue( array( $mName, 'pms', 'msg' ), 'text', $message );
				$result->addValue( array( $mName, 'pms', 'msg' ), 'timestamp', $timestamp );
				$result->addValue( array( $mName, 'pms', 'msg' ), 'from', $fromname );
				$result->addValue( array( $mName, 'pms', 'msg' ), 'conv', $convwith );

				$users[fromid] = $fromname; // ensure pm sender is in users list
				$users[toid] = $toname; // ensure pm receiver is in users list

			} elseif ( $row->chat_type == MediaWikiChat::TYPE_KICK ) {
				if ( $row->chat_to_name == $wgUser->getName() ) {
					$result->addValue( $mName, 'kick', true );
				}
				$result->addValue( array( $mName, 'messages', 'kick' ), 'from', $row->chat_user_name );
				$result->addValue( array( $mName, 'messages', 'kick' ), 'to', $row->chat_to_name );
				$result->addValue( array( $mName, 'messages', 'kick' ), 'timestamp', $row->chat_timestamp );

			} elseif ( $row->chat_type == MediaWikiChat::TYPE_BLOCK ) {
				$result->addValue( array( $mName, 'messages', 'block' ), 'from', $row->chat_user_name );
				$result->addValue( array( $mName, 'messages', 'block' ), 'to', $row->chat_to_name );
				$result->addValue( array( $mName, 'messages', 'block' ), 'timestamp', $row->chat_timestamp );

			} elseif ( $row->chat_type == MediaWikiChat::TYPE_UNBLOCK ) {
				$result->addValue( array( $mName, 'messages', 'unblock' ), 'from', $row->chat_user_name );
				$result->addValue( array( $mName, 'messages', 'unblock' ), 'to', $row->chat_to_name );
				$result->addValue( array( $mName, 'messages', 'unblock' ), 'timestamp', $row->chat_timestamp );
			}
		}

		$users[$wgUser->getId()] = $wgUser->getName(); // ensure current user is in the users list

		$onlineUsers = MediaWikiChat::getOnline();
		foreach ( $onlineUsers as $id => $name ) {
			$users[$id] = $name; // ensure all online users are present in the users list
		}

		foreach ( $users as $id => $name ) {
			$userObject = User::newFromId( $id );
			$idString = strval( $id );
			echo gettype( $idString );

			$result->addValue( array( $mName, 'users', $idString ), 'name', $name );
			$result->addValue( array( $mName, 'users', $idString ), 'avatar', MediaWikiChat::getAvatar( $id ) );
			if ( array_key_exists( $id, $onlineUsers ) ) {
				$result->addValue( array( $mName, 'users', $idString ), 'online', true );
			}
			$groups = $userObject->getGroups();
			if ( in_array( 'chatmod', $groups ) || in_array( 'sysop', $groups ) ) {
				$result->addValue( array( $mName, 'users', $idString ), 'mod', true );
			}
		}

		//$this->data['interval'] = MediaWikiChat::getInterval();
		$result->addValue( $mName, 'now', MediaWikiChat::now() );

		if ( !$wgUser->isAllowed( 'chat' ) ) {
			$result->addValue( $mName, 'kick', true ); // if user has since been blocked from chat, kick them now
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