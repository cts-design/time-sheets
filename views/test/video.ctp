<?php echo $this->Html->script('flowplayer-3.2.6.min', array('inline' => false)) ?>
<noscript>
	<?php __('You must have Javascript enabled to view this video.') ?>
</noscript>
<div id="player" style="width:600px;height:400px;"></div>
<script>
<?php if (empty($this->validationErrors)) : ?>
	$(document).ready(function(){
	$('#Acknowledge').hide();
	})
<?php endif ?>
flowplayer("player", "/swf/flowplayer-3.2.7.swf", {
	clip: {
		url: '<?php echo $media ?>',
		autoBuffering: true,
		autoPlay: false,
		onFinish: function() {
			$('#Acknowledge').show();
		}
	}
});
</script>
