<?= $this->Html->script('flowplayer-3.2.6.min') ?>
<?= $this->Html->script('jquery1102.min') ?>
<?= $this->Html->script('swfobject', array('inline' => false)) ?>

<?php if ($module['EcourseModule']['media_type'] == 'pdf'): ?>
	<div id="EcoursePdf">
		<object type="application/pdf" data="/ecourses/load_media/<?= $module['EcourseModule']['media_location'] ?>#navpanes=0" width="950" height="800">
		</object>
	</div>
<?php elseif ($module['EcourseModule']['media_type'] == 'flv'): ?>
	<noscript><?php __('You must have Javascript enabled to view this video.') ?></noscript>
	<div id="player" style="width:600px;height:400px;"></div>

	<script>
		$(document).ready(function() {
			flowplayer("player", "/swf/flowplayer-3.2.7.swf", {
				clip: {
					url: '<?= "/ecourses/load_media/{$module['EcourseModule']['media_location']}" ?>',
					autoBuffering: true,
					autoPlay: false
				}
			});
		});
	</script>
<?php elseif ($module['EcourseModule']['media_type'] == 'swf'): ?>
	<noscript><?php __('You must have Javascript enabled to view this video.') ?></noscript>
	<script type="text/javascript">
	  swfobject.registerObject("media", "9", "/swf/expressinstall.swf");
	</script>

	<object id="media" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="420">
		<param name="movie" value="/ecourses/load_media/<?= $module['EcourseModule']['media_location'] ?>" />
		<!--[if !IE]>-->
		<object type="application/x-shockwave-flash" data="/ecourses/load_media/<?= $module['EcourseModule']['media_location'] ?>" width="550" height="420">
		<!--<![endif]-->
		<p>You need flash to view this media</p>
		<!--[if !IE]>-->
		</object>
		<!--<![endif]-->
	</object>
<?php elseif ($module['EcourseModule']['media_type'] == 'ppt'): ?>
	<script type="text/javascript" src='https://www.scribd.com/javascripts/scribd_api.js'></script>

	<div id='embedded_doc' ><a href='http://www.scribd.com'>Scribd</a></div>

	<script type="text/javascript">
		var url = "<?= Configure::read('domain') ?>/ecourses/load_media/<?= $module['EcourseModule']['media_location'] ?>";
		var pub_id = 'pub-76946934246478514942';
		var scribd_doc = scribd.Document.getDocFromUrl(url, pub_id);

		var onDocReady = function(e){
			scribd_doc.api.setPage(1);
		}

		scribd_doc.addEventListener('docReady', onDocReady);
		scribd_doc.addParam('default_embed_format', 'flash');
		scribd_doc.addParam('jsapi_version', 2);
		scribd_doc.addParam('public', true);
		scribd_doc.addParam('title', '<?= $module['EcourseModule']['media_location'] ?>');
		scribd_doc.addParam('my_user_id', 'tbwa');
		scribd_doc.addParam('mode', 'slideshow');
		scribd_doc.addParam('use_ssl', true);
		scribd_doc.addParam('allow_share', false);

		scribd_doc.write('embedded_doc');
	</script>
<?php elseif ($module['EcourseModule']['media_type'] == 'url'): ?>
	<div id="EcoursePdf">
		<a href="<?= $module['EcourseModule']['media_location'] ?>" target="_blank">View URL in a new tab</a>
	</div>
<?php endif ?>
