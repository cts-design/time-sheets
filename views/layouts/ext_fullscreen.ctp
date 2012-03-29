<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2012
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->Html->charset(); ?>
<title>
    <?php __('ATLAS V3'); ?>
    <?php echo $title_for_layout; ?>
</title>
<script type="text/javascript">
    domain = "<?php echo Configure::read('domain')?>"
</script>
<?php
	echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));

	echo $this->Html->css('/js/ext/resources/css/ext-all');

    echo $this->Html->css('/css/admin-ext');

    echo $this->Html->script('/js/ext/bootstrap');

	echo $this->Html->script('atlas');
?>

<?php
	echo $scripts_for_layout;
?>
</head>
<body>
    <div id="content">
		<?php echo $content_for_layout; ?>
    </div>
</body>
</html>
