<?php echo $this->Html->script('flowplayer-3.2.6.min', array('inline' => false)) ?>
<noscript>
	You must have Javascript enabled to view this video. 
</noscript>
<div id="player" style="width:425px;height:300px;"></div>
<script>
flowplayer("player", "/swf/flowplayer-3.2.7.swf", {
	clip: {
		url: '<?php echo $media ?>',
		autoBuffering: true,
		autoPlay: false
	}
});
</script>