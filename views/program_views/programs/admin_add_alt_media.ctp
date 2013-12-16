<div class="container" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach" style="padding:10px">

			<div class="row">
				<div class="col-sm-8">
					<?php if(!empty($docs)): ?>
						<?= $this->Form->create('', array('url' => '/admin/programs/add_alt_media/' . $program_id, 'role' => 'form', 'enctype' => 'multipart/form-data')) ?>
							<div class="form-group">
								<label for="altname">Alternative Media Name:</label>
								<input type="text" name="altname" class="form-control" />
								<p class="help-block">This will be in the dropdown menu for customers to select which media they want to view</p>
							</div>

							<div class="form-group">
								<label for="altname">Alternative Media For:</label>
								<select name="program_document_id" class="form-control">
									<option value="0"></option>
										<?php foreach($docs as $doc): ?>
											<option value="<?= $doc['ProgramDocument']['id'] ?>">
												<?= $doc['ProgramDocument']['name'] ?>
											</option>
										<?php endforeach ?>
									</select>
									<p class="help-block">
										This will appear on the same Program Step that the 
										selected media is on as an alternative
									</p>
							</div>

							<div class="form-group">
								<label for="file">Alternate Media Upload</label>
								<input type="file" name="file" class="form-control" />
							</div>

							<input type="hidden" name="program_id" value="<?= $program_id ?>" />

							<button type="submit" class="btn btn-default">Upload</button>
						<?= $this->Form->end() ?>
					<?php else: ?>
						<p class="help-block">
										There is no media associated with this Program, first 
										add media in order to add an alternative
									</p>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>