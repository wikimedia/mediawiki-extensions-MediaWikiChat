<?php

class ChatGetNewAPI extends ApiBase {

	public function execute() {
		$result = $this->getResult();
		$user = $this->getUser();

		if ( $user->isAllowed( 'chat' ) ) {
			GetNewWorker::execute( $result, $user, $this->getMain() );
		} else {
			$result->addValue( $this->getModuleName(), 'error', 'blockedfromchat' );
		}

		return true;
	}

	public function getAllowedParams() {
		return array(
			'focussed' => array(
				ApiBase::PARAM_REQUIRED => false,
				ApiBase::PARAM_TYPE => 'boolean'
			)
		);
	}

	public function getExamplesMessages() {
		return array(
				'action=chatgetnew'
				=> 'apihelp-chatgetnew-example-1'
		);
	}
}
