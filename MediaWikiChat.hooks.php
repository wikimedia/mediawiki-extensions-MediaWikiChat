<?php
/**
 * Class containing hooks for the MediaWikiChat extension
 */
class MediaWikiChatHooks {
	/**
	 * Hook for parser, to parse chat messages slightly differently,
	 * not parsing tables, double underscores, and headings
	 * @param Parser $parser
	 * @param string $text
	 * @param $strip_state
	 * @return bool
	 */
	public static function onParserBeforeInternalParse( &$parser, &$text, &$strip_state ) {
		if ( $parser->getTitle()->equals( SpecialPage::getTitleFor( 'Chat', 'message' ) ) ) { // only do our version of parsing when this is Special:Chat and we're parsing a message
			$text = $parser->replaceVariables( $text );

			$text = Sanitizer::removeHTMLtags(
				$text,
				array( &$parser, 'attributeStripCallback' ),
				false,
				array_keys( $parser->mTransparentTagHooks )
			);

			$text = $parser->replaceInternalLinks( $text );
			$text = $parser->doAllQuotes( $text );
			$text = $parser->replaceExternalLinks( $text );
			$text = $parser->doMagicLinks( $text );

			return false; // stop parser doing anything as we've done the parsing ourselves
		} else {
			return true;
		}
	}

	/**
	 * Hook for user rights changes
	 *
	 * Whenever a user is added to or removed from the 'blockedfromchat' group,
	 * this function ensures that the chat database table is updated accordingly.
	 */
	public static function onUserRights( $user, array $add, array $remove ) {
		if ( in_array( 'blockedfromchat', $add ) ) {
			MediaWikiChat::sendSystemBlockingMessage( MediaWikiChat::TYPE_BLOCK, $user );
		}

		if ( in_array( 'blockedfromchat', $remove ) ) {
			MediaWikiChat::sendSystemBlockingMessage( MediaWikiChat::TYPE_UNBLOCK, $user );
		}

		return true;
	}

	/**
	 * Hook for update.php
	 */
	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$dir = __DIR__ . '/sql/';

		$updater->addExtensionTable( 'chat', $dir . 'chat.sql', true );
		$updater->addExtensionTable( 'chat_users', $dir . 'chat_users.sql', true );
		$updater->addExtensionField( 'chat_users', 'cu_away', $dir . 'cu_away.sql' );
		$updater->modifyExtensionField( 'chat_users', 'cu_away', $dir . 'cu_away_new.sql' );

		return true;
	}

	/**
	 * Hook for adding a sidebar portlet ($wgChatSidebarPortlet)
	 */
	public static function fnNewSidebarItem( Skin $skin, &$bar ) {
		global $wgChatSidebarPortlet;

		if (
			$skin->getUser()->isAllowed( 'chat' ) &&
			!$skin->getTitle()->isSpecial( 'Chat' ) &&
			$wgChatSidebarPortlet
		) {
			$users = MediaWikiChat::getOnline();

			if ( count( $users ) ) {
				$arr = array();

				foreach ( $users as $id => $away ) {
					$user = User::newFromId( $id );
					$style = "display: block;
						background-position: right 1em center;
						background-repeat: no-repeat;
						word-wrap: break-word;";
					if ( class_exists( 'SocialProfileHooks' ) ) {
						$avatar = MediaWikiChat::getAvatar( $id );
						$style .= "background-image: url($avatar);";
					}
					if ( $away > 120000 ) {
						$style .= "-webkit-filter: grayscale(1); /* old webkit */
							-webkit-filter: grayscale(100%); /* new webkit */
							-moz-filter: grayscale(100%); /* safari */
							-ms-filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); /* maybe ie */
							filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); /* maybe ie */
							filter: gray; /* maybe ie */
							filter: grayscale(100%); /* future */";
					}
					$arr[$id] = array(
						'text' => $user->getName(),
						'href' => htmlspecialchars( $user->getUserPage()->getFullURL() ),
						'style' => $style,
						'class' => 'mwchat-sidebar-user'
					);
				}

				if ( !MediaWikiChat::amIOnline() ) {
					$arr['join'] = array(
						'text' => $skin->msg( 'chat-sidebar-join' )->text(),
						'href' => htmlspecialchars( SpecialPage::getTitleFor( 'Chat' )->getFullURL() )
					);
				}

				$bar[$skin->msg( 'chat-sidebar-online' )->text()] = $arr;
			}
		}

		return true;
	}

	static function wfPrefHook( $user, &$preferences ) {
		$preferences['chat-fullscreen'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-fullscreen',
			'section' => 'misc/chat',
		);

		$preferences['chat-ping-mention'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-ping-mention',
			'section' => 'misc/chat',
		);
		$preferences['chat-ping-pm'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-ping-pm',
			'section' => 'misc/chat',
		);
		$preferences['chat-ping-message'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-ping-message',
			'section' => 'misc/chat',
		);
		$preferences['chat-ping-joinleave'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-ping-joinleave',
			'section' => 'misc/chat',
		);

		$preferences['chat-notify-mention'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-notify-mention',
			'section' => 'misc/chat',
		);
		$preferences['chat-notify-pm'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-notify-pm',
			'section' => 'misc/chat',
		);
		$preferences['chat-notify-message'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-notify-message',
			'section' => 'misc/chat',
		);
		$preferences['chat-notify-joinleave'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-chat-notify-joinleave',
			'section' => 'misc/chat',
		);

		return true;
	}

}