<style>
label > span
{
	display:block;
}
.small-header
{
	margin:0px 0px 20px 0px;
	padding:0px;
}
</style>
<script src="/js/jquery.jstree.js"></script>
<script src="/js/jquery.cookie.js"></script>
<script src="/js/master_kiosk_buttons/buttons.tree.js"></script>

<div class="container" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 no-pad bleach">
			<nav class="navbar navbar-default navbar-static-top" role="nagivation" style="padding:0px 10px">
				<div class="container-fluid">

					<button class="btn btn-default navbar-btn">
						Create Master Button
					</button>

				</div>
			</nav>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 no-pad bleach" style="padding-bottom:10px">
			<div class="col-sm-6 no-pad bleach">

				<div id="masterKioskButtonTree">
					<?= $tree->generate($data, array('element' => 'master_kiosk_buttons/master_kiosk_button_tree')) ?>
				</div>
			</div>

			<div class="col-sm-6 bleach">
				<h4 class="small-header">
					Create New Master Button
				</h4>

				<?= $this->Form->create('MasterKioskButton', array('action' => 'add')) ?>
				<?= $this->Form->input('name', array('class' => 'form-control')) ?>
				<?= $this->Form->input('parent_id', array('type' => 'hidden', 'value' => NULL)) ?>
				<button type="submit" class="btn btn-primary btn-lg" style="margin-top:10px">
					Create
				</button>
				<?= $this->Form->end() ?>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	var parent_id_field = $('input[name="data[MasterKioskButton][parent_id]"]');

	console.log(parent_id_field);
});
</script>