<style>
#programResponseDocs {
	padding: 10px;
}
.response-doc {
	border-bottom: 1px solid #666666;
	margin-bottom: 10px;
}
#programPaperForms {
	padding: 10px;
}
.paper-form {
	border-bottom: 1px solid #666666;
	margin-bottom: 10px;
}
</style>

<div id="programResponseDocs">
	<?php if(isset($docs)) : ?>
		<?php if(is_array($docs)) :?>
			<?php foreach($docs as $doc) : ?>
				<div class="response-doc">
					<p><strong>Doc id:</strong> <?php echo $doc['id'] ?></p> 
					<p><strong>Doc type:</strong> <?php echo $doc['name'] ?></p>
					<p><strong>Filed on:</strong> <?php echo $doc['filedDate'] ?></p>			
					<p><a href="<?php echo $doc['link'] ?>" target="_blank">View Doc</a></p>
				</div>
			<?php endforeach ?>	
		<?php else : ?>
			<?php echo $docs ?>	
		<?php endif ?>
	<?php endif ?>
</div>
<div id="programPaperForms">
	<?php if(isset($forms)) : ?>
		<?php foreach($forms as $form) :?>
			<div class="paper-form">
				<p><strong>Form:</strong> <?php echo $form['name']; ?></p>
				<?php echo $html->link('Generate', 
					array('action' => 'generate', $form['id'], $form['programResponseId'])) ?>
			</div>
			
		<?php endforeach ?>
	<?php endif ?>
</div>