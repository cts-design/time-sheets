<style>
h3.heading
{
	font-size:16pt;
	margin:0px;padding:0px;
}
.page
{
	display:inline-block;
	width:170px;
	height:230px;
	background-color:#FFF;
	border:1px solid #CCC;

	padding:10px;
	margin:5px;

	position:relative;
}
.page .title
{
	font-size:11pt;
	margin:0px;padding:0px;
}
.page .page-body
{
	font-size:9pt;
}
.page .actions
{
	position:absolute;
	bottom:5px;
	width:100%;
}
</style>
<div class="container" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-4 col-sm-offset-1 bleach pad">

			<h3 class="heading">
				Form basic options
			</h3>

			<?= $this->Form->create('Form') ?>

			<?= $this->Form->end() ?>

		</div>
		<div class="col-sm-6 bleach pad">
			<h3 class="heading">
				Program Pages
			</h3>
			<?php foreach($pages as $page): ?>
				<div class="page">

					<p class="title">Howdy There</p>

					<div class="page-body">
						<p>
							We are here
						</p>
					</div>

					<div class="actions">
						<a href="/admin/page/edit" class="btn btn-info btn-sm">
							Edit
						</a>
						<a href="/admin/page/delete" class="btn btn-danger btn-sm">
							Delete
						</a>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</div>