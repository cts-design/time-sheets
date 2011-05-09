<div id="ProgramAnswers">
	<table>
	<?php foreach($answers as $k => $v) : ?>
		<tr>
			<td class="label" style="padding-right: 10px;"><?php echo Inflector::humanize($k)?>:</td>
			<td><?php echo ucwords($v)?></td>
		</tr>
	<?php endforeach ?>	
	</table>
</div>