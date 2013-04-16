<?php $this->Html->script('flowplayer-3.2.6.min', array('inline' => false)) ?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
  $(function() {
	  $(".button" ).button();
  });
<?php $this->Html->scriptEnd(); ?>
<div id="instructions"><?php echo $nextModule[0]['instructions'] ?></div>

<?php if ($nextModule[0]['media_type'] == 'pdf'): ?>
	<div id="EcoursePdf">
		<object type="application/pdf" data="/ecourses/load_media/<?= $nextModule[0]['media_location'] ?>#navpanes=0" width="950" height="800">
		</object>
	</div>
<?php elseif ($nextModule[0]['media_type'] == 'flv'): ?>
	<noscript><?php __('You must have Javascript enabled to view this video.') ?></noscript>
	<div id="player" style="width:600px;height:400px;"></div>
	<script>
		flowplayer("player", "/swf/flowplayer-3.2.7.swf", {
			clip: {
				url: '<?= "/ecourses/load_media/{$nextModule[0]['media_location']}" ?>',
				autoBuffering: true,
				autoPlay: false
			}
		});
	</script>
<?php elseif ($nextModule[0]['media_type'] == 'ppt'): ?>
	<?php $this->Html->script('https://www.scribd.com/javascripts/scribd_api.js', array('inline' => false)) ?>
	<?php $this->Html->scriptStart(array('inline' => false)); ?>
		var url = '/ecourses/load_media/<?= $nextModule[0]['media_location'] ?>',
		  pub_id = 'pub-76946934246478514942',
		  scribd_doc = scribd.Document.getDocFromUrl(url, pub_id),
		  onDocReady;

		onDocReady = function(e){
			scribd_doc.api.setPage(1);
		}

		scribd_doc.addEventListener('docReady', onDocReady);
		scribd_doc.addParam('jsapi_version', 2);
		scribd_doc.addParam('public', true);
		scribd_doc.addParam('mode', 'slideshow');
		scribd_doc.addParam('allow_share', false);
		scribd_doc.write('embedded_doc');
	<?php $this->Html->scriptEnd(); ?>

	<div id='embedded_doc' ><a href='http://www.scribd.com'>Scribd</a></div>
<?php elseif ($nextModule[0]['media_type'] == 'url'): ?>
	<div id="EcoursePdf">
		<a href="<?= $nextModule[0]['media_location'] ?>" target="_blank">View Media</a>
	</div>
<?php endif ?>
<br />
<div>
	<a class="button" href="/ecourses/quiz/<?php echo $nextModule[0]['id'] . '/' . $modResponseTimeId?>">Proceed to quiz.</a>
</div>