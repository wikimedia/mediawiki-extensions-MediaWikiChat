<?php

/**
 * Class containing hooks for the MediaWikiChat extension
 */
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\User\User;

class MediaWikiChatHooks implements
	\MediaWiki\Hook\SkinBuildSidebarHook,
	\MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook,
	\MediaWiki\Preferences\Hook\GetPreferencesHook,
	\MediaWiki\Skins\Hook\SkinAfterPortletHook,
	\MediaWiki\User\Hook\UserGroupsChangedHook
{
	/**
	 * Properly set up AbuseFilter-related variables for when AbuseFilter is (probably) installed.
	 */
	public static function onRegistration(
		array $extInfo,
		SettingsBuilder $settings
	) {
		$config = $settings->getConfig();
		global $wgAbuseFilterValidGroups, $wgAbuseFilterEmergencyDisableThreshold, $wgAbuseFilterEmergencyDisableCount, $wgAbuseFilterEmergencyDisableAge;
		$filterGroup = $config->get( 'MediaWikiChatAbuseFilterGroup' );

		// Note, it's too early to use ExtensionRegistry->isLoaded()
		if ( $config->has( 'AbuseFilterActions' ) &&
			$filterGroup !== 'default'
		) {
			// Add a custom filter group for AbuseFilter
			$wgAbuseFilterValidGroups[] = $filterGroup;

			// set AbuseFilter emergency disable values for MediaWikiChat
			$wgAbuseFilterEmergencyDisableThreshold[$filterGroup] = 0.10;
			$wgAbuseFilterEmergencyDisableCount[$filterGroup] = 50;
			$wgAbuseFilterEmergencyDisableAge[$filterGroup] = 86400; // One day.
		}
	}

	/**
	 * Hook for user rights changes
	 *
	 * Whenever a user is added to or removed from the 'blockedfromchat' group,
	 * this function ensures that the chat database table is updated accordingly.
	 *
	 * @param User $user
	 * @param array $add
	 * @param array $remove
	 * @param User|bool $performer Boolean false in the case of autopromotions, normally a User
	 * @param string|false $reason
	 * @param UserGroupMembership[] $oldUGMs
	 * @param UserGroupMembership[] $newUGMs
	 * @return bool|void
	 */
	public function onUserGroupsChanged(
		$user,
		$add,
		$remove,
		$performer,
		$reason,
		$oldUGMs,
		$newUGMs
	) {
		if ( in_array( 'blockedfromchat', $add ) && $performer ) {
			MediaWikiChat::sendSystemBlockingMessage( MediaWikiChat::TYPE_BLOCK, $user, $performer );
		}

		if ( in_array( 'blockedfromchat', $remove ) && $performer ) {
			MediaWikiChat::sendSystemBlockingMessage( MediaWikiChat::TYPE_UNBLOCK, $user, $performer );
		}
	}

	/**
	 * Hook for update.php
	 *
	 * @param MediaWiki\Installer\DatabaseUpdater $updater
	 */
	public function onLoadExtensionSchemaUpdates( $updater ) {
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
	public function onSkinBuildSidebar( $skin, &$bar ) {
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
	public function onSkinAfterPortlet( $skin, $portlet, &$html ) {
		// Don't show this if:
		// 1) the user isn't allowed to use the special page,
		// 2) we *are* on the special page (pointless, as chat itself already has a user list)
		// 3) the feature is disabled in site configuration
		if (
			!$skin->getUser()->isAllowed( 'chat' ) ||
			$skin->getTitle()->isSpecial( 'Chat' ) ||
			!$skin->getConfig()->get( 'ChatSidebarPortlet' )
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
	public function onGetPreferences( $user, &$preferences ) {
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
