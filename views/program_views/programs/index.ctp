<?php if(!empty($program['ProgramField'])) : ?>
	
	<?php $form->create('Program') ?>
	<?php foreach($program['ProgramField'] as $k => $v) ?>
	
		<?php echo $form->input($v['name'], array(
										    'label' => $v['label'],
										    'type' => $v['type'],
										    'options' => $v['options']				
											))?>
<?php endif ?>