	<div class="pagination pagination-centered">
		<ul>
			<?php echo $this->Paginator->prev(
				'<i class="icon-double-angle-left"></i>',
				array(
					'escape' => false,
					'tag' => 'li'
				),
				'<span><i class="icon-double-angle-left"></i></span>',
				array(
					'class'=>'disabled',
					'escape' => false,
					'tag' => 'li'
				)
			) ?>
			<?php echo $this->Paginator->numbers(
				array(
					'first' => 1,
					'last' => 1,
					'separator' => false,
					'tag' => 'li'
				)
			) ?>
			<?php echo $this->Paginator->next(
				'<i class="icon-double-angle-right"></i>',
				array(
					'escape' => false,
					'tag' => 'li'
				),
				'<span><i class="icon-double-angle-right"></i></span>',
				array(
					'class'=>'disabled',
					'escape' => false,
					'tag' => 'li'
				)
			) ?>
		</ul>
	</div>
