<style>
#krampus
{
	margin-top:5px;
}
#krampus .bar 
{
	margin-bottom:0px;
	background-color:#DDD;
	border:none;
}
#krampus .bar > .navbar-header > .navbar-brand
{
	color:#666;
}
#krampus .bar > .navbar-header > .navbar-brand:hover
{
	color:#444;
}
#krampus .bleach
{
	background-color:#FFF;
}
#krampus .no-padding
{
	padding-left:0px;
	padding-right:0px;
}
#krampus .results
{
	margin-bottom:0px;
}
#krampus .results > thead > tr > th
{
	background-color:#EFEFEF;
	border-bottom:1px solid #BBB;
}
</style>
<div class="container" id="krampus">
	<div class="row">
		<nav class="nav navbar navbar-default navbar-static-top col-sm-10 col-sm-offset-1 bar">
			<div class="navbar-header">
				<a class="navbar-brand" href="" style="margin:0px">
					Search
				</a>
			</div>
			
			<form class="navbar-form navbar-left" role="search">
				<div class="form-group navbar-left">
					<input type="text" name="name" class="form-control" placeholder="Enter keywords" value="<?= isset($_GET['name']) ? $_GET['name'] : "" ?>" />
				</div>

				<?php foreach($filters as $filter_name => $distincts): ?>
					<div class="form-group">
						<label for="<?= $filter_name ?>" style="margin-left:20px">Type: </label>
						<select name="<?= $filter_name ?>" class="form-control">
							<option value=""></option>
							<?php foreach($distincts as $dist): ?>
								<option value="<?= $dist[$model][$filter_name] ?>" <?= isset($_GET[$filter_name]) && $_GET[$filter_name] == $dist[$model][$filter_name] ? "selected=\"selected\"" : "" ?>>
									<?= Inflector::humanize($dist[$model][$filter_name]) ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
				<?php endforeach ?>

				<button type="submit" class="btn btn-default">Search</button>
			</form>
		</nav>
	</div>

	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach no-padding">

			<table class="table table-bordered results">
				<thead>
					<tr>
						<?php foreach($fields as $field):  ?>
							<th>
								<?= Inflector::humanize($field) ?>
							</th>
						<?php endforeach ?>
						<th>
							Actions
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($rows as $row):  ?>
					<tr>
						<?php foreach($fields as $field): ?>
							<td>
								<?= $row[$model][$field] ?>
							</td>
						<?php endforeach ?>
							<td width="60px">
								<a href="<?= $html->url('/admin/programs/add_alt_media/' . $row[$model]['id'], true) ?>" class="btn btn-default">Add Alternate Media</a>
							</td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>

		</div>
	</div>

	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach">
			<ul class="pager">
				<li class="previous">
					<?php echo $this->Paginator->prev('<< Previous') ?>
				</li>
				<li>
					<?= $this->Paginator->counter() ?>
				</li>
				<li class="next">
					<?php echo $this->Paginator->next('Next >>') ?>
				</li>
			</ul>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){

	var krampus_links = $("#krampus a");
	var search_submit = $("button[type=submit]");

	var toggables = $('.btn-group[data-toggle=true]');
	var tog_buttons = toggables.find('> button');
	tog_buttons.click(function(){
		tog_buttons.removeClass('btn-primary');
		tog_buttons.addClass('btn-default');

		$(this).addClass('btn-primary');	
	});
});
</script>