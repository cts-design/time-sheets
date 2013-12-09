<style>
nav 
{
	margin-bottom:0px;
}
.forms > thead > tr > th
{
	background-color:#DDD;
	border:1px solid #AAA;
	border-top:none;

	font-size:12pt;
}
.navbar-brand
{
	padding-left:0px;
}
.action-menu
{
	font-size:11pt;
}
</style>
<div class="container" style="margin-top:5px">
	<div class="row">
		<nav class="navbar navbar-inverse navbar-static-top col-sm-10 col-sm-offset-1" style="margin-bottom:0px;">
			<div class="navbar-header">
				<a class="navbar-brand" href="">
					<?= $title_for_layout ?>
				</a>
			</div>

			<form class="navbar-form navbar-left">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search Keywords" />
					<input type="hidden" name="type" value="<?= (isset($this->params['url']['type']) ? $this->params['url']['type'] : "") ?>" />
				</div>
				<input type="button" value="Search" class="btn btn-default" />
			</form>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="" class="dropdown-toggle" data-toggle="dropdown">
						Select form type <b class="caret"></b>
					</a>

					<ul class="dropdown-menu">
						<?php foreach($form_types as $type): ?>
						<li>
							<a href="/admin/form?type=<?= $type['Form']['type'] ?>">
								<?= Inflector::humanize( $type['Form']['type']); ?>
							</a>
						</li>
						<?php endforeach ?>
					</ul>
				</li>
			</ul>
		</nav>
	</div>

	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach" style="padding:0px">
			<table class="table table-bordered forms">
				<thead>
					<tr>
						<th width="20%">
							<?= $this->Paginator->sort('Title', 'name') ?><b class="caret"></b>
						</th>
						<th width="50%">
							Description
						</th>
						<th width="20%">
							Type
						</th>
						<th width="10%">
							Pages
						</th>
						<th>
							Actions
						</th>
					</tr>
				</thead>
				<?php foreach($forms as $form): ?>
					<tr>
						<td>
							<?= $form['Form']['name'] ?>
						</td>
						<td>
							<?= nl2br($form['Form']['description']) ?>
						</td>
						<td>
							<?= $form['Form']['type'] ?>
						</td>
						<td>
							<?= count($form['Page']) ?>
						</td>
						<td>
							<div class="dropdown" style="text-align:center">
								<a href="#" class="dropdown-toggle btn btn-info btn-xs" data-toggle="dropdown">
									Action<span class="caret"></span>
								</a>
								<ul class="dropdown-menu pull-right action-menu" role="menu" aria-labelledby="action-dropdown">
									<li>
										<a href="/admin/page/form/<?= $form['Form']['id'] ?>">
											Edit
										</a>
									</li>
								</ul>
							</div>
						</td>
					</tr>
				<?php endforeach ?>
			</table>
		</div>
	</div>
</div>