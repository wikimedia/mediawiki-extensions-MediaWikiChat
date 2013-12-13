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
			$groups = $this->getUser->getGroups();
			if ( in_array( 'blockedfromchat', $groups ) ) {
				$out->addWikiMsg( 'chat-blocked-from-chat' );
			} else {
				$out->addWikiMsg( 'chat-not-allowed' );
			}

		} else {
			// Load modules via ResourceLoader
			$modules = array(
				'ext.mediawikichat.css',
				'ext.mediawikichat.js',
			);
			$out->addModules( $modules );

			// Load the GUI (from its own, separate file)
			include( 'SpecialChat.template.php' );
			$template = new SpecialChatTemplate;

			// Output the GUI HTML
			$out->addTemplate( $template );
		}
	}
}