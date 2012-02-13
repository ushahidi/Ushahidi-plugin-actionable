<div class="action-taken clearingfix">
	<?php if (!$action_taken && $actionable == 1) { ?>
    <div id="actionable-badge">
      Action needed
    </div>
	<?php }; ?>
  <?php if (!$action_taken && $actionable == 2) { ?>
    <div id="action-urgent-badge">
      Action urgent
    </div>
	<?php }; ?>
  <?php if ($action_taken) { ?>
    <div id="action-taken-badge">
      Action Taken
    </div>
	<?php }; ?>
  <?php if ($action_summary) { ?>
		<div id="action-summary">
		<strong>Summary: </strong><?php echo $action_summary; ?>
		</div>
	<?php }; ?>
</div>