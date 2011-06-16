<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php if(!empty($programResponse) ) : ?>
	<p>
		Congratulations! Your <?php echo $programResponse['Program']['name'] ?> certificate 
		has been issued. Please download the certificate below.
	</p>
	<p class="left"><strong>Program: </strong></p> 
	<p class="left"><?php echo $programResponse['Program']['name'] ?></p>
	<br class="clear" />
	<p class="left"><strong>Registrant name: </strong></p>
	<p class="left">
		<?php echo ucfirst($programResponse['User']['firstname']) 
			. ' ' . ucfirst($programResponse['User']['lastname']) ?></p>
	<br class="clear" />
	<p class="left"><strong>Approved on: </strong></p>
	<p class="left"><?php echo date('m/d/Y', strtotime($programResponse['ProgramResponse']['modified'])) ?></p>
	<br class="clear" />
	<p class="top-mar-10">
		<?php 
			echo $html->link('Download Certificate', 
				array('action' => 'view_cert', $this->params['pass'][0]), array('target' => '_blank'));
		?>
	</p>
<?php endif ?>
<div class="top-mar-10">
<?php echo $html->link($html->image('get_adobe_reader.png'), 'http://get.adobe.com/reader/', 
	array('escape' => false, 'target' => '_blank')) ?>
</div>
