<?php echo $this->Html->css('/js/ext/resources/css/BoxSelect', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/BoxSelect', array('inline' => false)); ?>
<script type="text/javascript">
	var adminId = <?php echo $this->Session->read('Auth.User.id') ?>;
</script>
<?php echo $this->Html->script('queued_documents/admin_index', array('inline' => false)); ?>