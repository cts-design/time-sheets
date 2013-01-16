<h2>Hello, <?= ucfirst($user['User']['firstname']) ?></h2>

<p>You've registered to attend <?= $event['Event']['name'] ?> on <?= date('l, F dS', strtotime($event['Event']['scheduled'])) ?> at <?= date('h:iA', strtotime($event['Event']['scheduled'])) ?></p>

<p>
	You can cancel at anytime by visiting
	<a href="http://<?= Configure::read('domain')?>/events/cancel/<?= $event['Event']['id'] ?>">
		http://<?= Configure::read('domain')?>/events/cancel/<?= $event['Event']['id'] ?>
	</a>
</p>

<p>
	<?= Configure::read('Company.name') ?><br />
	<a href="http://<?= Configure::read('domain') ?>">http://<?= Configure::read('domain') ?></a>
</p>
