<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php
	$this->Html->script('users/resolve_login_issues', array('inline' => FALSE));
?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Resolve Login Issues', true), null, 'unique') ; ?>
</div>
<br />
<div id="search"></div>
<br />
<div id="grid"></div>
