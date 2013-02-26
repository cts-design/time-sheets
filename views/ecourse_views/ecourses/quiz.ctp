<div id="ecourse-quiz">
	<form action="" method="post" accept-charset="utf-8">
	<?php foreach ($ecourseModule['EcourseModuleQuestion'] as $question): ?>
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

	<input type="submit" value="Continue" />
	</form>
</div>
