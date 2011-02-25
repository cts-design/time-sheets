<?php 
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php if(isset ($active)) {$active = $active;} else $active = 0 ?>
<?php echo $this->Html->script('pdfobject', array('inline' => false)) ?>
<?php echo $this->Html->script('queued_documents/index', array('inline' => false)) ?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
    $(document).ready(function(){
	$('#customerAddForm').hide();
	    $('#docAccordion').accordion({
		active: <?php echo $active ?>,
		collapsible: true,
		autoHeight: false
	    });
	<?php if(!empty($this->validationErrors)) { ?>
	    $('.add-customer').trigger('click');
	<?php } ?>
    });
  function embedPDF(){
    var myPDF = new PDFObject({
      url: '/admin/queued_documents/view/<?php echo $lockedDoc['QueuedDocument']['id']?>',
      height: "800px",
      pdfOpenParams: { scrollbars: '1', toolbar: '1', statusbar: '0', messages: '0', navpanes: '0' }

    }).embed('queuedDocumentsPdf');
  }
  window.onload = embedPDF; 
    var cat2 = <?php echo $this->Js->value(
	    (isset($selfScanCat['SelfScanCategory']['cat_2']))? $selfScanCat['SelfScanCategory']['cat_2'] : '' );?>;
    var cat3 = <?php echo $this->Js->value(
	    (isset($selfScanCat['SelfScanCategory']['cat_3']))? $selfScanCat['SelfScanCategory']['cat_3'] : '');?>;
<?php $this->Html->scriptEnd(); ?>
<?php echo $this->Html->script('jquery.idleTimer', array('inline' => false)) ?>
<?php echo $this->Html->script('jquery.validate', array('inline' => false)) ?>
<?php echo $this->Html->css('nyroModal', null, array('inline' => false)) ?>
<?php echo $this->Html->script('jquery.nyroModal-1.6.2', array('inline' => false)) ?>

<?php
$this->Paginator->options(array(
    'update' => '#queuedDocumentsNav',
    'url' => $this->passedArgs
));
?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Queued Documents', null, 'unique'); ?>
</div>
<div class="queuedDocuments admin">
    <div id="docAccordion">
	<h2>
	    <a>Filters <span class="right normal">
		    <strong>Current Applied Filters:</strong>
		    <?php $string = ''; ?>
		    <?php
				if(empty($locationId)) {
				    $string .= 'None';
				}
				if(!empty($locationId)) {
				    $string .= $locations[$locationId] . ', ';
				}
				if(!empty($locationId) && empty($queuedDocId)) {
				    $string .= 'All Programs, ';
				}
				if(!empty($queuedDocId)) {
				    foreach($queuedDocId as $k => $v) {
					$string .= $queueCategories[$v] . ', ';
				    }
				}
				if(!empty($from) && !empty($to)) {
				    $string .= $from . ' - ' . $to;
				}
			    ?>
		   <?php echo trim($string, ', '); ?>
		</span>
	    </a>
	</h2>	
	<div id="queuedDocumentsFilters" class="form">
	    <ul>
		<li>
		    <?php echo $this->Form->create() ?>
		    <?php echo $this->Form->input('location', array(
			    'type' => 'select',
			    'empty' => 'Select Location',
			    'selected' => $locationId
			))?>
		</li>
		<li><label>Program</label></li>
		<li>
		<div class="scrollingCheckboxes">
		    <?php echo $this->Form->input('program', array(
				'type' => 'select',
				'multiple' => 'checkbox',
				'options' => $queueCategories,
				'label' => false,
				'selected' => $queuedDocId
			))?>
		</div>
		</li>
		<li>
		    <?php echo $this->Form->input('date_from', array(
			    'class' => 'date',
			    'value' => (!empty($from) ? $from : ''),
			    'before' => '<p class="left">',
			    'between' => '</p><p class="left">',
			    'after' => '</p>'
			  )) ?>
		    <br class="clear" />
		    <?php echo $this->Form->input('date_to', array(
			    'class' => 'date',
			    'value' => (!empty($to) ? $to : ''),
			    'before' => '<p class="left">',
			    'between' => '</p><p class="left">',
			    'after' => '</p>'
			  )) ?>
		    
		</li>
	    </ul>
	    <br class="clear" />
		<ul class="right">
		    <li><?php echo $this->Form->end(array('label' => 'Set Filters' ))?><li>
		    <li><?php echo $this->Html->link('Reset Filters', array('action' => 'index', 'reset'), array('class' => 'reset'));?></li>
		</ul>
	</div>
	<h2><a>Documents in Queue</a></h2>
	<div id="queuedDocumentsNav">
	    <?php echo $this->element('/queued_documents/index_table')?>
	</div>
	<?php if($canFile) { ?>
	<h2><a>Document Filing</a></h2>
	<div id="documentFiling">
	    <p class="left"><strong>Scanned Date:</strong>
		<?php echo (isset($lockedDoc['QueuedDocument']['created'])) ?
			    date('m/d/Y h:i a', strtotime($lockedDoc['QueuedDocument']['created'])) : ''?>
	    </p>
	    <p class="right">
		<strong>Doc Id:</strong> <?php echo (isset($lockedDoc['QueuedDocument']['id'])) ? $lockedDoc['QueuedDocument']['id'] : '' ?>
		<br />
		<?php if(isset($lockedDoc)) { ?>
		    <span class="small">
			<?php echo Configure::read('Document.storage.path') .
				    date('Y', strtotime($lockedDoc['QueuedDocument']['created'])) . '/' .
				    date('m', strtotime($lockedDoc['QueuedDocument']['created'])) . '/' .
				    $lockedDoc['QueuedDocument']['filename']?>
		    </span>
		<?php }?>
	    </p>
	    <br class="clear" />
	    <hr />
	    <?php echo $this->Form->create(array('action' => 'reassign_queue'))?>
	    <h2>Reassign Queue</h2>
	    <?php echo $this->Form->input('queue_category_id', array(
		    'type' => 'select',
		    'label' => 'Queued Program',
		    'options' => $queueCategories,
		    'selected' => $lockedDoc['QueuedDocument']['queue_category_id']
		    ))?>
	    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $lockedDoc['QueuedDocument']['id'])); ?>
	    <?php echo $this->Form->end(array('label' => 'Re-Assign')) ?>
	    <?php echo $this->Form->create(array('action' => 'delete'))?>
	    <h2>Delete Document</h2>
	    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $lockedDoc['QueuedDocument']['id'])); ?>
	    <?php echo $this->Form->input('reason', array('type' => 'select'))?>
	    <?php echo $this->Form->end('Delete Doc') ?>
	    <br class="clear" />
	    <hr />
	    <?php echo $this->Form->create(array('action' => 'file_document'))?>
	    <h2>Filing Categories</h2><span class="formErrors"></span>
	    <div class="cats" >
		<?php echo $this->Form->input('FiledDocument.cat_1',
			array(
			    'type' => 'select',
			    'label' => false,
			    'empty' => 'Select Main Cat',
			    'options' => $cat1,
			    'class' => 'required',
			    'selected' =>  (!empty($selfScanCat['SelfScanCategory']['cat_1']) ? $selfScanCat['SelfScanCategory']['cat_1'] : '')))?>
		<?php echo $this->Form->input('FiledDocument.cat_2', array('type' => 'select', 'label' => false))?>
		<?php echo $this->Form->input('FiledDocument.cat_3', array('type' => 'select', 'label' => false))?>
		<?php echo $this->Form->input('FiledDocument.description', array('label' => 'Other'))?>
	    </div>
	    <div class="cuInfo">
		<?php echo $this->Form->input('FiledDocument.firstname', array(
			'class' => 'required',
			'value' => (!empty($user['User']['firstname']) ? $user['User']['firstname'] : '')))?>
		<?php echo $this->Form->input('FiledDocument.lastname', array(
			'class' => 'required',
			'value' =>  (!empty($user['User']['lastname']) ? $user['User']['lastname'] : '')))?>
		<?php echo $this->Form->input('FiledDocument.ssn', array(
			'class' => 'required',
			'value' =>  (!empty($user['User']['ssn']) ? $user['User']['ssn'] : '')))?>
		<?php echo $this->Form->input('FiledDocument.id', array('type' => 'hidden', 'value' => $lockedDoc['QueuedDocument']['id']))?>
		<?php echo $this->Form->input('FiledDocument.filename', array('type' => 'hidden', 'value' => $lockedDoc['QueuedDocument']['filename']))?>
		<?php echo $this->Form->input('FiledDocument.scanned_location_id', array('type' => 'hidden', 'value' => $lockedDoc['QueuedDocument']['scanned_location_id']))?>
		<?php echo $this->Form->input('FiledDocument.admin_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')))?>
		<?php echo $this->Form->input('FiledDocument.created', array('type' => 'hidden', 'value' => $lockedDoc['QueuedDocument']['created']))?>
		<?php echo $this->Form->end('Save Record')?>
		<p id="cusNotfound" class="right">If customer was not found via auto-complete, <?php echo $this->Html->link('click here',
			array('action' => '', 'admin' => true ), array('class' => 'add-customer'))?> to add them.
		</p>
		<p id="closeCusNotfound" class="right" style="display:none">To close add customer form, <?php echo $this->Html->link('click here',
			array('action' => '', 'admin' => true ), array('class' => 'close-add-customer'))?>
		</p>
	    </div>
	    <br class="clear" />
	    <div id="customerAddForm" style="display:none">
		<?php echo $this->element('/queued_documents/customer_add_form') ?>
	    </div>
	</div>
	<?php } ?>
    </div>
    <div id="idletimeout">
	If you have an open document it will be unlocked and returned to the queue in <span>1</span>&nbsp;seconds due to inactivity.
	<a href="#" id="idletimeout-resume">Click here to continue using this page</a>.
    </div>
      <?php if(isset($lockedDoc)) { ?>
	<div  id="queuedDocumentsPdf" >
	    
	</div>
    <?php } else echo '<br /><p><strong>There is no loaded document at this time, try changing your filters.</strong></p>'?>
</div>
