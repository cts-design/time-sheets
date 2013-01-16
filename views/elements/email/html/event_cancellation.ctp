<h2>Hello, <?= ucfirst($user['User']['firstname']) ?></h2>

<p>You've cancelled your registration to attend <?= $event['Event']['name'] ?> on <?= date('l, F dS', strtotime($event['Event']['scheduled'])) ?> at <?= date('h:iA', strtotime($event['Event']['scheduled'])) ?></p>

<p>
	<?= Configure::read('Company.name') ?><br />
	<a href="http://<?= Configure::read('domain') ?>">http://<?= Configure::read('domain') ?></a>
</p>
