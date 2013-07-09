<div id="ProgramAnswers">
	<?php $i = 0 ?>
	<?php if (!empty($notApprovedComment)): ?>
		<p class="label">Not approved reason:</p>
		<p class="left"><?= $notApprovedComment ?></p>
		<br />
		<br />
	<?php endif ?>
	<?php foreach($answers as $answer) : ?>
		<h2><?php echo $stepName[$i][0]?> </h2>
		<?php foreach($answer as $k => $v) : ?>
			<p class="label"><?php echo Inflector::humanize($k)?>:</p>
			<p class="left"><?php echo ucwords($v)?></p>
			<br class="clear" />
		<?php endforeach ?>	
		<?php $i++; ?>
	<?php endforeach ?>	
</div>
