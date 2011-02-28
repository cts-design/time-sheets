<div class="hotJobs">
	<?php foreach ($hotJobs as $hotJob): ?>
	<div class="job">
		<h2><?php echo $hotJob['HotJob']['title'] ?></h2>
		<p><?php echo $hotJob['HotJob']['description'] ?></p>
		
		<ul>
			<li><strong>Date Posted:</strong> <?php echo date('m/d/Y', strtotime($hotJob['HotJob']['created'])) ?></li>
			<li><strong>Location:</strong> <?php echo $hotJob['HotJob']['location'] ?></li>
			<li><strong>Reference #:</strong> <?php echo $hotJob['HotJob']['reference_number'] ?></li>
			<li><strong>Contact:</strong> <?php echo $hotJob['HotJob']['contact'] ?></li>
		</ul>
		
		<?php echo $this->Html->link('Apply for this job', array('controller' => 'hot_jobs', 'action' => 'apply', $hotJob['HotJob']['id'])) ?>
	</div>
	<?php endforeach; ?>
	
	<p class='counter'>
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
