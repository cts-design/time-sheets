<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php if(isset($instructions)) : ?>
	<div id="Instructions"><?php echo $instructions ?></div>
<?php endif ?>
<?php if(!empty($programResponse) ) : ?>
	<p>
        <?php printf(__('Congratulations! Your %s certificate has been issued. Please download the certificate below', true), $programResponse['Program']['name']) ?>
	</p>
    <p class="left"><strong><?php __('Program:') ?> </strong></p> 
	<p class="left"><?php echo $programResponse['Program']['name'] ?></p>
	<br class="clear" />
    <p class="left"><strong><?php __('Registrant name:') ?> </strong></p>
	<p class="left">
		<?php echo ucfirst($programResponse['User']['firstname']) 
			. ' ' . ucfirst($programResponse['User']['lastname']) ?></p>
	<br class="clear" />
    <p class="left"><strong><?php __('Approved on:') ?> </strong></p>
	<p class="left"><?php echo date('m/d/Y', strtotime($programResponse['ProgramResponse']['modified'])) ?></p>
	<br class="clear" />
	<p class="top-mar-10">
		<?php 
			echo $html->link(__('Download Certificate', true),
				array('action' => 'view_cert', $this->params['pass'][0]), array('target' => '_blank'));
		?>
	</p>
<?php endif ?>
<div class="top-mar-10">
<?php echo $html->link($html->image('get_adobe_reader.png'), 'http://get.adobe.com/reader/', 
	array('escape' => false, 'target' => '_blank')) ?>
</div>
