<style>
.panel-heading h4 {
	margin:0px;
	padding:0px;
}
.panel-body {
	padding:0px 0px 20px 10px;
	font-size:12pt;
}
.ui-icon {
	display: inline-block;
}
.navbar {
	margin-bottom:10px;
	padding:0px 10px;
}
</style>
<script src="/js/jquery.jstree.js"></script>
<script src="/js/jquery.cookie.js"></script>
<script src="/js/kiosk_buttons/buttons.tree.js"></script>

<div class="container" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 no-pad bleach">

			<nav class="navbar navbar-default navbar-static-top">
				<a href class="btn btn-default navbar-btn enable-button" disabled>
					Enable Button
				</a>
				<a href class="btn btn-danger navbar-btn disable-button navbar-right" disabled>
					Disable Kiosk Button
				</a>
				<a data-toggle="modal" data-target="#edit-button-modal" style="margin-right:5px" href class="btn btn-default navbar-btn edit-button navbar-right" disabled>
					View/Edit Kiosk Button
				</a>

			</nav>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach" style="padding-bottom:10px">
			<?= $this->Session->flash() ?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach">

			<div class="row">
				<div class="col-sm-6" style="padding-right:10px">

					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4>
								Current Master Buttons
							</h4>
						</div>
						<div class="panel-body">
							<div id="masterKioskButtonTree">
								<?= $tree->generate($masterButtons, array(
									'element' => 'master_kiosk_buttons/master_kiosk_button_tree'
									))
								?>
							</div>
						</div>
					</div>

				</div>
				<div class="col-sm-6" style="padding-left:10px">

					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4>
								Kiosk Buttons
							</h4>
						</div>
						<div class="panel-body">
							<?php if(!empty($locationButtons)): ?>
								<div id="kioskButtonTree">
									<?= $tree->generate($locationButtons, array(
											'element' => 'kiosk_buttons/kiosk_button_tree'
										));
									?>
								</div>
							<?php else: ?>
								<p style="color:#ccc;text-align:center;margin-top:10px">There are no buttons, please add some.</p>
							<?php endif ?>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>

<div class="modal fade" id="edit-button-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					Edit Button
				</h4>
			</div>
			<div class="modal-body" style="overflow:hidden">

				<?= $this->Form->create('KioskButton', array('action' => 'add_action')) ?>
				<?= $this->Form->input('action', array(
					'options' => array(
						'' => 'Choose an Action',
						'link' => 'Link',
						'logout' => 'Logout'
					),
					'class' => 'form-control'
				)) ?>
				<br />
				<?= $this->Form->input('action_meta', array('label' => '', 'type' => 'textarea', 'class' => 'form-control')) ?>
				<?= $this->Form->input('button_id', array('type' => 'hidden')) ?>
				<input type="submit" class="btn btn-primary pull-right" style="margin-top:5px"/>
				<?= $this->Form->end() ?>

			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	var $select = $('select[name="data[KioskButton][action]"]');

	var textAreaLabel = $('label[for=KioskButtonActionMeta]');
	$select.change(function(){
		var value =$(this).val();

		if(value == 'link')
		{
			textAreaLabel.text('Enter valid link');
		}
		else if(value == 'logout')
		{
			textAreaLabel.text('Enter a Logout Message');
		}
	});
});
</script>




