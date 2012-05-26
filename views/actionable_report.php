<div class="action-taken clearingfix">
	<?php if (!$action_taken && $actionable == 1) { ?>
    <div id="actionable-badge">
      <?php echo Kohana::lang('actionable.view_actionable');?>
    </div>
	<?php }; ?>
  <?php if (!$action_taken && $actionable == 2) { ?>
    <div id="action-urgent-badge">
      <?php echo Kohana::lang('actionable.view_urgent');?>
    </div>
	<?php }; ?>
  <?php if ($action_taken) { ?>
    <div id="action-taken-badge">
      <?php echo Kohana::lang('actionable.view_acted');?>
    </div>
	<?php }; ?>
  <?php if ($action_summary) { ?>
		<div id="action-summary">
		<strong><?php echo Kohana::lang('actionable.view_summary');?></strong><?php echo $action_summary; ?>
		</div>
	<?php }; ?>
</div>
