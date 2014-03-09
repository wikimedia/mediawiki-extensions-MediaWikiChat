<?php

class ChatAwayAPI extends ApiBase {

	public function execute() {
		$result = $this->getResult();
		$user = $this->getUser();

		if ( $user->isAllowed( 'chat' ) ) {
			$away = $this->getMain()->getVal( 'away' ) ? 1 : 0;

			$dbw = wfGetDB( DB_MASTER );

			$dbw->update(
				'chat_users',
				array( 'cu_away' => $away ),
				array( 'cu_user_id' => $user->getId() ),
				__METHOD__
			);

			$result->addValue( $this->getModuleName(), 'timestamp', MediaWikiChat::now() );

		} else {
			$result->addValue( $this->getModuleName(), 'error', 'you are not allowed to chat' );
		}

		return true;
	}

	public function getDescription() {
		return 'Toggle away on the current user.';
	}

	public function getAllowedParams() {
		return array(
			'away' => array (
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	public function getParamDescription() {
		return array(
			'away' => 'Whether the current user should be away or not away.'
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=chataway&away=true' => 'Make the current user away'
		);
	}

	public function mustBePosted() {
		return true;
	}
}