<?php echo $this->Html->script('filed_documents/view_all_docs', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Filed Document Archive', null, 'unique') ; ?>
</div>

<div id="allDocsSearch"></div>
<br />
<div id="allDocsGrid"></div>