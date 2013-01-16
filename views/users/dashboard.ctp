<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Dashboard', true), 'reset', 'unique') ; ?>
</div>

<div id="event-registrations" style="border: 1px solid; width: 400px; margin-top: 10px"> 
	<h1>Event Registrations</h1>
	<ul>
	<?php if(!empty($eventRegistrations)) : ?>	
		<?php foreach($eventRegistrations as $key => $value) : ?>
		<li>
			<?= $value['Event']['name'] ?>
			<a href="/events/cancel/<?= $value['Event']['id']?>" class="button green">Cancel Your Registration</a>
		</li>
		<?php endforeach ?>
	<?php endif ?>
	</ul>
</div>


<div id="Registrations" style="border: 1px solid; width: 400px; margin-top: 10px"> 
	<h1>Registrations</h1>
	<?php if(!empty($registrations)) : ?>	
		<?php foreach($registrations as $key => $value) : ?>
			<?php echo $this->Html->link($value['Program']['name'], array('controller' => 'programs', 'action' => 'registration', $value['Program']['id'])) ?>
			<span class="response-status"><?= (!empty($value['ProgramResponse']) ? Inflector::humanize($value['ProgramResponse'][0]['status']) : 'Get Started')?></span>
			<br />
		<?php endforeach ?>
	<?php endif ?>
</div>

<div id="Orientations" style="border: 1px solid; width: 400px; margin-top: 10px"> 
	<h1>Orientations</h1>
	<?php if(!empty($orientations)) : ?>	
		<?php foreach($orientations as $key => $value) : ?>
			<?php echo $this->Html->link($value['Program']['name'], array('controller' => 'programs', 'action' => 'orientation', $value['Program']['id'])) ?>
			<span class="response-status"><?= (!empty($value['ProgramResponse']) ? Inflector::humanize($value['ProgramResponse'][0]['status']) : 'Get Started')?></span>
			<br />
		<?php endforeach ?>
	<?php endif ?>
</div>

<div id="Enrollments" style="border: 1px solid; width: 400px; margin-top: 10px"> 
	<h1>Enrollments</h1>
	<?php if(!empty($enrollments)) : ?>	
		<?php foreach($enrollments as $key => $value) : ?>
			<?php echo $this->Html->link($value['Program']['name'], array('controller' => 'programs', 'action' => 'enrollment', $value['Program']['id'])) ?>
			<span class="response-status"><?= (!empty($value['ProgramResponse']) ? Inflector::humanize($value['ProgramResponse'][0]['status']) : 'Enroll Now')?></span>
			<br />
		<?php endforeach ?>
	<?php endif ?>
</div>
