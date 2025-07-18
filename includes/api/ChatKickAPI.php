<?php

use MediaWiki\Api\ApiBase;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\ParamValidator\ParamValidator;

class ChatKickAPI extends ApiBase {

	public function execute() {
		global $wgChatKicks;

		$user = $this->getUser();
		$result = $this->getResult();
		$toId = (int)$this->getMain()->getVal( 'id' );

		$toUser = User::newFromId( $toId );
		$toName = $toUser->getName();

		if ( $user->isAllowed( 'modchat' ) && !$toUser->isAllowed( 'modchat' ) && $wgChatKicks ) {
			$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();

			$fromId = $user->getId();
			$timestamp = MediaWikiChat::now();

			$dbw->insert(
				'chat',
				[
					'chat_to_id' => $toId,
					'chat_user_id' => $fromId,
					'chat_timestamp' => $timestamp,
					'chat_type' => MediaWikiChat::TYPE_KICK
				],
				__METHOD__
			);

			// Log the kick to Special:Log/chat
			$logEntry = new ManualLogEntry( 'chat', 'kick' );
			$logEntry->setPerformer( $user );
			$page = SpecialPage::getTitleFor( 'Chat' );
			$logEntry->setTarget( $page );
			$logEntry->setParameters( [
				'4::kick' => $toName,
			] );
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

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'id' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true
			]
		];
	}

	/** @inheritDoc */
	public function getExamplesMessages() {
		return [
			'action=chatkick&id=1' => 'apihelp-chatkick-example-1'
		];
	}
}
