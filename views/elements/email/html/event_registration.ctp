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
	<?php $location = $event['Location']['name'] . ' ' . Configure::read('Company.name') . ' Career Center  - ' . $address ?>
<?php else: ?>
	<?php $location = $event['Event']['address'] ?>
<?php endif ?>

<h2>Dear <?= ucfirst($user['User']['firstname']) ?>,</h2>

<p>Congratulations!</p>

<p>
	You've registered for the <?= $event['Event']['name'] ?> workshop on
	<?= date('l, F dS h:iA', strtotime($event['Event']['scheduled'])) ?> at the <?= $location ?>. 
	(<a href="http://maps.google.com/maps?q=<?php echo urlencode($location) ?>"><?php __('Map It') ?></a>)
</p>

<p>
	You can cancel at anytime by visiting
	<a href="http://<?= Configure::read('domain')?>/users/dashboard">
		http://<?= Configure::read('domain')?>/users/dashboard
	</a>
</p>

<p>Please arrive at least 10 minutes early so that the workshop can start on time.</p>

<p>We look forward to seeing you then.</p>

<p>
	Sincerely,<br />
	The <?= Configure::read('Company.name') ?> Team <br />
	<a href="http://<?= Configure::read('domain') ?>">http://<?= Configure::read('domain') ?></a>
</p>
