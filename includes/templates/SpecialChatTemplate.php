<?php

/**
 * Main user interface for Special:Chat.
 *
 * @ingroup Templates
 */
class SpecialChatTemplate extends QuickTemplate {
	public function execute() {
		// phpcs:disable Generic.Files.LineLength
?>
		<div id="mwchat-topic">
			<?php echo wfMessage( 'chat-topic' )->parse(); ?>
		</div>
		<div id="mwchat-container" style="opacity:0.5;">
			<div id="mwchat-main" >
				<div id="mwchat-content">
					<table id="mwchat-table">
						<tr class="mwchat-message"> <?php // ensures columns are appropriately wide ?>
							<td class="mwchat-item-user"></td>
							<td class="mwchat-item-avatar"></td>
							<td class="mwchat-item-messagecell"></td>
						</tr>
					</table>
				</div>
				<div id="mwchat-type">
					<span id="mwchat-loading" style="opacity:0;" data-queue="0" class="feedback-spinner"></span><?php // .feedback-spinner adds the loading gif ?>
					<input type="text" placeholder="<?php echo wfMessage( 'chat-type-your-message' )->plain() ?>" />
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
		<div id="mwchat-options">
			<span id="mwchat-jumptolatest-span" style="opacity:0;"><a id="mwchat-jumptolatest-link" href="javascript:;"><?php echo wfMessage( 'chat-jump-to-latest' )->plain(); ?></a>&nbsp;&bull;&nbsp;</span>
			<?php echo wfMessage( 'chat-autoscroll' )->plain(); ?><input type="checkbox" name="autoscroll" checked="checked" />&nbsp;&bull;&nbsp;
			<a target="_blank" href="<?php echo SpecialPage::getTitleFor( 'Preferences', false, 'mw-prefsection-misc' )->getFullURL(); ?>"><?php echo wfMessage( 'chat-change-preferences' ); ?></a>
		</div>
<?php
		// phpcs:enable
	}
}
