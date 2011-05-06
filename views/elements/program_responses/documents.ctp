<style>

}
</style>
<div id="ProgramResponseDocs">
	<?php if(isset($docs)) : ?>
		<?php if(is_array($docs)) :?>
			<?php foreach($docs as $doc) : ?>
				<div class="response-doc">
					<p><strong>Doc id:</strong> <?php echo $doc['id'] ?></p> 
					<p><strong>Doc type:</strong> <?php echo $doc['name'] ?></p>
					<p><strong>Filed on:</strong> <?php echo $doc['filedDate'] ?></p>			
					<p><?php echo $doc['link'] ?></p>
				</div>
			<?php endforeach ?>	
		<?php else : ?>
			<?php echo $docs ?>	
		<?php endif ?>
	<?php endif ?>
</div>
<div id="ProgramPaperForms">
	<?php if(isset($forms)) : ?>
		<?php foreach($forms as $form) :?>
			<div class="paper-form">
				<p><strong>Form:</strong> <?php echo $form['name']; ?></p>
				<?php if(isset($form['doc_id'])) : ?>
					<p><strong>Doc id:</strong> <?php echo $form['doc_id'] ?></p> 
				<?php endif ?>
				<?php if(isset($form['filed_on'])) : ?>
					<p><strong>Filed on:</strong> <?php echo $form['filed_on']; ?></p>
				<?php endif ?>
				<p>				
					<?php if(isset($form['view'])) echo $form['view'] . ' | ' ?>
					<?php echo $form['link'] ?>
				</p>
			</div>
			
		<?php endforeach ?>
	<?php endif ?>
</div>