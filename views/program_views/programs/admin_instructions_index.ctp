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

<?php echo $html->script('programs/admin_instructions_index', array('inline' => false));?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Program Instructions', null, 'unique') ; ?>
</div>

<div id="instructionGrid">

</div>