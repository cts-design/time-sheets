<div class="featuredEmployers">
	<?php foreach ($featuredEmployers as $featuredEmployer): ?>
	<div class="featuredEmployer">
		<h2><?php echo $featuredEmployer['FeaturedEmployer']['name'] ?></h2>
		<img height="140" width="250" src="<?php echo $featuredEmployer['FeaturedEmployer']['image'] ?>">
		<p><?php echo $featuredEmployer['FeaturedEmployer']['description'] ?></p>
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
