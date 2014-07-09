<style>
#upload_docs .center {
	text-align:center;
}
label {
	display:block;
}
label > p {
	display:inline-block;
	width:125px;
}
label > input {
	display:inline-block;
}
#upload_docs p {
	margin:10px 0px;
	padding:0px;
}
.success {
	color:#2ecc71 !important;
	font-family:Verdana;
	font-size:14pt !important;
}
</style>
<div id="upload_docs">

	<p class="success">
		<?= $this->Session->flash('flash_success') ?>
	</p>
	
	<p>
		You have chosen to upload your document via mobile upload, enter your phone number and service provider
	</p>

	<?= $this->Form->create() ?>

		<label>
			<p>
				Phone Number: 
			</p>
			1+ <input type="text" name="phone_number" maxlength="10" />
		</label>

		<label>
			<p>
				Service Provider: 
			</p>
			<select name="provider">
				<option value="@message.alltel.com">
					Alltel
				</option>
				<option value="@txt.att.net">
					AT&amp;T
				</option>
		        <option value="@myboostmobile.com">
		        	Boost
		        </option>
		        <option value="@mobile.mycingular.com">
		        	Cingular
		        </option>
		        <option value="@messaging.nextel.com">
		        	Nextel
		        </option>
		        <option value="@messaging.sprintpcs.com">
		        	Sprint
		        </option>
		        <option value="@tmomail.net">
		        	T-Mobile USA
		        </option>
		        <option value="@vtext.com">
		        	Verizon Wireless
		        </option>
		        <option value="@vmobl.com">
		        	Virgin Mobile USA
		        </option>
			</select>
		</label>

		<input type="submit" value="Send" />

	<?= $this->Form->end() ?>

</div>