<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => FALSE)); ?>
<?php echo $this->Html->script('ckfinder/ckfinder', array('inline' => FALSE)); ?>
<?php echo $this->Html->script('pages/wysiwyg', array('inline' => FALSE)); ?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Add Page', true), null, 'unique'); ?>
</div>
<div class="pages form admin">
    <div class="actions ui-widget-header">
	<ul>

		<li><?php echo $this->Html->link(__('List Pages', true), array('action' => 'index'));?></li>
	</ul>
</div>
<?php echo $this->Form->create('Page');?>
	<fieldset class="wide">
 		<legend><?php __('Admin Add Page'); ?></legend>
	<?php
		echo $this->Form->input('title', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('slug', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left url_label">'
                                                        . $html->url('/', true) .
                                                        '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('content', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left wide">',
							'after' => '</p>'));
		echo '<br class="clear" />';
                echo '<br />';
		echo $this->Form->input('published', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left short">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('authentication_required', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left short">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
