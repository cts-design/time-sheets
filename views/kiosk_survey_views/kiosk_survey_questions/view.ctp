<div class="kioskSurveyQuestions view">
<h2><?php  __('Kiosk Survey Question');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $kioskSurveyQuestion['KioskSurveyQuestion']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Kiosk Survey Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $kioskSurveyQuestion['KioskSurveyQuestion']['kiosk_survey_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Question'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $kioskSurveyQuestion['KioskSurveyQuestion']['question']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $kioskSurveyQuestion['KioskSurveyQuestion']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $kioskSurveyQuestion['KioskSurveyQuestion']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Kiosk Survey Question', true), array('action' => 'edit', $kioskSurveyQuestion['KioskSurveyQuestion']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Kiosk Survey Question', true), array('action' => 'delete', $kioskSurveyQuestion['KioskSurveyQuestion']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $kioskSurveyQuestion['KioskSurveyQuestion']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Kiosk Survey Questions', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Kiosk Survey Question', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
