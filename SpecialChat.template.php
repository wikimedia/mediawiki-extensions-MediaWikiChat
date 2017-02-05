<?php
/**
 * @file
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die();
}
/**
 * Main user interface for Special:Chat.
 *
 * @ingroup Templates
 */
class SpecialChatTemplate extends QuickTemplate {
	public function execute() {
?>
		<div id="mwchat-topic">
			<?php echo wfMessage( 'chat-topic' )->parse(); ?>
		</div>
		<div id="mwchat-container" style="opacity:0.5;">
			<div id="mwchat-main" >
				<div id="mwchat-content">
					<table id="mwchat-table">
						<tr class="mwchat-message"> <?php //ensures columns are appropriately wide ?>
							<td class="mwchat-item-user"></td>
							<td class="mwchat-item-avatar"></td>
							<td class="mwchat-item-messagecell"></td>
						</tr>
					</table>
				</div>
				<div id="mwchat-type">
					<span id="mwchat-loading" style="opacity:0;" data-queue="0" class="feedback-spinner"></span><?php // .feedback-spinner adds the loading gif ?>
					<?php
					new OOUI\TextInputWidget( [
						'placeholder' => wfMessage( 'chat-type-your-message' )->plain()
					] );
					?>
				</div>
			</div>
			<div id="mwchat-users">
				<div id="mwchat-no-other-users" style="display:none;">
					<?php echo wfMessage( 'chat-no-other-users' )->plain() ?>
				</div>
			</div>
			<div id="mwchat-me">
				<img src="" alt="" />
				<span class="mwchat-useritem-user"></span>
			</div>
		</div>
		<?php
			$mwChatOptions = ( new OOUI\Tag( 'footer' ) );
			$mwChatJumpToLatest = new OOUI\Tag( 'span' );
			$mwChatJumpToLatestAnchor = new OOUI\Tag( 'a' );
			$mwChatOptions->appendContent( new OOUI\FieldsetLayout( [
				'items' => [
					$mwChatJumpToLatest->setAttributes( [
						'id' => 'mwchat-jumptolatest-span',
						'style' => 'opacity: 0;'
					] )->appendContent(
						$mwChatJumpToLatestAnchor->setAttributes( [
							'id' => 'mwchat-jumptolatest-link',
							'href' => 'javascript:;'
						] )
					),
					new OOUI\CheckboxInputWidget( [
						'selected' => true,
						'name' => 'autoscroll',
						'label' => wfMessage( 'chat-autoscroll' )->plain()
					] ),
					new OOUI\ButtonWidget( [
						setHref( SpecialPage::getTitleFor( 'Preferences', false, 'mw-prefsection-misc' )->getFullURL() ),
						'flags' => [ 'constructive', 'primary' ],
						'label' => wfMessage( 'chat-change-preferences' )
					] )
				]
			] ) );
			echo $mwChatOptions;
	}
}
