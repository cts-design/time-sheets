<div class="locations">
	<div class="location">
		<div class="left">
			<address>
			<strong><?php echo $location['Location']['public_name'] ?><br /></strong>
			<?php echo $location['Location']['address_1']; ?><br />
			<?php echo (!empty($location['Location']['address_2'])) ? $location['Location']['address_2'] . '<br />' : ''; ?>
			<?php echo $location['Location']['city']; ?>, <?php echo $location['Location']['state']; ?>&nbsp;<?php echo $location['Location']['zip']; ?><br />
			</address>
			<?php echo $location['Location']['telephone']; ?><br />
			<?php echo $location['Location']['fax']; ?>
			<br />
			<br />
            <strong><?php __('Hours:') ?></strong> <?php __('Monday') ?>&ndash;<?php __('Friday') ?> <?php echo date('g:i A', strtotime($location['Location']['open'])) ?>&ndash;<?php echo date('g:i A', strtotime($location['Location']['close'])) ?>
		</div>
		
		<div class="right">
			<?php
				$addressString = '';
				$addressString .= $location['Location']['address_1'];
				if (!empty($location['Location']['address_2'])) {
					$addressString .= ' ' . $location['Location']['address_2'];
				}
				$addressString .= ', ' . $location['Location']['city'] . ', ' . $location['Location']['state'] . ' ' . $location['Location']['zip'];
			?>
			<a href="http://maps.google.com/maps?q=<?php echo urlencode($addressString) ?>">
				<img src="http://maps.google.com/maps/api/staticmap?center=<?php echo urlencode($addressString) ?>&zoom=15&size=230x230&sensor=false&markers=color:blue%7C<?php echo urlencode($addressString) ?>" />
			</a>
		</div>
	</div>
	<br class='clear' />
	<div class="location_facilities">
		<?php echo $location['Location']['facilities'] ?>
	</div>
</div>
