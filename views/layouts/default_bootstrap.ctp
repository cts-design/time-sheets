<DOCTYPE html>
<html>
<head>
	<?= $this->Html->css('bootstrap_yeti.min') ?>
	<?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js') ?>
	<?= $this->Html->script('bootstrap.min') ?>

	<?= $this->Html->css('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/south-street/jquery-ui.css') ?>
	<?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js') ?>

	<link rel="stylesheet" href="/js/jqueryui_signature/jquery.signature.css" />

	<script type="text/javascript" src="/js/jqueryui_signature/jquery.signature.min.js"></script>

	<?= $this->Html->script('pdfobject_min.js') ?>

	<!--[if IE]> 
	<script type="text/javascript" src="js/excanvas.js"></script>
	<?= $this->Html->script('jqueryui_signature/excanvas') ?>
	<![endif]-->

	<style>
	body, html
	{
		background-color:#1875BB;
	}
	.top-nav > div > div
	{
		background-color:#FFF;
		height:80px;

		padding-top:5px;
		padding-bottom:5px;
	}
	.top-nav > div > div p
	{
		margin:0px;
	}
	.top-nav > div:last-child > div
	{
		height:30px;
	}
	.top-nav > div:last-child
	{
		margin-top:5px;
		content:"";
	}
	.content
	{
		margin-top:5px;
	}
	.content > div > div
	{
		background-color:#FFF;
		padding-top:10px;
		padding-bottom:10px;
	}
	.content > div > div h4
	{
		color:orange;
		font-weight:bold;
		margin:0px;
		padding:5px 0px 5px 0px;
	}
	</style>
</head>
<body>
	<div class="container top-nav">
		<div class="row">
			<div class="col-lg-5 col-lg-offset-1 col-md-5 col-md-offset-1 col-sm-3 col-sm-offset-1">

				<?php echo $this->Html->link($this->Html->image('/img/default/default_header_logo.jpg'),
				array('controller' => 'pages',
					'action' => 'display',
					'admin' => false, 'home'), array('escape' => false));
			    ?>

			</div>
			<div class="col-lg-5 col-md-5 col-sm-7">

				<div class="row">
					<div class="col-sm-12">
						<?php echo $this->Html->image('atlas_logo_100.jpg', array('class' => 'pull-right')) ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">

						<p class="pull-right">
						<?php
							if ($session->read('Auth.User')) {
				                printf(__('<strong>Logged in as: %s %s</strong> | ', true), $this->Session->read('Auth.User.firstname'), $this->Session->read('Auth.User.lastname'));
								echo $this->Html->link(__('Edit Profile', true), array('controller' => 'users', 'action' => 'edit', 'kiosk' => false, $this->Session->read('Auth.User.id'))) . ' | ';
								echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout', 'kiosk' => false, 'web'));
				            }
						?>
						</p>

					</div>
				</div>

			</div>
		</div>

		<div class="row"> <!-- This is the large white line -->
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">

			</div>
		</div>
	</div>

	<?= $content_for_layout ?>
</body>
</html>