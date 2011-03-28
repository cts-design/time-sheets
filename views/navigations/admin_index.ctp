<?php
	if ($session->read('Auth.User.role_id') == 2) {
		echo $this->Html->scriptBlock(
			"var role = 2;"
		);
	}

    // @TODO add these to the head of the layout when we integrate ExtJS throughout the project
    $this->Html->script('ext/adapter/ext/ext-base-debug', array('inline' => FALSE));
    $this->Html->script('ext-all-debug', array('inline' => FALSE));
    $this->Html->script('navigations/tree', array('inline' => FALSE))
?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Navigation', null, 'unique'); ?>
</div>

<div class="navigations">
    <div id="window-div"></div>
    <div id="tree-div"></div>
</div>