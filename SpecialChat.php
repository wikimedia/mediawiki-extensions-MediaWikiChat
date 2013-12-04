<?php

class SpecialChat extends SpecialPage {

	/**
	 * Constructor -- set up the new special page
	 */
	public function __construct() {
		parent::__construct( 'Chat', 'chat' );
	}

	/**
	 * Show the special page
	 *
	 * @param $par Mixed: parameter passed to the special page or null
	 */
	public function execute( $par ) {
		$out = $this->getOutput();

		// Set the page title, robot policies, etc.
		$this->setHeaders();

		if ( !$this->getUser()->isAllowed( 'chat' ) ) {
			// @todo FIXME: rename this i18n message to conform with coding
			// standards, i.e lowercase-separated-with-hyphens and prefixed
			// with the extension's name (chat-)
			$out->addWikiMsg( 'BlockedFromChat' );
		} else {
			// What CSS & JS modules do we need?
			$modules = array(
				'ext.mediawikichat.css',
				'ext.mediawikichat.js'
			);

			// Load CSS & JS via ResourceLoader
			$out->addModules( $modules );

			// Load the GUI (from its own, separate file)
			include( 'SpecialChat.template.php' );
			$template = new SpecialChatTemplate;

			// Output the GUI HTML
			$out->addTemplate( $template );
		}
	}
}