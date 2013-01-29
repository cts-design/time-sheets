<?php echo $this->Html->script('events/category.js', array('inline' => false)) ?>
<div id="events">
	<form class="event_categories" action="<?php echo $this->here; ?>" method="post">
		<ol>
			<li class="top">
			<label class="event_categories_label" for="event_categories_dropdown"><?php __('Select an event type') ?></label>
				<select id="event_categories_dropdown" name="event_categories_dropdown">
				<?php
					$selected = $selectedCategory;
				?>
				<?php foreach($categories as $id => $category): ?>
					<option value="<?php echo $id ?>"<?php echo ($id === $selected) ? ' selected="selected"' : '' ?>><?php echo $category ?></option>
				<?php endforeach; ?>
				</select>
					<input type="submit" id="category_submit" value="<?php __('Go') ?>" />
			</li>
		</ol>
	</form>
	<div class="calnav events">
		<a href="/events/index?date=<?= $prevMonday ?>" class="button gray">
			<i class="icon-chevron-left"></i>
			Previous Week
		</a>

		<h2>
			Events for the week of
			<?= date('m/d/Y', strtotime($bow)) ?>
			&mdash;
			<?= date('m/d/Y', strtotime($eow)) ?>
		</h2>

		<a href="/events/index?date=<?= $nextMonday ?>" class="button gray">
			Next Week
			<i class="icon-chevron-right"></i>
		</a>
	</div>

	<?php $event_start_date = strtotime($events[0]['Event']['scheduled']) ?>
	<div class="event-start-date">
		<h2>
			<i class="icon-calendar"></i>
			<?= date('l, F dS Y', $event_start_date) ?>
		</h2>
	</div>
	<?php if (!empty($events)): ?>
		<?php foreach ($events as $event): ?>
			<?php $day = date('l, F dS', strtotime($event['Event']['scheduled'])) ?>
			<?php $month = date('M', strtotime($event['Event']['scheduled'])) ?>
			<?php $startTime = strtotime($event['Event']['scheduled']) ?>
			<?php $endTime = strtotime("+{$event['Event']['duration']} hours", $startTime) ?>
			<?php $event_start_date = strtotime($events[0]['Event']['scheduled']) ?>

			<?php if (strtotime(date('m/d/Y', strtotime($event['Event']['scheduled']))) > $event_start_date): ?>
			<?php $event_start_date = strtotime($event['Event']['scheduled']) ?>
			<div class="event-start-date">
				<h2>
					<i class="icon-calendar"></i>
					<?= date('l, F dS Y', $event_start_date) ?>
				</h2>
			</div>
			<?php endif ?>

			<div class="event">
				<div class="details">
					<div class="attend">
						<?php if (in_array($event['Event']['id'], $userEventRegistrations)): ?>
							<a href="/events/cancel/<?= $event['Event']['id'] ?>/workshop" class="button green">Cancel Your Registration</a>
						<?php else: ?>
							<a href="/events/attend/<?= $event['Event']['id'] ?>/workshop" class="button green">Attend This Event</a>
						<?php endif ?>
						<p class="availibility">
							<i class="icon-group icon-large"></i>
							<?php $seatsTaken = $event['Event']['seats_available'] - $event['Event']['event_registration_count'] ?>
							<?= $seatsTaken ?>
							of
							<?= $event['Event']['seats_available'] ?>
							seats still available
						</p>
					</div>

					<h2><?php echo $event['Event']['name'] ?></h2>

					<ul>
						<li>
							<i class="icon-time icon-large"></i>
							<span class="datetime">
								<?= date('h:iA', $startTime) ?> &ndash;
								<?= date('h:iA', $endTime) ?>
							</span>
						</li>
						<li>
							<i class="icon-map-marker icon-large"></i>
							<?php if (isset($event['Location']['id']) && !empty($event['Location']['id'])): ?>
								<?php
									$address_parts = array();
									array_push($address_parts, $event['Location']['address_1']);
									if (isset($event['Location']['address_2']) && !empty($event['Location']['address_2']))
										array_push($address_parts, $event['Location']['address_2']);
									array_push($address_parts, $event['Location']['city']);
									array_push($address_parts, "{$event['Location']['state']} {$event['Location']['zip']}");
									$address = join($address_parts, ', ');
								?>
								<strong><?= $event['Location']['name'] ?></strong> - <?= $address ?>
								(<a href="http://maps.google.com/maps?q=<?php echo urlencode($address) ?>"><?php __('Map It') ?></a>)
							<?php else: ?>
								<?= $event['Event']['address'] ?>
								(<a href="http://maps.google.com/maps?q=<?php echo urlencode($event['Event']['address']) ?>"><?php __('Map It') ?></a>)
							<?php endif ?>
						</li>
					</ul>

					<?php if (isset($event['Event']['url']) && !empty($event['Event']['url'])): ?>
					<ul>
						<li>
							<i class="icon-globe icon-large"></i>
							<a href="<?= $event['Event']['url'] ?>">
								<?= $event['Event']['url'] ?>
							</a>
						</li>
					</ul>
					<?php endif ?>

					<p class="description"><?= $event['Event']['description'] ?></p>

					<?php if (!empty($event['Event']['sponsor'])): ?>
					<p class="sponsor">
						This Event is Sponsored By
						<?php if (!empty($event['Event']['sponsor_url'])): ?>
						<a href="<?php echo $event['Event']['sponsor_url'] ?>"><?php echo $event['Event']['sponsor'] ?></a>
						<?php else: ?>
						<?php echo $event['Event']['sponsor'] ?>
						<?php endif ?>
					</p>
					<?php endif; ?>

					<p class="event_links">
						<?php if (!empty($event['Event']['event_url'])): ?>
						<a href="<?php echo $event['Event']['event_url'] ?>"><?php __('Visit Website') ?></a>
						<?php endif; ?>
						<?php if (!empty($event['Event']['address'])): ?>
						<?php endif; ?>
					</p>
				</div>
			</div>
			<div class="clear"></div>
		<?php endforeach; ?>
	<?php else: ?>
			<div class="no-events">
			<p><?php __('No events to display') ?></p>
			</div>
	<?php endif; ?>

	<?= $this->element('frontend_pagination') ?>
</div>
