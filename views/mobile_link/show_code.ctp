<style>
.centered {
	text-align: center;
}
.access_code {
	font-size:22pt !important;
	font-family:Verdana !important;
}
p {
	font-family:Verdana !important;
}
</style>
<div id="show_code" class="centered">

	<p>
		After following the link on your phone, enter this four digit passcode to gain access.		
	</p>

	<p class="access_code">
		<?= $link['MobileLink']['passcode'] ?>
	</p>

</div>