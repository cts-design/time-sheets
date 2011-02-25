<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('jquery.jstree', array('inline' => false)) ?>
<?php echo $this->Html->script('jquery.cookie', array('inline' => false)) ?>
<?php echo $this->Html->script('/js/document_filing_categories/tree', array('inline' => false))?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Document Filing Categories', null, 'unique') ; ?>
</div>
<div id="manageDocumentFilingCategories" class="admin">
    <div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link('Add Root Category', '/admin/document_filing_categories/', array('id' => 'addCategory')) ?></li>
	    <li><?php echo $this->Html->link('Edit Category', '', array('id' => 'editCategory')) ?></li>
	    <li><?php echo $this->Html->link('Delete Category', '', array('id' => 'deleteCategory'), __('Are you sure you want to delete this category?', true)) ?></li>
	</ul>
    </div>
    <?php if (!empty($data)) { ?>
    <h2><?php echo __('Categories')?></h2>
    <p class="expand-wrap"><?php echo $this->Html->link('Expand All', '', array('class' => 'expand')) ?></p>
	<?php echo '<div id="documentCategoryTree">' . $tree->generate($data, array('element' => 'document_filing_categories/document_filing_category_tree')) . '</div>'; ?>
    <?php } else
	echo '<p>There are no categories, please add some</p>'; ?>
    <br />
    <div class="mini-form">
	<fieldset>
	    <legend>Add New Document Filing Category</legend>
	    <?php echo $this->Form->create('DocumentFilingCategory', array('action' => 'add')) ?>
	    <?php echo $this->Form->hidden('parent_id') ?>
	    <?php echo $this->Form->hidden('order', array('value' => 1000))?>
	    <?php echo $this->Form->input('name') ?>
	</fieldset>
	<?php echo $this->Form->end('submit') ?>
    </div>
</div>