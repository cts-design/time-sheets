<?php echo $this->Html->css('/js/ext/resources/css/BoxSelect', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery', array('inline' => false)) ?>
<?php echo $this->Html->script('ext/ux/BoxSelect', array('inline' => false)); ?>
<script type="text/javascript">
	var adminId = <?php echo $this->Session->read('Auth.User.id'); ?>;
	var canFile = <?php echo $canFile; ?>;
	var canDelete = <?php echo $canDelete; ?>;
	var canReassign = <?php echo $canReassign; ?>;
	var canAddCustomer = <?php echo $canAddCustomer; ?>;

	// TODO: make this a database setting
	var docTimeOutDelay = <?php echo "600000"; ?>;
</script>
<?php echo $this->Html->script('queued_documents/admin_index', array('inline' => false)); ?>