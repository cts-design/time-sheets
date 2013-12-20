<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach no-padding" style="margin-top:5px">
			<?= $this->Form->create() ?>

			<label> Username
				<input type="text" name="data[User][username]" />
			</label>

			<label> Password
				<input type="text" name="data[User][password]" />
			</label>

			<input type="submit" class="btn btn-primary" />

			<?= $this->Form->end() ?>
		</div>
	</div>
</div>