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
<?php elseif ($nextModule[0]['media_type'] == 'url'): ?>
	<div id="EcoursePdf">
	<a href="<?= $nextModule[0]['media_location'] ?>" target="_blank">View Media</a>
	</div>
	<br />
<?php endif ?>
<div> <a class="button" href="/ecourses/quiz/<?php echo $nextModule[0]['id']?>">Proceed to quiz.</a></div>
