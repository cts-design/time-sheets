<div class="helpfulArticles view">
<h2><?php  __('Helpful Article');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpfulArticle['HelpfulArticle']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpfulArticle['HelpfulArticle']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Reporter'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpfulArticle['HelpfulArticle']['reporter']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Summary'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpfulArticle['HelpfulArticle']['summary']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Link'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpfulArticle['HelpfulArticle']['link']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Posted Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpfulArticle['HelpfulArticle']['posted_date']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpfulArticle['HelpfulArticle']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpfulArticle['HelpfulArticle']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Helpful Article', true), array('action' => 'edit', $helpfulArticle['HelpfulArticle']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Helpful Article', true), array('action' => 'delete', $helpfulArticle['HelpfulArticle']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $helpfulArticle['HelpfulArticle']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Helpful Articles', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Helpful Article', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
