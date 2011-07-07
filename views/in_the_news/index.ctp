<div class="inTheNews">
	<?php foreach ($inTheNews as $inTheNews): ?>
	<div class="article">
		<div class="meta">
			<p class="date"><?php echo date('F d, Y', strtotime($inTheNews['InTheNews']['posted_date'])); ?></p>
			
			<h2><?php echo $inTheNews['InTheNews']['title']; ?></h2>
			<span class="reporter"><?php echo $inTheNews['InTheNews']['reporter']; ?></span>
			
			<p class="summary"><?php echo $inTheNews['InTheNews']['summary']; ?></p>
			<a href="<?php echo $inTheNews['InTheNews']['link']; ?>"><?php __('Read More') ?></a>
		</div>
	</div>
	<?php endforeach; ?>

	<p class="counter">
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
