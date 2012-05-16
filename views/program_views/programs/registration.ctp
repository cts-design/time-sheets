<?php echo (!empty($instructions) ? '<div id="Instructions">' . $instructions . '</div>' : '' ) ?>
<br />
<div>
	<div class="bot-mar-10">
		<span>Overall Status:&nbsp;</span><?php echo Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
	</div>
	<ul>
		 <li><?php echo $program['ProgramStep'][0]['name']?> &nbsp;
            <?php if($programResponse['ProgramResponse']['status'] === 'incomplete') : ?>
				<?php echo $html->link('Complete Form',  array(
					'controller' => 'program_responses', 
					'action' => 'form', 
					$program['Program']['id'], 
					$program['ProgramStep'][0]['id']))?>
		</li>
            <?php endif ?>
            <?php
				if(!empty($programResponse['ProgramResponseActivity']) 
					&& $programResponse['ProgramResponseActivity'][0]['status'] === 'allow_edit' 
					&& $programResponse['ProgramResponse']['status'] === 'not_approved') : ?>
				<?php echo $html->link('Edit Form', array(
					'controller' => 'program_responses', 
					'action' => 'edit_form', 
					$program['Program']['id'], 
					$program['ProgramStep'][0]['id']))?>
		</li>
            <?php endif ?>
	</ul>
</div>
