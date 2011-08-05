<?php echo $this->Html->script('jquery.resizable-text', array('inline' => false)) ?>
<?php echo $this->Html->css('jquery.resizable-text', 'stylesheet', array('inline' => false)) ?>
<script type="text/javascript">
	$(function() {
		$('#content').resizableText();
	});
</script>
<?php echo $content; ?>