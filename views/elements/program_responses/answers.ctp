<div id="ProgramAnswers">
	<?php foreach($answers as $k => $v) : ?>
		<p class="left label"><?php echo Inflector::humanize($k)?>:</p><p class="left"><?php echo ucwords($v)?></p>
		<br class="clear" />
	<?php endforeach ?>	
</div>