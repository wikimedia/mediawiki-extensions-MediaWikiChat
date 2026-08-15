<?php

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiMain;
use MediaWiki\Cache\GenderCache;
use MediaWiki\User\UserGroupManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;

class ChatGetNewAPI extends ApiBase {
	private readonly GetNewWorker $getNewWorker;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		IConnectionProvider $dbProvider,
		GenderCache $genderCache,
		UserGroupManager $userGroupManager,
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->getNewWorker = new GetNewWorker( $dbProvider, $genderCache, $userGroupManager );
	}

	public function execute() {
		// To avoid API warning, register the parameter used to bust browser cache
		$this->getMain()->getVal( '_' );

		$result = $this->getResult();
		$user = $this->getUser();

		if ( $user->isAllowed( 'chat' ) ) {
			$this->getNewWorker->execute( $result, $user, $this->getMain() );
		} else {
			$result->addValue( $this->getModuleName(), 'error', 'blockedfromchat' );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'focussed' => [
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'boolean'
			]
		];
	}

	/** @inheritDoc */
	public function getExamplesMessages() {
		return [
			'action=chatgetnew' => 'apihelp-chatgetnew-example-1'
		];
	}
}
