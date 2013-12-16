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
	'chat-desc' => 'MediaWikiChat, a chat extension built in MediaWiki',
	'chat-type-your-message' => 'Type your message',
	'chat-type-your-private-message' => 'Type your private message',
	'chat-no-other-users' => 'No other users on chat',
	'chat-blocked-from-chat' => 'You have been blocked from this chat.',
	'chat-not-allowed' => 'You are not allowed to chat, try logging in first',
	'chat-just-now' => 'just now',
	'chat-a-minute-ago' => 'a minute ago',
	'chat-quarter-of-an-hour-ago' => 'quarter of an hour ago',
	'chat-half-an-hour-ago' => 'half an hour ago',
	'chat-an-hour-ago' => 'an hour ago',
	'chat-minutes-ago' => '$1 minutes ago',
	'chat-yesterday' => 'yesterday',
	'chat-youve-been-kicked' => 'You have been kicked by $1. Refresh the page to chat',
	'chat-you-kicked' => 'You kicked $1',
	'chat-kicked' => '$1 kicked $2',
	'chat-kick' => 'kick',
	'chat-youve-been-blocked' => 'You have been blocked by $1.',
	'chat-you-blocked' => 'You blocked $1',
	'chat-blocked' => '$1 blocked $2',
	'chat-block' => 'block',
	'chat-private-message' => '(private message)',
	'chat-user-is-moderator' => 'This user is a moderator',
	'chat-you-are-moderator' => 'You are a moderator',
	'chat-joined' => '$1 has joined the chat',
	'chat-left' => '$1 has left chat',
	'chat-mod-image' => 'http://meta.brickimedia.org/images/c/cb/Golden-minifigure.png',
	'chat-topic' => 'Welcome to {{SITENAME}} chat. ([[Special:Log/chat|chat log]])',
	'log-name-chat' => 'Chat log',
	'log-description-chat' => 'Messages sent by MediaWikiChat, as well as user kicks',
	'logentry-chat-send' => '$1: $4',
	'logentry-chat-kick' => '$1 kicked $4',
	'log-show-hide-chat' => '$1 chat log',
	'smileys' => '',
	'group-blockedfromchat-member' => 'blocked from chat',
);

/** Deutsch/German
 * @author George Barnick
 */
$messages['de'] = array(
	'chat' => 'Chat',
	'chat-desc' => 'MediaWikiChat, ein Chat-Erweiterung im MediaWiki gebaut',
	'chat-type-your-message' => 'Geben Sie Ihre Nachricht',
	'log-name-chat' => 'Chat-Logbuch',
	'log-description-chat' => 'Nachrichten von MediaWikiChat gesendet, sowie Benutzer-Kicks',
	'logentry-chat-send' => '$1: $4',
	'logentry-chat-kick' => 'Rauswurf von $4 durch $1',
	'log-show-hide-chat' => 'Chat-Logbuch $1',
	'smileys' => '* :) Bricki-emote-smile.gif',
	'group-blockedfromchat-member' => 'aus dem Chat gesperrte'
);

/** Message documentation
 * @author Adam Carter
 */
$messages['qqq'] = array(
	'chat' => 'Important! This is the string that appears on Special:SpecialPages',
	'chat-desc' => '{{desc}}',
	'chat-topic' => 'Header shown at the top of Special:Chat, to allow links and policies to be displayed, like you find on many IRC clients',
	'chat-not-allowed' => 'Message shown to users who are not in the groups required to chat (normally users who are not logged in)',
	'log-name-chat' => 'Name of the chat log as it appears on the drop-down menu on [[Special:Log]]',
	'log-show-hide-chat' => 'For [[Special:Log]]. Parameters:
* $1 - {{int:show}} or {{int:hide}}',
	'smileys' => 'JSON array of strings and image names'
);
