<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div id="crumbWrapper">
    <span>You are here > </span>
<?php
if (strpos($action, 'add' === false)) {
    echo "<?php echo \$crumb->getHtml('Edit ".$singularHumanName."', null, 'unique'); ?>";
} else {
    echo "<?php echo \$crumb->getHtml('Add ".$singularHumanName."', null, 'unique'); ?>";
}
?>
</div>
<div class="<?php echo $pluralVar;?> form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo "<?php echo \$this->Form->create('{$modelClass}');?>\n";?>
	<fieldset>
 		<legend><?php printf("<?php __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></legend>
<?php
		echo "\t<?php\n";
		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field == $primaryKey) {
				continue;
			} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
				echo "\t\techo \$this->Form->input('{$field}', array(
							'before' => '<p class=\"left\">',
							'between' => '</p><p class=\"left\">',
							'after' => '</p>'));\n\t\techo '<br class=\"clear\" />';\n";
			}
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\techo \$this->Form->input('{$assocName}',array(
							'before' => '<p class=\"left\">',
							'between' => '</p><p class=\"left\">',
							'after' => '</p>'));\n\t\techo '<br class=\"clear\" />';\n";
			}
		}
		echo "\t?>\n";
?>
	</fieldset>
<?php
	echo "<?php echo \$this->Form->end(__('Submit', true));?>\n";
?>
</div>
