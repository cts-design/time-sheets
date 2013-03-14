<?= $this->Html->script('jquery.validate', array('inline' => false)); ?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
$(function () {
  $(".button").button();
  $("#quiz-form").validate({
    errorPlacement: function (error, element) {
      error.insertAfter(element.parent("li"));
    }
  });
  $.validator.messages.required = 'Question must be answered';
});
<?php $this->Html->scriptEnd(); ?>

<style type="text/css">
	label.error {
		border: solid 1px red;
		color: Red;
	}
</style>

<div id="ecourse-quiz">
	<?= $this->Form->create(null, array('url' => '/ecourses/grade', 'id' => 'quiz-form')) ?>
	<ol>
	<?php foreach ($ecourseModule['EcourseModuleQuestion'] as $question): ?>
		<?php $answers = array() ?>
		<?php $attributes = array('legend' => false, 'separator' => '<br />', 'class' =>  'required', 'message' => 'text') ?>
		<?php foreach($question['EcourseModuleQuestionAnswer'] as $answer): ?>
			<?php $answers[$answer['id']] = Sanitize::html($answer['text']) ?>
		<?php endforeach ?>
		<li>
		<?= $this->Form->label(Inflector::slug($question['text']), $question['text'], array('class' => 'main-label') ); ?>
		<br />
		<?= $this->Form->radio(Inflector::slug($question['text']), $answers, $attributes) ?>
		</li>
		<div class="error"></div>
	<?php endforeach ?>
	</ol>
	<br />
	<?= $this->Form->hidden('module_id', array('value' => $ecourseModule['EcourseModule']['id'])); ?>
	<?= $this->Form->end('Save'); ?>
</div>
