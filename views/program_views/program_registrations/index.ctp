
<?php if(!empty($program['ProgramField'])) : ?>
	<?php echo $form->create('ProgramRegistration') ?>
	
	<?php foreach($program['ProgramField'] as $k => $v) : ?>
		
		<?php if($v['type'] == 'radio') : ?>
			<?php $options = json_decode($v['options'], true); ?>
			<?php echo $form->radio($v['name'], $options, array('label' => $v['label'], 'separator' => '&nbsp;')); ?>
			<?php echo '<br />'; ?>
		<?php else : ?>	
			<?php echo $form->input($v['name'], array(
											    'label' => $v['label'],
											    'type' => $v['type'],
											    'options' => $v['options'],
											    'multiple' => $v['multiple']				
												))?>
			<?php echo '<br />'; ?>										
		<?php endif ?>											
	<?php endforeach ?>
	
	<?php echo $form->end('Submit') ?>										
<?php endif ?>