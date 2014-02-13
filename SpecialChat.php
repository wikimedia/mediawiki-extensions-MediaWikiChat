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
		global $wgChatSocialAvatars, $wgChatKicks, $wgChatLinkUsernames;

		$out = $this->getOutput();
		$user = $this->getUser();

		// Set the page title, robot policies, etc.
		$this->setHeaders();

		if ( !$this->getUser()->isAllowed( 'chat' ) ) {
			$groups = $this->getUser()->getGroups();
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

			$mention = $user->getOption( 'chat-ping-mention' );
			$pm = $user->getOption( 'chat-ping-pm' );
			$message = $user->getOption( 'chat-ping-message' );

			$out->addJsConfigVars(
				array(
					'wgChatKicks' => $wgChatKicks,
					'wgChatSocialAvatars' => $wgChatSocialAvatars,
					'wgChatLinkUsernames' => $wgChatLinkUsernames,
					'wgChatPingMentions' => $mention,
					'wgChatPingPMs' => $pm,
					'wgChatPingMessages' => $message,
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
