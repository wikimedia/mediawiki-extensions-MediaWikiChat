<?php

class SpecialChat extends SpecialPage {
        function __construct() {
                parent::__construct( 'Chat', 'chat' );
        }
 
        function execute( $par ) {
        	global $wgUser;
        	if( !$wgUser -> isAllowed('chat') ){
                $out = $this->getOutput();
        		$out -> addWikiText("{{MediaWiki:BlockedFromChat}}");
        	} else {
                $request = $this->getRequest();
                $out = $this->getOutput();
                $this->setHeaders();
 
                # Get request data from, e.g.
                $param = $request->getText( 'param' );
                
                $html = "<style>";
                $html .= file_get_contents( __DIR__ . '/MediaWikiChat.css' );
                global $bmProject;
                if( $bmProject == 'dev' ){
                	$html .= file_get_contents( __DIR__ . '/dev.css' );
                }
                $html .= "
                	<!-- test -->
                	</style>
                	<!-- test2 -->
					<div id='mwchat-container'>
                	<!-- test3 -->
						<div id='mwchat-main'>
    						<div id='mwchat-content'>
                				<table id='mwchat-table'></table>
                			</div>
        					<div id='mwchat-type'>
                				<!--<span></span>
                				<img />-->
                				<input type='text' placeholder='Type your message' />
                			</div>
    					</div>
    					<div id='mwchat-users'></div>
    					<div id='mwchat-me'>
                			<img src='' />
                			<span class='mwchat-useritem-user'></span>
						</div>
					</div>
                	<script>";
                $html .= file_get_contents( __DIR__ . '/MediaWikiChat.js' );
                $html .= "</script>";
                
                $out->addHTML( $html );
        	}
        }
}