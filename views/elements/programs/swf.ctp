<?php echo $this->Html->script('swfobject', array('inline' => false)) ?>
<noscript>
	<?php __('You must have Javascript enabled to view this video.') ?>
</noscript>

<script type="text/javascript">
  swfobject.registerObject("media", "9", "/swf/expressinstall.swf");
</script>

<object id="media" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="550px">
	<param name="movie" value="<?= $media ?>" />
	<!--[if !IE]>-->
	<object type="application/x-shockwave-flash" data="<?= $media ?>" width="100%" height="550px">
	<!--<![endif]-->
	<p>You need flash to view this media</p>
	<!--[if !IE]>-->
	</object>
	<!--<![endif]-->
</object>
