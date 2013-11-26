<style>
.tos
{
	height:150px;
	overflow-y:scroll;

	border:1px solid #CCC;
	padding:10px;
}
#sig
{
	width:100%;
	height:75px;
	margin-top:5px;
	margin-bottom:5px;
}
#sig > canvas
{
	width:100%;
}
</style>
<div class="container content">
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
			
			<div class="row">
				<div class="col-sm-10">
					<h4>
						<?= $title_for_layout ?>
					</h4>

					<p>
						Please read the agreement below then sign on the signature pad with your mouse
					</p>

					<div class="tos">

						<h5>
							E-signature text
						</h5>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris pretium turpis elit. Integer laoreet turpis vel malesuada tristique. Donec velit odio, lacinia ac luctus at, placerat quis mauris. Suspendisse sit amet erat eu arcu rutrum consectetur viverra non odio. Vestibulum dapibus venenatis elit, at auctor felis tincidunt ut. Nam vitae tortor metus. Phasellus mollis erat vel ligula bibendum, quis adipiscing tortor cursus. Etiam non dictum elit, vel volutpat sem. Phasellus nunc orci, imperdiet nec nisl sed, interdum blandit velit. Donec bibendum elementum augue, vitae pellentesque leo condimentum nec. Vivamus in neque lacus. Vivamus in dui malesuada, pellentesque nunc et, tempus felis. Duis non mattis mauris. Mauris facilisis elit eu orci rutrum, id feugiat ipsum tristique.

		Proin vehicula, risus quis vehicula eleifend, mi metus laoreet nibh, sit amet gravida libero sem ac leo. Nulla congue nisl mi, eget fringilla dolor fringilla sit amet. Aenean mauris risus, tincidunt at justo sit amet, feugiat volutpat justo. Ut id tortor et odio adipiscing lacinia. Duis tincidunt consequat turpis eu elementum. Ut venenatis interdum aliquam. Phasellus pellentesque feugiat lectus sit amet fermentum. Etiam quis lorem egestas, adipiscing purus id, bibendum mauris. Etiam ac erat massa. Vivamus quis ante faucibus nunc tincidunt pharetra.

		Aliquam at dui aliquam, fringilla sapien ut, pellentesque nisi. Cras mollis accumsan ultricies. Donec dignissim velit eget nulla imperdiet feugiat. Pellentesque pellentesque placerat sapien, vitae hendrerit nisi. Donec urna libero, pellentesque id pulvinar quis, fringilla at purus. Sed nec velit sit amet ligula dictum suscipit. Phasellus sit amet tempor tortor, quis rhoncus nunc. Curabitur tellus ante, convallis id vulputate id, facilisis ut lacus. Suspendisse cursus rhoncus dui, ac adipiscing libero pulvinar sit amet. Ut ac nibh sem.

		Proin at ornare enim. Proin vulputate, erat ac iaculis lacinia, ante mauris rhoncus nisl, et tincidunt ipsum lectus ac elit. Vestibulum fermentum diam tincidunt urna tristique, varius sollicitudin dolor euismod. Praesent ipsum metus, eleifend pellentesque eros at, ornare dapibus metus. Cras adipiscing, urna eget condimentum blandit, erat nisi sollicitudin nunc, ac condimentum turpis nisi a elit. Sed varius placerat tortor ac tempor. Vestibulum porta viverra nisi, ut ultrices mi placerat id. Suspendisse ut est justo. Pellentesque condimentum ac ligula in dignissim. Nunc massa purus, accumsan non quam tempus, dapibus adipiscing mauris. Phasellus eleifend sagittis mattis. Ut justo dui, tincidunt ut mollis vel, consectetur porta nibh.

		Fusce ac facilisis nunc. Pellentesque dolor tellus, iaculis non dolor iaculis, aliquam vulputate est. Curabitur sed porttitor neque, non ullamcorper est. In in odio et felis accumsan commodo id vitae eros. Nullam fringilla, nunc in dapibus iaculis, nisi augue sollicitudin lectus, quis fringilla urna ligula ac lorem. Phasellus auctor varius consectetur. Curabitur ac lectus hendrerit, malesuada risus a, molestie tellus. Proin accumsan nunc id nibh vestibulum posuere. Ut lacinia dui nec faucibus adipiscing.
						</p>
					</div>

				</div>
			</div>

			<div class="row">
				<div class="col-sm-3">
					<p class="pull-right" style="margin:30px 0px 0px 0px">
						Sign in the white box
					</p>
				</div>
				<div class="col-sm-7">
					<div id="sig"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<p class="error-output pull-right text-danger"></p>
				</div>
				<div class="col-sm-6">

					<div class="btn-group pull-right">
						<button name="done" id="done" class="btn btn-primary">Done Signing</button>
						<button name="reset" id="reset" class="btn btn-primary">Reset</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<textarea name="lines" id="lines" style="display:none"></textarea>



<script type="text/javascript">
$(document).ready(function(){
$("#sig").signature({syncField: '#lines'});

var errorOutput = $(".error-output");

var done = $("#done");
var reset = $("#reset");

done.click(function(){

	var isEmpty = $('#sig').signature('isEmpty');

	if(isEmpty)
	{
		$(this).removeClass('btn-primary');
		$(this).addClass('btn-danger');

		errorOutput.text("You must sign with your mouse before continuing");
	}
	else
	{
		$(this).removeClass('btn-danger');
		$(this).addClass('btn-success');
		errorOutput.text("");

		$.ajax({
			url : '/test/esign_document',
			type : 'POST',
			dataType : 'json',
			data : { lines : $("#lines").val(), esign_id : '<?= $this->params['pass'][0] ?>' },
			success : function(response){
				if(response.success)
				{
					var image = '/' + response.output;
					$("#post-save-image").attr('src', image);

					location.pathname = '<?= (isset($_GET['redirect']) ? $_GET['redirect'] : "/users/dashboard") ?>';
				}
			},
			error : function(response, error){
				console.log(response);
				console.log(error);
			}
		});

	}
});

reset.click(function(e){
	$("#sig").signature('clear');
	$("#lines").val('');

	done.removeClass('btn-danger');
	done.addClass('btn-primary');
	errorOutput.text("");
});
});
</script>