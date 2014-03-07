<?php echo $this->Form->create('Quiz', array('url' => '/admin/quiz/create')) ?>


<style>
label {
	display:block;

	margin-top:5px;
}
label > p {
	color:#333;
	font-size:14pt;
}
label > textarea {
	width:300px;
	height:100px;

	margin-left:5px;
}
label > input[type=text] {
	line-height:25px;
	border:1px solid #777;
	font-size:14pt;
	padding:0px 5px;

	margin-left:5px;
	width:250px;
}

label > .select-container {
	width:250px;
	margin-left:5px;
}
label select {
	width:250px;
	height:30px;
}
label > input[type=checkbox] {
	margin-left:5px;
	margin-top:5px;
}

.checkbox {
	margin:10px 0px;
}

</style>
	<label for="name">
		<p>Name:</p>
		<input type="text" name="data[Quiz][name]" />
	</label>
	<label for="type">
		<p>Type:</p>
		<div class="select-container">
			<select name="data[Quiz][quiz_category_id]">
				<?php foreach($quiz_categories as $qc): ?>
					<option value="<?= $qc['QuizCategory']['id'] ?>">
						<?= Inflector::humanize($qc['QuizCategory']['name']) ?>
					</option>
				<?php endforeach ?>
			</select>
		</div>
	</label>
	<label for="description">
		<p>Description:</p>
		<textarea name="data[Quiz][description]"></textarea>
	</label>
	<label for="esign_required" class="checkbox">
		<p>E-Signature Required</p>
		<input type="checkbox" name="data[Quiz][esign_required]" /> Require E-Signature from user before granting access
	</label>

	<input type="submit" value="Create Program" />

<?= $this->Form->end() ?>





