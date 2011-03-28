<div class="careerSeekersSurveys view">
<h2><?php  __('Career Seekers Survey');?></h2>
<<<<<<< HEAD
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $careerSeekersSurvey['CareerSeekersSurvey']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Answers'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $careerSeekersSurvey['CareerSeekersSurvey']['answers']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $careerSeekersSurvey['CareerSeekersSurvey']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $careerSeekersSurvey['CareerSeekersSurvey']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Career Seekers Survey', true), array('action' => 'edit', $careerSeekersSurvey['CareerSeekersSurvey']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Career Seekers Survey', true), array('action' => 'delete', $careerSeekersSurvey['CareerSeekersSurvey']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $careerSeekersSurvey['CareerSeekersSurvey']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Career Seekers Surveys', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Career Seekers Survey', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
=======
        <dl><?php $i = 0; $class = ' class="altrow"';?>
                <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
                <dd<?php if ($i++ % 2 == 0) echo $class;?>>
                        <?php echo $careerSeekersSurvey['CareerSeekersSurvey']['id']; ?>
                         
                </dd>
                <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Answers'); ?></dt>
                <dd<?php if ($i++ % 2 == 0) echo $class;?>>
                        <?php echo $careerSeekersSurvey['CareerSeekersSurvey']['answers']; ?>
                         
                </dd>
                <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
                <dd<?php if ($i++ % 2 == 0) echo $class;?>>
                        <?php echo $careerSeekersSurvey['CareerSeekersSurvey']['created']; ?>
                         
                </dd>
                <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
                <dd<?php if ($i++ % 2 == 0) echo $class;?>>
                        <?php echo $careerSeekersSurvey['CareerSeekersSurvey']['modified']; ?>
                         
                </dd>
        </dl>
</div>
<div class="actions">
        <h3><?php __('Actions'); ?></h3>
        <ul>
                <li><?php echo $this->Html->link(__('Edit Career Seekers Survey', true), array('action' => 'edit', $careerSeekersSurvey['CareerSeekersSurvey']['id'])); ?> </li>
                <li><?php echo $this->Html->link(__('Delete Career Seekers Survey', true), array('action' => 'delete', $careerSeekersSurvey['CareerSeekersSurvey']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $careerSeekersSurvey['CareerSeekersSurvey']['id'])); ?> </li>
                <li><?php echo $this->Html->link(__('List Career Seekers Surveys', true), array('action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('New Career Seekers Survey', true), array('action' => 'add')); ?> </li>
        </ul>
</div>
>>>>>>> staging
