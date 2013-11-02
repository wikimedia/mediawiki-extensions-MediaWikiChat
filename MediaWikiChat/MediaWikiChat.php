<?php 
/**
 * An extension to integrate a special page with a built in chat.
 *
 * @file
 * @ingroup Extensions
 * @author Adam Carter
 * @copyright Copyright © 2013, Adam Carter
 */

 if ( !defined( 'MEDIAWIKI' ) ) {
	die();
}

$wgExtensionCredits[ 'specialpage' ][] = array(
		'path' => __FILE__,
		'name' => 'MediaWikiChat',
		'author' => 'Adam Carter/UltrasonicNXT',
		'url' => 'https://github.com/Brickimedia/MediaWikiChat',
		'descriptionmsg' => 'chat-desc',
		'version' => '1.0',
);

$dir = dirname( __FILE__ ) . '/';
$wgAutoloadClasses['SpecialChat'] = $dir . 'SpecialChat.php';
$wgAutoloadClasses['MediaWikiChat'] = $dir. 'MediaWikiChatClass.php';
$wgSpecialPages[ 'Chat' ] = 'SpecialChat';
$wgExtensionMessagesFiles[ 'MediaWikiChat' ] = $dir . 'MediaWikiChat.i18n.php';

// Hooks
$wgHooks['ParserBeforeInternalParse'][] = 'MediaWikiChat::onParserBeforeInternalParse';
$wgHooks['UserRights'][] = 'MediaWikiChat::onUserRights';

// Logs
$wgLogTypes[] = 'chat';
$wgLogActionsHandlers['chat/send'] = 'LogFormatter';
$wgFilterLogTypes['chat'] = true;

$mwchat = new MediaWikiChat();

function getNew(){
	global $mwchat;
	return $mwchat -> getNew();
}
function sendMessage( $message ){
	global $mwchat;
	return $mwchat -> sendMessage( $message );
}
function sendPM( $message, $toname, $toid ){
	global $mwchat;
	return $mwchat -> sendPM( $message, $toname, $toid );
}
function kick( $userName, $userId ){
	global $mwchat;
	return $mwchat -> kick( $userName, $userId );
}

$wgAjaxExportList[] = 'getNew';
$wgAjaxExportList[] = 'sendMessage';
$wgAjaxExportList[] = 'sendPM';
$wgAjaxExportList[] = 'kick';

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