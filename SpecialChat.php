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
		global $wgChatSocialAvatars, $wgChatKicks;

		$out = $this->getOutput();

		// Set the page title, robot policies, etc.
		$this->setHeaders();

		if ( !$this->getUser()->isAllowed( 'chat' ) ) {
			$out->addWikiMsg( 'chat-blocked-from-chat' );

		} else {
			// Load modules via ResourceLoader
			$modules = array(
				'ext.mediawikichat.css',
				'ext.mediawikichat.js',
			);
			$out->addModules( $modules );

			$out->addJsConfigVars(
				array(
					'wgChatKicks' => $wgChatKicks,
					'wgChatSocialAvatars' => $wgChatSocialAvatars
				)
			);

			// Load the GUI (from its own, separate file)
			include( 'SpecialChat.template.php' );
			$template = new SpecialChatTemplate;

			// Output the GUI HTML
			$out->addTemplate( $template );
		}
	}
}