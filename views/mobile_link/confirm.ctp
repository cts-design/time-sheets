<style>
.passcode {
	font-size:22pt;
	padding:5px;
	text-align: center;

	display:block;
	margin:0px auto;

	width:300px;
	max-width:auto;
}
.submit {
	width:300px;
	max-width: auto;

	margin-top:20px;
}
</style>
<div id="confirm" class="centered">

	<p class="error" style="margin-bottom:10px">
		<?= $this->Session->flash('flash_error') ?>
	</p>
	<?= $this->Form->create(array('url' => '/mobile_link/confirm/' . $this->params['pass'][0])) ?>

		<input type="text" name="passcode" value="<?= $passcode ?>" class="passcode" maxlength="4" placeholder="Enter Code" autocomplete="off" />

		<button type="submit" class="submit slate" disabled>Submit</button>

	<?= $this->Form->end() ?>

</div>
<script>
$(document).ready(function(){
	var passcode = $('.passcode');
	var submit = $('.submit');

	checkInput();

	passcode.keyup(checkInput);

	function checkInput() {
		var pass = passcode.val();

		if(pass.length == 4)
		{
			submit.removeClass('slate');
			submit.addClass('sky');
			submit.removeAttr('disabled');
		}
		else
		{
			submit.removeClass('sky');
			submit.addClass('slate');
			submit.attr('disabled', 'disabled');
		}
	}
});
</script>