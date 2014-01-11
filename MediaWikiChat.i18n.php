<?php
/**
 * Internationalisation file for the MediaWikiChat extension.
 *
 * @file
 * @ingroup Extensions
 */
$messages = array();

/** English
 * @author Adam Carter
 */
$messages['en'] = array(
	'chat' => 'Chat',
	'chat-desc' => 'Provides a lightweight chat client and server',

	'chat-type-your-message' => 'Type your message',
	'chat-type-your-private-message' => 'Type your private message',
	'chat-no-other-users' => 'No other users on chat',
	'chat-blocked-from-chat' => 'You have been blocked from this chat.',
	'chat-not-allowed' => 'You are not allowed to chat, try logging in first',

	'chat-just-now' => 'just now',

	'chat-a-minute-ago' => 'a minute ago',
	'chat-minutes-ago' => '{{PLURAL:$1|1 minute|$1 minutes}} ago',

	'chat-yesterday' => 'yesterday',

	'chat-youve-been-kicked' => 'You have been {{GENDER:$2|kicked}} by $1. Refresh the page to chat',
	'chat-you-kicked' => 'You {{GENDER:$2|kicked}} $1',
	'chat-kicked' => '$1 {{GENDER:$3|kicked}} $2',
	'chat-kick' => 'kick',

	'chat-youve-been-blocked' => 'You have been {{GENDER:$2|blocked}} by $1.',
	'chat-you-blocked' => 'You {{GENDER:$2|blocked}} $1',
	'chat-blocked' => '$1 {{GENDER:$3|blocked}} $2',
	'chat-block' => 'block',

	'chat-you-unblocked' => 'You {{GENDER:$2|unblocked}} $1',
	'chat-unblocked' => '$1 {{GENDER:$3|unblocked}} $2',

	'chat-private-message' => '(private message)',
	'chat-user-is-moderator' => 'This user {{GENDER:$1|is}} a moderator',
	'chat-you-are-moderator' => 'You {{GENDER:$1|are}} a moderator',
	'chat-joined' => '$1 has {{GENDER:$2|joined}} the chat',
	'chat-left' => '$1 has {{GENDER:$2|left}} chat',

	'chat-topic' => 'Welcome to {{SITENAME}} chat. ([[Special:Log/chat|chat log]])',

	'chat-sidebar-online' => 'Online users in chat:',
	'chat-sidebar-join' => 'Join the chat',

	'chat-mod-image' => 'http://images.brickimedia.org/c/cb/Golden-minifigure.png', // Ignore for translation
	'chat-smileys' => '', // Ignore for translation

	'log-name-chat' => 'Chat log',
	'log-description-chat' => 'Messages sent by MediaWikiChat, as well as user kicks',
	'logentry-chat-send' => '$1: $4', // Optional for translation
	'logentry-chat-kick' => '$1 {{GENDER:$1|kicked}} $4',
	'log-show-hide-chat' => '$1 chat log',

	'group-chatmod' => 'Chat moderators',
	'group-chatmod-member' => '{{GENDER:$1|chat moderator}}',
	'grouppage-chatmod' => '{{ns:project}}:Chat moderators',

	'group-blockedfromchat' => 'Users blocked from chat',
	'group-blockedfromchat-member' => '{{GENDER:$1|blocked from chat}}',
	'grouppage-blockedfromchat' => '{{ns:project}}:Users blocked from chat',

	'right-mediawikichat-chat' => 'Use Special:Chat',
	'right-mediawikichat-modchat' => 'Block and kick (if enabled) users from Special:Chat',

	'log-name-forum' => 'Forum log',
	'log-description-forum' => 'Logs [[Special:WikiForum|WikiForum]] events',

	'logentry-forum-add-category' => '$1 {{GENDER:$1|created}} a new category, $4',
	'logentry-forum-add-forum' => '$1 {{GENDER:$1|created}} a new forum, $4',
	'logentry-forum-add-thread' => '$1 {{GENDER:$1|created}} a new thread, $4',
	'logentry-forum-add-reply' => '$1 {{GENDER:$1|replied}} on the thread $4',
);

/** Message documentation
 * @author Adam Carter
 */
$messages['qqq'] = array(
	'chat' => 'Important! This is the string that appears on Special:SpecialPages',
	'chat-desc' => '{{desc}}',

	'chat-topic' => 'Header shown at the top of Special:Chat, to allow links and policies to be displayed, like you find on many IRC clients',
	'chat-type-your-message' => 'Message shown in the input field you type your message in.',
	'chat-type-your-private-message' => 'The same as chat-type-your-message, but for private messages, shown in the input field for typing private messages',
	'chat-no-other-users' => 'Shown in the user list when there are no other users on chat',
	'chat-blocked-from-chat' => 'Shown to users who have been blocked from chat',
	'chat-not-allowed' => 'Shown to users who do not have sufficient permissions to chat (normally users who are not logged in)',

	'chat-just-now' => 'Timestamps: shown when a message was sent in the last 30 seconds',
	'chat-minutes-ago' => 'Timestamps: the message was sent $1 minutes ago',
	'chat-yesterday' => 'Timestamps: the message was sent yesterday',

	'chat-youve-been-kicked' => 'Shown to users who have been kicked from the chat, $1 being the user who kicked them',
	'chat-you-kicked' => 'Shown when the current user kicked the user $1',
	'chat-kicked' => 'Shown when the user $1 kicked the user $2',
	'chat-youve-been-blocked' => 'Shown when the current user has been blocked, by the user $1',
	'chat-you-blocked' => 'Shown when the current user blocked the user $1',
	'chat-blocked' => 'Shown when the user $1 blocked the user $2',
	'chat-block' => 'The link shown to chatmods to block a user',

	'chat-private-message' => 'The link shown to users to private message another user',
	'chat-user-is-a-moderator' => 'Shown on hover to show the user being hovered over is a moderator',
	'chat-you-are-moderator' => 'Shown when the current user is a moderator',
	'chat-joined' => 'Shown when the user $1 joined the chat',
	'chat-left' => 'Shown when the user $1 left the chat',
	'chat-mod-image' => 'The URL of the image to show a user is a moderator',

	'chat-sidebar-online' => 'The header for the chat unit on the sidebar that shows online users on chat',
	'chat-sidebar-join' => 'A link in the sidebar, below the currently active users, linking to Special:Chat',

	'log-name-chat' => 'The name of the chat log',
	'log-description' => 'The description of the chat log',
	'logentry-chat-send' => 'The user $1 sent the message $4',
	'logentry-chat-kick' => 'The user $1 kicked the user $4',
	'log-show-hide-chat' => 'For [[Special:Log]]. Parameters: $1 - {{int:show}} or {{int:hide}}',

	'group-chatmod' => '{{doc-group|chatmod}}',
	'group-chatmod-member' => '{{doc-group|forumadmin|member}}',
	'grouppage-chatmod' => '{{doc-group|forumadmin|page}}',

	'group-blockedfromchat' => '{{doc-group|blockedfromchat}}',
	'group-blockedfromchat-member' => '{{doc-group|blockedfromchat|member}}',
	'grouppage-blockedfromchat' => '{{doc-group|blockedfromchat|page}}',

	'right-mediawikichat-chat' => '{{doc-right|mediawikichat-chat}}',
	'right-mediawikichat-modchat' => '{{doc-right|mediawikichat-modchat}}',
);

/** Deutsch/German
 * @author George Barnick
 */
$messages['de'] = array(
	'chat' => 'Chat',
	'chat-desc' => 'MediaWikiChat, ein Chat-Erweiterung im MediaWiki gebaut',
	'chat-type-your-message' => 'Gib die Nachricht ein',
	'log-name-chat' => 'Chat-Logbuch',
	'log-description-chat' => 'Nachrichten von MediaWikiChat gesendet, sowie Benutzer-Kicks',
	'logentry-chat-kick' => 'Rauswurf von $4 durch $1',
	'log-show-hide-chat' => 'Chat-Logbuch $1',
	'group-blockedfromchat-member' => 'aus dem Chat gesperrte'
);