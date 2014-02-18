<style>
.month
{
	width:140px;

	margin:0px;
	padding:0px;
	margin-left:15px;

	line-height:25px;
	background-color:#FFF;

	vertical-align: middle;

	position: relative;
	top:-3px;
}
.day
{
	width:40px;
}
.year
{
	width:60px;
}
input[type=text]
{
	padding:3px 5px 3px 5px;
	margin:2px 0px 2px 15px;
}
label 
{
	background-color:#CCC;

	display:block;
	width:100%;
	height:100%;

	vertical-align:middle;
	text-align:right;
	line-height:25px;

	padding:0px 5px;
}
button
{
	background-color:#444;
	color:#FFF;
	border:none;
	width:100%;
	height:35px;
}
button:hover
{
	background-color:#333;
}
.error
{
	color:red;
	text-align: center;
	font-size:13pt;
	margin-top:5px;
}
table td:first-child
{
	padding:0px;
}
</style>
<table>
	<tr>
		<td colspan="2">
			Enter your child's birthday
		</td>
	</tr>
	<tr>
		<td>
			<label for="month">Month:</label>
		</td>
		<td>
			<select name="month" class="month" onchange="" size="1">
			    <option value="01">January</option>
			    <option value="02">February</option>
			    <option value="03">March</option>
			    <option value="04">April</option>
			    <option value="05">May</option>
			    <option value="06">June</option>
			    <option value="07">July</option>
			    <option value="08">August</option>
			    <option value="09">September</option>
			    <option value="10">October</option>
			    <option value="11">November</option>
			    <option value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<label for="day">Day:</label>
		</td>
		<td>
			<input type="text" name="day" class="day" maxlength="2" />
		</td>
	</tr>
	<tr>
		<td>
			<label for="day">Year:</label>
		</td>
		<td>
			<input type="text" name="year" class="year" maxlength="4" />
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button class="calc">Go</button>
		</td>
	</tr>
</table>

<br />

<p class="error"></p>
<script>
$(document).ready(function(){
	var monthNames = [ "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December" ];

	var month = $('.month');
	var day = $('.day');
	var year = $('.year');

	var errorOutput = $('.error');

	var vpk2013_start = new Date(2008, 8, 2);
	var vpk2013_end = new Date(2009, 8, 2);

	var vpk2014_start = new Date(2009, 8, 2);
	var vpk2014_end = new Date(2010, 8, 2);

	$('.calc').click(function(){
		var fullDate = new Date(year.val(), (month.val() - 1), day.val());

		if(fullDate >= vpk2013_start && fullDate <= vpk2013_end)
		{
			console.log('VPK2013');
			window.location = 'https://vpk.elc-manatee.org/users/login/program/1';
		}
		else if(fullDate >= vpk2014_start && fullDate <= vpk2014_end)
		{
			console.log('VPK2014');
			window.location = 'https://vpk.elc-manatee.org/users/login/program/2';
		}
		else
		{
			var readableDate = (fullDate.getMonth()+1) + '/' + fullDate.getDate() + '/' + fullDate.getFullYear();
			errorOutput.text('Any child born on: ' + readableDate + ', is not eligable for any existing programs');
			errorOutput.show();
		}
	});


});
</script>