<ol>
	<?php foreach($ecourseModule['EcourseModuleQuestion'] as $question) : ?> 
		<li>
			<h5><?= Inflector::humanize($question['text']) ?></h5>
			<?php foreach($question['EcourseModuleQuestionAnswer'] as $answer) : ?>
				<?php if(in_array($answer['id'], $userAnswers) && $answer['correct']) : ?>
					<p style="color: green"><?= Sanitize::html($answer['text']) ?></p>
				<?php else : ?>	
					<p style="color: red"><?= Sanitize::html($answer['text']) ?> </p>
				<?php endif ?>
			<?php endforeach ?>
		</li>
	<?php endforeach ?>
</ol>
<br />
<p>
	<?php echo $this->Html->link('Return to course', '/ecourses/index/'.$ecourseModule['EcourseModule']['ecourse_id'] )?>
</p>
