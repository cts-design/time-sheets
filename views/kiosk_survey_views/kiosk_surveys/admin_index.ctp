<?php echo $this->Html->script('kiosk_surveys/surveys', array('inline' => false)) ?>
<style type="text/css">
  .x-column-inner {
    background: #DFE8F6 !important;
  }
</style>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Kiosk Surveys', null, 'unique'); ?>
</div>
<div id="surveys"></div>
