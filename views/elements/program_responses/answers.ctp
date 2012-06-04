<div id="ProgramAnswers">
	<?php $i = 0 ?>
	<?php foreach($answers as $answer) : ?>
		<h2><?php echo $stepName[$i]?> </h2>
		<?php foreach($answer as $k => $v) : ?>
			<p class="label"><?php echo Inflector::humanize($k)?>:</p>
			<p class="left"><?php echo ucwords($v)?></p>
			<br class="clear" />
		<?php endforeach ?>	
		<?php $i++; ?>
	<?php endforeach ?>	
</div>
