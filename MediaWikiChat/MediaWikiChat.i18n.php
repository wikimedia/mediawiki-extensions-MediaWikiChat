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
	'log-name-chat' => 'Chat log',
	'log-description-chat' => 'Messages sent by MediaWikiChat, as well as user kicks',
	'logentry-chat-send' => '$1: $4',
	'logentry-chat-kick' => '$1 kicked $4',
	'log-show-hide-chat' => '$1 chat log',
	'smileys' => '* :) Bricki-emote-smile.gif'
);
 
/** Message documentation
 * @author Adam Carter
 */
$messages['qqq'] = array(
	'chat' => 'Important! This is the string that appears on Special:SpecialPages',
	'chat-desc' => '{{desc}}',
	'log-name-chat' => 'Name of the chat log as it appears on the drop-down menu on [[Special:Log]]',
	'log-show-hide-chat' => 'For [[Special:Log]]. Parameters:
* $1 - {{int:show}} or {{int:hide}}',
	'smileys' => 'JSON array of strings and image names'
);