<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->css('accord') ?>
<?php echo $this->Html->css('user_transactions/admin_index.css') ?>
<?php echo $this->Html->script('user_transactions/admin_index.js') ?>
<?php echo $this->Html->script('jquery-cookie-master/jquery.cookie.js') ?>
<?php echo $this->Html->script('QueryString.js') ?>
<div id="crumbWrapper">
<span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml('Activity', null, 'unique') ; ?>
</div>
<div class="userTransactions admin">
    <div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link(__('Docs', true), array('controller' => 'filed_documents', 'action' => 'index', $this->params['pass'][0])); ?></li>
	    <li>
	    	<a href="/admin/user_transactions/index/<?= $this->params['pass'][0] ?>?from_date=<?= $human_from_date ?>&to_date=<?= $human_to_date ?>">
	    		Activity Report
	    	</a>
	    </li>

	    <li style="float:right;vertical-align:middle">
	    	<a name="reset" class="reset-button reset-select">Reset Form</a>
	    </li>

	    <li class="search-module">
	    	<label>Find by module:
		    	<select name="select-module" id="select-module">
		    		<option value=""></option>
		    		<?php foreach($modules as $avail_module): ?>
		    			<option value="<?php echo $avail_module ?>" <?= ($avail_module == $selected_module ? "selected" : "")?>>
		    				<?php echo $avail_module ?>
		    			</option>
		    		<?php endforeach ?>
		    	</select>
	    	</label>
	    </li>
	</ul>
    </div>

    <div class="accord">
    	<div class="accord-header">
    		<h3>Other Options:</h3>
    	</div>
    	<div class="accord-body" style="display:none">
    		<p>Search by Date:</p>
    		<label>
    			From: 
    			<input type="text" name="from" class="fromPicker" value="<?= $human_from_date ?>"/>
    		</label>
    		<label>
    			To:
    			<input type="text" name="to" class="toPicker" value="<?= $human_to_date ?>" />
    		</label>

    		<button class="form-button submit-options">Filter</button>
    		<br clear="all" />
    	</div>
    </div>

    <br />

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
