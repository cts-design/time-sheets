<?php $this->Html->scriptStart(array('inline' => false)); ?>
$(function () {
  $(".button").button();
});
<?php $this->Html->scriptEnd(); ?>
<style>
.big
{
	font-size:1em;
}
.light
{
	color:#636363;
}
.shoulders
{
	margin:5px 0px;
}
</style>
<div style="margin:10px 0px;font-size:12pt">
	<p class="shoulders big light">
		You got a <?= round($quizScore, 1) ?>%
	</p>
	<p class="shoulders big light">
		You have answered <?= $numberCorrect ?> questions right out of <?= count($quizAnswers) ?> questions
	</p>
	<p class="shoulders big light">
		A score of <?= $ecourseModule['EcourseModule']['passing_percentage'] ?>% or higher is required to pass
	</p>
	<p class="shoulders big light">
		You may review your test results in more detail below or use the "Return To Course Overview" button at the bottom of the page.
	</p>
</div>
<ol id="ecourse-quiz-results">
	<?php foreach($ecourseModule['EcourseModuleQuestion'] as $question) : ?> 
		<li>
			<h5><?= Inflector::humanize($question['text']) ?></h5>
			<?php foreach($question['EcourseModuleQuestionAnswer'] as $answer) : ?>
				<?php if(in_array($answer['id'], $userAnswers) && $answer['correct']) : ?>
					<p class="correct-answer"><?= Sanitize::html($answer['text']) ?></p>
				<?php else : ?>	
					<p class="incorrect-answer"><?= Sanitize::html($answer['text']) ?> </p>
				<?php endif ?>
			<?php endforeach ?>
		</li>
	<?php endforeach ?>
</ol>
<br />
<p>
	<?php echo $this->Html->link('Return to Course Overview', '/ecourses/media/'.$ecourseModule['EcourseModule']['ecourse_id'], array('class' => 'button'))?>
</p>
