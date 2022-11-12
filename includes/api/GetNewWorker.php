<?php

use MediaWiki\MediaWikiServices;

class GetNewWorker {
	/**
	 * Internals of GetNew, separate class so they can be used by other API actions
	 *
	 * @param ApiResult $result
	 * @param User $user
	 * @param ApiMain $main
	 */
	static function execute( ApiResult $result, User $user, ApiMain $main ) {
		global $wgChatOnlineTimeout;
		$dbr = wfGetDB( DB_REPLICA );
		$dbw = wfGetDB( DB_PRIMARY );
		$mName = 'chatgetnew';

		$thisCheck = MediaWikiChat::now();

		$lastCheck = $dbr->selectField(
			'chat_users',
			'cu_timestamp',
			[ 'cu_user_id' => $user->getId() ],
			__METHOD__
		);

		if ( $lastCheck ) {
			$dbw->update(
				'chat_users',
				[ 'cu_timestamp' => $thisCheck ],
				[ 'cu_user_id' => $user->getId() ],
				__METHOD__
			);

		} else {
			$dbw->insert(
				'chat_users',
				[
					'cu_user_id' => $user->getId(),
					'cu_timestamp' => $thisCheck,
				],
				__METHOD__
			);
			$lastCheck = 0;
		}

		if ( $lastCheck < $thisCheck - $wgChatOnlineTimeout || $main->getVal( 'focussed' ) ) {
			MediaWikiChat::updateAway( $user ); // user is returning from offline, so say they're not away, or their window is marked as focussed.
		}

		$res = $dbr->select(
			'chat',
			[ 'chat_user_id', 'chat_message', 'chat_timestamp', 'chat_type', 'chat_to_id' ],
			[ "chat_timestamp > $lastCheck" ],
			'',
			__METHOD__,
			[
				'LIMIT' => 20,
				'ORDER BY' => 'chat_timestamp DESC'
			]
		);

		$users = [];

		$prevTimestamp = 0;

		foreach ( $res as $row ) {
			$timestamp = $row->chat_timestamp;
			if ( $timestamp == $prevTimestamp ) {
				$timestamp += 1; // prevent dupe timestamps
			}

			if ( $row->chat_type == MediaWikiChat::TYPE_MESSAGE ) {

				$id = $row->chat_user_id;
				$message = $row->chat_message;

				$result->addValue( [ $mName, 'messages', $timestamp ], 'from', strval( $id ) );
				$result->addValue( [ $mName, 'messages', $timestamp ], '*', $message );

				$users[$id] = true; // ensure message sender is in users list

			} elseif ( $row->chat_type == MediaWikiChat::TYPE_PM
				&& (
					$row->chat_user_id == $user->getId()
					|| $row->chat_to_id == $user->getId()
				) ) {

				$message = $row->chat_message;

				$fromid = $row->chat_user_id;
				$toid = $row->chat_to_id;

				if ( $fromid == $user->getId() ) {
					$convwith = User::newFromId( $toid )->getName();
				} else {
					$convwith = User::newFromId( $fromid )->getName();
				}

				$result->addValue( [ $mName, 'pms', $timestamp ], '*', $message );
				$result->addValue( [ $mName, 'pms', $timestamp ], 'from', $fromid );
				$result->addValue( [ $mName, 'pms', $timestamp ], 'conv', $convwith );

				$users[$fromid] = true; // ensure pm sender is in users list
				$users[$toid] = true; // ensure pm receiver is in users list

			} elseif ( $row->chat_type == MediaWikiChat::TYPE_KICK ) {
				if ( $row->chat_to_id == $user->getId() ) {
					$result->addValue( $mName, 'kick', true );
				}
				$result->addValue( [ $mName, 'kicks', $timestamp ], 'from', $row->chat_user_id );
				$result->addValue( [ $mName, 'kicks', $timestamp ], 'to', $row->chat_to_id );

			} elseif ( $row->chat_type == MediaWikiChat::TYPE_BLOCK ) {
				$result->addValue( [ $mName, 'blocks', $timestamp ], 'from', $row->chat_user_id );
				$result->addValue( [ $mName, 'blocks', $timestamp ], 'to', $row->chat_to_id );

			} elseif ( $row->chat_type == MediaWikiChat::TYPE_UNBLOCK ) {
				$result->addValue( [ $mName, 'unblocks', $timestamp ], 'from', $row->chat_user_id );
				$result->addValue( [ $mName, 'unblocks', $timestamp ], 'to', $row->chat_to_id );
			}

			$prevTimestamp = $timestamp;
		}

		$users[$user->getId()] = true; // ensure current user is in the users list

		$onlineUsers = MediaWikiChat::getOnline( $user );
		foreach ( $onlineUsers as $id => $away ) {
			$users[$id] = true; // ensure all online users are present in the users list
		}
		$genderCache = MediaWikiServices::getInstance()->getGenderCache();
		$userGroupManager = MediaWikiServices::getInstance()->getUserGroupManager();
		foreach ( $users as $id => $tr ) {
			$userObject = User::newFromId( $id );
			$idString = strval( $id );

			$result->addValue( [ $mName, 'users', $idString ], 'name', $userObject->getName() );
			if ( class_exists( 'SocialProfileHooks' ) ) { // is SocialProfile installed?
				$result->addValue( [ $mName, 'users', $idString ], 'avatar', MediaWikiChat::getAvatar( $id ) );
			}
			if ( array_key_exists( $id, $onlineUsers ) ) {
				$result->addValue( [ $mName, 'users', $idString ], 'online', 'true' );
				$result->addValue( [ $mName, 'users', $idString ], 'away', $onlineUsers[$id] );
			}
			$groups = $userGroupManager->getUserGroups( $userObject );
			if ( in_array( 'chatmod', $groups ) || in_array( 'sysop', $groups ) ) {
				$result->addValue( [ $mName, 'users', $idString ], 'mod', 'true' );
			}
			$gender = $genderCache->getGenderOf( $userObject );
			$result->addValue( [ $mName, 'users', $idString, ], 'gender', $gender );
		}

		$result->addValue( $mName, 'now', MediaWikiChat::now() );

		$result->addValue( $mName, 'interval', MediaWikiChat::getInterval() );

		if ( !$user->isAllowed( 'chat' ) ) {
			$result->addValue( $mName, 'kick', 'true' ); // if user has since been blocked from chat, kick them now
		}
	}
}
