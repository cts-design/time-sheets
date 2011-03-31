<?php echo $this->Html->script('flowplayer-3.2.6.min', array('inline' => false)) ?>
<?php echo (!empty($program['Program']['instructions']) ? '<p>' . $program['Program']['instructions'] . '</p>' : '' ) ?>
<br />
<?php if(isset($element)) echo $this->element($element) ?>
