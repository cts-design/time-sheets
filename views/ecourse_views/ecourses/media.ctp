<p class="bot-mar-10"><?php echo $instructions ?> </p>
<div id="EcoursePdf">
	<object type="application/pdf" data="<?=$media?>#navpanes=0" width="950" height="800">
    </object>
</div>
<br />
<div> <a href="/ecourses/quiz/<?php echo $this->params['pass'][0]?>">Proceed to quiz.</a></div>
