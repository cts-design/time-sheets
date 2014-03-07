<!--<?php echo $this->Html->script('alerts/admin_index.js', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php //echo $crumb->getHtml(__('Alerts', true), null, 'unique') ; ?>
</div>

<div id="alerts"></div>-->

<link rel="stylesheet" href="/css/alert.css" />
<div class="container container-content">
	<div class="row">
		<div class="col-sm-4 col-sm-offset-1">

			<h3>
				Create Alert
			</h3>

			<?= $this->Form->create() ?>
				<label>
					<p>Alert Name: </p>
					<input type="text" name="data[Alert][name]" />
				</label>

				<label>
					<p>Location: </p>
					<select name="data[Alert][location_id]">
						<?php foreach($locations as $location): ?>
						<option value="<?= $location['Location']['id'] ?>">
							<?= $location['Location']['name'] ?>
						</option>
						<?php endforeach ?>
					</select>
				</label>

				<input type="hidden" name="data[Alert][type]" value="self_sign" />
				<input type="hidden" name="data[Alert][watched_id]" value="2" />
				<input type="hidden" name="data[Alert][user_id]" value="2" />

				<input type="submit" value="Add" class="btn btn-primary btn-lg" />

			<?= $this->Form->end() ?>

		</div>

		<div class="col-sm-6">

			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>User</th>
						<th>Type</th>
						<th>Location</th>
						<th>User</th>
					</tr>
				</thead>
				<?php foreach($alerts as $alert): ?>
					<tr>
						<td>
							<?= $alert['Alert']['name'] ?>
						</td>
						<td>
							<?= $alert['Alert']['user_id'] ?>
						</td>
						<td>
							<?= Inflector::humanize($alert['Alert']['type']) ?>
						</td>
						<td>
							<?= $alert['Alert']['name'] ?>
						</td>
						<td>
							<?= $alert['User']['firstname'] . ' ' . $alert['User']['lastname'] ?>
						</td>
					</tr>
				<?php endforeach ?>
			</table>

			<ul class="pager">
				<li class="previous">
					<?= $this->Paginator->prev('<< Previous', null, null, array('class' => 'previous')) ?>
				</li>
				<li class="next">
					<?= $this->Paginator->next('Next >>', null, null, array('class' => 'next')) ?>
				</li>
			</ul>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){

var table = $('.table');

table.find('tr').click(function(){
	table.find('td').removeClass('table-selected');
	$(this).find('td').addClass('table-selected');
});


});
</script>