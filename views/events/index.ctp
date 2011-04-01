<div class="events">
	
	<form class="event_categories">
		<ol>
			<li class="top">
				<label class="event_categories_label" for="event_categories_dropdown">Select an event type</label>
				<select id="event_categories_dropdown" name="event_categories_dropdown">
					<option>Board Meetings</option>
					<option>Business Seminars</option>
					<option>Job Fairs</option>
					<option>Networking Events</option>
					<option>Workshops</option>
				</select>
			</li>
			<li class="bottom">
				<span>
					<a href=""> < </a>
				</span>
				<span>
					<a href="">March</a>
				</span>
				<span>
					<a href=""> > </a>
				</span>
			</li>
		</ol>
	</form>
	<br class="clear" />
	<?php foreach ($events as $event): ?>
		<div class="event">
			<h3><?php echo $event['Event']['title'] ?></h3>
			<?php if ($event['Event']['all_day'] == '0'): ?>
			<span class="datetime"><?php echo date('F d, Y h:iA', strtotime($event['Event']['start'])) ?>&mdash;<?php echo date('h:iA', strtotime($event['Event']['end'])) ?></span>	
			<?php else: ?>	
			<span class="datetime"><?php echo date('F d, Y', strtotime($event['Event']['start'])) ?></span>
			<?php endif; ?>	
			<p class="location"><span class="label">Location:</span> <?php echo $event['Event']['location'] ?></p>
			<p class="address"><span class="label">Address: <?php echo $event['Event']['address'] ?></span></p>
			
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
				<a href="<?php echo $event['Event']['event_url'] ?>">Visit Website</a>
				<?php endif; ?>
				<a href="http://maps.google.com/maps?q=<?php echo urlencode($event['Event']['address']) ?>">Map It</a>
			</p>
		</div>
	<?php endforeach; ?>
	
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
</div>
