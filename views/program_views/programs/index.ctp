<?php echo $this->Html->script('flowplayer-3.2.6.min', array('inline' => false)) ?>
<div id="player" style="width:425px;height:300px;"></div>
<script>
flowplayer("player", "/swf/flowplayer-3.2.7.swf", {
	clip: {
		url: "http://pseudo01.hddn.com/vod/demo.flowplayervod/flowplayer-700.flv",
		autoBuffering: true,
		autoPlay: false,
		onFinish: function() {
			window.location = '/program_registrations/index/1'
		}
	}
});
</script>
