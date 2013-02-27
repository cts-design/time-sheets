<?php $this->Html->scriptStart(array('inline' => false)); ?>
  $(function() {
	  $(".button" ).button();
  });
<?php $this->Html->scriptEnd(); ?>
<div id="ecourse-quiz">
	<form action="/ecourses/save/<?= $this->params['pass'][0] ?>" method="post" accept-charset="utf-8">
	<?php foreach ($ecourse['EcourseModule'][0]['EcourseModuleQuestion'] as $question): ?>
		<div class="question">
			<p><strong><?= $question['text'] ?></strong></p>
			<ol>
			<?php foreach ($question['EcourseModuleQuestionAnswer'] as $answer): ?>
				<li>
					<label>
						<input type="radio" name="<?= Inflector::slug($question['text']) ?>" id="" value="" />
						<?= $answer['text'] ?>
					</label>
				</li>
			<?php endforeach ?>
			</ol>
		</div>
	<?php endforeach ?>

	<input class="button" type="submit" value="Save Quiz" />
	</form>
</div>
