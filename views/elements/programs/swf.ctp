<?php echo $this->Html->script('swfobject', array('inline' => false)) ?>
<noscript>
	<?php __('You must have Javascript enabled to view this video.') ?>
</noscript>

<script type="text/javascript">
  swfobject.registerObject("media", "9", "/swf/expressinstall.swf");
</script>

<object id="media" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="420">
	<param name="movie" value="<?= $media ?>" />
	<!--[if !IE]>-->
	<object type="application/x-shockwave-flash" data="<?= $media ?>" width="550" height="420">
	<!--<![endif]-->
	<p>Alternative content</p>
	<!--[if !IE]>-->
	</object>
	<!--<![endif]-->
</object>
