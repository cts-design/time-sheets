<?php echo (!empty($instructions) ? '<div id="Instructions">' . $instructions . '</div>' : '' ) ?>
<br />
<div>
    <table width="100%">
        <tr>
            <th>Step</th><th>Status</th><th>Actions</th>
        </tr>
		<?php foreach($program['ProgramStep'] as $step) : ?>
		   <tr>
				<td><?php echo $step['name']; ?></td>	
				<td></td>
				<td>
					<?php if($step['type'] === 'media') : ?>
						<?php $linkTitle = 'View Media'; ?>
					<?php else : ?>
						<?php $linkTitle = 'Take Quiz'; ?>
					<?php endif ?>
					<?php echo $this->Html->link($linkTitle, array(
						'controller' => 'program_responses',
						'action' => $step['type'],
						$program['Program']['id']));
					?>
					
				</td>
		   </tr>
		<?php endforeach ?>
    </table>
</div>
