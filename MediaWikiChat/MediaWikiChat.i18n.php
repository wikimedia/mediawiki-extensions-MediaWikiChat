<?php
/**
 * Internationalisation for myextension
 *
 * @file
 * @ingroup Extensions
 */
$messages = array();
 
/** English
 * @author Adam Carter
 */
$messages[ 'en' ] = array(
	'chat' => "Chat", // Important! This is the string that appears on Special:SpecialPages
	'chat-desc' => "MediaWikiChat, a chat extension built in MediaWiki",
	'log-name-chat' => 'Chat log',
	'log-description-chat' => 'Messages sent by MediaWikiChat, as well as user kicks',
	'logentry-chat-send' => '$1: $4',
	'logentry-chat-kick' => '$1 kicked $4',
	'log-show-hide-chat' => '$1 chat log', // For Special:Log
	'smileys' => '* :) Bricki-emote-smile.gif'
);
 
/** Message documentation
 * @author Adam Carter
 */
$messages[ 'qqq' ] = array(
	'chat' => "Chat (MediaWikiChat)",
	'chat-desc' => "{{desc}}",
	'smileys' => 'JSON Array of strings and image names'
);