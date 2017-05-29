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
		global $wgChatKicks, $wgChatLinkUsernames, $wgChatMeCommand, $wgChatMaxMessageLength, $wgCanonicalServer;

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
			// Load the GUI (from its own, separate file)
			include( 'SpecialChat.template.php' );
			$template = new SpecialChatTemplate;

			// Load modules via ResourceLoader
			$out->addModules( [
				'ext.mediawikichat.js',
				'ext.mediawikichat.site',
				'mediawiki.feedback'
			] );

			$out->addModuleStyles( [
				'ext.mediawikichat.css',
				'ext.mediawikichat.site.styles'
			] );

			$out->addJsConfigVars(
				array(
					'wgChatKicks' => $wgChatKicks,
					'wgChatSocialAvatars' => class_exists( 'SocialProfileHooks' ), // has SocialProfile been installed?
					'wgChatLinkUsernames' => $wgChatLinkUsernames,
					'wgChatMeCommand' => $wgChatMeCommand,
					'wgChatMaxMessageLength' => $wgChatMaxMessageLength,
					'wgCanonicalServer' => $wgCanonicalServer,
					'wgUserId' => $this->getUser()->getId()
				)
			);

			if ( $user->getOption( 'chat-fullscreen' ) ) {
				$out->disable(); // disable the normal skin stuff so only the MWC window appears

				echo $out->headElement( $this->getSkin() );

				echo "<div id='mwchat-wrapper'>";
				$template->execute(); // print template
				echo "</div>";

				echo $this->getSkin()->bottomScripts();
				echo "</body></html>";
			} else {
				$out->addTemplate( $template ); // output the MWC window along with everything else
			}
		}
	}
}
