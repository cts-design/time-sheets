    <style type="text/css">
        label.error 
        {
            border: solid 1px red;  
			color: Red;
        }
    </style><?php echo $this->Html->script('jquery.validate', array('inline' => false)); ?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
  $(function() {
	  $(".button" ).button();
	  $("#quiz-form").validate({
		errorPlacement: function(error, element) {
			console.log(element)
			error.insertAfter(element.parent("li"));
		}
	  });
	$.validator.messages.required = 'Question must be answered';
  });

<?php $this->Html->scriptEnd(); ?>

<div id="ecourse-quiz">
	<?php echo $this->Form->create(null, array('url' => '/ecourses/grade', 'id' => 'quiz-form')) ?>
	<ol>
	<?php foreach ($ecourseModule['EcourseModuleQuestion'] as $question): ?>
		<?php $answers = array() ?>
		<?php $attributes = array('legend' => false, 'separator' => '<br />', 'class' =>  'required', 'message' => 'text') ?>
		<?php foreach($question['EcourseModuleQuestionAnswer'] as $answer): ?>
			<?php $answers[$answer['id']] = $answer['text'] ?>
		<?php endforeach ?>
		<li>
		<?php echo $this->Form->label(Inflector::slug($question['text']), $question['text'], array('class' => 'main-label') ); ?>
		<br />
		<?php echo $this->Form->radio(Inflector::slug($question['text']), $answers, $attributes) ?>
		</li>
		<div class="error"></div>
	<?php endforeach ?>
	</ol>
	
	<br />
	<?php echo $this->Form->hidden('module_id', array('value' => $ecourseModule['EcourseModule']['id'])); ?>
	<?php echo $this->Form->end('Save'); ?>
</div>
