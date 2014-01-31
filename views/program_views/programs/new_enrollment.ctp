<style>

</style>
<div class="container" style="margin-top:5px;">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach">

			<?php var_dump($program) ?>
			
			<h3>
				<?= $program['Program']['name'] ?>
			</h3>


			<?php foreach($program['ProgramStep'] as $step): ?>
				<h4>
					<?= $step['name'] ?>
				</h4>
				<p>
					<?php print_r($step) ?>
				</p>
			<?php endforeach ?>
		</div>
	</div>
</div>