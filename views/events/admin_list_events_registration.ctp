<style>
table
{
	width:100%;
	font-family:Arial;
}
table th
{
	font-weight:bold;
	text-align: center;

	border:1px solid #BBB;
	background-color:#E5E5E5;
}
table th a
{
	display:block;
	padding-top:5px;
	padding-bottom:5px;

	width:100%;
	height:100%;
}
table td
{
	padding:10px;
	border:1px solid #CCC;
}
.pager
{
	width:100%;
	text-align: center;
	margin-top:10px;
}
.pager li
{
	width:40%;
	display:inline-block;
}
.searchbar
{
	width:100%;
	background-color:#E5E5E5;
	border:1px solid #CCC;
	border-bottom:0px;
	
	padding:5px 10px;
}
.searchbar input[name=keywords],
.searchbar input[name=date]
{
	font-size:11pt;
	padding:2px 5px;
}
.searchbar a
{
	padding:5px 5px;
	margin-right:5px;
}
.date-light
{
	color:#FFF;
	text-decoration:none;
	background-color:#1875BB;
}
#datepicker
{
	width:0px;height:0px;
	border:0px;
	margin-top:-5px;
}
.ui-datepicker-trigger
{
	width:27px;
	height:auto;
	vertical-align:middle;
}
#date
{
	min-width:40px;
	max-width:90px;
	display:inline-block;
	vertical-align:middle;
	border:0px;
	margin-top:-1px;
	padding-top:3px;
	padding-bottom:3px;
}
.form-search
{
	float:right;
}
</style>
<?php
$post = $session->read('ler');
?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('List of Events', true), null, 'unique') ; ?>
</div>
<h1>List of Events</h1>

<div class="searchbar">
	<?= $this->Form->create(array('controller' => 'events', 'action' => 'list_events_registration')) ?>
	<label>Search: <input type="text" name="keywords" value="<?= (isset($post['keywords']) ? $post['keywords'] : "") ?>"/></label>
	
	<a href="#" id="today" style="margin-left:15px" class="<?= ( isset($post['today']) ? "date-light" : "" ) ?>">Today</a>
	<a href="#" id="tomorrow" class="<?= ( isset($post['tomorrow']) ? "date-light" : "" ) ?>">Tomorrow</a>
	
	<input type="checkbox" style="display:none" name="today" <?= ( isset($post['today']) ? "checked" : "" ) ?> />
	<input type="checkbox" style="display:none" name="tomorrow" <?= ( isset($post['tomorrow']) ? "checked" : "" ) ?> />
	
	<input type="text" id="datepicker" name="datepicker" value="<?= ($post['datepicker'] != "" ? $post['datepicker'] : "") ?>" />
	
	<input id="date" type="text" name="date" readonly value="<?= ($post['datepicker'] != "" ? $post['datepicker'] : "") ?>"
	class="<?= ($post['datepicker'] != "" ? "date-light" : "") ?>"
	/>
	
	<input type="submit" value="Search" class="form-search" />
	<?= $this->Form->end() ?>
</div>

<table style="width:100%">
	<thead>
		<tr>
			<th>
				<?= $this->Paginator->sort('Event Title', 'name') ?>
			</th>
			<th>
				Description
			</th>
			<th style="width:100px">
				<?= $this->Paginator->sort('Seats Available', 'seats_available') ?>
			</th>
			<th style="width:140px">
				<?= $this->Paginator->sort('Date', 'scheduled') ?>
			</th>
			<th style="width:125px">
				View Registrations
			</th>
		</tr>
	</thead>
<tbody>

<?php foreach($events as $event): ?>
<tr>
	<td>
		<?= $event['Event']['name'] ?>
	</td>
	<td>
		<?php
			echo substr($event['Event']['description'], 0, 35);
		?>
	</td>
	<td style="text-align:center">
		<?= $event['Event']['seats_available'] - count($event['EventRegistration']) . '/' . $event['Event']['seats_available'] ?> Seats Available
	</td>
	<td style="text-align:center">
		<?= date('n/d/Y g:i A', strtotime($event['Event']['scheduled'])) ?>
	</td>
	<td style="text-align:center">
		<a href="/admin/event_registrations/index/<?= $event['Event']['id'] ?>">
			View
		</a>
	</td>
</tr>
<?php endforeach ?>
</tbody>
</table>

<?php if( empty($events) ): ?>
	<div style="width:100%;text-align:center">
		<p style="padding:10px">No events were found from your search</p>
	</div>
<?php endif ?>

<ul class="pager">
	<li>
		&larr;<?= $this->Paginator->prev('Previous', array(), null, array()) ?>
	</li>
	<li>
		<?= $this->Paginator->next('Next', array(), null, array()) ?>&rarr;
	</li>
</ul>

<script type="text/javascript">
$(function() {
	$("#datepicker").datepicker({
		showOn : 'button',
		buttonImage : "/img/date.png",
		buttonImageOnly : true,
		onSelect : unselect
	});
	
	var today = $("#today");
	var tomorrow = $("#tomorrow");
	
	var chkToday = $("input[name=today]");
	var chkTomorrow = $("input[name=tomorrow]");
	
	today.click(function(e){
		
		e.preventDefault();
		if(chkToday.prop('checked') != true)
		{
			chkToday.prop('checked', true);
			chkTomorrow.prop('checked', false);
		
			tomorrow.removeClass('date-light');
			today.addClass('date-light');
		
			$("#date").removeClass('date-light');
			$("#date").val("");
			$("#datepicker").val("");
		}
		else
		{
			chkToday.prop('checked', false);
			today.removeClass('date-light');
		}
	});
	
	tomorrow.click(function(e){
		e.preventDefault();
		
		if(chkTomorrow.prop('checked') != true)
		{
			chkTomorrow.prop('checked', true);
			chkToday.prop('checked', false);
		
			today.removeClass('date-light');
			tomorrow.addClass('date-light');
		
			$("#date").removeClass('date-light');
			$("#date").val("");
			$("#datepicker").val("");
		}
		else
		{
			chkTomorrow.prop('checked', false);
			tomorrow.removeClass('date-light');
		}
	});
	
	function unselect(date)
	{
		chkToday.prop('checked', false);
		chkTomorrow.prop('checked', false);
		
		today.removeClass('date-light');
		tomorrow.removeClass('date-light');
		
		$("#date").val(date);
		$("#date").addClass('date-light');
	}
	
});
</script>
