<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<?php echo $this->Html->script('jquery.validate', array('inline' => false)) ?>
<?php echo $this->Html->script('filed_documents/upload_document' ,array('inline' => false)) ?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Upload Document', null, 'unique') ; ?>
</div>
<div class="filedDocuments form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
<?php  if(!empty($this->data['FiledDocument']['filename'])) {  ?>
<div id="filedDocumentsPdf">
    <object type="application/pdf" data="<?php echo Configure::read('Document.storage.path') .
	    date('Y', strtotime($this->data['FiledDocument']['created'])) . '/' .
	    date('m', strtotime($this->data['FiledDocument']['created'])) . '/' .
	    $this->data['FiledDocument']['filename']?>#navpanes=0"  width="950" height="500">
    </object>
</div>
<?php } ?>
<?php echo $this->Form->create('FiledDocument', array( 'action' => 'upload_document',  'enctype' => 'multipart/form-data'));?>
	<fieldset>
 		<legend><?php __('Upload Document'); ?></legend>
		<div class="formErrors"></div>
	<?php

		echo $this->Form->input('FiledDocument.admin_id',
			array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
		echo $this->Form->input('FiledDocument.last_activity_admin_id',
			array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
		echo $this->Form->input('FiledDocument.filed_location_id',
			array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.location_id')));
		echo $this->Form->input('User.id', array('type' => 'hidden'));
		echo $this->Form->input('User.firstname', array('class' => 'required', 'readonly' => 'readonly'));
		echo $this->Form->input('User.lastname', array('class' => 'required', 'readonly' => 'readonly'));
		if($this->data['User']['role_id'] == 1 ) 
		echo $this->Form->input('User.ssn', array('label' => 'SSN', 'class' => 'required', 'readonly' => 'readonly'));
		echo '<br class="clear" />';
		echo $this->Form->input('cat_1',
			array('type' => 'select', 'label' => false, 'empty' => 'Select Main Cat', 'options' => $cat1, 'class' => 'required'));
		echo $this->Form->input('cat_2', array('type' => 'select', 'label' => false, 'empty' => 'Select 2nd Cat'));
		echo $this->Form->input('cat_3', array('type' => 'select', 'label' => false, 'empty' => 'Select 3rd Cat'));
		echo $this->Form->input('description', array('label' => 'Other'));
		echo '<br class="clear" />';
		echo $this->Form->file('FiledDocument.submittedfile', array('class' => 'required', 'size' => '50'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>