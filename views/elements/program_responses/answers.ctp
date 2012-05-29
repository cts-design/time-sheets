<div id="ProgramAnswers">
	<?php $i = 0 ?>
	<?php foreach($answers as $answer) : ?>
		<h2><?php echo $stepName[$i]?> </h2>
		<?php foreach($answer as $k => $v) : ?>
			<span class="label"><?php echo Inflector::humanize($k)?>:</span>
			<span><?php echo ucwords($v)?></span>
		<?php endforeach ?>	
		<?php $i++; ?>
	<?php endforeach ?>	
</div>
