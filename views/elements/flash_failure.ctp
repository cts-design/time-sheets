<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<?php if (isset($this->params['prefix']) && $this->params['prefix'] == 'kiosk' ) {?>
<div class="message flash-failure">
    <?php echo $message ?>
</div>
<?php } else {?>
<div class="message flash-failure ui-state-error">
    <span class="ui-icon ui-icon-alert left" ></span>
    <span class="left ui-state-error-text"><?php echo $message ?></span>
</div>
<?php }?>
