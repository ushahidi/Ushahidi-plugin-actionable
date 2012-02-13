<div class="action-taken clearingfix">
	<?php if ($actionable == 1) { ?>
    <div id="action-taken-badge">
      Actionable
    </div>
	<?php }; ?>
  <?php if ($actionable == 2) { ?>
    <div id="action-taken-badge">
      Urgent
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