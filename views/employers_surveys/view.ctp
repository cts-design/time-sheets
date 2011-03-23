<div class="employersSurveys view">
<h2><?php  __('Employers Survey');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $employersSurvey['EmployersSurvey']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Answers'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $employersSurvey['EmployersSurvey']['answers']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $employersSurvey['EmployersSurvey']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $employersSurvey['EmployersSurvey']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Employers Survey', true), array('action' => 'edit', $employersSurvey['EmployersSurvey']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Employers Survey', true), array('action' => 'delete', $employersSurvey['EmployersSurvey']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $employersSurvey['EmployersSurvey']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Employers Surveys', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Employers Survey', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
