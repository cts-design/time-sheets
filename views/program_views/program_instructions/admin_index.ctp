<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php $html->scriptStart(array('inline' => false)); ?>
	var programId = <?php echo $this->params['pass'][0] ?>;
<?php $html->scriptEnd() ?>

<?php echo $html->script('program_instructions/admin_index', array('inline' => false));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Program Instructions', true), null, 'unique') ; ?>
</div>

<div id="instructions">

</div>
