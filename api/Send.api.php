<?php

class ChatSendAPI extends ApiBase {

	public function execute() {
		global $wgChatFloodMessages, $wgChatFloodSeconds, $wgChatMaxMessageLength;

		$result = $this->getResult();
		$user = $this->getUser();

		if ( $user->isAllowed( 'chat' ) && !$user->isBlocked() ) {
			$originalMessage = $this->getMain()->getVal( 'message' );
			$message = MediaWikiChat::parseMessage( $originalMessage, $user );

			if ( $message != '' ) {
				$dbw = wfGetDB( DB_MASTER );
				$dbr = wfGetDB( DB_REPLICA );

				$id = $user->getId();
				$timestamp = MediaWikiChat::now();

				if ( strlen( $message ) > $wgChatMaxMessageLength ) {
					$result->addValue( $this->getModuleName(), 'error', 'length' );
					return true;
				}

				// Flood check
				$res = $dbr->selectField(
					'chat',
					array( 'count(*)' ),
					array( "chat_timestamp > " . ( $timestamp - ( $wgChatFloodSeconds * 100 ) ), " chat_user_id = " . $id ),
					__METHOD__
				);
				if ( $res > $wgChatFloodMessages ) {
					$result->addValue( $this->getModuleName(), 'error', 'flood' );
					return true;
				}

				$dbw->insert(
					'chat',
					array(
						'chat_user_id' => $id,
						'chat_timestamp' => $timestamp,
						'chat_message' => $message,
						'chat_type' => MediaWikiChat::TYPE_MESSAGE
					)
				);

				$logEntry = new ManualLogEntry( 'chat', 'send' ); // Action bar in log foo
				$logEntry->setPerformer( $user ); // User object, the user who did this action
				$page = SpecialPage::getTitleFor( 'Chat' );
				$logEntry->setTarget( $page ); // The page that this log entry affects
				$logEntry->setParameters( array(
					'4::message' => $originalMessage, // we want the logs to show the source message, not the parsed one
				) );

				$logID = $logEntry->insert();

				if ( class_exists( 'CheckUserHooks' ) ) {
					$rc = $logEntry->getRecentChange( $logID );

					CheckUserHooks::updateCheckUserData( $rc );
				}

				MediaWikiChat::deleteEntryIfNeeded();
				MediaWikiChat::updateAway( $user );

				GetNewWorker::execute( $result, $user, $this->getMain() );

			} else {
				$result->addValue( $this->getModuleName(), 'error', 'empty message' );
			}
		} else {
			$result->addValue( $this->getModuleName(), 'error', 'blockedfromchat' );
		}

		return true;
	}

	public function getAllowedParams() {
		return array(
			'message' => array (
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	public function getExamplesMessages() {
		return array(
			'action=chatsend&message=Hello%20World!'
				=> 'apihelp-chatsend-example-1'
		);
	}

	public function mustBePosted() {
		return true;
	}
}
