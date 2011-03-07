<div id="crumbWrapper">
    <span>You are here > </span>
<?php echo $crumb->getHtml('Add Helpful Article', null, 'unique'); ?></div>
<div class="helpfulArticles form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('HelpfulArticle');?>
	<fieldset>
 		<legend><?php __('Add Helpful Article'); ?></legend>
	<?php
		echo $this->Form->input('title', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('reporter', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('summary', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('link', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('posted_date', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
