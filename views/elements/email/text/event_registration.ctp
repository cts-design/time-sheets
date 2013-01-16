Hello, <?= ucfirst($user['User']['firstname']) ?>\r\n
\r\n
You've registered to attend <?= $event['Event']['name'] ?> on <?= date('l, F dS', strtotime($event['Event']['scheduled'])) ?> at <?= date('h:iA', strtotime($event['Event']['scheduled'])) ?>\r\n
\r\n
You can cancel at anytime by visiting http://<?= Configure::read('domain')?>/events/cancel/<?= $event['Event']['id'] ?>\r\n
\r\n
<?= Configure::read('Company.name') ?>\r\n
http://<?= Configure::read('domain') ?>
