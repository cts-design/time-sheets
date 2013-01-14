<?php echo $this->Html->script('events/category.js', array('inline' => false)) ?>
<div class="events">
	<div class="allEvents">
		<p class="calnav">
			<span>
				<a class="previousMonth" href="/events/index/<?php echo $prevMonth ?>"> < </a>
			</span>
			<span>
				<a class="currentMonth" href=""><?php echo $curMonth ?></a>
			</span>
			<span>
				<a class="nextMonth" href="/events/index/<?php echo $nextMonth ?>"> > </a>
			</span>
		</p>
		<?php if (!empty($events)): ?>
			<?php foreach ($events as $event): ?>
				<div class="event">
					<h3><?php echo $event['Event']['name'] ?></h3>
					<span class="category">Workshop</span>
					<?php $startTime = strtotime($event['Event']['scheduled']) ?>
					<?php $endTime = strtotime("+{$event['Event']['duration']} hours", $startTime) ?>
					<span class="datetime"><?php echo date('F d, Y h:iA', $startTime) ?>&mdash;<?php echo date('h:iA', $endTime) ?></span>
					<p class="location">
						<span class="label">Location:</span>
						<?php echo $event['Location']['name'] ?>
					</p>
					<p class="address">
						<span class="label">Address: <?php echo $event['Event']['address'] ?></span>
					</p>

					<p class="description"><?php echo $event['Event']['description'] ?></p>

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
							<a href="http://maps.google.com/maps?q=<?php echo urlencode($event['Event']['address']) ?>"><?php __('Map It') ?></a>
						<?php endif; ?>
					</p>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
				<div class="no-events">
				<p><?php __('No events to display') ?></p>
				</div>
		<?php endif; ?>

	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>
	<br />
	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
	</div> <!-- end .allEvents -->
</div>
