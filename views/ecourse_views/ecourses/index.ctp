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
	<br />
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
	<br />
<?php elseif ($nextModule[0]['media_type'] == 'ppt'): ?>
<?php elseif ($nextModule[0]['media_type'] == 'url'): ?>
	<div id="EcoursePdf">
		<a href="<?= $nextModule[0]['media_location'] ?>" target="_blank">View Media</a>
	</div>
	<br />
<?php endif ?>
<div>
	<a class="button" href="/ecourses/quiz/<?php echo $nextModule[0]['id']?>">Proceed to quiz.</a>
</div>
