<?php $this->Html->scriptStart(array('inline' => false)); ?>
  $(function() {
	  $(".button" ).button();
  });
<?php $this->Html->scriptEnd(); ?>
<div id="ecourse-quiz">
	<?php echo $this->Form->create(null, array('url' => '/ecourses/grade')) ?>
	<ol>
	<?php foreach ($ecourseModule['EcourseModuleQuestion'] as $question): ?>
		<?php $answers = array() ?>
		<?php $attributes = array('legend' => false, 'separator' => '<br />') ?>
		<?php foreach($question['EcourseModuleQuestionAnswer'] as $answer): ?>
			<?php $answers[$answer['id']] = $answer['text'] ?>
		<?php endforeach ?>
		<li>
		<?php echo $this->Form->label($question['text']); ?>
		<br />
		<?php echo $this->Form->radio(Inflector::slug($question['text']), $answers, $attributes) ?>
		</li>
	<?php endforeach ?>
	</ol>
	
	<br />
	<?php echo $this->Form->hidden('module_id', array('value' => $ecourseModule['EcourseModule']['id'])); ?>
	<?php echo $this->Form->end('Save'); ?>
</div>
