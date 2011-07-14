<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
    var test = obscure('246493082');
    var search_by1 = '<?php echo (isset($search_by1)) ? $search_by1 : null ?>';
    var search_scope1 = '<?php echo (isset($search_scope1)) ? $search_scope1 : null ?>';
    var search_term1 = '<?php echo (isset($search_term1)) ? $search_term1 : null ?>';
    var search_by2 = '<?php echo (isset($search_by2)) ? $search_by2 : null ?>';
    var search_scope2 = '<?php echo (isset($search_scope2)) ? $search_scope2 : null ?>';
    var search_term2 = '<?php echo (isset($search_term2)) ? $search_term2 : null ?>';
<?php echo $this->Html->scriptEnd() ?>
<?php if ($canViewFullSsn): ?>
    <?php echo $this->Html->script('users/obscure_ssn.js', array('inline' => false)) ?>
<?php endif; ?>
<?php echo $this->Html->script('users/search.js', array('inline' => false)) ?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Customers');?>
</div>
<div class="">
    <?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
    <div class="actions ui-widget-header">
	    <ul>
		<li><?php echo $this->Html->link(__('New Customer', true), array('action' => 'add')); ?></li>
	    <?php if(empty($this->params['pass'][0])) : ?>
	    	<li><?php echo $this->Html->link(__('Show Disabled', true), array('action' => 'index', 'admin' => true, 'include_disabled')); ?></li>
		<?php else : ?>
			<li><?php echo $this->Html->link(__('Hide Disabled', true), array('action' => 'index', 'admin' => true)); ?></li>
		<?php endif ?>		
	    </ul>
    </div>
    <div id="searchForm"></div>
<br />
<div class="admin">
    <table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
	    <tr>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort('firstname'); ?></th>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort('lastname'); ?></th>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort('ssn'); ?></th>
		<th width="30%" class="ui-state-default"><?php echo $this->Paginator->sort('email'); ?></th>
		<th width="10%" class="actions ui-state-default"><?php __('Actions'); ?></th>
	    </tr>
	</thead>
	<?php
	$i = 0;
	foreach ($users as $user):
	    $class = null;
	    if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	    }
	    ?>
    	<tr<?php echo $class; ?>>
    	    <td><?php echo $user['User']['firstname']; ?>&nbsp;</td>
    	    <td><?php echo $user['User']['lastname']; ?>&nbsp;</td>
            <?php if ($canViewFullSsn): ?>
    	    <td class="ssn"><?php echo $user['User']['ssn'] ?>&nbsp;</td>
            <?php else: ?>
    	    <td><?php echo "*****" . substr($user['User']['ssn'], -4); ?>&nbsp;</td>
            <?php endif; ?>
	    	<td><?php echo $this->Html->link($user['User']['email'], 'mailto:'.$user['User']['email']); ?>&nbsp;</td>
	    	<td class="actions">
				<?php echo $this->Html->link(__('Docs', true), array('controller' => 'filed_documents',  'action' => 'index', $user['User']['id']), array('class' => 'docs')); ?>
				<?php echo $this->Html->link(__('Activity', true), array('controller' => 'user_transactions',  'action' => 'index', $user['User']['id']), array('class' => 'activity')); ?>
				<?php if($user['User']['disabled'] == 0) :?>
					<?php echo $this->Html->link(__('Upload', true), array('controller' => 'filed_documents',  'action' => 'upload_document', $user['User']['id']), array('class' => 'docs')); ?>
					<?php echo $this->Html->link(__('Scan', true), array('controller' => 'filed_documents',  'action' => 'scan_document', $user['User']['id']), array('class' => 'docs')); ?>
			    <?php endif ?>
			    <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $user['User']['id']), array('class' => 'edit')); ?>
			   	<?php if($user['User']['disabled'] == 0) :?>
		    		<?php echo $this->Html->link(__('Disable', true), array('controller' => 'users',  'action' => 'toggle_disabled', $user['User']['id'], 1, 'Customer')); ?>
		    	<?php else : ?>
		    		<?php echo $this->Html->link(__('Enable', true), array('controller' => 'users',  'action' => 'toggle_disabled', $user['User']['id'], 0, 'Customer')); ?> 
		    	<?php endif ?>
    		</td>
    	</tr>
	<?php endforeach; ?>
    </table>
    <p class="paging-counter">
	<?php
    $options = array();

    if (isset($search_by1) && $search_by1) {
        $options['url']['search_by1'] = $search_by1;
        $options['url']['search_scope1'] = $search_scope1;
        $options['url']['search_term1'] = $search_term1;
    }

    if (isset($search_by2) && $search_by2) {
        $options['url']['search_by2'] = $search_by2;
        $options['url']['search_scope2'] = $search_scope2;
        $options['url']['search_term2'] = $search_term2;
    }

    $this->Paginator->options($options);

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
</div>
