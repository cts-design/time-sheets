<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Activity', null, 'unique') ; ?>
</div>
<div class="userTransactions admin">
    <div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link(__('Docs', true), array('controller' => 'filed_documents', 'action' => 'index', $this->params['pass'][0])); ?></li>
	</ul>
    </div>
    <table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
	    <tr>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('Name', 'User.lastname'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('location'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('module'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('details'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('created'); ?></th>
	    </tr>
	</thead>
	<?php
	$i = 0;
	foreach($userTransactions as $userTransaction):
	    $class = null;
	    if($i++ % 2 == 0) {
		$class = ' class="altrow"';
	    }
	?>
    	<tr<?php echo $class; ?>>
    	    <td><?php echo $userTransaction['User']['lastname'] . ', ' . $userTransaction['User']['firstname']; ?>&nbsp;</td>
    	    <td><?php echo $userTransaction['UserTransaction']['location']; ?>&nbsp;</td>
    	    <td><?php echo $userTransaction['UserTransaction']['module']; ?>&nbsp;</td>
    	    <td><?php echo $userTransaction['UserTransaction']['details']; ?>&nbsp;</td>
    	    <td><?php echo $this->Time->format('m-d-Y g:i a', $userTransaction['UserTransaction']['created']); ?>&nbsp;</td>

    	</tr>
	<?php endforeach; ?>
        </table>
        <p class="paging-counter">
	<?php
	    echo $this->Paginator->counter(array(
		'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	    ));
	?>	</p>
        <br />
        <div class="paging">
	<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
    	 | 	<?php echo $this->Paginator->numbers(); ?>
    	|
	<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>
</div>