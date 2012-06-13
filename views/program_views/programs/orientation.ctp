<style>.step-incomplete{color: #999} .step-complete{color: green}</style>
<?php echo (!empty($instructions) ? '<div id="instructions">' . $instructions . '</div>' : '' ) ?>
<br />
<div>
  <div class="bot-mar-10">
    <span>Overall Status:&nbsp;</span><?php echo Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
  </div>
  <ul>
    <?php if($programResponse['ProgramResponse']['status'] === 'incomplete') : ?>
      <?php foreach($program['ProgramStep'] as $step) : ?>
        <?php if(in_array($step['id'], $completedSteps)) : ?>
          <?php $class = 'step-complete' ?>
        <?php else : ?>
          <?php $class = 'step-incomplete' ?>
        <?php endif ?>
        <li class="<?php echo $class ?>"><?php echo $step['name']; ?>:&nbsp;	
            <?php if($step['type'] === 'media') : ?>
              <?php $linkTitle = 'View Media'; ?>
            <?php else : ?>
              <?php $linkTitle = 'Take Quiz'; ?>
            <?php endif ?>
<?php echo $this->Html->link($linkTitle, array(
  'controller' => 'program_responses',
  'action' => $step['type'],
  $program['Program']['id'], $step['id']));
?>

          </li>
      <?php endforeach ?>
    <?php elseif($programResponse['ProgramResponse']['status'] === 'complete') : ?>
    <li> <?php echo $this->Html->link('View Certificate', array(
      'controller' => 'program_responses', 
      'action' => 'view_cert', $program['Program']['id'])); ?>
      </li>
    <?php endif ?>
  </ul>
</div>
