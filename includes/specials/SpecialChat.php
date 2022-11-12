<?php

use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserOptionsLookup;

class SpecialChat extends SpecialPage {

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var UserGroupManager */
	private $userGroupManager;

	/**
	 * Constructor -- set up the new special page
	 *
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param UserGroupManager $userGroupManager
	 */
	public function __construct(
		UserOptionsLookup $userOptionsLookup,
		UserGroupManager $userGroupManager
	) {
		parent::__construct( 'Chat', 'chat' );
		$this->userOptionsLookup = $userOptionsLookup;
		$this->userGroupManager = $userGroupManager;
	}

	/**
	 * Show the special page
	 *
	 * @param string|null $par parameter passed to the special page or null
	 */
	public function execute( $par ) {
		global $wgChatKicks, $wgChatLinkUsernames, $wgChatMeCommand, $wgChatMaxMessageLength, $wgCanonicalServer;

		$out = $this->getOutput();
		$user = $this->getUser();

		// Set the page title, robot policies, etc.
		$this->setHeaders();

		if ( !$user->isAllowed( 'chat' ) ) {
			$groups = $this->userGroupManager->getUserGroups( $user );
			if ( in_array( 'blockedfromchat', $groups ) ) {
				$out->addWikiMsg( 'chat-blocked-from-chat' );
			} else {
				$out->addWikiMsg( 'chat-not-allowed' );
			}
		} else {
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
				[
					'wgChatKicks' => $wgChatKicks,
					'wgChatSocialAvatars' => class_exists( 'SocialProfileHooks' ), // has SocialProfile been installed?
					'wgChatLinkUsernames' => $wgChatLinkUsernames,
					'wgChatMeCommand' => $wgChatMeCommand,
					'wgChatMaxMessageLength' => $wgChatMaxMessageLength,
					'wgCanonicalServer' => $wgCanonicalServer
				]
			);

			if ( $this->userOptionsLookup->getOption( $user, 'chat-fullscreen' ) ) {
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
