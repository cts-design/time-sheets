<?php $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php $this->Html->script('ckfinder/ckfinder', array('inline' => false)); ?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var isLandingPage = <?= ($this->data['Page']['landing_page'] ? 'true' : 'false') ?>;
<?php $this->Html->scriptEnd(); ?>
<?php $this->Html->script('pages/admin_edit', array('inline' => false)); ?>

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

	<?= $this->Form->create('Page', array('type' => 'file')) ?>
		<fieldset class="wide">
			<legend><?php __('Admin Add Page'); ?></legend>

		<?php
			echo $this->Form->input('title', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'
			));
			echo '<br class="clear" />';

			echo $this->Form->input('slug', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left url_label">'
				. $html->url('/', true) .
				'pages/</p><p class="left">',
				'after' => '</p>'
			));
			echo '<br class="clear" />'
		?>

		<div class="input text">
			<p class="left"><label for="PageLandingPage">Landing Page?</label></p>
			<?= $this->Form->input('landing_page', array(
				'label' => false
			)) ?>
		</div>

		<?php
			echo '<br class="clear" />';

			echo $this->Form->input('parent_id', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>',
				'options' => $landingPageList,
				'empty' => true
			));
			echo '<br class="clear" />';

			echo $this->Form->input('content', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left wide">',
				'after' => '</p>'
			));
			echo '<br class="clear" /><br />';

			echo $this->Form->input('header_content', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left wide">',
				'after' => '</p>'
			));
			echo '<br class="clear" /><br />';

			echo $this->Form->input('footer_content', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left wide">',
				'after' => '</p>'
			));
			echo '<br class="clear" /><br />';

			echo $this->Form->input('image_url', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>',
				'label' => 'Page Image',
				'type' => 'file'
			));
			echo '<br class="clear" /><br />';

			if (isset($this->data['Page']['image_url'])) {
				echo "<div class='current_image'>
					<h6>Current Image</h6>
					<img src='/img/public/pages/{$this->data['Page']['image_url']}'>
					</div>";
			}

			echo $this->Form->input('published', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left short">',
				'after' => '</p>'
			));
			echo '<br class="clear" />';

			echo $this->Form->input('authentication_required', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left short">',
				'after' => '</p>'
			));
			echo '<br class="clear" />';
		?>
		</fieldset>

	<?php echo $this->Form->end(__('Submit', true));?>
</div>
