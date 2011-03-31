<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<?php if (isset($this->params['prefix']) && $this->params['prefix'] == 'kiosk' ) {?>
<div class="message flash-success">
    <?php echo $message ?>
</div>
<?php } else {?>
<div class="message flash-success ui-state-highlight">
    <span class="ui-icon ui-icon-check left" ></span>
    <span class="left ui-state-highlight-text"><?php echo $message ?></span>
</div>
<?php }?>
