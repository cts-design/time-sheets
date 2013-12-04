<style>
.tos
{
	height:150px;
	overflow-y:scroll;

	border:1px solid #CCC;
	padding:10px;
}
#sig, #guard
{
	width:100%;
	height:75px;
}
#sig
{
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
							Tampa Bay WorkForce Alliance Acknowledgement of Electronic Signature
						</h5>
						<p>
							I, the undersigned, acknowledge and agree the use of the Tampa Bay WorkForce Alliance (TBWA) 
		Electronic Signature when completing required online forms, agreements and acknowledgements for the TBWA 
		program(s) for which I am obtaining or seeking to obtain services. The information provided may be 
		used to determine eligibility and suitability for services, to meet program participation requirements 
		and post employment, follow up services.
						</p>

						<br />

						<h5>
							Tampa Bay WorkForce Alliance General Release of Information
						</h5>

						<p>
							I hereby give my permission for TBWA Staff to obtain and/or disclose my past, present, and future 
		information or records that may be needed for eligibility determination, monitoring and follow-up purposes. 
		This information may include, but shall not be limited to: school records, grade records, attendance records, 
		employment information, medical records, public assistance records, employment information and vocational 
		rehabilitation assessment or evaluation tools. A photocopy/facsimile of this signed consent form may be used 
		to obtain/release information authorized by signature on this form.
						</p>

						<p>
							It is also my understanding that any information obtained by the above organization will be held 
		in strict confidence.
						</p>

						<p>
							I understand that I may revoke this consent at any time by providing a written statement indicating 
		that my consent to the release of information is no longer given to the party(ies) previously granted permission.
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

			<div class="row" style="margin-bottom:5px">
				<div class="col-sm-3">
					<p class="pull-right">
						Guardian Name
					</p>
				</div>
				<div class="col-sm-7">
					<input type="text" name="guardian_name" class="input-sm" style="width:60%" placeholder="Enter guardian name" />
				</div>
			</div>

			<div class="row" id="under18" style="<?= (!$under_18 ? 'display:none' : '') ?>">
				<div class="col-sm-3">
					<p class="pull-right">
						You are under 18 years of age, have a parent or guardian sign
					</p>
				</div>
				<div class="col-sm-7">
					<div id="guard"></div>
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

<textarea name="guardian" id="guardian" style="display:none"></textarea>



<script type="text/javascript">
$(document).ready(function(){

var sig = $("#sig");
sig.signature({syncField: '#lines'});

var guard = $("#guard");
guard.signature({syncField: '#guardian'});

var guardian_name = $("input[name=guardian_name]");

var under18 = $("#under18");

var errorOutput = $(".error-output");

var done = $("#done");
var reset = $("#reset");

done.click(function(){

	var isEmpty = sig.signature('isEmpty');
	var guardEmpty = guard.signature('isEmpty');

	if(isEmpty)
	{
		$(this).removeClass('btn-primary');
		$(this).addClass('btn-danger');

		errorOutput.text("You must sign with your mouse before continuing");
	}
	else if(guardEmpty && under18.css('display') != 'none'){
		$(this).removeClass('btn-primary');
		$(this).addClass('btn-danger');

		errorOutput.text("An adult over the age of 18 must sign the bottom signature");
	}
	else if(guardian_name.val() == "")
	{
		$(this).removeClass('btn-primary');
		$(this).addClass('btn-danger');

		errorOutput.text("Guardian's name is required");
	}
	else
	{
		$(this).removeClass('btn-danger');
		$(this).addClass('btn-success');
		errorOutput.text("");

		$.ajax({
			url : '/programs/esign_document',
			type : 'POST',
			dataType : 'json',
			data : { 
				lines : $("#lines").val(), 
				esign_id : '<?= $this->params['pass'][0] ?>', 
				guardian: $("#guardian").val(),
				guardian_name : guardian_name.val()
			},
			success : function(response){
				if(response.success)
				{
					var image = '/' + response.output;
					$("#post-save-image").attr('src', image);

					location.pathname = '<?= (isset($_GET['redirect']) ? $_GET['redirect'] : "/users/dashboard") ?>';
				}
				else
				{
					console.log(response);
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