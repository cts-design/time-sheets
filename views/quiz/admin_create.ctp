<?php echo $this->Form->create('Quiz', array('url' => '/admin/quiz/create')) ?>

<table>
	<tr>
		<td>Name: </td>
		<td><?= $this->Form->input('name') ?></td>
	</tr>
	<tr>
		<td>Description: </td>
		<td><?= $this->Form->text('description') ?></td>
	</tr>
	<tr>
		<td>
			
		</td>
		<td><?= $this->Form->checkbox('active') ?></td>
	</tr>
	<tr>
		<td>
			<?= $this->Form->checkbox('esign_required') ?>
		</td>
	</td>
</table>

<?= $this->Form->end() ?>