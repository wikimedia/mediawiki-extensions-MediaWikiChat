<?php

class ChatWikiModule extends ResourceLoaderWikiModule {
	/**
	 * Get a list of pages used by this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		return [
			'MediaWiki:Chat.css' => [ 'type' => 'style' ],
			'MediaWiki:Chat.js' => [ 'type' => 'script' ],
		];
	}
}
