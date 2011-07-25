<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php $this->Html->scriptStart(array('inline' => false));?>
    var cat2 = <?php echo $this->Js->value($this->data['FiledDocument']['cat_2']);?>;
    var cat3 = <?php echo $this->Js->value($this->data['FiledDocument']['cat_3']);?>;
<?php $this->Html->scriptEnd(); ?>
<?php echo $this->Html->script('jquery.validate', array('inline' => false)) ?>
<?php echo $this->Html->script('filed_documents/edit' ,array('inline' => false)) ?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Edit Document', true), null, 'action') ; ?>
</div>
<div class="filedDocuments form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
<?php  if(!empty($this->data['FiledDocument']['filename'])) {  ?>
<div id="filedDocumentsPdf">
    <object type="application/pdf" data="/admin/filed_documents/view/<?php echo $this->params['pass'][0] ?>#navpanes=0"  width="950" height="500">
    </object>
</div>
<?php } ?>
<?php echo $this->Form->create('FiledDocument');?>
	<fieldset>
 		<legend><?php __('Edit Filed Document'); ?></legend>
	<?php
		echo $this->Form->input('id');
		if(isset($this->params['pass'][1])) {
		    echo $this->Form->input('FiledDocument.edit_type', array('type' => 'hidden', 'value' => 'user'));
		}
		echo $this->Form->input('FiledDocument.scanned_location_id', array('type' => 'hidden'));
		echo $this->Form->input('FiledDocument.admin_id', array('type' => 'hidden'));
		echo $this->Form->input('User.firstname', array('class' => 'required'));

		echo $this->Form->input('User.lastname', array('class' => 'required'));

		echo $this->Form->input('User.ssn', array('label' => 'SSN', 'class' => 'required'));
		echo '<br class="clear" />';
		echo $this->Form->input('cat_1',
			array('type' => 'select', 'label' => false, 'empty' => 'Select Main Cat', 'options' => $cat1, 'class' => 'required'));
		echo $this->Form->input('cat_2', array('type' => 'select', 'label' => false, 'empty' => 'Select 2nd Cat'));
		echo $this->Form->input('cat_3', array('type' => 'select', 'label' => false, 'empty' => 'Select 3rd Cat'));
		echo $this->Form->input('description', array('label' => 'Other'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
