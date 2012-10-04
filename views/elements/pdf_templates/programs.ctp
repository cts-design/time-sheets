<?php foreach($steps as $step) : ?>
	<h2><?php echo $step['name'] ?></h2>
	<ol>
		<?php foreach ($step['answers'] as $k => $v) : ?>
			<li><?php echo Inflector::humanize($k) . ': ' . Inflector::humanize($v) ?></li>
		<?php endforeach ?>
	</ol>
<?php endforeach ?>
