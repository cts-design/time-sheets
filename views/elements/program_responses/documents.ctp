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