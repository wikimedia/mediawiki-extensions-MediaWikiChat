<?php 

if( !defined( 'MEDIAWIKI' ) ){
	exit( 1 );
}

$wgExtensionCredits[ 'specialpage' ][] = array(
		'path' => __FILE__,
		'name' => 'MediaWikiChat',
		'author' => 'Adam Carter/UltrasonicNXT',
		'url' => '',
		'descriptionmsg' => 'chat-desc',
		'version' => '1.0',
);

$wgAutoloadClasses[ 'SpecialChat' ] = __DIR__ . '/SpecialChat.php';
$wgSpecialPages[ 'Chat' ] = 'SpecialChat';

$wgExtensionMessagesFiles[ 'MediaWikiChat' ] = __DIR__ . '/MediaWikiChat.i18n.php';

//HOOKS
$wgHooks['UserRights'][] = 'MediaWikiChat::onUserRights';

//LOGS
$wgLogTypes[] = 'chat';
$wgLogActionsHandlers['chat/send'] = 'LogFormatter';
$wgFilterLogTypes['chat'] = true;

require_once( __DIR__ . '/MediaWikiChatClass.php' );


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


$wgGroupPermissions['user']['chat'] = true;
$wgGroupPermissions['blockedfromchat']['chat'] = false;

$wgGroupPermissions['chatmod']['modchat'] = true;
$wgAddGroups['sysop'][] = 'chatmod';
$wgRemoveGroups['sysop'][] = 'chatmod';

$wgAddGroups['chatmod'][] = 'blockedfromchat';
$wgRemoveGroups['chatmod'][] = 'blockedfromchat';
$wgAddGroups['sysop'][] = 'blockedfromchat';
$wgRemoveGroups['sysop'][] = 'blockedfromchat';

$wgHooks['ParserBeforeInternalParse'][] = 'MediaWikiChat::onParserBeforeInternalParse';