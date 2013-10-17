<?php 

# Alert the user that this is not a valid access point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

$wgExtensionCredits[ 'specialpage' ][] = array(
		'path' => __FILE__,
		'name' => 'MediaWikiChat',
		'author' => 'Adam Carter/UltrasonicNXT',
		'url' => '',
		'descriptionmsg' => 'chat-desc',
		'version' => '0.0.0',
);

$wgAutoloadClasses[ 'SpecialChat' ] = __DIR__ . '/SpecialChat.php'; # Location of the SpecialMyExtension class (Tell MediaWiki to load this file)
//$wgAutoloadClasses[ 'MediaWikiChat' ] = __DIR__ . '/MediaWikiChatClass.php'; # Location of the SpecialMyExtension class (Tell MediaWiki to load this file)

$wgExtensionMessagesFiles[ 'MediaWikiChat' ] = __DIR__ . '/MediaWikiChat.i18n.php'; # Location of a messages file (Tell MediaWiki to load this file)

$wgSpecialPages[ 'Chat' ] = 'SpecialChat'; # Tell MediaWiki about the new special page and its class name

//HOOKS
$wgHooks['UserRights'][] = 'MediaWikiChat::onUserRights';

//LOGS
$wgLogTypes[] = 'chat';
$wgLogActionsHandlers['chat/send'] = 'LogFormatter';
$wgFilterLogTypes['chat'] = true;

//$wgAjaxExportList[] = 'MediaWikiChat::getOnline';

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
$wgGroupPermissions['chatmod']['chat'] = true;
$wgGroupPermissions['sysop']['chat'] = true;
$wgGroupPermissions['blockedfromchat']['chat'] = false;

$wgAddGroups['chatmod'][] = 'blockedfromchat';
$wgRemoveGroups['chatmod'][] = 'blockedfromchat';
$wgGroupPermissions['chatmod']['modchat'] = true;

$wgAddGroups['sysop'][] = 'forcechat';
$wgRemoveGroups['sysop'][] = 'forcechat';
$wgAddGroups['sysop'][] = 'blockedfromchat';
$wgRemoveGroups['sysop'][] = 'blockedfromchat';

/*
$mwChatResourceTemplate = array(
		'localBasePath' => __DIR__,
		'remoteExtPath' => 'MediaWikiChat',
		'group' => 'ext.mwchat',
);
$wgResourceModules['ext.mwchat'] = $mwChatResourceTemplate + array(
		'scripts' => array(
				'MediaWikiChat.js',
		),
		'styles' => array(
				//'ajaxpoll.css',
		),
		'dependencies' => array(
		)
);*/