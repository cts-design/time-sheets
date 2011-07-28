<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('self_sign_log_archives/index', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery.form', array('inline' => false));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Self Sign Queue Archives', true), null, 'unique'); ?>
</div>
<div class="selfSignLogArchives admin">
    <div id="search" class="form">
    <h3><a><?php __('Search') ?></a></h3>
	<div>
	    <div id="searchFieldset1" class="left left-mar-10">
		<?php echo $this->Form->create(array( 'controller' => 'self_sign_log_archives', 'action' => 'index')) ?>
        <span><strong><?php __('Locations') ?></strong></span>
		<div class="scrollingCheckboxes left-mar-10 left">
		<?php echo $this->Form->input('locations', array(
			'type' => 'select',
			'label' => false,
			'multiple' => 'checkbox',
			'options' => $locations
		     )) ?>		    
		</div>

		</div>
	    <div id="searchFieldset2" class="left">
		<?php echo $this->Form->input('button_1', array(
			'type' => 'select',
			'empty' => 'All Buttons',
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		     )) ?>
		<br class="clear" />
		<?php echo $this->Form->input('button_2', array(
			'type' => 'select',
			'empty' => 'All Buttons',
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		     )) ?>
		<br class="clear" />
		<?php echo $this->Form->input('button_3', array(
			'type' => 'select',
			'empty' => 'All Buttons',
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		     )) ?>
	    </div>
	    <div  id="searchFieldset3" class="left">
	    <?php echo $this->Form->input('status', array(
		    'type' => 'select',
		    'empty' => 'All',
		    'options' => $statuses,
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		 )) ?>
	    <br class="clear" />
	    <?php echo $this->Form->input('date_from', array(
		    'class' => 'date',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		  )) ?>
	    <br class="clear" />
	    <?php echo $this->Form->input('date_to', array(
		    'class' => 'date',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		  )) ?>	    
	    <?php echo $this->Form->hidden('search', array('value' => 1)) ?>
	    </div>
	    <div id="buttonWrapper">
		<div id="searchButtons">
		    <?php echo $this->Form->button('Reset', array('type' => 'reset', 'id' => 'reset'));?>
		    <?php echo $this->Form->end(array('label' => 'Search', 'div' => false)) ?>
		</div>
		<div id="reportForm">
		    <?php echo $this->Form->create(array('action' => 'report')) ?>
		    <?php echo $this->Form->hidden('search', array('value' => '1')) ?>
		    <?php echo $this->Form->hidden('locations', array('value' => '', 'id' => 'searchLocations')) ?>
		    <?php echo $this->Form->hidden('button_1', array('value' => '', 'id' => 'searchButton1')) ?>
		    <?php echo $this->Form->hidden('button_2', array('value' => '', 'id' => 'searchButton2')) ?>
		    <?php echo $this->Form->hidden('button_3', array('value' => '', 'id' => 'searchButton3')) ?>
		    <?php echo $this->Form->hidden('status', array('value' => '', 'id' => 'searchStatus')) ?>
		    <?php echo $this->Form->hidden('date_from', array('value' => '', 'id' => 'searchDateFrom')) ?>
		    <?php echo $this->Form->hidden('date_to', array('value' => '', 'id' => 'searchDateTo')) ?>
		    <?php echo $this->Form->end(array('label' => 'Report', 'div' => false, 'id' => 'report')) ?>
		</div>
	    </div>
	</div>
    </div>
    <br />
    <div id="ajaxContent">
	<?php echo $this->element('self_sign_log_archives/index_table') ?>
    </div>
</div>
