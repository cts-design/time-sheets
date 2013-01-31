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
	<?php $location = "{$event['Location']['name']} - {$address}" ?>
<?php else: ?>
	<?php $location = $event['Event']['address'] ?>
<?php endif ?>

<h2>Hello, <?= ucfirst($user['User']['firstname']) ?></h2>

<p>
	This is just a reminder that you are registered to attend <?= $event['Event']['name'] ?> on
	<?= date('l, F dS h:iA', strtotime($event['Event']['scheduled'])) ?> at <?= $location ?>
	(<a href="http://maps.google.com/maps?q=<?php echo urlencode($location) ?>"><?php __('Map It') ?></a>)
</p>

<p>
	You can cancel at anytime by visiting
	<a href="http://<?= Configure::read('domain')?>/users/dashboard">
		http://<?= Configure::read('domain')?>/users/dashboard
	</a>
</p>

<p>
	<?= Configure::read('Company.name') ?><br />
	<a href="http://<?= Configure::read('domain') ?>">http://<?= Configure::read('domain') ?></a>
</p>
