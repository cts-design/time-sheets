<style>
table
{
	width:100%;
	font-family:Arial;
}
table th
{
	font-weight:bold;
	text-align: center;

	border:1px solid #BBB;
	background-color:#E5E5E5;
}
table th a
{
	display:block;
	padding-top:5px;
	padding-bottom:5px;

	width:100%;
	height:100%;
}
table td
{
	padding:10px;
	border:1px solid #CCC;
}
.pager
{
	width:100%;
	text-align: center;
	margin-top:10px;
}
.pager li
{
	width:40%;
	display:inline-block;
}
</style>
<h1>List of Events</h1>
<table style="width:100%">
	<thead>
		<tr>
			<th>
				<?= $this->Paginator->sort('Event Title', 'name') ?>
			</th>
			<th>
				Description
			</th>
			<th>
				<?= $this->Paginator->sort('Seats Available', 'seats_available') ?>
			</th>
			<th>
				<?= $this->Paginator->sort('Date', 'scheduled') ?>
			</th>
			<th>
				View Registrations
			</th>
		</tr>
	</thead>
<tbody>
<?php foreach($events as $event): ?>
<tr>
	<td>
		<?= $event['Event']['name'] ?>
	</td>
	<td>
		<?= $event['Event']['description'] ?>
	</td>
	<td>
		<?= $event['Event']['seats_available'] ?>
	</td>
	<td>
		<?= $event['Event']['scheduled'] ?>
	</td>
	<td>
		<a href="/admin/event_registrations/index/<?= $event['Event']['id'] ?>">
			View
		</a>
	</td>
</tr>
<?php endforeach ?>
</tbody>
</table>
<ul class="pager">
	<li>
		&larr;<?= $this->Paginator->prev('Previous', array(), null, array()) ?>
	</li>
	<li>
		<?= $this->Paginator->next('Next', array(), null, array()) ?>&rarr;
	</li>
</ul>
