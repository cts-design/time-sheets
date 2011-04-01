<?php echo $this->Html->script('event_categories/category.js', array('inline' => false)) ?>
<div class="events">
	
	<form class="event_categories" action="<?php echo $this->here; ?>" method="post">
		<ol>
			<li class="top">
				<label class="event_categories_label" for="event_categories_dropdown">Select an event type</label>
				<select id="event_categories_dropdown" name="event_categories_dropdown">
				<?php
					$selected = $categories['selected'];
					unset($categories['selected']);
				?>
				<?php foreach($categories as $id => $category): ?>
					<option value="<?php echo $id ?>"<?php echo ($id === $selected) ? ' selected="selected"' : '' ?>><?php echo $category ?></option>
				<?php endforeach; ?>
				</select>
				<input type="submit" id="category_submit" value="Go" />
			</li>
		</ol>
	</form>
	<br class="clear" />
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
					<h3><?php echo $event['Event']['title'] ?></h3>
					<span class="category"><?php echo $event['EventCategory']['name'] ?></span>
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
						<?php if (!empty($event['Event']['address'])): ?>
						<a href="http://maps.google.com/maps?q=<?php echo urlencode($event['Event']['address']) ?>">Map It</a>
						<?php endif; ?>
					</p>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
				<div class="no-events">
					<p>No events to display</p>
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
