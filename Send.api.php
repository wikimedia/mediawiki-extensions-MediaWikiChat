<?php

class ChatSendAPI extends ApiBase {

	public function execute() {
		global $wgUser;

		$result = $this->getResult();
		$message = $this->getMain()->getVal( 'message' );

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
						'chat_type' => MediaWikiChat::TYPE_MESSAGE
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

				$logEntry->insert();

				MediaWikiChat::deleteEntryIfNeeded();

				$result->addValue( $this->getModuleName(), 'timestamp', $timestamp );

			} else {
				$result->addValue( $this->getModuleName(), 'error', 'empty message' );
			}
		} else {
			$result->addValue( $this->getModuleName(), 'error', 'blockedfromchat' );
		}

		return true;
	}

	public function getDescription() {
		return 'Send a message to the chat.';
	}

	public function getAllowedParams() {
		return array(
			'message' => array (
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	public function getParamDescription() {
		return array(
			'message' => 'The message to send.'
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=chatsend&message=Hello%20World!'
				=> 'Send "Hello World!" to the chat'
		);
	}
}