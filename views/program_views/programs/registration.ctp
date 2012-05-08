<?php echo (!empty($instructions) ? '<div id="Instructions">' . $instructions . '</div>' : '' ) ?>
<br />
<div>
	<div class="bot-mar-10">
            <span>Overall Status:&nbsp;</span><?php echo Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
	</div>
	<ul>
		 <li><?php echo $program['ProgramStep'][0]['name']?> &nbsp;
            <?php if($programResponse['ProgramResponse']['status'] === 'incomplete') : ?>
                <?php echo $html->link('Complete Form', '/program_responses/form/' . $program['ProgramStep'][0]['id'])?></li>
            <?php endif ?>
            <?php
				if(!empty($porgramResponse['ProgramResponseActivity']) 
					&& $programResponse['ProgramResponseActivity']['status'] === 'allow_edit' 
                    && $programResponse['ProgramResponse']['status'] === 'not_approved') :
            ?>
                <?php echo $html->link('Edit Form', '/program_responses/edit_form/' . $program['ProgramStep'][0]['id'])?></li>
            <?php endif ?>
	</ul>
</div>
