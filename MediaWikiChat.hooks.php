<?php
/**
 * Class containing hooks for the MediaWikiChat extension
 */
class MediaWikiChatHooks {

	/**
	 * Hook to add MediaWikiChat's config variables to the loaded JS on pages
	 */
	public static function onResourceLoaderGetConfigVars( array &$vars ) {
		global $wgChatKicks, $wgChatSocialAvatars;

		$vars['wgChatKicks'] = $wgChatKicks;
		$vars['wgChatSocialAvatars'] = $wgChatSocialAvatars;

		return true;
	}

	/**
	 * Hook for parser, to parse chat messages slightly differently,
	 * not parsing tables, double underscores, and headings
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
	function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$dir = dirname( __FILE__ ) . '/';

		$updater->addExtensionTable( 'chat', $dir . 'chat.sql', true );
		$updater->addExtensionTable( 'chat_users', $dir . 'chat_users.sql', true );

		return true;
	}
}