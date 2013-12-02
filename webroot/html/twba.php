<?php ob_start() ?>
<body>

	<div class="header">
		<div class="left">
			<img src="/img/admin_header_logo.jpg" />
		</div>
		

		<div class="right">
			<ul class="list">
				<li>
					- Make sure you sign next to the X in both locations
				</li>
				<li>
					- Print, Sign and Save As PDF file format before emailing to: electronicsignature@workforcetampa.com
				</li>
				<li>
					OR Print, Sign and fax to: 855-262-6957
				</li>
			</ul>
		</div>
	</div>

	<br clear="all" />

	<h3>
		Tampa Bay WorkForce Alliance Acknowledgement of Electronic Signature
	</h3>

	<p class="copy">
		I, the undersigned, acknowledge and agree the use of the Tampa Bay WorkForce Alliance (TBWA) 
		Electronic Signature when completing required online forms, agreements and acknowledgements for the TBWA 
		program(s) for which I am obtaining or seeking to obtain services. The information provided may be 
		used to determine eligibility and suitability for services, to meet program participation requirements 
		and post employment, follow up services.
	</p>

	<table class="form">
		<tr class="input-row">
			<td class="field-left">
				<b>Ted Furgesburg</b>
			</td>

			<td class="field-center">
				<b>12/13/2055</b>
			</td>

			<td class="field-right">
				<b>12/13/2077</b>
			</td>
		</tr>

		<tr class="label-row">
			<td class="label-left">
				<p>
					Participant Name(Please print legibly)
				</p>
			</td>

			<td class="label-center">
				<p>
					Date of Birth
				</p>
			</td>

			<td class="label-right">
				<p>
					Date
				</p>
			</td>
		</tr>
	</table>

	<table class="form">
		<tr class="input-row">
			<td class="field-left">
				<img class="sig" src="/storage/signatures/<?= $user_id ?>/signature.png" />
			</td>

			<td class="field-center">
				<b>ted.furg@gmail.com</b>
			</td>
		</tr>

		<tr class="label-row">
			<td class="label-left">
				<p>
					Participant Signature
				</p>
			</td>

			<td class="label-center">
				<p>
					Email Address
				</p>
			</td>
		</tr>
	</table>

	<p>
		If under 18 years of age, it is required to have a parent or legal guardian sign:
	</p>

	<table class="form">
		<tr class="input-row">
			<td class="field-left">
				<b>
					Benny Fergesburg
				</b>
			</td>

			<td class="field-center">
				<img class="sig" src="/storage/signatures/<?= $user_id ?>/signature.png" />
			</td>
		</tr>

		<tr class="label-row">
			<td class="label-left">
				<p>
					Parent/Guadian (Please print legibly)
				</p>
			</td>

			<td class="label-center">
				<p>
					Parent/Guardian Signature
				</p>
			</td>
		</tr>
	</table>

	<p class="equal-divider">
		=============================================================
	</p>

	<h3>
		Tampa Bay WorkForce Alliance General Release of Information
	</h3>

	<p class="copy">
		I hereby give my permission for TBWA Staff to obtain and/or disclose my past, present, and future 
		information or records that may be needed for eligibility determination, monitoring and follow-up purposes. 
		This information may include, but shall not be limited to: school records, grade records, attendance records, 
		employment information, medical records, public assistance records, employment information and vocational 
		rehabilitation assessment or evaluation tools. A photocopy/facsimile of this signed consent form may be used 
		to obtain/release information authorized by signature on this form.
	</p>
	<p class="copy">

		It is also my understanding that any information obtained by the above organization will be held 
		in strict confidence.
	</p>
	<p class="copy">
		I understand that I may revoke this consent at any time by providing a written statement indicating 
		that my consent to the release of information is no longer given to the party(ies) previously granted permission.
	</p>

	<table class="form">
		<tr class="input-row">
			<td class="field-left">
				<b>
					Martha Fergesburg
				</b>
			</td>

			<td class="field-center">
				<b>23/19/2013</b>
			</td>
		</tr>

		<tr class="label-row">
			<td class="label-left">
				<p>
					Participant Name (Please print legibly)
				</p>
			</td>

			<td class="label-center">
				<p>
					Date
				</p>
			</td>
		</tr>
	</table>

	<table class="form">
		<tr class="input-row">
			<td class="field-left">
				<img class="sig" src="/storage/signatures/<?= $user_id ?>/signature.png" />
			</td>

			<td class="field-center">
				<b>(413) 665 - 2319</b>
			</td>
		</tr>

		<tr class="label-row">
			<td class="label-left">
				<p>
					Participant Signature
				</p>
			</td>

			<td class="label-center">
				<p>
					Phone Number
				</p>
			</td>
		</tr>
	</table>

	<p>
		If under 18 years of age, it is required to have a parent or legal guardian sign:
	</p>

	<table class="form">
		<tr class="input-row">
			<td class="field-left">
				<img class="sig" src="/storage/signatures/<?= $user_id ?>/signature.png" />
			</td>

			<td class="field-center">
				<b>Martha Fergesburg</b>
			</td>
		</tr>

		<tr class="label-row">
			<td class="label-left">
				<p>
					Parent/Guadian (Please print legibly)
				</p>
			</td>

			<td class="label-center">
				<p>
					Parent/Guardian Signature
				</p>
			</td>
		</tr>
	</table>

	<p class="disclaimer">
		TampaBay WorkForce Alliance is an equal opportunity employer/program. 
		Auxiliary aids and services are available upon request to individuals with disabilities. 
		All voice telephone numbers listed may be reached by persons using TTY/TDD equipment via the
	</p>
</body>
<?php return ob_get_clean() ?>