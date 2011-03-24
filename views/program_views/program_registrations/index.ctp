<?php if(!empty($program['ProgramField'])) : ?>
	<?php echo $form->create('ProgramRegistration', array('action' => 'index/' . $program['Program']['id'])); ?>	
	<?php foreach($program['ProgramField'] as $k => $v) : ?>
		<?php $options = json_decode($v['options'], true); ?>
		<?php $attributes = array(
							    'label' => $v['label'],
							    'type' => $v['type'],
							    'options' => $options); ?>
		<?php if(!empty($v['attributes'])) : ?>
			<?php $attributes = Set::merge($attributes, json_decode($v['attributes'])); ?>
		<?php endif; ?>						    
		<?php echo $form->input($v['name'], $attributes); ?>
		<?php echo '<br />'; ?>																				
	<?php endforeach; ?>	
	<?php echo $form->end('Submit'); ?>										
<?php endif; ?>