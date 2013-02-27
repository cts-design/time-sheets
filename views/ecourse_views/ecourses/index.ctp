<?php $this->Html->scriptStart(array('inline' => false)); ?>
  $(function() {
	  $(".button" ).button();
  });
<?php $this->Html->scriptEnd(); ?>
<div id="instructions"><?php echo $ecourse['EcourseModule'][0]['instructions'] ?></div>

<?php if ($ecourse['EcourseModule'][0]['media_type'] == 'pdf'): ?>
	<div id="EcoursePdf">
		<object type="application/pdf" data="/ecourses/load_media/<?= $ecourse['EcourseModule'][0]['media_location'] ?>#navpanes=0" width="950" height="800">
		</object>
	</div>
	<br />
<?php elseif ($ecourse['EcourseModule'][0]['media_type'] == 'url'): ?>
	<div id="EcoursePdf">
	<a href="<?= $ecourse['EcourseModule'][0]['media_location'] ?>" target="_blank">View Media</a>
	</div>
	<br />
<?php endif ?>
<div> <a class="button" href="/ecourses/quiz/<?php echo $this->params['pass'][0]?>">Proceed to quiz.</a></div>
