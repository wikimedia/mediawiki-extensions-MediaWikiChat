<?php 
/**
 * An extension to integrate a special page with a built in chat.
 *
 * @file
 * @ingroup Extensions
 * @version 1.0
 * @author Adam Carter
 * @copyright Copyright © 2013, Adam Carter
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This is not a valid entry point to MediaWiki.' );
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'MediaWikiChat',
	'version' => '1.0',
	'author' => 'Adam Carter/UltrasonicNXT',
	'url' => 'https://github.com/Brickimedia/MediaWikiChat',
	'descriptionmsg' => 'chat-desc',
);

// ResourceLoader support for MediaWiki 1.17+
$wgResourceModules['ext.mediawikichat.css'] = array(
	'styles' => 'MediaWikiChat.css',
	'localBasePath' => dirname( __FILE__ ),
	'remoteExtPath' => 'MediaWikiChat',
	'position' => 'top' // available since r85616
);

$wgResourceModules['ext.mediawikichat.css.dev'] = array(
	'styles' => 'dev.css',
	'localBasePath' => dirname( __FILE__ ),
	'remoteExtPath' => 'MediaWikiChat',
	'position' => 'top' // available since r85616
);

$wgResourceModules['ext.mediawikichat.js'] = array(
	'scripts' => 'MediaWikiChat.js',
	'localBasePath' => dirname( __FILE__ ),
	'remoteExtPath' => 'MediaWikiChat',
);

// Set up everything
$dir = dirname( __FILE__ ) . '/';
$wgAutoloadClasses['SpecialChat'] = $dir . 'SpecialChat.php';
$wgAutoloadClasses['MediaWikiChat'] = $dir. 'MediaWikiChatClass.php';
$wgSpecialPages['Chat'] = 'SpecialChat';
$wgExtensionMessagesFiles['MediaWikiChat'] = $dir . 'MediaWikiChat.i18n.php';

// Hooks
$wgHooks['ParserBeforeInternalParse'][] = 'MediaWikiChat::onParserBeforeInternalParse';
$wgHooks['UserRights'][] = 'MediaWikiChat::onUserRights';

// Logs
$wgLogTypes[] = 'chat';
$wgLogActionsHandlers['chat/*'] = 'LogFormatter';
$wgFilterLogTypes['chat'] = true;

// @todo FIXME: this is wrong, should 1) be in their own file and 2) be using
// the API instead of AJAX stuff (that's stupid, but don't blame it on me, I'm
// just the messenger here)
$mwchat = new MediaWikiChat();

function getNew() {
	global $mwchat;
	return $mwchat->getNew();
}
function sendMessage( $message ) {
	global $mwchat;
	return $mwchat->sendMessage( $message );
}
function sendPM( $message, $toName, $toId ) {
	global $mwchat;
	return $mwchat->sendPM( $message, $toName, $toId );
}
function kick( $userName, $userId ) {
	global $mwchat;
	return $mwchat->kick( $userName, $userId );
}

$wgAjaxExportList[] = 'getNew';
$wgAjaxExportList[] = 'sendMessage';
$wgAjaxExportList[] = 'sendPM';
$wgAjaxExportList[] = 'kick';
// </fixme>

// Permissions
$wgGroupPermissions['user']['chat'] = true;
$wgGroupPermissions['blockedfromchat']['chat'] = false;

$wgGroupPermissions['chatmod']['modchat'] = true;
$wgAddGroups['sysop'][] = 'chatmod';
$wgRemoveGroups['sysop'][] = 'chatmod';

$wgAddGroups['chatmod'][] = 'blockedfromchat';
$wgRemoveGroups['chatmod'][] = 'blockedfromchat';
$wgAddGroups['sysop'][] = 'blockedfromchat';
$wgRemoveGroups['sysop'][] = 'blockedfromchat';