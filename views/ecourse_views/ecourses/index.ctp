<?php $this->Html->scriptStart(array('inline' => false)); ?>
  $(function() {
	  $(".button" ).button();
  });
<?php $this->Html->scriptEnd(); ?>
<div id="instructions"><?php echo $instructions ?></div>
<div id="EcoursePdf">
	<object type="application/pdf" data="<?=$media?>#navpanes=0" width="950" height="800">
    </object>
</div>
<br />
<div> <a class="button" href="/ecourses/quiz/<?php echo $this->params['pass'][0]?>">Proceed to quiz.</a></div>
