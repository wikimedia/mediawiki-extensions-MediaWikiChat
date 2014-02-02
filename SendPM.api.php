<?php

class ChatSendPMAPI extends ApiBase {

	public function execute() {
		global $wgUser;

		$result = $this->getResult();
		$message = $this->getMain()->getVal( 'message' );
		$toId = intval( $this->getMain()->getVal( 'id' ) );

		if ( $wgUser->isAllowed( 'chat' ) ) {
			if ( $message != '' ) {
				$dbw = wfGetDB( DB_MASTER );

				$fromId = $wgUser->getID();
				$timestamp = MediaWikiChat::now();

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

	public function mustBePosted() {
		return true;
	}
}