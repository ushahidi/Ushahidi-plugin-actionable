<div style="border-top: 2px solid gainsboro; border-bottom: 2px solid gainsboro; margin-top: 20px; padding: 0 10px 20px 10px; background-color: #eee;">
<!-- report is actionable -->
<div class="row">
	<h4>
		Actionable:
    <span>Check if responders can act on this information.</span>
  </h4>
    <?php print form::radio('actionable', '0', $actionable == 0); ?> Un-actionable
    <?php print form::radio('actionable', '1', $actionable == 1); ?> Actionable
		<?php print form::radio('actionable', '2', $actionable == 2); ?> Urgent
</div>
<!-- / report is actionable -->

<!-- report is acted on -->
<div class="row">
  <h4>
    Action Taken: <?php print form::checkbox('action_taken', '1', $action_taken); ?> 
    <span>Check if action was taken and enter the action summary.</span>
  </h4>
  <textarea name="action_summary" id="action_summary" style=" height: 60px;"><?php echo $action_summary; ?></textarea>
</div>
<!-- / report is acted on -->
</div>