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
		if ( strpos( $text, 'MWCHAT' ) === false ) {
			return true;
		} else {
			$text = $parser->replaceVariables( $text );

			$text = Sanitizer::removeHTMLtags( $text, array( &$parser, 'attributeStripCallback' ), false, array_keys( $parser->mTransparentTagHooks ) );

			$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

			$text = $parser->replaceInternalLinks( $text );
			$text = $parser->doAllQuotes( $text );
			$text = $parser->replaceExternalLinks( $text );

			$text = str_replace( $parser->mUniqPrefix . 'NOPARSE', '', $text );

			$text = $parser->doMagicLinks( $text );

			return false;
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
		$dir = dirname( __FILE__ ) . '/';

		$updater->addExtensionTable( 'chat', $dir . 'chat.sql', true );
		$updater->addExtensionTable( 'chat_users', $dir . 'chat_users.sql', true );

		return true;
	}

	/**
	 * Hook for adding a sidebar portlet ($wgChatSidebarPortlet)
	 */
	public static function fnNewSidebarItem( Skin $skin, &$bar ) {
		global $wgArticlePath, $wgChatSidebarPortlet;

		if (
			$skin->getUser()->isAllowed( 'chat' ) &&
			$skin->getTitle()->getBaseTitle() != 'Special:Chat' &&
			$wgChatSidebarPortlet
		) {
			$users = MediaWikiChat::getOnline();

			if ( count( $users ) ) {
				$arr = array();

				foreach ( $users as $id => $name ) {
					$avatar = MediaWikiChat::getAvatar( $id );
					$page = str_replace( '$1', 'User:' . rawurlencode( $name ), $wgArticlePath );
					$arr[$id] = array(
						'text' => $name,
						'href' => $page,
						'style' => "display: block;
							background-position: right 1em center;
							background-repeat: no-repeat;
							background-image: url($avatar);"
					);
				}

				if ( ! MediaWikiChat::amIOnline() ) {
					$arr['join'] = array(
						'text' => wfMessage( 'chat-sidebar-join' )->text(),
						'href' => str_replace( '$1', 'Special:Chat', $wgArticlePath )
					);
				}

				$bar[wfMessage( 'chat-sidebar-online' )->text()] = $arr;
			}
		}

		return true;
	}

	/**
	 * Hook to rename users' db entries when they are renamed.
	 */
	public static function onRenameUserComplete( $uid, $oldName, $newName ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update(
			'chat',
			array( 'chat_user_name' => $newName ),
			array( 'chat_user_id' => $uid ),
			__METHOD__
		);

		return true;
	}
}