<div style="border-top: 2px solid gainsboro; border-bottom: 2px solid gainsboro; margin-top: 20px; padding: 0 10px 20px 10px; background-color: #eee;">
<!-- report is actionable -->
<div class="row">
	<h4>
		<?php echo Kohana::lang('actionable.report_actionable');?>
		<span><?php echo Kohana::lang('actionable.report_actionable_help');?></span>
	</h4>
    <?php print form::radio('actionable', '0', $actionable == 0); ?>&nbsp;<?php echo Kohana::lang('actionable.un_actionable');?>
    <?php print form::radio('actionable', '1', $actionable == 1); ?>&nbsp;<?php echo Kohana::lang('actionable.actionable');?>
	<?php print form::radio('actionable', '2', $actionable == 2); ?>&nbsp;<?php echo Kohana::lang('actionable.urgent');?>
</div>
<!-- / report is actionable -->

<!-- report is acted on -->
<div class="row">
	<h4>
		<?php echo Kohana::lang('actionable.report_acted');?>&nbsp;<?php print form::checkbox('action_taken', '1', $action_taken); ?>
		<span><?php echo Kohana::lang('actionable.report_acted_help');?></span>
	</h4>
  <textarea name="action_summary" id="action_summary" style=" height: 60px;"><?php echo $action_summary; ?></textarea>
</div>
<!-- / report is acted on -->
</div>
