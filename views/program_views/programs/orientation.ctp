<?= $this->Html->script('programs/dashboard', array('inline' => false)) ?>
<?= $this->Html->script('adobe-reader-check', array('inline' => false)) ?>
<?= (!empty($instructions) ? '<div id="instructions">' . $instructions . '</div>' : '' ) ?>

<div class="steps-container">
  <ol class="steps">
    <?php if($programResponse['ProgramResponse']['status'] === 'incomplete') : ?>
    <li class="module incomplete">
      <div class="details">
        <h3><?= $program['Program']['name'] ?> Orientation</h3>
        <p><?= count($completedSteps) ?> of 2 steps completed</p>
      </div>
      <span class="status">
        <?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
      </span>
      <ol>
        <?php foreach($program['ProgramStep'] as $step) : ?>
          <?php $class = (in_array($step['id'], $completedSteps)) ? 'complete' : 'incomplete' ?>
		  <?php if($step['parent_id']) : ?>
			<?php $redoable = ($step['redoable']) ? 'redoable' : '' ?>
			<li class="step <?= $class ?> <?= $redoable ?>">
            <div class="inner-container">
              <?= $step['name'] ?>
              <?php $link = ($step['type'] === 'media') ? 'View Media' : 'Take Quiz' ?>
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
