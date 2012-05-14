<?php echo $this->Html->script(array(
  'Atlas/model/Program',
  'Atlas/store/ProgramStore',
  'Atlas/view/ProgramGrid'),
  array('inline' => false)) ?>
<?php echo $this->Html->script('programs/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Programs', true), null, 'unique') ; ?>
</div>

<div id="programGrid"></div>
<div id="programForm"></div>
