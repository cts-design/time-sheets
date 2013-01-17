<!--[if gte IE 9]>
	<style type="text/css" media="screen">
		.widget .header { filter: none; }
	</style>
<![endif]-->

<div id="dashboard">
	<?php if(!empty($eventRegistrations)) : ?>
	<div id="event-registrations" class="widget">
		<div class="widget-header">
			<h2>Event Registrations</h2>
		</div>
		<div class="widget-content">
			<ul>
				<?php foreach($eventRegistrations as $key => $value) : ?>
				<li>
					<div class="title">
						<?= $value['Event']['name'] ?>
					</div>
					<div class="details">
						<i class="icon-calendar"></i>
						<?= date('l, F dS', strtotime($value['Event']['scheduled'])) ?>
						<i class="icon-time"></i>
						<?= date('h:iA', strtotime($value['Event']['scheduled'])) ?>
						<i class="icon-map-marker"></i>
						<?php if (isset($value['Location']['id']) && !empty($value['Location']['id'])): ?>
							<?php
								$address_parts = array();
								array_push($address_parts, $value['Location']['address_1']);
								if (isset($value['Location']['address_2']) && !empty($value['Location']['address_2']))
									array_push($address_parts, $value['Location']['address_2']);
								array_push($address_parts, $value['Location']['city']);
								array_push($address_parts, "{$value['Location']['state']} {$value['Location']['zip']}");
								$address = join($address_parts, ', ');
							?>
							<?= "{$value['Location']['name']} - {$address}" ?>
							(<a href="http://maps.google.com/maps?q=<?php echo urlencode($address) ?>"><?php __('Map It') ?></a>)
						<?php else: ?>
							<?= $value['Event']['address'] ?>
							(<a href="http://maps.google.com/maps?q=<?php echo urlencode($value['Event']['address']) ?>"><?php __('Map It') ?></a>)
						<?php endif ?>
					</div>

					<span class="action">
						<a href="/events/cancel/<?= $value['Event']['id']?>" class="button gray">Cancel Your Registration</a>
					</span>
				</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
	<?php endif ?>


	<?php if(!empty($registrations)) : ?>
	<div id="online-registrations" class="widget">
		<div class="widget-header">
			<h2>Online Registrations</h2>
		</div>

		<div class="widget-content">
			<ul>
			<?php foreach($registrations as $key => $value) : ?>
				<li>
					<div class="title">
						<a href="/programs/registration/<?= $value['Program']['id'] ?>"><?= Inflector::humanize($value['Program']['name']) ?></a>
					</div>
					<div class="details"></div>

					<span class="action">
						<a href="/programs/registration/<?= $value['Program']['id'] ?>" class="button gray">
							<?= (!empty($value['ProgramResponse']) ? Inflector::humanize($value['ProgramResponse'][0]['status']) : 'Get Started') ?>
						</a>
					</span>
				</li>
			<?php endforeach ?>
			</ul>
		</div>
	</div>
	<?php endif ?>

	<?php if(!empty($orientations)) : ?>
	<div id="online-orientations" class="widget">
		<div class="widget-header">
			<h2>Online Orientations</h2>
		</div>

		<div class="widget-content">
			<ul>
			<?php foreach($orientations as $key => $value) : ?>
				<li>
					<div class="title">
						<a href="/programs/orientation/<?= $value['Program']['id'] ?>"><?= Inflector::humanize($value['Program']['name']) ?></a>
					</div>
					<div class="details"></div>

					<span class="action">
						<a href="/programs/orientation/<?= $value['Program']['id'] ?>" class="button gray">
							<?= (!empty($value['ProgramResponse']) ? Inflector::humanize($value['ProgramResponse'][0]['status']) : 'Get Started') ?>
						</a>
					</span>
				</li>
			<?php endforeach ?>
			</ul>
		</div>
	</div>
	<?php endif ?>

	<?php if(!empty($enrollments)) : ?>
	<div id="online-enrollments" class="widget">
		<div class="widget-header">
			<h2>Online Enrollments</h2>
		</div>

		<div class="widget-content">
			<ul>
			<?php foreach($enrollments as $key => $value) : ?>
				<li>
					<div class="title">
						<a href="/programs/enrollment/<?= $value['Program']['id'] ?>"><?= Inflector::humanize($value['Program']['name']) ?></a>
					</div>
					<div class="details"></div>

					<span class="action">
						<a href="/programs/enrollment/<?= $value['Program']['id'] ?>" class="button gray">
							<?= (!empty($value['ProgramResponse']) ? Inflector::humanize($value['ProgramResponse'][0]['status']) : 'Get Started') ?>
						</a>
					</span>
				</li>
			<?php endforeach ?>
			</ul>
		</div>
	</div>
	<?php endif ?>
</div>
