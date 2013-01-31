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
Hello, <?= ucfirst($user['User']['firstname']) ?>\r\n
\r\n
This is just a reminder that you are registered to attend <?= $event['Event']['name'] ?> on 
<?= date('l, F dS h:iA', strtotime($event['Event']['scheduled'])) ?> at <?= $location ?>
(<a href="http://maps.google.com/maps?q=<?php echo urlencode($location) ?>"><?php __('Map It') ?></a>)\r\n
\r\n
You can cancel at anytime by visiting http://<?= Configure::read('domain')?>/users/dashboard
\r\n
<?= Configure::read('Company.name') ?>\r\n
http://<?= Configure::read('domain') ?>
