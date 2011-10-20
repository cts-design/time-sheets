<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
    <style>
      /* = disabled nodes
----------------------------------------------- */
.dvp-tree-node-disabled .x-grid-cell {
    -moz-opacity: 0.5;
   opacity:.5;
   filter: alpha(opacity=50);
}

      </style>  
<?php echo $this->Html->script('document_filing_categories/index', array('inline' => FALSE));?>
<?php echo $this->Html->script('ext/ux/NodeDisabled', array('inline' => FALSE));?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Document Filing Categories', true), null, 'unique') ; ?>
</div>

<div id="documentFilingCategoryTree"></div>
