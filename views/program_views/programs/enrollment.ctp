<?= $this->Html->script('programs/dashboard', array('inline' => false)) ?>
<?= $this->Html->script('adobe-reader-check', array('inline' => false)) ?>
<?= (!empty($instructions) ? '<div id="instructions">' . $instructions . '</div>' : '' ) ?>

<div class="steps-container">
  <ol class="steps">
    <?php if($programResponse['ProgramResponse']['status'] === 'incomplete' || $programResponse['ProgramResponse']['status'] === 'pending_approval') : ?>
    <li class="module incomplete">
      <div class="details">
        <h3><?= $program['Program']['name'] ?> Enrollment</h3>
        <p><?= count($completedSteps)//TODO: get total steps per module ?> of X steps completed</p>
      </div>
      <span class="status">
        <?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
      </span>
      <ol>
        <?php foreach($program['ProgramStep'] as $step) : ?>
		  <?php if(!$step['type']) : ?>
			  <li class="module"><?php echo $step['name'] ?></li>
		  <?php else : ?>
          <?php $class = (in_array($step['id'], $completedSteps)) ? 'complete' : 'incomplete' ?>
          <li class="step <?= $class ?>">
            <div class="inner-container">
              <?= $step['name'] ?>
              <?php $link = ($step['type'] === 'media') ? 'View Media' : 'Complete Form' // TODO make this work for other step types like doc upload?>
              <span class="action">
                <?= $this->Html->link($link, array(
                  'controller' => 'program_responses',
                  'action' => $step['type'],
                  $program['Program']['id'],
                  $step['id']
                )) ?>
              </span>
            </div>
          </li>
		<?php endif ?>
        <?php endforeach ?>
      </ol>
      <?php elseif($programResponse['ProgramResponse']['status'] === 'complete') : ?>
    <li class="module complete">
      <div class="details">
        <h3><?= $program['Program']['name'] ?> Enrollment</h3>
        <p><?= count($completedSteps) ?> of X steps completed</p>
      </div>
      <span class="status">
        <?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
      </span>
      <ol>
        <li class="step certificate">
          <div class="inner-container">
            <?= $this->Html->link(
              'View Certificate',
              array(
                'controller' => 'program_responses',
                'action' => 'view_cert',
                $program['Program']['id']
              )
            )
            ?>
          </div>
        </li>
      </ol>
      <?php endif ?>
    </li>
  </ol>
</div>
