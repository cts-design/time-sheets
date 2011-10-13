<?php echo $this->Html->script('kiosk_surveys/surveys', array('inline' => false)) ?>
<style type="text/css">
</style>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Kiosk Surveys', null, 'unique'); ?>
</div>
<div id="survey-grid"></div>
<div id="grid-form"></div>
