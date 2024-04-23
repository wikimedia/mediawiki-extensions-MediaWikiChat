<?php

/**
 * Class containing hooks for the MediaWikiChat extension
 */
class MediaWikiChatHooks {

	/**
	 * Hook for user rights changes
	 *
	 * Whenever a user is added to or removed from the 'blockedfromchat' group,
	 * this function ensures that the chat database table is updated accordingly.
	 *
	 * @param User $user
	 * @param array $add
	 * @param array $remove
	 * @param User $performer
	 */
	public static function onUserGroupsChanged( $user, array $add, array $remove, User $performer ) {
		if ( in_array( 'blockedfromchat', $add ) ) {
			MediaWikiChat::sendSystemBlockingMessage( MediaWikiChat::TYPE_BLOCK, $user, $performer );
		}

		if ( in_array( 'blockedfromchat', $remove ) ) {
			MediaWikiChat::sendSystemBlockingMessage( MediaWikiChat::TYPE_UNBLOCK, $user, $performer );
		}
	}

	/**
	 * Hook for update.php
	 *
	 * @param DatabaseUpdater $updater
	 */
	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$dir = __DIR__ . '/../sql/';

		$updater->addExtensionTable( 'chat', $dir . 'chat.sql' );
		$updater->addExtensionTable( 'chat_users', $dir . 'chat_users.sql' );
		$updater->addExtensionField( 'chat_users', 'cu_away', $dir . 'cu_away.sql' );
		$updater->modifyExtensionField( 'chat_users', 'cu_away', $dir . 'cu_away_new.sql' );
	}

	/**
	 * Hook for adding a sidebar portlet ($wgChatSidebarPortlet)
	 *
	 * As of MediaWiki 1.39+ the actual rendering is done in onSkinAfterPortlet()
	 * but we nevertheless need this hook handler as well.
	 *
	 * @param Skin $skin
	 * @param array &$bar
	 */
	public static function onSkinBuildSidebar( Skin $skin, &$bar ) {
		$bar['chat-sidebar-online'] = [];
	}

	/**
	 * Prints the sidebar portlet listing users online on chat, if so configured.
	 *
	 * @param Skin $skin Instance of Skin class or its subclass
	 * @param string $portlet Portlet name, either an internal one (e.g. "tb", "lang", etc.) or a
	 *                        user-controlled string for [[MediaWiki:Sidebar]] top-level entries
	 * @param string &$html The HTML we want to inject to the output
	 */
	public static function onSkinAfterPortlet( Skin $skin, string $portlet, string &$html ) {
		global $wgChatSidebarPortlet;

		// Don't show this if:
		// 1) the user isn't allowed to use the special page,
		// 2) we *are* on the special page (pointless, as chat itself already has a user list)
		// 3) the feature is disabled in site configuration
		if (
			!$skin->getUser()->isAllowed( 'chat' ) ||
			$skin->getTitle()->isSpecial( 'Chat' ) ||
			!$wgChatSidebarPortlet
		) {
			return;
		}

		// The comparison must match the string defined in onSkinBuildSidebar().
		if ( $portlet === 'chat-sidebar-online' ) {
			$users = MediaWikiChat::getOnline( $skin->getUser() );

			// Only bother rendering this portlet if we have some active chatters...
			if ( count( $users ) ) {
				$arr = [];

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
							filter: grayscale(100%); /* future */";
					}
					$arr[$id] = [
						'text' => $user->getName(),
						'href' => htmlspecialchars( $user->getUserPage()->getFullURL() ),
						'style' => $style,
						'class' => 'mwchat-sidebar-user'
					];
				}

				// Display a "join chat" link if the user doesn't have the chat already open
				// in another tab or whatever
				if ( !MediaWikiChat::amIOnline( $skin->getUser() ) ) {
					$arr['join'] = [
						'text' => $skin->msg( 'chat-sidebar-join' )->text(),
						'href' => htmlspecialchars( SpecialPage::getTitleFor( 'Chat' )->getFullURL() )
					];
				}

				// Output the list and whatnot.
				foreach ( $arr as $key => $item ) {
					$html .= $skin->makeListItem( $key, $item );
				}
			}
		}
	}

	/**
	 * Register new preference options so that they show up on Special:Preferences.
	 *
	 * @param User $user
	 * @param array[] &$preferences
	 */
	public static function onGetPreferences( $user, &$preferences ) {
		$preferences['chat-fullscreen'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-fullscreen',
			'section' => 'misc/chat',
		];

		$preferences['chat-ping-mention'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-ping-mention',
			'section' => 'misc/chat',
		];
		$preferences['chat-ping-pm'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-ping-pm',
			'section' => 'misc/chat',
		];
		$preferences['chat-ping-message'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-ping-message',
			'section' => 'misc/chat',
		];
		$preferences['chat-ping-joinleave'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-ping-joinleave',
			'section' => 'misc/chat',
		];

		$preferences['chat-notify-mention'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-notify-mention',
			'section' => 'misc/chat',
		];
		$preferences['chat-notify-pm'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-notify-pm',
			'section' => 'misc/chat',
		];
		$preferences['chat-notify-message'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-notify-message',
			'section' => 'misc/chat',
		];
		$preferences['chat-notify-joinleave'] = [
			'type' => 'toggle',
			'label-message' => 'tog-chat-notify-joinleave',
			'section' => 'misc/chat',
		];
	}

}
