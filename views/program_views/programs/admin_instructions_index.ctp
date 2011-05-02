<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<script type="text/javascript">
	var programId = <?php echo $this->params['pass'][0] ?>;
</script>

<?php echo $this->Html->script('programs/admin_instructions_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Program Instructions', null, 'unique') ; ?>
</div>

<div id="instructionGrid">

</div>