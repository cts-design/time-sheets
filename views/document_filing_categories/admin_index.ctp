<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('ext/adapter/ext/ext-base-debug', array('inline' => FALSE));?>
<?php echo $this->Html->script('ext-all-debug', array('inline' => FALSE));?>
<?php echo $this->Html->script('document_filing_categories/index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Document Filing Categories', null, 'unique') ; ?>
</div>

<div id="documentFilingCategoryTree"></div>
