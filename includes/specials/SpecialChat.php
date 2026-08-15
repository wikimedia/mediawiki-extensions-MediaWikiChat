<?php

use MediaWiki\Config\Config;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserOptionsLookup;

class SpecialChat extends SpecialPage {

	public function __construct(
		private readonly Config $config,
		private readonly UserOptionsLookup $userOptionsLookup,
		private readonly UserGroupManager $userGroupManager,
	) {
		parent::__construct( 'Chat' );
	}

	/** @inheritDoc */
	public function getRestriction(): string {
		return 'chat';
	}

	/**
	 * Show the special page
	 *
	 * @param string|null $par parameter passed to the special page or null
	 */
	public function execute( $par ) {
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
					'wgChatKicks' => $this->config->get( 'ChatKicks' ),
					'wgChatSocialAvatars' => class_exists( 'SocialProfileHooks' ), // has SocialProfile been installed?
					'wgChatLinkUsernames' => $this->config->get( 'ChatLinkUsernames' ),
					'wgChatMeCommand' => $this->config->get( 'ChatMeCommand' ),
					'wgChatMaxMessageLength' => $this->config->get( 'ChatMaxMessageLength' ),
					'wgCanonicalServer' => $this->config->get( MainConfigNames::CanonicalServer ),
				]
			);

			if ( $this->userOptionsLookup->getOption( $user, 'chat-fullscreen' ) ) {
				$out->disable(); // disable the normal skin stuff so only the MWC window appears

				echo $out->headElement( $this->getSkin() );

				echo "<div id='mwchat-wrapper'>";
				$template->execute(); // print template
				echo "</div>";

				echo $out->getBottomScripts();
				echo "</body></html>";
			} else {
				$out->addTemplate( $template ); // output the MWC window along with everything else
			}
		}
	}
}
