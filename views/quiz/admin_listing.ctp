<style>
.navigation {
	height:40px;
	background-color:#DDD;
	border:1px solid #CCC;

	display:block;
	width:100%;

	vertical-align: middle;
}
.navigation > select {
	width:150px;
	line-height:40px;
	vertical-align: middle;
}
.navigation input[type=text] {
	height:26px;
	font-size:14pt;
}
.navigation select {
	height:26px;
	border-radius:0px;
	border:1px solid #ccc;
	position:relative;
	top:-2px;
}
.navigation > form {
	line-height:40px;
	height:40px;
}

table {
	width:100%;
}
table th {
	font-weight:bold;
	font-size:12pt;
	border:1px solid #CCC;

	padding:7px 5px;
}
table td {
	border:1px solid #CCC;

	padding:10px 5px;
}
</style>

<div class="navigation">
	<form method="GET" action="/admin/quiz/listing">
		<select name="category">
			<?php foreach($quiz_categories as $qc): ?>
				<option value="<?= $qc['QuizCategory']['id'] ?>"  <?= $this->Request->get('category', '') == $qc['QuizCategory']['name'] ? 'selected' : '' ?>>
					<?= Inflector::humanize($qc['QuizCategory']['name']) ?>
				<option>
			<?php endforeach ?>
		</select>

		<input type="text" name="name" />
		<input type="submit" value="Search" />
	</form>
</div>

<table>
	<thead>
		<tr>
			<th>
				Name
			</th>
			<th>
				Type
			</th>
			<th>
				Description
			</th>
			<th>
				Pages
			</th>
		</tr>
	</thead>
	<?php foreach($quizzes as $quiz): ?>
		<tr>
			<td>
				<?= $quiz['Quiz']['name'] ?>
			</td>
			<td>
				<?= Inflector::humanize($quiz['QuizCategory']['name']) ?>
			</td>
			<td>
				<?= $quiz['Quiz']['description'] ?>
			</td>
			<td>
				<?= count($quiz['QuizPage']) ?>
			</td>
		</tr>
	<?php endforeach ?>
</table>





