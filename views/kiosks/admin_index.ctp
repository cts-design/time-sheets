<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('kiosks/admin_index', array('inline' => false)) ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Kiosks', true), null, 'unique'); ?>
</div>
<div class="admin">
    <div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link(__('New Kiosk', true), array('action' => 'add')); ?></li>
	</ul>
    </div>
    <table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
	    <tr>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort(__('Location', true), 'Location.name'); ?></th>
		<th width="20%" class="ui-state-default"><?php echo $this->Paginator->sort(__('Location Recognition Name', true), 'location_recognition_name'); ?></th>
		<th width="30%" class="ui-state-default"><?php echo $this->Paginator->sort(__('Location Description', true), 'location_description'); ?></th>
		<th width="30%" class="ui-state-default"><?php echo $this->Paginator->sort(__('Logout Message', true), 'logout_message'); ?></th>
		<th width="30%" class="ui-state-default"><?php echo $this->Paginator->sort(__('Assistance Message', true), 'assistance_message'); ?></th>
		<th width="10%" class="actions ui-state-default"><?php __('Actions'); ?></th>
	    </tr>
	</thead>
	<?php
	$i = 0;
	foreach ($kiosks as $kiosk):
	    $class = null;
	    if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	    }
	    ?>
    	<tr<?php echo $class; ?> data-kiosk-id="<?php echo $kiosk['Kiosk']['id'] ?>">
    	    <td><?php echo $kiosk['Location']['name']; ?>&nbsp;</td>
    	    <td><?php echo $kiosk['Kiosk']['location_recognition_name']; ?>&nbsp;</td>
    	    <td><?php echo $kiosk['Kiosk']['location_description']; ?>&nbsp;</td>
    	    <td><?php echo $kiosk['Kiosk']['logout_message']; ?>&nbsp;</td>
    	    <td><?php echo $kiosk['Kiosk']['assistance_message']; ?>&nbsp;</td>
    	    <td class="actions">
		    <?php echo $this->Html->link(__('Buttons', true), array('controller' => 'kiosk_buttons', 'action' => 'index', $kiosk['Kiosk']['id']), array('class' => 'buttons')); ?>
		    <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $kiosk['Kiosk']['id']), array('class' => 'edit')); ?>
		    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $kiosk['Kiosk']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete this kiosk?', true), $kiosk['Kiosk']['id'])); ?>

		    <?php 
		    	echo $this->Html->link( 'Set Defaults', 
		    		array('action' => 'set_default_login', $kiosk['Kiosk']['id']) );
		    ?>

		    <?php if (!empty($kiosk['KioskSurvey'])): ?>
		    	<?php echo $this->Html->link(__('Remove Survey', true), '', array('class' => 'remove-survey')) ?>
			    <?php echo $this->Html->link(__('Add Survey', true), '', array('class' => 'add-survey hidden')) ?>
			    <?php echo $this->Html->link(__('Select a Survey', true), '', array('class' => 'select-survey hidden')) ?>
	            <ul style="z-index: 10000 !important">
					<?php foreach ($surveys as $key => $value): ?>
					<li>
						<?php echo $this->Html->link($value['KioskSurvey']['name'], '', array('data-survey-id' => $value['KioskSurvey']['id'])) ?>
					</li>
					<?php endforeach ?>
				</ul>
	            </ul>
		    <?php else: ?>
		    	<?php echo $this->Html->link(__('Remove Survey', true), '', array('class' => 'remove-survey hidden')) ?>
			    <?php echo $this->Html->link(__('Add Survey', true), '', array('class' => 'add-survey')) ?>
			    <?php echo $this->Html->link(__('Select a Survey', true), '', array('class' => 'select-survey')) ?>
	            <ul style="z-index: 10000 !important">
                    <?php foreach ($surveys as $key => $value): ?>
                    <li>
                        <?php echo $this->Html->link($value['KioskSurvey']['name'], '', array('data-survey-id' => $value['KioskSurvey']['id'])) ?>
                    </li>
                    <?php endforeach ?>
	            </ul>
	        <?php endif ?>
    	    </td>
    	</tr>
	<?php endforeach; ?>
    </table>
    <p class="paging-counter">
	<?php
	echo $this->Paginator->counter(array(
	    'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>
    </p>
    <br />
    <div class="paging">
	<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
	 | 	<?php echo $this->Paginator->numbers(); ?>
	|
	<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>
</div>
