<style>
#enrollment .heading {
	width:100%;
	background-color:#F0F0F0;
	color:#333;
	padding:10px 0px;

	display:table;
}
#enrollment .heading > h1 {
	display:block;
	margin:0px;
	color:#333;
}
#enrollment .body ul {
	margin:0px;
	padding:0px;
	width:100%;
}
#enrollment .body li {
	height:40px;
	background-color:#F0F0F0;
	margin:10px 0px;
}
#enrollment .body li > h2 {
	font-size:18pt;
	color:#333;
	vertical-align: middle;
	margin-left:20px;
	line-height:40px; /* Must be same height as li's height */
}
</style>

<?php print_r($program) ?>
<div id="enrollment">

	<div class="heading">
		<h1 style="float:left;padding-left:10px">
			<?= $programResponse['Program']['name'] ?>
		</h1>

		<h1 style="float:right;padding-right:10px">
			<small>Current Status:</small> 
			<?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
		</h1>
	</div>

	<div class="body">
		<ul>
			<?php foreach($program['ProgramStep'] as $step): ?>
				<li>
					<h2><?= $step['name'] ?></h2>
				</li>
			<?php endforeach ?>
		</ul>
	</div>

</div>