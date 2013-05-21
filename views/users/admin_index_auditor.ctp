<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div id="crumbWrapper">
	<span><?php __('You are here') ?> > </span>
	<?php echo $crumb->getHtml(__('Customers', true));?>
</div>

<div class="">
	<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
	<div class="actions ui-widget-header">
		<ul>
			<li></li>
		</ul>
	</div>
	<div id="searchForm"></div>
<br />
<div class="admin">
	<table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
		<tr>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort('username'); ?></th>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort('audit'); ?></th>
		<th width="10%" class="actions ui-state-default"><?php __('Actions'); ?></th>
		</tr>
	</thead>
<?php
$i = 0;
foreach ($auditors as $auditor):
	$class = null;
if ($i++ % 2 == 0) {
	$class = ' class="altrow"';
}
?>
		<tr<?php echo $class; ?>>
			<td><?php echo $auditor['User']['firstname']; ?>&nbsp;</td>
			<td><?php echo $auditor['Audit'][0]['name'] ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('Activity', true), array('controller' => 'user_transactions',  'action' => 'index', $auditor['User']['id']), array('class' => 'activity')); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<p class="paging-counter">
<?php
$options = array();

if (isset($basic_search_term) && $basic_search_term) {
	$options['url']['basic_search_term'] = $basic_search_term;
}

if (isset($search_by1) && $search_by1) {
	$options['url']['search_by1'] = $search_by1;
	$options['url']['search_scope1'] = $search_scope1;
	$options['url']['search_term1'] = $search_term1;
}

if (isset($search_by2) && $search_by2) {
	$options['url']['search_by2'] = $search_by2;
	$options['url']['search_scope2'] = $search_scope2;
	$options['url']['search_term2'] = $search_term2;
}

$this->Paginator->options($options);

echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?>
	</p>
	<br />
	<div class="paging">
	<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
	 | 	<?php echo $this->Paginator->numbers(); ?>
	|
	<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>

	</div>
</div>
</div>
