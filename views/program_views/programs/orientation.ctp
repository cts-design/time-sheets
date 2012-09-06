<?= $this->Html->script('programs/dashboard', array('inline' => false)) ?>
<?= $this->Html->script('adobe-reader-check', array('inline' => false)) ?>
<?= (!empty($instructions) ? '<div id="instructions">' . $instructions . '</div>' : '' ) ?>

<div class="steps-container">
  <ol class="steps">
    <?php if($programResponse['ProgramResponse']['status'] === 'incomplete') : ?>
    <li class="program incomplete">
      <div class="details">
        <h3><?= $program['Program']['name'] ?> Orientation</h3>
        <p><?= count($completedSteps) ?> of 2 steps completed</p>
      </div>
      <span class="status">
        Current Status: <?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
      </span>
      <ol>
		<?php $currentStep = 0 ?>
        <?php foreach($program['ProgramStep'] as $step) : ?>
          <?php $class = (in_array($step['id'], $completedSteps)) ? 'complete' : 'incomplete' ?>
		  <?php if($step['parent_id']) : ?>
			<? $currentStep++ ?>
			<?php $redoable = ($step['redoable']) ? 'redoable' : '' ?>
			<li class="step <?= $class ?> <?= $redoable ?>">
            <div class="inner-container">
			<?= "Step $currentStep:" ?>
              <?= $step['name'] ?>
              <?php $link = ($step['type'] === 'media') ? 'View Media' : 'Take Quiz' ?>
				<?php if ($class === 'complete') $link = 'Complete' ?>
              <span class="action">
                <?= $this->Html->link($link, array(
                  'controller' => 'program_responses',
                  'action' => $step['type'],
                  $program['Program']['id'],
                  $step['id']
                )) ?>
				<?php if ($class === 'complete'): ?>
					<?php $completedDate = Set::extract('/ProgramResponseActivity[program_step_id=' . $step['id'] . ']/created', $programResponse) ?>
					<span class="completed-date">
						Completed: <?= date('m/d/Y', strtotime($completedDate[0])) ?>
					</span>
				<?php endif; ?>
              </span>
            </div>
          </li>
		  <?php endif ?>
        <?php endforeach ?>
      </ol>
      <?php elseif($programResponse['ProgramResponse']['status'] === 'complete') : ?>
    <li class="module complete">
      <div class="details">
        <h3><?= $program['Program']['name'] ?> Orientation</h3>
        <p><?= count($completedSteps) ?> of 2 steps completed</p>
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
