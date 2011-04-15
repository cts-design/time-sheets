<style>
	#programAnswers p {margin-bottom: 5px;}
	#programAnswers .label {width: 500px; text-align: right; margin-right: 10px; font-weight: bold}
</style>

<div id="programAnswers">
	<?php foreach($answers as $k => $v) : ?>
		<p class="left label"><?php echo Inflector::humanize($k)?>:</p><p class="left"><?php echo $v?></p>
		<br class="clear" />
	<?php endforeach ?>	
</div>
