<?php

class GetNewAPI extends APIBase {

	public function execute() {

		global $wgUser;

		$result = $this->getResult();
		$mName = $this->getModuleName();

		$dbr = wfGetDB( DB_SLAVE );
		$dbw = wfGetDB( DB_MASTER );

		$res = $dbr->selectField(
				'chat_users',
				array( 'cu_timestamp' ),
				array( "cu_user_id = {$wgUser->getId()}" ),
				__METHOD__
		);

		if ( is_int( $res ) ) {
			$lastCheck = $res;
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
			if ( $row->chat_type == 'message' ) {

				$id = $row->chat_user_id;
				$name = $row->chat_user_name;
				$message = $row->chat_message;
				$timestamp = $row->chat_timestamp;
				$avatar = MediaWikiChat::getAvatar( $id );

				$message = MediaWikiChat::parseMessage( $message );

				$result->addValue( array( $mName, 'messages', 'msg' ), 'from', $name );
				$result->addValue( array( $mName, 'messages', 'msg' ), 'text', $message );
				$result->addValue( array( $mName, 'messages', 'msg' ), 'timestamp', $timestamp );

				$users[id] = $name;

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

				$users[fromid] = $fromname;
				$users[toid] = $toname;

			} elseif ( $row->chat_type == 'kick' ) {
				if ( $row->chat_to_name == $wgUser->getName() ) {
					$result->addValue( $mName, 'kick', true );
				}
				$result->addValue( array( $mName, 'messages', 'kick' ), 'from', $row->chat_user_name );
				$result->addValue( array( $mName, 'messages', 'kick' ), 'to', $row->chat_to_name );
				$result->addValue( array( $mName, 'messages', 'kick' ), 'timestamp', $row->chat_timestamp );

			} elseif ( $row->chat_type == 'block' ) {
				$result->addValue( array( $mName, 'messages', 'block' ), 'from', $row->chat_user_name );
				$result->addValue( array( $mName, 'messages', 'block' ), 'to', $row->chat_to_name );
				$result->addValue( array( $mName, 'messages', 'block' ), 'timestamp', $row->chat_timestamp );

			} elseif ( $row->chat_type == 'unblock' ) {
				$result->addValue( array( $mName, 'messages', 'unblock' ), 'from', $row->chat_user_name );
				$result->addValue( array( $mName, 'messages', 'unblock' ), 'to', $row->chat_to_name );
				$result->addValue( array( $mName, 'messages', 'unblock' ), 'timestamp', $row->chat_timestamp );

			}
		}

		$result->addValue( $mName, 'me', $wgUser->getName() );

		$users[$wgUser->getId()] = $wgUser->getName();

		$onlineUsers = MediaWikiChat::getOnline();

		foreach ( $onlineUsers as $id => $name ) {
			$users[$id] = $name;
		}

		foreach ( $users as $id => $name ) {
			$result->addValue( array( $mName, 'users', 'user' ), 'id', $id );
			$result->addValue( array( $mName, 'users', 'user' ), 'name', $name );
			$result->addValue( array( $mName, 'users', 'user' ), 'avatar', MediaWikiChat::getAvatar( $id ) );
			if ( array_key_exists( $id, $onlineUsers ) ) {
				$result->addValue( array( $mName, 'users', 'user' ), 'online', true );
			}
		}

		//$this->data['interval'] = MediaWikiChat::getInterval();
		$result->addValue( $mName, 'now', MediaWikiChat::now() );

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