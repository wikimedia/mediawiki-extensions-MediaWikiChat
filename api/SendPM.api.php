<?php

class ChatSendPMAPI extends ApiBase {

	public function execute() {
		global $wgChatFloodMessages, $wgChatFloodSeconds;
		$result = $this->getResult();
		$user = $this->getUser();

		if ( $user->isAllowed( 'chat' ) && !$user->isBlocked() ) {
			$toId = intval( $this->getMain()->getVal( 'id' ) );

			$originalMessage = $this->getMain()->getVal( 'message' );
			$message = MediaWikiChat::parseMessage( $originalMessage, $user );

			if ( $message != '' ) {
				$dbr = wfGetDB( DB_SLAVE );
				$dbw = wfGetDB( DB_MASTER );

				$fromId = $user->getID();
				$timestamp = MediaWikiChat::now();

				// Flood check
				$res = $dbr->selectField(
					'chat',
					array( 'count(*)' ),
					array( "chat_timestamp > " . ( $timestamp - ( $wgChatFloodSeconds * 100 ) ), " chat_user_id = " . $fromId ),
					__METHOD__
				);
				 if ( $res > $wgChatFloodMessages ) {
					$result->addValue( $this->getModuleName(), 'error', 'flood' );
					return true;
				}

				$dbw->insert(
					'chat',
					array(
						'chat_to_id' => $toId,
						'chat_user_id' => $fromId,
						'chat_timestamp' => $timestamp,
						'chat_message' => $message,
						'chat_type' => MediaWikiChat::TYPE_PM
					),
					__METHOD__
				);

				$logEntry = new ManualLogEntry( 'privatechat', 'send' ); // Action bar in log foo
				$logEntry->setPerformer( $user ); // User object, the user who did this action
				$page = SpecialPage::getTitleFor( 'Chat' );
				$logEntry->setTarget( $page ); // The page that this log entry affects
				$logEntry->setParameters( array(
					'4::message' => $originalMessage, // we want the logs to show the source message, not the parsed one
					'5::to' => User::newFromId( $toId )->getName()
				) );

				$logEntry->insert();

				MediaWikiChat::deleteEntryIfNeeded();
				MediaWikiChat::updateAway( $user );

				$result->addValue( $this->getModuleName(), 'timestamp', $timestamp );

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
			'message' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'id' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	public function getExamplesMessages() {
		return array(
			'action=chatsendpm&id=5&message=Hello%20World!'
				=> 'apihelp-chatsendpm-example-1'
		);
	}

	public function mustBePosted() {
		return true;
	}
}
