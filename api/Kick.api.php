<?php

class ChatKickAPI extends ApiBase {

	public function execute() {
		global $wgChatKicks;

		$user = $this->getUser();
		$result = $this->getResult();
		$toId = $this->getMain()->getVal( 'id' );

		$toUser = User::newFromId( $toId );
		$toName = $toUser->getName();

		if ( $user->isAllowed( 'modchat' ) && !$toUser->isAllowed( 'modchat' ) && $wgChatKicks ) {
			$dbw = wfGetDB( DB_MASTER );

			$fromId = $user->getId();
			$timestamp = MediaWikiChat::now();

			$dbw->insert(
				'chat',
				array(
					'chat_to_id' => $toId,
					'chat_user_id' => $fromId,
					'chat_timestamp' => $timestamp,
					'chat_type' => MediaWikiChat::TYPE_KICK
				),
				__METHOD__
			);

			// Log the kick to Special:Log/chatkick
			$logEntry = new ManualLogEntry( 'chatkick', 'kick' );
			$logEntry->setPerformer( $user );
			$page = SpecialPage::getTitleFor( 'Chat' );
			$logEntry->setTarget( $page );
			$logEntry->setParameters( array(
				'4::kick' => $toName,
			) );
			$logEntry->insert();

			MediaWikiChat::updateAway( $user );

			$result->addValue( $this->getModuleName(), 'timestamp', $timestamp );

		} else {
			if ( !$user->isAllowed( 'modchat' ) ) {
				$result->addValue( $this->getModuleName(), 'error', 'you are not allowed to kick people' );
			}
			if ( $toUser->isAllowed( 'modchat' ) ) {
				$result->addValue( $this->getModuleName(), 'error', 'the person you are kicking is a moderator' );
			}
			if ( !$wgChatKicks ) {
				$result->addValue( $this->getModuleName(), 'error', 'kicking has been disabled' );
			}
		}

		return true;
	}

	public function getAllowedParams() {
		return array(
			'id' => array (
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	public function getExamplesMessages() {
		return array(
			'action=chatkick&id=1'
				=> 'apihelp-chatkick-example-1'
		);
	}

	public function mustBePosted() {
		return true;
	}
}
