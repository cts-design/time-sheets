<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<?php if(empty ($selfSignLogs)) echo '<p>There are no records at this time.</p><br />' ?>
    <?php foreach ($locationNames as $key => $value) { ?>
	<h1><?php echo $value ?></h1>
	<table cellpadding="1" cellspacing="1">
	    <thead class="ui-widget-header">
		<tr>
		    <th class="ui-state-default" width="6%"><?php echo __('Status', true) ?></th>
		    <th class="ui-state-default" width="22%"><?php echo __('Visitor', true) ?></th>
		    <th class="ui-state-default" width="35%"><?php echo __('Service', true) ?></th>
		    <th class="ui-state-default" width="20%"><?php echo __('Last Activity Admin', true) ?></th>
		    <th class="ui-state-default" width="19%"><?php echo __('Date', true); ?></th>
		</tr>
	    </thead>
	    <tbody>

	<?php 
	    foreach ($selfSignLogs as $selfSignLog){
		$class = null;
		if ($selfSignLog['SelfSignLog']['status'] == 0) {
		    $class = ' class="open"';
		} elseif ($selfSignLog['SelfSignLog']['status'] == 1) {
		    $class = ' class="closed"';
		}
		?>
		<?php if ($selfSignLog['Location']['name'] == $value) { ?>
	    	<tr<?php echo $class; ?>>
	    	    <td>
			    <?php
			    if ($selfSignLog['SelfSignLog']['status'] == 0) {
				echo $this->Html->link($statuses[$selfSignLog['SelfSignLog']['status']], Configure::read('Admin.URL') . '/self_sign_logs/toggle_status/' . $selfSignLog['SelfSignLog']['id'] .'/' . 1, array('class' => 'toggle'));
			    } elseif ($selfSignLog['SelfSignLog']['status'] == 1) {
				echo $this->Html->link($statuses[$selfSignLog['SelfSignLog']['status']],Configure::read('Admin.URL') . '/self_sign_logs/toggle_status/' . $selfSignLog['SelfSignLog']['id'] .'/' . 0, array('class' => 'toggle'));
			    }
			    ?>&nbsp;
	    	    </td>
	    	    <td><?php echo strtoupper($selfSignLog['User']['lastname'] . ', ' . $selfSignLog['User']['firstname']) ?></td>
	    	    <td>
			    <?php
			    echo $buttonNames[$selfSignLog['SelfSignLog']['level_1']];
			    if (!empty($selfSignLog['SelfSignLog']['level_2'])) {
				echo ' - ' . $buttonNames[$selfSignLog['SelfSignLog']['level_2']];
			    }
			    if (!empty($selfSignLog['SelfSignLog']['level_3'])) {
				echo ' - ' . $buttonNames[$selfSignLog['SelfSignLog']['level_3']];
			    }
			    if (!empty($selfSignLog['SelfSignLog']['other'])) {
				echo ' - ' . $selfSignLog['SelfSignLog']['other'];
			    }
			    ?>
	    	    </td>
		    <td><?php echo trim($selfSignLog['Admin']['lastname'] . ', ' . $selfSignLog['Admin']['firstname'], ', '); ?>&nbsp;</td>
	    	    <td><?php echo $selfSignLog['SelfSignLog']['created']; ?>&nbsp;</td>
	    	</tr>
		<?php } ?>
	    <?php } ?>
        </tbody>
    </table>
    <br />
<?php } ?>
