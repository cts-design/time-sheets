<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php
    // @TODO add these to the head of the layout when we integrate ExtJS throughout the project
    $this->Html->script('ext/adapter/ext/ext-base-debug', array('inline' => FALSE));
    $this->Html->script('ext-all-debug', array('inline' => FALSE));
	$this->Html->script('ext/ux/RowEditor', array('inline' => FALSE));
	$this->Html->script('users/resolve_login_issues', array('inline' => FALSE));
?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Resolve Login Issues', null, 'unique') ; ?>
</div>
<br />
<div id="search"></div>
<br />
<div id="grid"></div>